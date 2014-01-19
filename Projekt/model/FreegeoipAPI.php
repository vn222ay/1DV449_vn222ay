<?php

namespace model;

class FreegeoipAPI {
    
    private static $apiUrl = "http://freegeoip.net/json/";
    public $geoObject;
    
    public function __construct($a_ip) {
        $data = file_get_contents(self::$apiUrl . $a_ip);
        if (strlen($data) > 0 && false) {
            $this->geoObject = json_decode(file_get_contents(self::$apiUrl . $a_ip));
        }
        else {
            //If API is down return below json object
            $this->geoObject = json_decode("{\"latitude\": \"62\", \"longitude\": \"15\", \"city\": \"Hittades ej\"}");
        }
        
        $this->geoObject = (object)array_map(array($this, "sanitize_string"), (array)$this->geoObject);
        
    }
    private function sanitize_string($dirtyString) {
        return preg_replace("/[^0-9a-zA-ZŒŠš€….:_\/\-]/", "", $dirtyString);
    }
}