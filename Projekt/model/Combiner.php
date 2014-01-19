<?php

namespace model;

class Combiner {
    
    private $dataHolder;
    private static $nodeUrl = "http://127.0.0.1/sale";
    private static $nodePort = 8080;
    private static $cacheFile = "cache.json";
    
    private static $maxCacheLength = 10;
    
    public function __construct(AdrecordCallback $a_adrecordCallback, FreegeoipAPI $a_freegeoipAPI) {
        $this->dataHolder = (object)array_merge((array)$a_adrecordCallback, (array)$a_freegeoipAPI);
        $this->dataHolder = json_encode($this->dataHolder);
    }
    public function pushToNode() {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dataHolder);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        
        curl_setopt($curl, CURLOPT_URL, self::$nodeUrl);
        curl_setopt($curl, CURLOPT_PORT, self::$nodePort);
        echo "Före: " . $this->dataHolder;
        $data = curl_exec($curl);
        
        echo $data;
        
        curl_close($curl);
    }
    
    public function updateCache() {
        
        $cacheArray = array();
        $rawData = file_get_contents(self::$cacheFile);
        if (strlen($rawData) > 0) {
            echo "<br />--- Finns, så lägger till";
            $cacheArray = json_decode($rawData, true);
            $cacheArray[] = json_decode($this->dataHolder, true);    
        }
        else {
            //Does not exist so create new one
            $cacheArray[] = json_decode($this->dataHolder, true);
        }
        
        //If cache is too big, remove the first/earliest elements
        if (count($cacheArray) - self::$maxCacheLength > 0) {
            //Removing
            array_splice($cacheArray, 0, count($cacheArray) - self::$maxCacheLength);
        }
        
        file_put_contents(self::$cacheFile, json_encode($cacheArray, JSON_PRETTY_PRINT));
    }
}
