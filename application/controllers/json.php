<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/theme
     *    - or -
     *         http://example.com/index.php/theme/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/theme/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct(){
        parent::__construct();

        //add helper
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('weather');
        $this->lang->load('setting');
        $this->lang->load('golf');
        $this->load->library('tank_auth');
        $this->load->model('Area_model');
        $this->load->model('Future_model');
        $this->load->model('Weather_model');
        $this->load->library('weather_lib');
        $this->data['all_areas'] = $this->Area_model->getAllAreas();
        $this->data['all_holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
    }

    function futures()
    {
        //sleep(1);
        /*
        spの場合は画面表示6個になります。
        */
        $sp = isset($_POST['sp']) && is_numeric($_POST['sp']) ? $_POST['sp'] : 1;
        $type = isset($_POST['type']) && strlen($_POST['type']) > 0 ? ($sp == 1 ? $_POST['type'] : 'sp' ) : 'area';
        $object_id = 1;
        if(isset($_POST['area_id']) && is_numeric($_POST['area_id'])) $object_id = $_POST['area_id'];

        //書式：2012/01/01
        $start_date = null;//指定なし。直近
        if($sp == 1){
            $date = $_POST['date'];
        }else{
            $date = $_POST['sp_date'];
        }
        if( isset($date) && preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)){
            $start_datetime = strtotime("+8 day");
            $ymd = explode('/',$date);
            if($start_datetime > mktime(0,0,0,$ymd[1],$ymd[2],$ymd[0])){
                $start_date = date("Y-n-j",$start_datetime);
            }else{
                $start_date = str_replace('/','-',$date);
            }
        }
        $page = isset($_POST['page']) && is_numeric($_POST['page']) ? $_POST['page'] : 1;
        $weather = isset($_POST['weather']) && strlen($_POST['weather']) > 0 ? $_POST['weather'] : 'shine';
        if($sp == 1){
            $daytime_shine_sequence = isset($_POST['daytime_shine_sequence']) && is_numeric($_POST['daytime_shine_sequence']) ? $_POST['daytime_shine_sequence'] : 1;
        }else{
            $daytime_shine_sequence = isset($_POST['sp_daytime_shine_sequence']) && is_numeric($_POST['sp_daytime_shine_sequence']) ? $_POST['sp_daytime_shine_sequence'] : 1;
        }
        
        //day type
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//土日祝日
        if( isset($_POST['day_type']) && !empty($_POST['day_type']) ){
            $day_type = array('type'=>'multi','value'=>explode(',',$_POST['day_type']));
        }
        $orderExpression = "date ASC";
        $daytime_shine_sequenceExpression = $daytime_shine_sequence > 1 ? ' = '.$daytime_shine_sequence : ' >= '.$daytime_shine_sequence;
        $futuresData = $this->Future_model->getFutures($type, $object_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $html = 'error';
        if(!empty($futuresData['data'])){
            $html = '';
            $chunk = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
            foreach ($chunk as $key => $futures){
                //$html .= '<div class="line0'.$key.' cf">';
                $html .= '<div class="line'.($key >= $this->config->item('sp_display_number') ? ' undisp' : '').' cf">';
                foreach ($futures as $future){
                        $html .= '<div class="box">';
                            $html .= '<a href="/area/date/'.$future->area_id.'/'.$future->date.'">';
                            //$html .= '<div class="photo"><img src="/images/weather/sunny.jpg" alt="" /><div class="shadow">&nbsp;</div><span>'.$future->daytime.'</span></div>';
                            $html .= '<div class="weather"><img src="/images/weather/icon/'.$future->daytime_icon_image.'" alt="'. $future->daytime.'" /></div>';
                            
                            $html .= '<div class="info">';
                                $html .= '<div class="date">'.$future->month.'/'.$future->day.get_day_of_the_week($future->day_of_the_week,array_key_exists($future->date,$this->data['all_holidays']),TRUE).'</div>';
                                $html .= '<div class="highTemp">最高気温 <em>'.$future->temperature_max.'°C</em></div>';
                                $html .= '<div class="lowTemp">最低気温 <em>'.$future->temperature_min.'°C</em></div>';
                            $html .= '</div>';
                            $html .= '</a>';
                        $html .= '</div>';
                }
                $html .= '</div>';
            }
        }
        print $html;
        die();
        //print json_encode( array('result'=>'success','futures'=>$futuresData['data']) );//for javascript
        //die();
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */