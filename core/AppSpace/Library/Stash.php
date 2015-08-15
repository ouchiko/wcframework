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
    
    /**
     * is valid?
     * @param type $id 
     * @return type
     */
    public function is($id) {
        return ($this->data[$id]) ? true : false;
    }
    
    /**
     * Sets a new stash point
     * @param type $id 
     * @param type $data 
     * @return type
     */
    public function set($id, $data) {
        $this->data[$id] = $data;
    }
    
    /**
     * Gets an existing stash
     * @param type $id 
     * @return type
     */
    public function get($id) {
        return $this->data[$id];
    }
    
    /**
     * Removes
     */
    public function del($id) {
        unset($this->data[$id]);
    }
    
    /**
     * Resets
     * @return type
     */
    public function reset() {
        $this->data = array();
    }
}
?>