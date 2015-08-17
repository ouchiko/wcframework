<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __destruct()
* - getConnectionError()
* - setReturnType()
* - multi_string_query()
* - queryRows()
* - queryRowsAsArray()
* - queryRow()
* - query()
* - row()
* - getValue()
* - json()
* - obtainRows()
* - numRows()
* - lastInsertID()
* - affectedRows()
* - getError()
* - explodei()
* Classes list:
* - Mysql extends mysqli
*/
namespace AppSpace\Data;

class Mysql extends \mysqli {
    
    // The number of errors before we abort the running script
    const ABORT_ERRORS = 100;
    
    // Number of times to retry query if a deadlock error is encountered
    const DEADLOCK_RETRIES = 3;
    
    // Logging information
    private $_dbResult;
    private $returnFunc;
    private $productName;
    private $logType;
     // Can basename(path)e no_logging, mail_logging or file_logging
    private $emailContact;
    private $errorCount;
    private $replicate;
    private $tablesToReplicate;
    
    function __construct($host, $user, $password) {

        if ( preg_match("/{(.*)}/", $host, $matches)) { 
            $host = $_SERVER[$matches[1]];
        }

        $this->errorCount = 0;
        $this->returnFunc = "fetch_object";
        
        parent::__construct($host, $user, $password);
        parent::set_charset('utf8');
         // php5 defaults to latin1 client charset, which breaks a lot of stuff.
        
    }
    
    function __destruct() {
        parent::close();
    }
    
    public function getConnectionError() {
        return ($this->connect_errno ? $this->connect_error : "");
    }
    
    public function setReturnType($type) {
        if ($type == "object" || $type == "row" || $type == "assoc") $this->returnFunc = "fetch_" . $type;
    }
    
    public function multi_string_query($str) {
        parent::multi_query($str);
    }
    
    public function queryRows($query) {
        $this->query($query);
        
        return $this->obtainRows();
    }
    
    // Returns the response as a numerically indexed array (must be a single field name)
    public function queryRowsAsArray($query, $fieldName) {
        $response = array();
        
        if ($this->query($query)) {
            foreach ($this->obtainRows() as $row) {
                if ($this->returnFunc == "fetch_object") $response[] = $row->$fieldName;
                else if ($this->returnFunc == "fetch_assoc") $response[] = $row[$fieldName];
            }
        }
        
        return $response;
    }
    
    /*
     * $fieldNames can be a string or an array of strings
     *
     * This function will return either a value (if $fieldNames is a string)
     * or an object or array depending on the return type set
     * or the row if $fieldNames is not set
     * or false if the whole thing fails for whatever reason
     *
    */
    
    public function queryRow($query, $fieldNames = false) {
        if (!$this->query($query)) return false;
        
        $response = null;
        
        if ($fieldNames) {
            if (!is_array($fieldNames)) $fieldNames = array(
                $fieldNames
            );
            
            if (count($fieldNames) == 1) $response = $this->getValue($this->row() , $fieldNames[0]);
            else {
                $row = $this->row();
                
                if ($row) {
                    if ($this->returnFunc == "fetch_object") {
                        $response = new stdClass();
                        
                        foreach ($fieldNames as $fieldName) $response->$fieldName = $row->$fieldName;
                    } 
                    else if ($this->returnFunc == "fetch_assoc") {
                        $response = array();
                        
                        foreach ($fieldNames as $fieldName) $response[$fieldName] = $row[$fieldName];
                    }
                }
            }
        } 
        else $response = $this->row();
        
        return $response;
    }
    
    public function query($query) {
        
        $success = false;
        
        if ($query) {
            $queryFmt = preg_replace('/\s{2,}/', ' ', preg_replace('/[\r\n]/', ' ', $query));
            
            $attempt = 1;
            
            do {
                $retry = false;
                $this->_dbResult = parent::query($query);
                
                if (!$this->_dbResult) {
                    
                    // Deadlock error number seems to be 1213
                    if ($this->errno == 1213 && $attempt++ <= self::DEADLOCK_RETRIES) {
                        
                        // Half second sleep
                        usleep(500000);
                        $retry = true;
                    }
                    
                    // else
                    // 	$this->logError($query);
                    
                } 
                else $success = true;
            }
            while ($retry);
        } 
        return $success;
    } 
    public function row() {
        if ($this->_dbResult) {
            $tmp = $this->returnFunc;
            return $this->_dbResult->$tmp();
        }
    }
    
    private function getValue($row, $field) {
        $value = "";
        
        if ($row) {
            if ($this->returnFunc == "fetch_object") $value = $row->$field;
            else if ($this->returnFunc == "fetch_assoc") $value = $row[$field];
        }
        
        return $value;
    }
    
    /**
     * json
     * A correct implementation of JSON - we didn't have the extension installed to make this
     * work  previously. I will need to check existing code to make sure they work correctly with
     * this new method before I update "getAsJSON"
     */
    public function json() {
        if ($this->_dbResult) {
            $tmp = $this->returnFunc;
            
            while ($row = $this->_dbResult->$tmp()) if ($row) $rows[] = $row;
            
            return json_encode($rows);
        }
    }
    
    public function obtainRows() {
        $results = array();
        
        if ($this->_dbResult) {
            $tmp = $this->returnFunc;
            while ($result = $this->_dbResult->$tmp()) $results[] = $result;
            
            $this->_dbResult->close();
            $this->_dbResult = null;
        }
        
        return $results;
    }
    
    public function numRows() {
        return ($this->_dbResult ? $this->_dbResult->num_rows : 0);
    }
    
    public function lastInsertID() {
        return $this->insert_id;
    }
    
    public function affectedRows() {
        return $this->affected_rows;
    }
    
    public function getError() {
        return $this->error;
    }
    
    // Case insensitive explode function
    private function explodei($separator, $string, $limit = false) {
        $len = strlen($separator);
        
        for ($i = 0;;$i++) {
            if (($pos = stripos($string, $separator)) === false || ($limit && $i > $limit - 2)) {
                $result[$i] = $string;
                break;
            }
            
            $result[$i] = substr($string, 0, $pos);
            $string = substr($string, $pos + $len);
        }
        
        return $result;
    }
}
?>
