<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    /**
     * 多表联立查询方法,参数params各元素含义
     * @param：fields   查询字段
     * @param：joins    表连接信息
     * @param：where    查询条件
     * @param：orderBy  排序情况
     * @param：groupBy  分组情况
     * @param：limit    数量限制
     * @param：skip     偏移量
     * @param：toArray  返回数组/对象
     */

    //获取query（返回对象）
    public function getQuery($params = [])
    {
        $fields = isset($params['fields']) ? $params['fields'] : [];
        $joins = isset($params['joins']) ? $params['joins'] : [];
        $where = isset($params['where']) ? $params['where'] : [];
        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : [];
        $groupBy = isset($params['groupBy']) ? $params['groupBy'] : [];
        if($fields){
            $query = $this->select($fields);
        } else {
            $query = $this;
        }
        if ($joins) {
            $query = $this->createJoin($query, $joins);
        }
        $query = $this->createWhere($query, $where, $orderBy, $groupBy);
        return $query;
    }
    //获取部分或全部(返回数组或对象)
    public function getList($params = [])
    {
        $toArray = isset($params['toArray']) ? $params['toArray'] : true;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $query = $this->getQuery($params);
        if ($limit) {
            $query = $query->skip($skip)->take($limit);
        }
        $result = $query->get();
        if ($toArray) {
            $result = $result->toArray();
        }
        return $result;
    }
    //获取单条记录(返回数组或对象)
    public function getOne($params = [])
    {
        $toArray = isset($params['toArray']) ? $params['toArray'] : true;
        $query = $this->getQuery($params);
        $result = $query->first();
        if (!$result) {
            return [];
        }
        if ($toArray) {
            $result = $result->toArray();
        }
        return $result;
    }
    //分页获取数据（返回分页对象）
    public function getPageList($params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 50;
        $query = $this->getQuery($params);
        $result = $query->paginate($limit);
        return $result;
    }

    //获取数量
    public function countBy($params = [])
    {
        $query = $this->getQuery($params);
        $count= $query->count();
        return $count;
    }
    //更新数据
    public function updateBy($data, $where)
    {
        $query = $this->createWhere($this, $where);
        return $query->update($data);
    }
    //删除数据
    public function deleteBy($where)
    {
        $query = $this->createWhere($this, $where);
        return $query->delete();
    }

    //设置查询条件
    public static function createWhere($query, $where =[], $orderBy = [], $groupBy = [])
    {
        //暂存groupBy后的having条件
        if(isset($where['having'])) {
            $having = $where['having'];
            unset($where['having']);
        }

        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $query = $query->whereIn($k, $v);
            }
            unset($where['in']);
        }
        if(isset($where['not_in'])) {
            foreach($where['not_in'] as $k => $v) {
                $query = $query->whereNotIn($k, $v);
            }
            unset($where['not_in']);
        }
        if(isset($where['raw'])) {
            foreach($where['raw'] as $k => $v) {
                $query = $query->whereRaw($v);
            }
            unset($where['raw']);
        }
        if(isset($where['null'])) {
            foreach($where['null'] as $param) {
                $query = $query->whereNull($param);
            }
            unset($where['null']);
        }

        if($where){
            foreach ($where as $k => $v) {
                $operator = '=';
                if (substr($k, -2) == ' <') {
                    $k = trim(str_replace(' <', '', $k));
                    $operator = '<';
                } elseif (substr($k, -3) == ' <=') {
                    $k = trim(str_replace(' <=', '', $k));
                    $operator = '<=';
                } elseif (substr($k, -2) == ' >') {
                    $k = trim(str_replace(' >', '', $k));
                    $operator = '>';
                } elseif (substr($k, -3) == ' >=') {
                    $k = trim(str_replace(' >=', '', $k));
                    $operator = '>=';
                } elseif (substr($k, -3) == ' !=') {
                    $k = trim(str_replace(' !=', '', $k));
                    $operator = '!=';
                } elseif (substr($k, -3) == ' <>') {
                    $k = trim(str_replace(' <>', '', $k));
                    $operator = '<>';
                } elseif (substr($k, -5) == ' like') {
                    $k = trim(str_replace(' like', '', $k));
                    $operator = 'like';
                    $v = '%' . $v . '%';
                }
                $query = $query->where($k, $operator, $v);
            }
        }

        if($orderBy) {
            foreach($orderBy as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        if($groupBy) {
            $query = $query->groupBy($groupBy);
        }

        //groupBy后的having条件
        if(isset($having) && $having) {
            foreach ($having as $k => $v) {
                $operator = '=';
                if (substr($k, -2) == ' <') {
                    $k = trim(str_replace(' <', '', $k));
                    $operator = '<';
                } elseif (substr($k, -3) == ' <=') {
                    $k = trim(str_replace(' <=', '', $k));
                    $operator = '<=';
                } elseif (substr($k, -2) == ' >') {
                    $k = trim(str_replace(' >', '', $k));
                    $operator = '>';
                } elseif (substr($k, -3) == ' >=') {
                    $k = trim(str_replace(' >=', '', $k));
                    $operator = '>=';
                } elseif (substr($k, -3) == ' !=') {
                    $k = trim(str_replace(' !=', '', $k));
                    $operator = '!=';
                } elseif (substr($k, -3) == ' <>') {
                    $k = trim(str_replace(' <>', '', $k));
                    $operator = '<>';
                } elseif (substr($k, -5) == ' like') {
                    $k = trim(str_replace(' like', '', $k));
                    $operator = 'like';
                    $v = '%' . $v . '%';
                }
                $query = $query->having($k, $operator, $v);
            }
        }

        return $query;
    }

    /**
     * join操作
     * $joins = [
     *     '连接的表名' => [ '连接方式(可省略)', ['操作方式(可省略)','字段一','两个字段的关系','字段二'], ['操作方式(可省略)','字段一','两个字段的关系','字段二'] ......],      //格式说明
     *     'zebra_user_profile' => [ 'leftjoin', ['on','zebra_user.id','=','zebra_user_profile.user_id'] ], //左外连接表，复杂写法
     *     'zebra_user_sns' => [ ['zebra_user.id','=','zebra_user_sns.user_id'] ]   //常用的简略写法
     *     ]
     * @return $query
     */
    public static function createJoin($query, $joins) {
        if (!$joins) {
            return $query;
        }
        foreach ($joins as $k => $v) {
            //判断join类型
            if (is_array($v[0])) {
                $type = 'leftJoin';
            } else {
                $type = $v[0];
                array_shift($v);
            }
            //分情况进行联表
            if ($type == 'leftJoin' || $type == 'leftjoin') {
                $query = $query->leftJoin($k, function($join) use($v) {
                    foreach ($v as $condition) {
                        if ($condition[0] == 'join_on') {
                            $join->on($condition[1],$condition[2],$condition[3]);
                        } else if ($condition[0] == 'join_orOn') {
                            $join->orOn($condition[1],$condition[2],$condition[3]);
                        } else if ($condition[0] == 'join_where') {
                            $join->where($condition[1],$condition[2],$condition[3]);
                        }  else if ($condition[0] == 'join_whereIn') {
                            $join->whereIn($condition[1],$condition[2]);
                        } else {
                            $join->on($condition[0],$condition[1],$condition[2]);
                        }
                    }
                });
            } else if ($type == 'join') {
                $query = $query->join($k, function($join) use($v) {
                    foreach ($v as $condition) {
                        if ($condition[0] == 'join_on') {
                            $join->on($condition[1],$condition[2],$condition[3]);
                        } else if ($condition[0] == 'join_orOn') {
                            $join->orOn($condition[1],$condition[2],$condition[3]);
                        } else if ($condition[0] == 'join_where') {
                            $join->where($condition[1],$condition[2],$condition[3]);
                        } else {
                            $join->on($condition[0],$condition[1],$condition[2]);
                        }
                    }
                });
            }
        }
        return $query;
    }
    
}
