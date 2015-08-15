<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getLocalStreetInformation()
 * - formatDataType()
 * - getPostcode()
 * Classes list:
 * - PostcodeModel
 */
namespace MVC\Models;

class PostcodeModel {
    
    private $choices = null;
    private $db = null;
    private $postcode = null;
    private $extension = null;
    private $street_count_limit = 20;
    private $dbref = array(
        "postcodes" => "CodePoint.Postcodes"
    );
    
    /**
     * Create the default Model
     * @param type $db
     * @param type $postcode
     * @param type $extension
     * @param type $choices
     * @return type
     */
    public function __construct($db, $postcode, $extension, $choices) {
        $this->choices = explode(",", $choices);
        $this->db = $db;
        $this->postcode = $postcode;
        $this->extension = $extension;
    }
    
    /**
     * Obtains the local street information from the presented postcode data
     * @param type $mineasting
     * @param type $minnorthing
     * @param type $maxeasting
     * @param type $maxnorthing
     * @return type
     */
    public function getLocalStreetInformation($mineasting, $minnorthing, $maxeasting, $maxnorthing) {
        
        global $logger;
        
        $logger->addInfo("Entering street resolution");
        
        /* Make sure we have the right details to do this */
        if (isset($this->choices) && in_array("streets", $this->choices)) {
            
            $logger->addInfo("Street choices selected");
            
            /* Process the middle point of the road selection */
            $centralx = $maxeasting - (($maxeasting - $mineasting) / 2);
            $centraly = $maxnorthing - (($maxnorthing - $minnorthing) / 2);
            
            /* Caclulate a new min/max values if they're not big enough */
            if (abs($mineasting - $maxeasting) < 1000) {
                $mineasting = $mineasting - 500;
                $maxeasting = $maxeasting + 500;
            }
            if (abs($minnorthing - $maxnorthing) < 1000) {
                $minnorthing = $minnorthing - 500;
                $maxnorthing = $maxnorthing + 500;
            }
            
            /* Run the quuery against the database for more information */
            
            // print $centralx . " - " . $centraly . "<BR>";
            
            $logger->addInfo("Preparing query for streets");
            
            $response_streets_query = sprintf("
            SELECT 
                LOWER(name) as name, classification, centx, centy, settlement, locality, cou_unit, localauth, cent_lat, cent_lon,
                SQRT(POW(centx-%s,2) + POW(centy-%s,2)) as distance
            FROM
                CodePoint.Streets
            WHERE
                centx > '%s' AND centy > '%s' AND centx < '%s' AND centy < '%s'
            ORDER BY 
                distance ASC
            LIMIT 0, %s
            ", $centralx, $centraly, $mineasting, $minnorthing, $maxeasting, $maxnorthing, $this->street_count_limit);
            
            /* Run the query */
            $response_data = $this->db->queryRows($response_streets_query);
            
            $logger->addInfo("Streets returned " . count($response_data));
            
            if ($this->db->error) {
                throw new \Exception("Error in mysql: " . $this->db->error);
            }
            
            return isset($response_data) ? $response_data : array();
        }
    }
    
    /**
     * Receives the origin data array and formats into the correct
     * response type, being JSON, SERIALIZED, XML (Not supported),
     * PRINTR (raw) or just the data lump.
     * @param type $data
     * @return type
     */
    private function formatDataType($data) {
        switch ($this->extension) {
            case "json":
                return json_encode($data);
            break;
            case "ser":
                return serialize($data);
            break;
            case "xml":
                return null;
            break;
            case "printr":
                return print_r($data, true);
            break;
            default:
                return $data;
            break;
        }
    }
    
    /**
     * The core method.. Obtains the relevant postcode information and if applicable
     * adds in the street ifnromatioin.
     * @return type
     */
    public function getPostcode() {
        
        /* Ensure we have a postcode */
        if (strlen($this->postcode) > 2 && preg_match("/[A-Za-z0-9 ]/", $this->postcode)) {
            
            $dbresponse_postcoderows = null;
            $dbresponse_streetrows = null;
            
            /** Remove any spacing from the this->postcode **/
            $this->postcode = str_replace(" ", "", $this->postcode);
            
            /* Run our query rows */
            $dbresponse_postcoderows = $this->db->queryRows(sprintf("
                    SELECT 
                        
                        postcode, easting, northing, latitude, longitude, formatted, 
                        (SELECT CONCAT(area_name,', ',core_text) FROM CodePoint.Areas LEFT JOIN CodePoint.AreaCodes ON AreaCodes.core_type = Areas.`core_type` WHERE Areas.area_code = Postcodes.admin_ward_code) as admin_ward,
                        (SELECT CONCAT(area_name,', ',core_text) FROM CodePoint.Areas LEFT JOIN CodePoint.AreaCodes ON AreaCodes.core_type = Areas.`core_type` WHERE Areas.area_code = Postcodes.admin_district_code) as admin_district
                    FROM
                        CodePoint.Postcodes 
                    WHERE
                        postcode LIKE '%s%%' LIMIT 0,20", $this->postcode));
            
            /* Take the first record so we can get the min/max */
            if (isset($dbresponse_postcoderows) && count($dbresponse_postcoderows) > 0) {
                
                $mineasting = $minnorthing = 100000000;
                $maxeasting = $maxnorthing = 0;
                
                foreach ($dbresponse_postcoderows as $tmp) {
                    if ($mineasting > $tmp->easting) $mineasting = $tmp->easting;
                    if ($maxeasting < $tmp->easting) $maxeasting = $tmp->easting;
                    if ($minnorthing > $tmp->northing) $minnorthing = $tmp->northing;
                    if ($maxnorthing < $tmp->northing) $maxnorthing = $tmp->northing;
                }
                
                //print "mm: " . $mineasting . ", " . $minnorthing . " - " . $maxeasting . ", " . $maxnorthing . "<BR>";
                
                $dbresponse_streetrows = $this->getLocalStreetInformation($mineasting, $minnorthing, $maxeasting, $maxnorthing);
                
                $collated_arrayset = array();
                $collated_arrayset['postcodes'] = $dbresponse_postcoderows;
                $collated_arrayset['streets'] = $dbresponse_streetrows;
                
                $formatted_response = $this->formatDataType($collated_arrayset);
                
                return $formatted_response;
            } 
            else {
                return $this->formatDataType(array());
            }
        } 
        else {
            throw new \Exception("Postcode is incorrect length");
        }
    }
}
?>