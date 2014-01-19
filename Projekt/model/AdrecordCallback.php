<?php

namespace model;

class AdrecordCallback {
    public $ordervalue;
    public $ordertype;
    public $commission;
    public $epi;
    public $ip;
    public $clicktime;
    public $referrer;
    public $ordertime;
    public $programid;
    public $channelid;
    public $ect_keyword;
    public $ect_searchengine;
    public $ect_time;
    public $ect_rank;
    public $transactionid;
    
    //For AdrecordAPI
    public $channel;
    public $program;
    
    public $adrecordAPI;
    
    public function __construct(AdrecordAPI $a_adrecordAPI) {
        //First some sanitization
        $cleanArray = array_map("urldecode", $_GET);
        $cleanArray = array_map(array($this, "sanitize_string"), $cleanArray);
        
        //Put into membervariables
        //No need to check because all are not required
        $this->ordervalue =         $cleanArray['ordervalue'];
        $this->ordertype =          $cleanArray['ordertype'];
        $this->commission =         $cleanArray['commission'];
        $this->epi =                $cleanArray['epi'];
        $this->ip =                 $cleanArray['ip'];
        $this->clicktime =          $cleanArray['clicktime'];
        $this->referrer =           $cleanArray['referrer'];
        $this->ordertime =          $cleanArray['ordertime'];
        $this->programid =          $cleanArray['programid'];
        $this->channelid =          $cleanArray['channelid'];
        $this->ect_keyword =        $cleanArray['ect_keyword'];
        $this->ect_searchengine =   $cleanArray['ect_searchengine'];
        $this->ect_time =           $cleanArray['ect_time'];
        $this->ect_rank =           $cleanArray['ect_rank'];
        $this->transactionid =      $cleanArray['transactionid'];
        $this->network =            "Adrecord";
        
        //Get additional stuff from AdrecordAPI
        $this->adrecordAPI = $a_adrecordAPI;
                                
        $this->channel = $this->sanitize_string($this->adrecordAPI->getChannel($this->channelid));
        $this->program = $this->sanitize_string($this->adrecordAPI->getProgram($this->programid));
        
    }
    private function sanitize_string($dirtyString) {
        return preg_replace("/[^0-9a-zA-ZåäöÅÄÖ.:_\/\-]/", "", $dirtyString);
    }
    
    public function dump() {
        var_dump($this);
    }
    
}