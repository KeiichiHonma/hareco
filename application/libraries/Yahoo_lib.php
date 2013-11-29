<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Yahoo_lib
{
    function __construct()
    {
        $this->ci =& get_instance();
        define("GEO_CODE_API_URL", "http://geo.search.olp.yahooapis.jp/OpenLocalPlatform/V1/geoCoder");
        define("MAP_API_URL", "http://map.olp.yahooapis.jp/OpenLocalPlatform/V1/static");
        //define("APP_ID", "dj0zaiZpPWgwWkN3SUtHbXpxViZzPWNvbnN1bWVyc2VjcmV0Jng9ODM-");
        define("APP_ID", "dj0zaiZpPVJOek93SUZwN0RJUCZzPWNvbnN1bWVyc2VjcmV0Jng9Yzc-");
    }
    
    /**
    * getGeoCode
    *
    * @param string address // 検索に使用する文字列
    * @return array ret // 結果配列(緯度、経度、住所、地図のURL)
    **/
    function getGeoCode($address)
    {
        $to_url = GEO_CODE_API_URL;
        $to_url .= "?appid=" . APP_ID;
        $to_url .= "&query=" . urlencode($address);
        //$to_url .= "&ie=UTF-8";
        //$to_url .= "&datum=wgs";
        //$to_url .= "&results=5";
        $xml = @simplexml_load_file($to_url);
        return $xml->Feature->Property->Address;
/*
        if (!$data) return false;
        $ret = array();
        foreach ($data as $key => $value) {
            if ($key == "Feature") {
                list($longitude,$latitude) = explode(",", $value->Geometry->Coordinates);
                $ret[] = array(
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "image_url" => getImageUrl($latitude, $longitude),
                    "address" => $value->Property->Address,
                );
            }
        }
*/
        return $ret;
    }

    /**
    * getImageUrl
    *
    * @param string latitude
    * @param string longitude
    * @return string map_url
    **/
    function getImageUrl($latitude, $longitude)
    {
        $map_url = MAP_API_URL;
        $map_url .= "?appid=" . APP_ID;
        $map_url .= "&lat=" . $latitude;
        $map_url .= "&lon=" . $longitude;
        $map_url .= "&z=10";
        $map_url .= "&width=300";
        $map_url .= "&height=200";
        $map_url .= "&pin1=" . $latitude . "," . $longitude;
        return $map_url;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */