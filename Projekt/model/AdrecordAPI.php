<?php

namespace model;

class AdrecordAPI {
    
    private static $apiKey = "HIIqLsSmX0h6UVLq";
    
    public function getChannel($channelId) {
        $data = json_decode(file_get_contents("https://api.adrecord.com/v1/channels/" . intval($channelId) . "?apikey=" . self::$apiKey));
        if ($data->status == "OK") {
            return $data->result->channelName;
        }
        return "Gick ej att hämta";
    }
    public function getProgram($programId) {
        $data = json_decode(file_get_contents("https://api.adrecord.com/v1/programs/" . intval($programId) . "?apikey=" . self::$apiKey));
        if ($data->status == "OK") {
            return $data->result->name;
        }
        return "Gick ej att hämta";
    }
}