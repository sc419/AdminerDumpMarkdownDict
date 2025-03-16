<?php
/**
 * 导出 Markdown 格式数据库字典
 * Export Markdown format database dictionary
 *
 * @link https://github.com/sc419/AdminerDumpMarkdownDict
 * @author Asilu, https://www.asilu.com
 **/

class AdminerDumpMarkdownDict {
    private $type = 'dict-md';

    private $_fields = [
        'table' => [
            'Name' => 'Table name',
            'Engine' => 'Engine',
            'Collation' => 'Collation',
            'Auto_increment' => 'Auto Increment',
            'Data_length' => 'Data Length',
            'Comment' => 'Comment',
        ],
        'field' => [
            'field' => 'Column name',
            'full_type' => 'Type',
            'default' => 'Default values',
            'primary' => 'Primary',
            'auto_increment' => 'Auto Increment',
            'null' => 'empty',
            'comment' => 'Comment',
        ],
    ];

    private $time, $date, $title;

    function __construct(){
        $this->time = time();
        $this->title = Adminer\lang('Database schema');
        $this->date = date('Y/m/d H:i:s', $this->time);
    }

    function dumpFormat() {
        return [$this->type => $this->title];
    }

    function dumpTable($table, $style, $is_view = false) {
        if ($_POST["format"] == $this->type) {
            if($table == 0){
                $status = Adminer\table_status1($table);
                
                echo "\r\n\r\n\r\n\r\n# {$status['Name']} {$status['Comment']}";
                echo $this->_table_head();
                echo "\r\n| {$status['Name']} | {$status['Engine']} | {$status['Collation']} | {$status['Auto_increment']} | {$status['Data_length']} | {$status['Comment']} |";

                echo $this->_field_head();
                foreach(Adminer\fields($table) as $field){
                    $this->_b($field['primary']);
                    $this->_b($field['auto_increment']);
                    $this->_b($field['null']);
                    echo "\r\n| {$field['field']} | {$field['full_type']} | {$field['default']} | {$field['primary']} | {$field['auto_increment']} | {$field['null']} | {$field['comment']} |";
                }
                return true;
            }
        }
    }
    
    function _table_head(){
        static $head = null;
        if(!$head){
            $fields = array_map('lang', $this->_fields['table']);
            $head = implode(' | ', $fields);
            $head = "\r\n\r\n\r\n| $head |\r\n| -- | -- | -- | -- | -- | -- |";
        }
        return $head;
    }
    
    function _field_head(){
        static $head = null;
        if(!$head){
            $fields = array_map('lang', $this->_fields['field']);
            $head = implode(' | ', $fields);
            $head = "\r\n\r\n\r\n| $head |\r\n| -- | -- | -- | -- | -- | -- | -- |";
        }
        return $head;
    }


    function dumpData($table, $style, $is_view = false) {
        if ($_POST["format"] == $this->type) {
            return true;
        }
    }

    function dumpHeaders($identifier, $multi_table = false) {
        if ($_POST["format"] == $this->type) {
            header("Content-Type: text/text; charset=utf-8");
            echo "\r\n# {$this->title}\r\n\r\n". Adminer\lang('Time') .":{$this->date}";
            return 'md';
        }
    }

    function _b(&$s){
        $s = $s ? '**'. Adminer\lang('yes') .'**' : Adminer\lang('no');
    }
}
