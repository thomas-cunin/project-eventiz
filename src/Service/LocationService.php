<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationService {

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAdress($long, $lati)
    {
        $url = 'https://api-adresse.data.gouv.fr/reverse/?lon='. $long .'&lat='. $lati;
        $response = $this->client->request(
            'GET',
            $url
        );
        $content = $response->toArray();
        if (isset($content)){
            return $content;
        }
            return null;
        
    }
    public function getDistance($coord1, $coord2, $unit = 'K'): float
    {
        $coordonates1 = explode(",", $coord1);
        $lat1 = $coordonates1[0];
        $lon1 = $coordonates1[1];
        $coordonates2 = explode(",", $coord2);
        $lat2 = $coordonates2[0];
        $lon2 = $coordonates2[1];

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}