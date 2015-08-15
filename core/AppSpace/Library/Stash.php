<?php
/**
 * Class and Function List:
 * Function list:
 * - is()
 * - set()
 * - get()
 * - del()
 * - reset()
 * Classes list:
 * - Stash
 */
namespace AppSpace\Library;

class Stash {
    private $data = array();
    
    public function is($id) {
        return ($this->data[$id]) ? true : false;
    }
    
    public function set($id, $data) {
        $this->data[$id] = $data;
    }
    
    public function get($id) {
        return $this->data[$id];
    }
    
    public function del($id) {
        unset($this->data[$id]);
    }
    
    public function reset() {
        $this->data = array();
    }
}
?>