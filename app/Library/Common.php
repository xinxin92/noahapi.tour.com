<?php
/**
 * 公共工具类
 */

namespace App\Library;

class Common {
    //获取客户端IP地址
    public static function getClientIp() {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $pos = strpos($ip, '|');
        if ($pos) {
            $ip = substr($ip, 0, $pos);
        }
        $ip = trim($ip);
        return $ip;
    }

    //后台列表获取分页参数并处理
    public static function getPageMsg ($request=[], $size=100){
        if (isset($request['page']) && intval($request['page'])){
            $page = intval($request['page']);
        } else {
            $page = 1;
        }
        if (isset($request['page_size']) && intval($request['page_size'])){
            $pageSize = intval($request['page_size']);
        } else {
            $pageSize = $size;
        }
        //分页处理
        if ($page > 0) {
            $skip = $pageSize * ($page - 1);
        } else {
            $skip = $page;
        }
        $pageMsg['page'] = $page;
        $pageMsg['page_size'] = $pageSize;
        $pageMsg['skip'] = $skip;
        $pageMsg['limit'] = $pageSize;
        return $pageMsg;
    }

    //格式化数据之多条记录(添加序号、替换为指定值、限定长度)
    public static function formatList($lists = [], $condition = [], $get_index = true, $index = 1)
    {
        if(!$lists || !$condition){
            return $lists;
        }
        if (!is_array($lists)) {
            foreach ($lists as $list) {
                //添加序号
                if ($get_index) {
                    $list->index = $index++;
                }
                foreach ($condition as $k => $v) {
                    //替换为设定值
                    if (!isset($list->$k)) {
                        if (isset($v['isNull'])) {
                            $list->$k = $v['isNull'];
                        }
                    } else {
                        if (isset($v[$list->$k])) {
                            $list->$k = $v[$list->$k];
                        }
                    }
                    //限定长度
                    if (isset($v['lengthLimit']) && isset($list->$k)) {
                        $newk = $k . "_short";
                        if (mb_strlen($list->$k) > $v['lengthLimit'] && $v['lengthLimit']) {
                            $list->$newk = mb_substr($list->$k, 0, $v['lengthLimit'], 'utf-8') . '...';
                        } else {
                            $list->$newk = $list->$k;
                        }
                    }
                }
            }
        } else {
            for ($i=0;$i<count($lists);$i++) {
                $list = $lists[$i];
                //添加序号
                if ($get_index) {
                    $list['index'] = $index++;
                }
                foreach ($condition as $k => $v) {
                    //替换为设定值
                    if (!isset($list[$k])) {
                        if (isset($v['isNull'])) {
                            $list[$k] = $v['isNull'];
                        }
                    } else {
                        if (isset($v[$list[$k]])) {
                            $list[$k] = $v[$list[$k]];
                        }
                    }
                    //限定长度
                    if (isset($v['lengthLimit']) && isset($list[$k])) {
                        $newk = $k . "_short";
                        if (mb_strlen($list[$k]) > $v['lengthLimit'] && $v['lengthLimit']) {
                            $list[$newk] = mb_substr($list[$k], 0, $v['lengthLimit'], 'utf-8') . '...';
                        } else {
                            $list[$newk] = $list[$k];
                        }
                    }
                }
                $lists[$i] = $list;
            }
        }
        return $lists;
    }

    //获取16位或32位唯一值（订单号、文件名等用）
    public static function getUniqueValue($params =[], $raw = false) {
        $microsecond = microtime(true) * 10000;  //获取微妙级当前时间戳
        $randomStr = str_pad(rand(0, 100000), 6, 0, STR_PAD_LEFT);  //取6位随机数，不足为以0填补
        $uniqueValue = $microsecond . $randomStr;
        if ($params) {
            foreach ($params as $param) {
                $uniqueValue = $uniqueValue.$param;
            }
        }
        return md5($uniqueValue, $raw);
    }

    // 过滤掉emoji表情
    public static function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }

    //获取文件后缀名（处理了附带随机参数的特殊情况）
    public static function getFileType($file){
        $params_str = strrchr($file, '?');
        if ($params_str) {
            $file = str_replace($params_str,'',$file);
        }
        $file_type = strtolower(substr(strrchr($file, '.'), 1));
        return $file_type;
    }

    //处理文本两端的空白及不可见字符
    public static function delStrBothBlank($str = '', $saveEnterWrap=false){
        if (!$str) {
            return $str;
        }
        if ($saveEnterWrap) {
            //中文空格、中文制表符、英文空格、英文制表符
            $str = preg_replace('/^(\xc2\xa0|\xe3\x80\x80|\x20|\t)+/', '', $str);
            $str = preg_replace('/(\xc2\xa0|\xe3\x80\x80|\x20|\t)+$/', '', $str);
        } else {
            //中文空格、中文制表符、\s(换页符、英文制表符、英文垂直制表符、回车符、换行符)
            $str = preg_replace('/^(\xc2\xa0|\xe3\x80\x80|\s)+/', '', $str);
            $str = preg_replace('/(\xc2\xa0|\xe3\x80\x80|\s)+$/', '', $str);
        }
        return html_entity_decode($str);
    }

}