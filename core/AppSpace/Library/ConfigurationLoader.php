<?php
/**
 * Class and Function List:
 * Function list:
 * - load()
 * Classes list:
 * - ConfigurationLoader
 */
namespace AppSpace\Library;

class ConfigurationLoader {
    
    /**
     * Load a configuration.
     * @param type $configuration_directory
     * @param type $configuration_source
     * @return type
     */
    public static function load($configuration_directory, $configuration_source) {
        
        /* Define the source */
        $source = $configuration_directory . '/' . $configuration_source . '.json';
        
        /* Grab the config */
        $json_content = (file_exists($source)) ? json_decode(file_get_contents($source)) : file_get_contents($source);
        return $json_content;
    }
}
?>