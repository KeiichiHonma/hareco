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
        
    }
    function weathers(){
        if(!isset($_POST['tab_id']) || !($id = explode('tab',$_POST['tab_id'])) || !is_numeric($id[1]) || $id[1] > 8) return;
        $page = 1;
        if(isset($_POST['page']) && is_numeric($_POST['page'])) $page = $_POST['page'];

        /*
        天気予想
        */
        $orderExpression = "area_id ASC,date ASC";
        $weather = 'shine';
        $daytime_shine_sequenceExpression = null;
        $day_type = array('type'=>'index','value'=>1);//休日+祝日
        if($page > 1){
            $start_datetime = strtotime("+8 day") + (86400 * 7 * ($page - 1));//strtotime("+8 day");
            $start_date = date("Y-n-j",$start_datetime);
            $end_date = date("Y-n-j",$start_datetime + (86400 * 7));
        }else{
            $start_date = null;//指定なし。直近
            $end_date = null;
        }
        $region_areas = $this->Area_model->getAreasByRegionId($id[1]);
        $paging = count($region_areas);//関東圏
        $futuresData = $this->Future_model->getFutures('index', $id[1], $orderExpression, $page,$weather, $daytime_shine_sequenceExpression, $day_type, $start_date,$end_date,$paging);//関東

        $futures = $futuresData['data'];

        if(empty($futures)) return;
        $i = 1;
        $class_array = array();
        $local_sp_style = array();
        foreach ($futures as $future){
            if($i == 8) break;
            if($future->day_of_the_week == 6){
                $class_array[$i] = 'day0'.$i.' sat';
            }elseif($future->day_of_the_week == 7){
                $class_array[$i] = 'day0'.$i.' sun';
            }else{
                $class_array[$i] = 'day0'.$i;
                $local_sp_style[] = '#weather td.day0'.$i;
            }
            $i++;
        }

        $html = '';
            $html .= '<table class="weather_index">';
                $html .= '<tr class="title">';
                    $html .= '<th class="cell01">日付</th>';
                    $html .= '<td class="'.$class_array[1].'">'.$futures[0]->month.'/'.$futures[0]->day.get_day_of_the_week($futures[0]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[2].'">'.$futures[0]->month.'/'.$futures[1]->day.get_day_of_the_week($futures[1]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[3].'">'.$futures[0]->month.'/'.$futures[2]->day.get_day_of_the_week($futures[2]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[4].'">'.$futures[0]->month.'/'.$futures[3]->day.get_day_of_the_week($futures[3]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[5].'">'.$futures[0]->month.'/'.$futures[4]->day.get_day_of_the_week($futures[4]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[6].'">'.$futures[0]->month.'/'.$futures[5]->day.get_day_of_the_week($futures[5]->day_of_the_week,FALSE,FALSE).'</td>';
                    $html .= '<td class="'.$class_array[7].'">'.$futures[0]->month.'/'.$futures[6]->day.get_day_of_the_week($futures[6]->day_of_the_week,FALSE,FALSE).'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<th class="cell02">'.$this->data['all_areas'][$futures[0]->area_id]->area_name.'</th>';
                    $td_number = 1;
                    $count = count($futures);
                    for ($index=0;$index<$count;$index++){
                        if ($index > 0 && $index != $count - 1 && $index % 7 == 0){
                            $td_number = 1;
                            $html .= '</tr><tr>';
                            $html .= '<th class="cell02">'.$this->data['all_areas'][$futures[$index]->area_id]->area_name.'</th>';
                        }
                        $html .= '<td class="'.$class_array[$td_number].'"><img src="images/weather/icon/'.$futures[$index]->daytime_icon_image.'" alt="'.$futures[$index]->daytime.'" class="icon" /><br />'.$futures[$index]->daytime.'</td>';
                        $td_number++;
                    }
            $html .= '</table>';
            $mes_text = json_encode(array('html'=>$html,'tab_id'=>$id[1]));
        echo $mes_text;
    }
    function futures()
    {
        /*
        spの場合は画面表示6個になります。
        */
        $this->data['all_holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
        
        $sp = isset($_POST['sp']) && is_numeric($_POST['sp']) ? $_POST['sp'] : 1;

        $date = isset($_POST['date']) ? $_POST['date'] : '';
        $daytime_shine_sequence = isset($_POST['daytime_shine_sequence']) && is_numeric($_POST['daytime_shine_sequence']) ? $_POST['daytime_shine_sequence'] : 1;
        $weather = isset($_POST['weather']) ? $_POST['weather'] : '';
        //day type
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//土日祝日
        if( isset($_POST['sp_day_type']) && !empty($_POST['sp_day_type'])){
            $day_type = array('type'=>'multi','value'=>$_POST['sp_day_type']);
            
        }elseif( isset($_POST['day_type']) && !empty($_POST['day_type']) ){
            $day_type = array('type'=>'multi','value'=>explode(',',$_POST['day_type']));
        }

        if($sp == 1){
            //$date = isset($_POST['date']) ? $_POST['date'] : '';
            //$daytime_shine_sequence = isset($_POST['daytime_shine_sequence']) && is_numeric($_POST['daytime_shine_sequence']) ? $_POST['daytime_shine_sequence'] : 1;
            //$weather = isset($_POST['weather']) ? $_POST['weather'] : '';
        }else{
            //$date = isset($_POST['sp_date']) ? $_POST['sp_date'] : '';
            //$daytime_shine_sequence = isset($_POST['sp_daytime_shine_sequence']) && is_numeric($_POST['sp_daytime_shine_sequence']) ? $_POST['sp_daytime_shine_sequence'] : 1;
            //$weather = isset($_POST['sp_weather']) ? $_POST['sp_weather'] : '';
        }
        
        $type = isset($_POST['type']) && strlen($_POST['type']) > 0 ? ($sp == 1 ? $_POST['type'] : 'sp' ) : 'area';

        $search_type = isset($_POST['search_type']) && strlen($_POST['search_type']) > 0 ? $_POST['search_type'] : 'area';

        $search_object_id = 0;
        if(isset($_POST['search_object_id']) && is_numeric($_POST['search_object_id'])) $search_object_id = $_POST['search_object_id'];

        $search_keyword = isset($_POST['search_keyword']) && strlen($_POST['search_keyword']) > 0 ? $_POST['search_keyword'] : '';

        $jalan_h_id = 0;
        if(isset($_POST['h_id']) && is_numeric($_POST['h_id'])) $jalan_h_id = $_POST['h_id'];

        $area_id = 1;
        if(isset($_POST['area_id']) && is_numeric($_POST['area_id'])) $area_id = $_POST['area_id'];

        //書式：2012/01/01
        $start_date = null;//指定なし。直近
        if( preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)){
            $start_datetime = strtotime("+8 day");
            $ymd = explode('/',$date);
            if($start_datetime > mktime(0,0,0,$ymd[1],$ymd[2],$ymd[0])){
                $start_date = date("Y-n-j",$start_datetime);
            }else{
                $start_date = str_replace('/','-',$date);
            }
        }
        $page = isset($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] > 0 ? $_POST['page'] : 1;
        $wether_number = is_numeric($weather) ? $weather : 9;
        if($wether_number >= 0 && $wether_number <= 5  ){
            switch ($wether_number){
                case 0:
                    $weather = 'shine';
                break;
                case 1:
                    $weather = 'rain';
                break;
                case 2:
                    $weather = 'cloud';
                break;
                case 3:
                    $weather = 'thunder';
                break;
                case 4:
                    $weather = 'snow';
                break;
                case 5:
                    $weather = 'mist';
                break;
                default:
                    $weather = '';
            }
        }

        $orderExpression = "date ASC";
        $daytime_shine_sequenceExpression = $daytime_shine_sequence > 1 ? ' = '.$daytime_shine_sequence : ' >= '.$daytime_shine_sequence;
        $futuresData = $this->Future_model->getFutures($type, $area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);

        $html = 'error';
        if(!empty($futuresData['data'])){
            $html = '';
            $chunk = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
            foreach ($chunk as $key => $futures){
                $html .= '<div class="line'.($key >= $this->config->item('sp_display_number') ? ' undisp' : '').' cf">';
                foreach ($futures as $future){
                        $html .= '<div class="box">';
                            //ここのURLのは画面によって変わります
                            if($search_type == 'area'){
                                $html .= '<a href="/area/date/'.$future->area_id.'/'.$future->date.'">';
                            }elseif($search_type == 'spring'){
                                if($jalan_h_id > 0){
                                    $html .= '<a href="/spring/date/'.$search_object_id.'/'.$jalan_h_id.'/'.$future->area_id.'/'.$future->date.'">';
                                }else{
                                    $html .= '<a href="/spring/date/'.$search_object_id.'/0/'.$future->area_id.'/'.$future->date.'">';
                                }
                            }elseif($search_type == 'airport'){
                                $html .= '<a href="/airport/date/'.$search_object_id.'/'.$future->date.'">';
                            }elseif($search_type == 'leisure'){
                                $html .= '<a href="/leisure/date/'.$search_object_id.'/'.$future->date.'">';
                            }elseif($search_type == 'search'){
                                $html .= '<a href="/search?keyword='.urlencode($search_keyword).'&date='.urlencode(str_replace('-','/',$future->date)).'">';
                            }

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
        }else{
            print '<div class="empty">指定条件で提案できる日程がありません。</div>';
            die();
        }
        print $html;
        die();
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */