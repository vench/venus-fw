<?php

namespace app\util;

/**
 * Description of Model
 *
 * @author vench
 */
class Model {
    
    
    /**
     * 
     * @param type $table
     * @param type $modelName
     * @return type
     */
    public static function echoDao($table, $modelName) {
        $rows = self::getSqlCols($table);
        $pkName = self::getPkName($rows);
        $pkNameUp = ucfirst($pkName); 
        $keys = array_map(function($row){
            return ( $row['Field'] );
        }, $rows);
        $keysUpdate = [];
        $keysInsert = [];
        $keysInsertVal = [];
        $valuesUpdate  = [];
        $valuesInsert = [];
        foreach($keys as $key) {
            
            $keyUp = self::getFirstStripName($key);
            
            if ($key != $pkName) {
                $keysUpdate[] = "`{$key}`=:{$key}";
                $keysInsert[] = "`{$key}`";
                $keysInsertVal[] = ":{$key}";
                $valuesInsert[] = "\t\t':{$key}' => \$model->get{$keyUp}(),\n";
            }
            $valuesUpdate[] = "\t\t':{$key}' => \$model->get{$keyUp}(),\n";
        } 
        
        $keysUpdate = join(',', $keysUpdate);
        $valuesUpdate = join('', $valuesUpdate);
        $keysInsert = join(',', $keysInsert);
        $keysInsertVal = join(',', $keysInsertVal);
        $valuesInsert = join('', $valuesInsert);
        
        $classname = self::getFirstStripName($table);
        
        $result =<<<PHP
class  {$classname}Dao { 
    /**
     * 
     * @param int \$offset
     * @param int \$limit
     * @return {$modelName}[]
     */
    public function query(\$offset = 0, \$limit = 10) {
        \$conn = \$this->getConnection(); 
        \$sql = 'SELECT * FROM {$table} '
                . 'LIMIT '. (int)\$offset . ',' . (int)\$limit; 
        \$stmt = \$conn->prepare(\$sql);
        \$stmt->execute();
        \$rows = \$stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(is_array(\$rows)) {
            \$self = \$this;
            return array_map(function(\$row) use(&\$self){
                return \$self->fillModel(\$row);
            }, \$rows);  
        }         
        return [];
    }
    
    /**
     * 
     * @param int \$id
     * @return {$modelName}
     */
    public function get(\$id) {
        \$conn = \$this->getConnection();
        \$sql = 'SELECT * FROM {$table} WHERE {$pkName} =:{$pkName}';
        \$stmt = \$conn->prepare(\$sql);
        
        \$stmt->execute([':{$pkName}'=> \$id]);
        \$row = \$stmt->fetch(\PDO::FETCH_ASSOC);
        if(\$row) {
            return \$this->fillModel(\$row);  
        } 
        return null;
    }
    
    /**
     * 
     * @param {$modelName} \$model
     * @return booelan
     */
    public function save({$modelName} \$model) {
        \$conn = \$this->getConnection();
        if(is_null(\$model->get{$pkNameUp}())) {
            \$sql = 'INSERT INTO {$table} ($keysInsert) '
                    . 'VALUES ({$keysInsertVal})';
            \$stmt = \$conn->prepare(\$sql); 
            return \$stmt->execute([  
{$valuesInsert}                    
                ]);
        }  
        \$sql = 'UPDATE  {$table} SET {$keysUpdate} WHERE {$pkName} =:{$pkName}';
        \$stmt = \$conn->prepare(\$sql);
        return \$stmt->execute([ 
{$valuesUpdate}
        ]);
    }
    
    /**
     * 
     * @param {$modelName} \$model
     * @return boolean
     */
    public function delete({$modelName} \$model) {
        \$conn = \$this->getConnection();
        \$sql = 'DELETE FROM {$table} WHERE {$pkName} =:{$pkName}';
        \$stmt = \$conn->prepare(\$sql);
        return \$stmt->execute([':{$pkName}' => \$model->get{$pkNameUp}()]);
    }

    /**
     * @return int Description
     */
    public function size() {
        \$conn = \$this->getConnection();
        \$sql = 'SELECT COUNT(*) AS c FROM {$table}';
        \$stmt = \$conn->prepare(\$sql);
        
        \$stmt->execute();
        \$row = \$stmt->fetch(\PDO::FETCH_ASSOC);
        return isset(\$row['c']) ? \$row['c'] : 0;
    }
            
    /**
     * 
     * @return \PDO
     */
    public function getConnection() {
        return \app\util\Connection::getConn();
    }
    
    /**
     * 
     * @param array \$row
     * @return {$modelName}
     */
    private function fillModel(array \$row) {
        \$model = new {$modelName}();
        foreach (\$row as \$key => \$value) {
            \$method = 'set' . ucfirst(\$key);
            if(method_exists(\$model, \$method)) {
               call_user_func_array([\$model, \$method], [\$value]); 
               continue;
            }
            \$method = 'set' . (\app\util\Model::getFirstStripName(\$key));
            if(method_exists(\$model, \$method)) {
               call_user_func_array([\$model, \$method], [\$value]); 
               continue;
            }
        }
        return \$model;
    }            
}      
                
PHP;
        
        return $result;
    }

    /**
     * 
     * @param string $table
     */
    public static function echoTableModel($table) { 
        $rows = self::getSqlCols($table);
        
        $classname = self::getFirstStripName($table);
        $result = "<?php\n\nclass\t{$classname}\t{\n\n";
        foreach($rows as $row) {
            $FieldClass = self::getStripName($row['Field']);
            $type = self::getSqlType($row['Type']);
            $result .= "\t/**\n\t*\n\t* @var {$type}\n\t*/\n";
            $result .= "\tprivate \${$FieldClass};\n\n";
        }
        foreach($rows as $row) {
            $Field = self::getFirstStripName($row['Field']);
            $FieldClass = self::getStripName($row['Field']);
            $type = self::getSqlType($row['Type']);
            $result .= "\t/**\n\t*\n\t* @return {$type}\n\t*/\n";
            $result .= "\tpublic function get{$Field}() {\n\t\treturn \$this->{$FieldClass};\n\t}\n\n";
            
            $result .= "\t/**\n\t*\n\t* @param \${$FieldClass} {$type}\n\t*/\n";
            $result .= "\tpublic function set{$Field}(\${$FieldClass}) {\n\t\t\$this->{$FieldClass} = \${$FieldClass};\n\t}\n\n";
        }
        
        $result .= "\n}";
        return $result;
    }
    
    /**
     * 
     * @param string $type
     * @return string
     */
    public static function getSqlType($type) {
        if(strpos($type, 'int') !== false) {
            return 'int';
        }
        return 'string';
    } 
    
    /**
     * 
     * @param string $table
     * @return array
     */
    public static function getSqlCols($table) {
        $conn = Connection::getConn();
        $sql = ' SHOW COLUMNS FROM '.$table.'';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @param array $rows
     * @return string
     */
    public static function getPkName($rows) {
        foreach ($rows as $row) {
            if(isset($row['Key']) && $row['Key'] == 'PRI') {
                return $row['Field'];
            }
        }
        return 'id';
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public static function getFirstStripName($string) {        
        return self::getStripName( ucfirst ($string)  );
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public static function getStripName($string) {        
        return preg_replace_callback('/(_)(\w)/i', function($matches){
            return isset($matches[2]) ? strtoupper($matches[2]) : '';
        },  $string);
    }
}
