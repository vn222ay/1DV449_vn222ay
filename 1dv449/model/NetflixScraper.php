<?php

namespace model;

require_once("NetflixMovie.php");

class NetflixScraper {
    
    private $email;
    private $password;
    private $authURL;
    private $movieAuthURL;
    private $picDir = "pictures/";
    private $netflixMovies = array();
    
    private $db;
    
    private $cookieFile;
    
    private $loginurl = "https://signup.netflix.com/Login";
    private $scrapeurl = "http://movies.netflix.com/WiRecentAdditions";
    
    public function __construct(PersistanceDatabase $db, $a_email, $a_password) {
        $this->email = $a_email;
        $this->password = $a_password;
        $this->cookieFile = dirname(__FILE__) . "/cookies.txt";
        
        $this->db = $db;
        
        //Rensa upp innan vi kör igång (databasen och cookies)
        $this->db->truncateTable();
        unlink($this->cookieFile);
        
    }
    public function scrape() {
        /*
         * -> Få inloggningssidan
         * -> Kolla authURL
         * -> logga in med uppgifter och authURL
         * -> Ladda filmsidan
         * -> Plocka hem info från varje films egna sida som skapas med trkid och movieId
         */
        //Ny curl
        $curl = $this->newCURL($this->loginurl);
        
        //Få loginsidan
        $data = curl_exec($curl);
        
        //Sätt authURL för inloggning
        $this->setAuthURL($data);
        
        //Stäng nuvarande curl
        curl_close($curl);
        
        echo "DEBUG authURL ska vara hämtad<br />";
        
        //Ny curl för själva inloggningen med post
        $curl_login = $this->newCURL($this->loginurl, $this->getFormArray());
        
        //Kör inloggningen
        $data = curl_exec($curl_login);
        
        //Stäng curlen
        curl_close($curl_login);
        
        echo "DEBUG Inloggning ska vara klar<br />";
        
        //Dags att jaga lite data.
        $scrape_curl = $this->newCURL($this->scrapeurl);
        
        //få hem sidan som innehåller filmerna
        $data = curl_exec($scrape_curl);
        
        //Krama ut lite filmer från innehållet
        $this->extractMovies($data);
        
        curl_close($scrape_curl);
        
        echo "DEBUG Skrapning genomförd<br />";
    }
    
    /*
     * @param String $url URL som ska skrapas
     * @param Array / BOOL $post som ska skickas med som post.
     * @return curl
     */
    private function newCURL($url, $post = FALSE) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_Setopt($curl, CURLOPT_HEADER, FALSE);
        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:25.0) Gecko/20100101 Firefox/24.0");
        
        if ($post != FALSE) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        
        curl_setopt($curl, CURLOPT_URL, $url);
        Sleep(1);
        return $curl;
    }
    private function setAuthURL($data) {    
        $dom = new \DomDocument();
        if ($dom->loadHTML($data)) {
            $xpath = new \DOMXPath($dom);
            $authCode = $xpath->query('//div[@class = "form-container"]//input[@name = "authURL"]');
            if ($authCode != FALSE) {
                $this->authURL = $authCode->item(0)->attributes->item(2)->value;
            }
        }
        if (strlen($this->authURL) < 1) {
            throw new \Exception("Ingen authURL kunde sättas (NetflixScraper::setAuthURL())");
        }
    }
    
    public function extractMovies($data) {
    
        $movieURLs = array();
        $dom = new \DomDocument();
        
        if ($dom->loadHTML($data)) {
            $xpath = new \DOMXPath($dom);
            
            $movies = $xpath->query('//div[@id = "slider_0"]//a');
            if ($movies != FALSE) {
                foreach ($movies as $movie) {
                    $movieid = 0;
                    $trkid = 0;
                    if (preg_match('/movieid=(.*)&trkid/', $movie->attributes->item(2)->value, $r)) {
                        $movieid = trim($r[1]);
                    }
                    if (preg_match('/trkid=(.*)&tctx/', $movie->attributes->item(2)->value, $r)) {
                        $trkid = trim($r[1]);
                    }
                    $newMovieURL = "http://movies.netflix.com/WiMovie/NOTNEEDED/" . $movieid . "?trkid=" . $trkid;
                    $movieURLs[] = $newMovieURL;
                    //$this->addMovieFromURL("http://movies.netflix.com/WiMovie/NOTNEEDED/" . $movieid . "?trkid=" . $trkid);
                }
                
                //Vi har nu alla URLer, dags att plocka hem HTMLen från sidorna och plocka ut datan
                
                echo "DEBUG Arraylängd: " . count($movieURLs) . "<br />";
                
                for ($i = 0; $i < count($movieURLs); $i++) {
                    echo "DEBUG Kör en film nu<br />";
                    $curl = $this->newCURL($movieURLs[$i]);
                    $movieHtml = curl_exec($curl);
                    $this->addMovieFromURL($movieHtml);
                }
            }
        }
    }
    
    public function addMovieFromURL($html) {
        //echo " --------- " . $html . " .......... ";
        $dom = new \DomDocument();
        if ($dom->loadHTML($html)) {
            $xpath = new \DOMXPath($dom);
            
            $movieId = 0;
            $movieUrl = $xpath->query('//div[@id = "displaypage-overview-image"]//a');
            $movieUrl = $movieUrl->item(0)->attributes->item(0)->value;
            if (preg_match('/movieid=(.*)&trkid/', $movieUrl, $r)) {
                $movieId = trim($r[1]);
            }
            
            $title = $xpath->query('//h1[@class = "title"]');
            $title = $title->item(0)->nodeValue;
            
            $pic = $xpath->query('//img[@class="boxShotImg"]');
            $pic = $pic->item(0)->attributes->item(2)->value;
            
            $year = $xpath->query('//span[@class = "year"]');
            $year = $year->item(0)->nodeValue;
            
            $summary = $xpath->query('//p[@class="synopsis"]');
            $summary = $summary->item(0)->nodeValue;
            
            $duration = $xpath->query('//span[@class="duration"]');
            $duration = $duration->item(0)->nodeValue;
            
            $myRating = $xpath->query('//span[@class="rating"]');
            $myRating = $myRating->item(0)->nodeValue;
            
            //Länk till filmen
            
            //Först sparar vi ner bilden, sen skapar en NetflixMovie instans som vi lägger till i den privata arrayen
            $picData = file_get_contents($pic);
            $filename = $this->picDir . $movieId . ".jpg";
            $fp = fopen($filename, 'w');
            if ($fp !== FALSE) {
                fwrite($fp, $picData);
            }
            else {
                echo "DEBUG Problem with fopen()<br />";
            }
            $this->netflixMovies[] = new NetflixMovie($movieId, $filename, $title, $duration, $myRating, $summary, $movieUrl, $year);
            echo "DEBUG --> " . $movieId . " -- " . $title . " -- " . $pic . " -|- " . $year . " -|- " . $summary . " -|- " . $duration . " -|- " . $myRating . "<br />";
        }
        else {
            echo "DEBUG Problem with DOM->loadHTML() in NetflixScraper->addMovieFromURL()";
        }
    }

    public function saveMovies() {
        for ($i = 0; $i < count($this->netflixMovies); $i++) {
            echo count($this->netflixMovies) . " --- $i ----";
            $this->db->insertNetflixMovie($this->netflixMovies[$i]);
        }
        //Loopa igenom alla
        //Skapa ny Netflix-movie-instans
        //Skicka denna till DAL-klass som tar emot netflix-movie-instans
    }
    
    private function getFormArray() {
        return array("email" => $this->email, "password" => $this->password, "authURL" => $this->authURL);
    }
}

/*
         $keepGoing = TRUE;
        $i = 0;
        while ($keepGoing && $i < 5) {
            $location = "";
            if (preg_match('/Location: (.*)/i', $data, $r)) {
                $location = trim($r[1]);
                curl_setopt($curl, CURLOPT_URL, $location);
                echo "\n\n\nLocation: " . $location;
                $data = curl_exec($curl);
                echo $data;
            }
            else {
                $keepGoing = FALSE;
            }
            $i++;
        }
 
 */

         /*
        if (preg_match('/\"xsrf\":\"(.*)\",\"COUNTRY/', $data, $r)) {
            $authCode = trim($r[1]);
            $this->movieAuthURL = $authCode;
            echo "FOUND AUTH: " . $this->movieAuthURL;
        }
        else {
            die("No AuthURL found in content");
        }
        */