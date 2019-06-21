<?php
/**
 * excel导出
 */
namespace app\common;

class Excel{
    public $fileName;

    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    public  function outPut($headArr,$data){
        if(!$data) return ;
//        exportCsv(basename($this->fileName, '.xls'), $headArr, $data);
//        return ;
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $key = ord("A");
        foreach($headArr as $field=>$fieldName){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $fieldName);
            $key += 1;
        }
        $field_keys = array_keys($headArr);
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($field_keys as $v){
                // $value = $rows[$v];
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $rows[$v]);
                $span++;
            }
            $column++;
        }
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$this->fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /*
     * 导出多sheet
     */
    public  function outPuts($headArr,$data){
        $objPHPExcel = new \PHPExcel();

        foreach ($data as $data_k=>$data_v) {
            if ($data_k == 0) {
                $objProps = $objPHPExcel->getProperties();
            } else {
                $objPHPExcel->createSheet();

            }
            $objPHPExcel->setactivesheetindex($data_k);
            //写入多行数据
            $key = ord("A");
            foreach($headArr[$data_k] as $field=>$fieldName){
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex($data_k) ->setCellValue($colum.'1', $fieldName);
                $key += 1;
            }
            $field_keys = array_keys($headArr[$data_k]);
            $column = 2;
            $objActSheet = $objPHPExcel->getActiveSheet();
            foreach($data_v as $key => $rows){ //行写入
                $span = ord("A");
                foreach($field_keys as $v){
                    // $value = $rows[$v];
                    $j = chr($span);
                    $objActSheet->setCellValue($j.$column, $rows[$v]);
                    $span++;
                }
                $column++;
            }
        }

        //写入类容
        $obwrite = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$this->fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }
    /*
     * 输出EXCEL到指定目录
     */
    public  function outPutToPath($headArr,$data,$file_path){
        if(!$data) return ;
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $key = ord("A");
        foreach($headArr as $field=>$fieldName){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $fieldName);
            $key += 1;
        }
        $field_keys = array_keys($headArr);
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($field_keys as $v){
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $rows[$v]);
                $span++;
            }
            $column++;
        }
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($file_path);
        return true;
    }

    /*
     * 导入EXCEL
     */
    public  function import($file_path){
        if(!$file_path) return false;
        $objPHPExcel = new \PHPExcel();
        $objPHPExcelReader = \PHPExcel_IOFactory::load($file_path);//加载excel文件
        $special_str = chr(194).chr(160); //特殊空格
        foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            foreach($sheet->getRowIterator() as $k=>$row)  //逐行处理
            {
                if($row->getRowIndex()<2)  //确定从哪一行开始读取
                {
                    continue;
                }
                foreach($row->getCellIterator() as $kk=>$cell)  //逐列读取
                {
                    $value = $cell->getValue(); //获取cell中数据
                    if(is_object($value)){
                        $value= $cell->__toString();
                    }
                    if(is_string($value)){ //过滤掉特殊空格
                        $value = str_replace($special_str,'',$value);
                    }
                    $data[$k][] = $value;
                }
            }
        }
        return $data;
    }
}
