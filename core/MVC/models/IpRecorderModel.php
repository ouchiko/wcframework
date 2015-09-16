<?php
namespace MVC\Models;

class IpRecorderModel
{
    
    private $source = null;

    public function __construct() { 
    	$this -> source = $_SERVER['DOCUMENT_ROOT']."/logging/ip.txt";
    }
    
    public function pushIpAddress($ip_address) {
        $fp = @fopen($this->source, "w");
        @fputs($fp, $ip_address);
        @fclose($fp);
    }
    
    public function getIpAddress() {
        return file_get_contents($this->source);
    }
}
