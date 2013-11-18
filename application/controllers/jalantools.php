<?php
class Jalantools extends CI_Controller {

    var $CI;
    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
        if ( ! $this->input->is_cli_request() )     {
            //die('Permission denied.');
        }
        $this->load->library('tank_auth');
        $this->load->library('weather_lib');
        
        //connect database
        $this->load->database();

    }
    private $api_key = 'cap142351e1c1a';
    private $handleTagList = array('td');
    private $html = '';
    private $csv_data = array();
    
    function doDaily($back_day = 1){
        $this->importWeatherBackDay($back_day);
        $this->createFutureForNextYearDaily($back_day);
        $this->updateCorrectBackDay($back_day);
        $this->updateSequenceBackDay($back_day);
        $this->updateOddsBackDay($back_day);
    }
    
    function importHotel(){
        $order = 4;//人気順
        $this->load->model('Spring_model');

        $springs = $this->Spring_model->getAllSpringsFlipJalanOnsenArea();
        foreach ($springs as $jalan_o_area => $value){
            $url="http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key=$this->api_key&o_area_id=$jalan_o_area&order=$order&count=100";
            //$hotels = @simplexml_load_file("/usr/local/apache2/htdocs/mirai_tenki/application/test.xml");
            $hotels = @simplexml_load_file($url);
var_dump($hotels);
die();
            $hotelData = array();
            $test = array();
            
            $i = 0;
            foreach ($hotels->Hotel as $hotel){
                $AccessInformation = array();
                foreach ($hotel as $item){
                    if($item->getName() != 'Area' && $item->getName() != 'WifiHikariStation'){
                        if($item->getName() == 'AccessInformation'){
                            $AccessInformation[] = strval($item->attributes()->name) . ':' . strval($item);
                        }
                        $hotelData[$i][$item->getName()] = strval($item);
                    }
                }
                $i++;
            }
var_dump($hotelData);
die();
        }
        
        
    }


}
?>
