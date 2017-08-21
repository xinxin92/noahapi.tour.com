<?php
/**
 * @desc EXCEL 工具类
 *
 * Created by PhpStorm.
 * User: lixupeng
 * Date: 16/2/23
 * Time: 上午11:24
 *
 */

namespace App\Library;

class PHPExcel
{
    //读取Excel
    public static function read($filePath)
    {
        ini_set("memory_limit", "3000M");
        //建立reader对象
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                return [];
            }
        }

        //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
        $PHPExcel = $PHPReader->load($filePath);

        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet(0);
        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();

        $excelData = [];
        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex ++) {
            $row = array();
            for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex ++) {
                $addr = $colIndex . $rowIndex;
                $cell = $currentSheet->getCell($addr)->getValue();
                if ($cell instanceof \PHPExcel_RichText) {    //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $row[] = $cell;
            }
            $excelData[] = $row;
        }

        return $excelData;
    }

    //生成excel文件
    public static function write($data,$filePath)
    {
        ini_set("memory_limit", "1024M");
        $fileExt = pathinfo($filePath,PATHINFO_EXTENSION);
        if(!in_array($fileExt,array('xls','xlsx'))){
            return false;
        }
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("PHPExcel Test Document")
            ->setSubject("PHPExcel Test Document")
            ->setDescription("Test document for PHPExcel, generated using PHP classes.")
            ->setKeywords("office PHPExcel php")
            ->setCategory("Test result file");

        if($data){
            $lineNo = 1;
            foreach ($data as $line) {
                $fieldNo = 0;
                foreach($line as $field){
                    //过滤emoji表情
                    $field = Common::filterEmoji($field);
                    $fieldNo_excel = self::stringFromColumnIndex($fieldNo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($fieldNo_excel.$lineNo, $field, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $fieldNo++;
                }
                $lineNo++;
            }
        }

        if($fileExt == 'xlsx'){
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        }elseif($fileExt == 'xls'){
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        }
        $objWriter->save($filePath);//返回NULL
        return true;
    }

    //下载excel文件
    public static function download($data,$clientFileName='data.xls')
    {
        ini_set("memory_limit", "3000M");
        $fileExt = pathinfo($clientFileName,PATHINFO_EXTENSION);
        if(!in_array($fileExt,array('xls','xlsx'))){
            return false;
        }
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("PHPExcel Test Document")
            ->setSubject("PHPExcel Test Document")
            ->setDescription("Test document for PHPExcel, generated using PHP classes.")
            ->setKeywords("office PHPExcel php")
            ->setCategory("Test result file");

        if($data){
            $lineNo = 1;
            foreach ($data as $line) {
                $fieldNo = 0;
                foreach($line as $field){
                    //过滤emoji表情
                    $field = Common::filterEmoji($field);
                    $fieldNo_excel = self::stringFromColumnIndex($fieldNo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($fieldNo_excel.$lineNo, $field, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $fieldNo++;
                }
                $lineNo++;
            }
        }

        if($fileExt == 'xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }elseif($fileExt == 'xls'){
            header('Content-Type: application/vnd.ms-excel');
        }
        header('Content-Disposition: attachment;filename="'.$clientFileName.'"');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        if($fileExt == 'xlsx') {
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        }elseif($fileExt == 'xls'){
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        }
        $objWriter->save('php://output');
        return true;
    }

    //导出csv文件
    public static function exportCsv($fileName, $data)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 设置header信息
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName);

        // 写入内容
        foreach ($data as $line) {
            echo iconv('utf-8', 'gbk//TRANSLIT', '"' . implode('","', $line) . "\"\n");
        }
        exit();
    }

    //根据第几列获取excel列的名字，如列名AB
    private static function stringFromColumnIndex($pColumnIndex = 0)
    {
        if ($pColumnIndex < 26) {
            return chr(65 + $pColumnIndex);
        } else if ($pColumnIndex < 702) {
            return chr(64 + ($pColumnIndex / 26)) . chr(65 + $pColumnIndex % 26);
        } else {
            return chr(64 + (($pColumnIndex - 26) / 676)) . chr(65 + ((($pColumnIndex - 26) % 676) / 26)) . chr(65 + $pColumnIndex % 26);
        }
    }

}