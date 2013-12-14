<?php

namespace model;

class SRData {

    static $staticDataFile = "srjson.json";
    static $srurl = "http://api.sr.se/api/v2/traffic/messages?format=json&indent=true&pagination=false";
    //static $srurl = "tempdata.txt";
    static $cacheSeconds = 300;
    
    private $jsondata;
    
    //Constructor
    public function __construct($maxObjects = 100) {

        if (filemtime(self::$staticDataFile) < time() - self::$cacheSeconds) {

            $this->jsondata = json_decode(file_get_contents(self::$srurl))->messages;

            //Fix the ugly dates
            $this->fixCorrectTimestamps();
            $this->sortAfterDate();
            $this->reduceData($maxObjects);
            $this->saveCache();
        }
        else {
            //Load cache
            $this->jsondata = json_decode(file_get_contents(self::$staticDataFile));
        }
    }
    
    //Returerar json
    public function rawOutput() {
        return json_encode($this->jsondata);
    }
    
    //Privat funktion som fixar till timestamps
    private function fixCorrectTimestamps() {
        
        $count = count($this->jsondata);
        
        for($i = 0; $i < $count; $i++) {
            preg_match('/([0-9]{13})([\-\+0-9]{5})/', $this->jsondata[$i]->createddate, $parts);
            
            $seconds = round(intval($parts[1])/1000);
        
            $this->jsondata[$i]->createddate = $seconds;
        }
    }
    
    //Privat funktion för att sortera listan
    private function sortAfterDate($latestFirst = true) {
        $count = count($this->jsondata);
        for($i = 0; $i < $count - 1; $i++) {
            //Mindre än nästa => ska hamna längre ner
            if ($this->jsondata[$i]->createddate < $this->jsondata[$i + 1]->createddate) {
                $temp = $this->jsondata[$i + 1]->createddate;
                $this->jsondata[$i + 1]->createddate = $this->jsondata[$i]->createddate;
                $this->jsondata[$i]->createddate = $temp;
                //Ändring har skett, kör om från början!
                $i = -1;
            }
        }
        //Skulle man önska först tillagda först i listan fixar vi det,
        //annars är jsondata nu sorterad fallande på createddate
        if (!$latestFirst) {
            $this->jsondata = array_reverse($this->jsondata);
        }
    }
    
    //Minimera antal objekt till $maxData
    private function reduceData($maxData) {
        if (count($this->jsondata) > $maxData) {
            array_splice($this->jsondata, $maxData);
        }
    }
    
    //Spara undan som statisk fil
    private function saveCache() {
        file_put_contents(self::$staticDataFile, json_encode($this->jsondata));
    }
}