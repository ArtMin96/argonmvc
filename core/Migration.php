<?php

namespace Core;
use Core\{DB, Helper};

abstract class Migration {
    
    protected $_db;
    
    protected $_columnTypesMap = [
        'int' => 'INT', 'INT' => 'INT', 'integer' => 'INT', 'tinyint' => 'TINYINT', 'smallint' => 'SMALLINT',
        'mediumint' => 'MEDIUMINT', 'bigint' => 'BIGINT', 'numeric' => 'DECIMAL()', 'decimal' => 'DECIMAL()',
        'double' => 'DOUBLE()', 'float' => 'FLOAT()', 'bit' => 'BIT()',
        'int' => '_intColumn', 'integer' => '_intColumn', 'tinyint' => '_tinyintColumn', 'smallint' => '_smallintColumn',
        'mediumint' => '_mediumintColumn', 'bigint' => '_bigintColumn', 'numeric' => '_decimalColumn', 'decimal' => '_decimalColumn',
        'double' => '_doubleColumn', 'float' => '_floatColumn', 'bit' => '_bitColumn', 'date' => '_dateColumn',
        'datetime' => '_datetimeColumn', 'timestamp' => '_timestampColumn', 'time' => '_timeColumn', 'year' => '_yearColumn',
        'char' => '_charColumn', 'varchar' => '_varcharColumn', 'text' => '_textColumn'
    ];
    
    public function __constructor() {
        $this->_db = DB::getInstance();
    }
    
    abstract function up();
    
    /**
    * Creates a table in the database
    * @method createTable
    * @param  string      $table name of the db table
    * @return boolean
    */
    public function createTable($table) {
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
        id INT AUTO_INCREMENT,
        PRIMARY KEY (id)
        ) ENGINE=INNODB;";
        echo "Creating Table $table \n";
        
        return $this->_db->query($sql)->error();
    }
    
    /**
    * Drops a table in the database
    * @method dropTable
    * @param  string    $table name of table to be dropped
    * @return boolean
    */
    public function dropTable($table) {
        $sql = "DROP TABLE IF EXISTS {$table}";
        echo "Dropping Table $table \n";
        return !$this->_db->query($sql)->error();
    }
    
    /**
    * Adds created_at and updated_at columns to db table
    * @method addTimeStamps
    * @param  string        $table name of db table
    * @return boolean
    */
    public function addTimeStamps($table){
        $c = $this->addColumn($table,'created_at','datetime',['after'=>'id']);
        $u = $this->addColumn($table,'updated_at','datetime',['after'=>'created_at']);
        return $c && $u;
    }
    
    /**
    * Add Index to db table
    * @method addIndex
    * @param  string   $table db table name
    * @param  string   $name  name of column to add index
    * @return boolean
    */
    public function addIndex($table,$name){
        $sql = "ALTER TABLE {$table} ADD INDEX {$name} ({$name})";
        echo "Adding Index $name To $table \n";
        return !$this->_db->query($sql)->error();
    }
    
    /**
    * Drops index from table
    * @method dropIndex
    * @param  string    $table db table name
    * @param  string    $name  name of column to drop index
    * @return boolean
    */
    public function dropIndex($table, $name){
        $sql = "DROP INDEX {$name} ON {$table}";
        echo "Dropping Index $name From $table \n";
        return !$this->_db->query($sql)->error();
    }

    /**
    * Adds deleted column to db table to be used for soft deleting rows
    * @method addSoftDelete
    * @param  string        $table name of table to add soft delete to
    */
    public function addSoftDelete($table){
        $this->addColumn($table, 'deleted', 'tinyint');
        $this->addIndex($table, 'deleted');
    }
    
    protected function _textColumn($attrs){
        return "TEXT";
    }

    protected function _varcharColumn($attrs){
        $params = $this->_parsePrecisionScale($attrs);
        return "VARCHAR".$params;
    }

    protected function _charColumn($attrs){
        $params = $this->_parsePrecisionScale($attrs);
        return "CHAR".$params;
    }
    
    protected function _yearColumn($attrs){
        return "YEAR(4)";
    }

    protected function _timeColumn($attrs){
        return "TIME";
    }

    protected function _timestampColumn($attrs){
        return "TIMESTAMP";
    }

    protected function _datetimeColumn($attrs){
        return "DATETIME";
    }

    protected function _dateColumn($attrs){
        return "DATE";
    }

    protected function _bitColumn($attrs){
        return "BIT(".$attrs['size'].")";
    }
    
    protected function _doubleColumn($attrs){
        $params = $this->_parsePrecisionScale($attrs);
        return "DOUBLE".$params;
    }

    protected function _floatColumn($attrs){
        $params = $this->_parsePrecisionScale($attrs);
        return "FLOAT".$params;
    }

    protected function _decimalColumn($attrs){
        $params = $this->_parsePrecisionScale($attrs);
        return "DECIMAL".$params;
    }

    protected function _bigintColumn($attrs){
        return 'BIGINT';
    }

    protected function _mediumintColumn($attrs){
        return 'MEDIUMINT';
    }

    protected function _smallintColumn($attrs){
        return 'SMALLINT';
    }

    protected function _tinyintColumn($attrs){
        return 'TINYINT';
    }

    protected function _intColumn($attrs){
        return "INT";
    }
    
    protected function _parsePrecisionScale($attrs){
        $precision = (array_key_exists('precision', $attrs))? $attrs['precision'] : null;
        $precision = (!$precision && array_key_exists('size', $attrs))? $attrs['size'] : $precision;
        $scale = (array_key_exists('scale', $attrs))? $attrs['scale'] : null;
        $params = ($precision)? "(" . $precision : "";
        $params .= ($precision && $scale) ? ", " .$scale : "";
        $params .= ($precision) ? ")" : "";
        return $params;
    }

    protected function _orderingColumn($attrs){
        if(array_key_exists('after', $attrs)){
            return "AFTER ".$attrs['after'];
        } else if(array_key_exists('before', $attrs)){
            return "BEFORE ".$attrs['before'];
        } else {
            return "";
        }
    }
    
    /**
    * Add a column to a db table
    * @method addColumn
    * @param  string    $table name of db table
    * @param  string    $name  name of the column
    * @param  string    $type  type of column varchar, text, int, tinyint, smallint, mediumint, bigint
    * @param  array     $attrs attributes such as size, precision, scale, before, after, definition
    * @return boolean
    */
    public function addColumn($table, $name, $type, $attrs = []) {
        $formattedType = call_user_func([$this, $this->_columnTypesMap[$type]], $attrs);
        $definition = array_key_exists('definition', $attrs)? $attrs['definition']." " : "";
        $order = $this->_orderingColumn($attrs);
        $sql = "ALTER TABLE {$table} ADD COLUMN {$name} {$formattedType} {$definition}{$order};";
        echo "Adding Column $name To $table \n";
        return !$this->_db->query($sql)->error();
    }
    
}
