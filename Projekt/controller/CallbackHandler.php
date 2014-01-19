<?php

namespace controller;

require("model/AdrecordAPI.php");
require("model/AdrecordCallback.php");
require("model/Combiner.php");
require("model/FreegeoipAPI.php");
require("view/AffiliateSite.php");

class CallbackHandler {
    private $affiliateSite;
    public function __construct() {
        $this->affiliateSite = new \view\AffiliateSite();
    }
    
    public function run() {
        //If we would like to add more networks later, we can use \view\AffiliateSite
        //for checking which network call is comming from
        
        if ($this->affiliateSite->isAdrecord()) {

            $callback = new \model\AdrecordCallback(new \model\AdrecordAPI());

            $freegeoipAPI = new \model\FreegeoipAPI($callback->ip);
            
            $combiner = new \model\Combiner($callback, $freegeoipAPI);

            $combiner->pushToNode();
            
            $combiner->pushToProwl();
            
            $combiner->updateCache();   
        }
    }
}