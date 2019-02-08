<?php

namespace Core;
use Core\DB;

class Migration extends DB {
    
    protected $_db;
    
    protected $_columnTypesMap = [
        'int' => 'INT', 'INT' => 'INT', 'integer' => 'INT', 'tinyint' => 'TINYINT', 'smallint' => 'SMALLINT',
        'mediumint' => 'MEDIUMINT', 'bigint' => 'BIGINT', 'numeric' => 'DECIMAL()', 'decimal' => 'DECIMAL()',
        'double' => 'DOUBLE()', 'float' => 'FLOAT()', 'bit' => 'BIT()',
    ];
    
    public function __constructor() {
        $this->_db = DB::getInstance();
    }
    
    public function createTable($table) {
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id INT AUTO_INCREMENT,
        PRIMARY KEY (id)
        ) ENGINE=INNODB;";
        
        $this->_db->query($sql);
    }
    
    public function addColumn($table, $name, $type, $attrs = []) {
        $sql = "ALERT TABLE {$table} ADD COLUMN {$table} VARCHAR(15) AFTER name;";
    }
    
}