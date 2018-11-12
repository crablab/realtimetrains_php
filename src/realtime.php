<?php

/*
RTT Encapuslation Class v1.1
Hugh Wells 2018

Please check the documentation for the endpoint here: http://www.realtimetrains.co.uk/api

Nb: this expects $username and $password to be passed as a constructor - else it won't work!
It also needed a copy of Rail References (a converter between varius CORPUS codes), but this has been disabled for now. Please find one here: https://gist.github.com/crablab/93a50eeb338646614287eddc3c2776b1 
*/

namespace realtime;

class realtime{
    protected $username;
    protected $password;
    private $stream;

    /*
    Constructer - takes email and password 
    NOTE: this are NOT your credentials to log into the developer portal

    */

    function __construct($username, $password){
        if(empty($username) || empty($password)){
            throw new \Exception("Missing RTT credentials");
        } else {
            $this->username = $username;
            $this->password = $password;
        } 
    }

    /*

    Rail Reference Streamer - creates a stream for for a file on disk

    */

    private function railReferences($dir){
        if(file_exists($this->railReferences)){
            try{
                $handle = fopen($this->railReferences, "r");
                $this->stream = json_decode(fread($handle, filesize($this->railReferences)), true);
            } catch (Exception $e) {
                $this->stream = "[]";
            } finally {
                return; 
            }
        } else {
            throw new \Exception("Missing Rail References");
        }
    }

    /* 

    Converts TipLoc to CRS using Rail References

    TODO: decide if this should be optionally parmaterised, or just a seperate function to call

    */

    private function tiplocToCrs($tiploc){
        if(empty($tiploc) || empty($this->railReferences)) return false;
  
        foreach($this->stream as $key => $value){
            if($tiploc == $value['TiplocCode']){
                return $value['CrsCode'];
            }
        }
        return false;       
    }

    /*

    Runs the cURL query on the RTT server

    */   

    private function doCurl($query_string){
        $login = $this->username;
        $password = $this->password;
        $url = 'https://api.rtt.io/api/v1/json/' . $query_string;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        $result = curl_exec($ch);
        curl_close($ch);  
        return json_decode($result);
    }

    /*

    A "low level" method that returns the complete RTT object given certain search critera

    */
    
    public function locationList($crs, $type=null, $to=null, $from=null, $timestamp=null, $rows=5){
        if(empty($crs)) return false;

        $data = $this->doCurl(
            "search/" . $crs . 
            (!empty($to) ? "/to/" . $to : null) . 
            (!empty($from) ? "/from/" . $from : null) . 
            (!empty($timestamp) ? "/" . date("Y/m/d/GH", $timestamp) : null) . 
            (strtolower($type) == "/arrivals" ? $type : null));

        $count = count($data->services);

        if($count > $rows){
            echo ($count - 1) + $rows;
            for ($i= $rows; $i <= $count - 1; $i++) { 
                unset($data->services[$i]);
            }        
        }

        return $data;
    }

    /*

    A nicer arrivals board method for a given station 

    */

    public function arrivalsBoard($crs, $from=null, $rows=5){
        $data = $this->locationList($crs, "arrivals", null, $from, null, $rows);

        return $data;
    }

    /*

    A nicer departures board method for a given station 

    */

    public function departuresBoard($crs, $to=null, $rows=5){
        $data = $this->locationList($crs, "departures", $to, null, null, $rows);

        return $data;
    }
}