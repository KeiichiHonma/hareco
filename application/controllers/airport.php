<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Airport extends MY_Controller {

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
        $this->load->helper('image');
        $this->load->helper('weather');
        $this->lang->load('setting');
        $this->load->library('tank_auth');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->model('Weather_model');
        $this->load->model('Airport_model');
        $this->load->library('weather_lib');
        $this->load->library('jalan_lib');
        $this->data['all_regions'] = $this->Region_model->getAllregions();
        $this->data['all_areas'] = $this->Area_model->getAllAreas();
        $this->data['all_holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
        $this->data['all_springs'] = $this->Spring_model->getAllSpringsOrderSpringAreaId();
    }

    function index()
    {
        $data['isSlide'] = TRUE;
        $data['isBigSlide'] = TRUE;
        $data['bodyId'] = 'ind';
        
        $this->data['all_airports'] = $this->Airport_model->getAllAirports();
        
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/airport/',$this->lang->line('topicpath_airport'));

        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '各空港', $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('topicpath_airport'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), '各空港');

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.easing.1.3.js','js/jquery.bxslider.js','js/scrolltop.js',)));
        $this->load->view('airport/index', array_merge($this->data,$data));
    }

    /**
     * search airport action
     *
     */
    function show($airport_id)
    {
        $this->data['airport'] = $this->Airport_model->getAirportById($airport_id);
        
        if(empty($this->data['airport'])){
            show_404();
        }
        $data['bodyId'] = 'area';
        $data['airport_id'] = $airport_id;
        $data['area_id'] = $this->data['airport']->area_id;
        $data['search_type'] = 'airport';//sp
        $data['search_object_id'] = $airport_id;//sp
        
        //未来データ/////////////////////////////////////////
        $data['recommend_futures_title'] = $this->data['airport']->airport_name.'の'.$this->lang->line('recommend_futures_title_default');
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 1';//指定なし
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $data['area_id'], $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

        //じゃらんホテル
        $data['hotel_title'] = '晴れの日に'.$this->data['airport']->airport_name.'近辺の温泉へ行く';
        $this->jalan_lib->makeSpringsHotelsByAreaId($data,$data['area_id']);
        $data['stop_line'] = 2;
        
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/airport/',$this->lang->line('topicpath_airport'));
        $data['topicpaths'][] = array('/airport/show/'.$airport_id,$this->data['airport']->airport_name);
        
        //set header title
        $data['og_image'] = site_url('/images/airport/big/airport1.jpg');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->data['airport']->airport_name, $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['airport']->airport_name);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->data['airport']->airport_name);
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array(
            'css/future.css',
            'css/add.css',
            'css/add_sp.css',
            'css/calendar/default.css',
            'css/calendar/default.date.css',
            'css/calendar/default.time.css'
        )));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array(
            'js/jquery.form.js',
            'js/jquery.blockUI.js',
            'js/jquery.easing.1.3.js',
            'js/scrolltop.js',
            'js/future.js',
        )));

        $this->load->view('airport/show', array_merge($this->data,$data));
    }

    function date($airport_id,$date)
    {
        //書式：2012/01/01
        if(0 === preg_match('/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();

        $this->data['airport'] = $this->Airport_model->getAirportById($airport_id);
        
        if(empty($this->data['airport'])){
            show_404();
        }

        $data['bodyId'] = 'area';
        $data['airport_id'] = $airport_id;
        $data['area_id'] = $this->data['airport']->area_id;
        $data['search_type'] = 'airport';//sp
        $data['search_object_id'] = $airport_id;//sp
        
        //dateページでは全て指定日表示なので、この段階で生成
        $data['target_date'] = $date;
        $data['from_ymd'] = explode('-',$date);
        $data['from_datetime'] = mktime(0,0,0,$data['from_ymd'][1],$data['from_ymd'][2],$data['from_ymd'][0]);
        $data['from_display_date'] = date("n/j",$data['from_datetime']);
        $data['from_youbi'] = get_day_of_the_week(date("N",$data['from_datetime']),array_key_exists($data['target_date'],$this->data['all_holidays']),TRUE);
        $data['jalan_date'] = $data['from_ymd'][0].$data['from_ymd'][1].$data['from_ymd'][2];
        $data['display_date'] = date("Y年n月j日",$data['from_datetime']);
        $data['display_date_nj'] = date("n月j日",$data['from_datetime']);
        
        $data['to_datetime'] = $data['from_datetime'] + 86400;
        $data['to_display_date'] = date("n/j",$data['to_datetime']);
        $data['to_youbi'] = get_day_of_the_week(date("N",$data['to_datetime']),array_key_exists(date("Y-m-d",$data['to_datetime']),$this->data['all_holidays']),TRUE);
        
        //共通タイトル
        $this->weather_lib->getTitlesForDate($data,$this->data['airport']->airport_name);
        
        //未来データ
        $data['week_futures'] = $this->Future_model->getFuturesByAreaIdByDateForWeek($data['area_id'],$date);

        if(empty($data['week_futures'])){
            show_404();
        }

        //天気の歴史
        $this->weather_lib->makeHistoricalWeatherByAreaIdByDate($data,$data['area_id'],$date);

        //デフォルト
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 1';//指定なし
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $data['area_id'], $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        //$data['etc_futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
        
        //温泉
        $this->jalan_lib->makeSpringsPlansByAreaIdByDate($data,$data['area_id'],$date);
        $data['use_image_type'] = 'hotel';//ホテル画像の方が映える
        $data['stop_line'] = 2;

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/airport/',$this->lang->line('topicpath_airport'));
        $data['topicpaths'][] = array('/airport/show/'.$airport_id,$this->data['airport']->airport_name);
        $data['topicpaths'][] = array('/airport/date/'.$airport_id.'/'.str_replace('/','-',$date),$date);

        //set header title
        $data['og_image'] = site_url('/images/airport/big/airport2.jpg');
        $data['header_title'] = sprintf($this->lang->line('common_date_header_title'), $this->data['airport']->airport_name, $data['display_date'], $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['airport']->airport_name);
        $data['header_description'] = sprintf($this->lang->line('common_date_header_description'), $data['display_date'], $this->data['airport']->airport_name);

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array(
            'css/future.css',
            'css/add.css',
            'css/add_sp.css',
            'css/calendar/default.css',
            'css/calendar/default.date.css',
            'css/calendar/default.time.css'
        )));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array(
            'js/jquery.form.js',
            'js/jquery.blockUI.js',
            'js/jquery.easing.1.3.js',
            'js/scrolltop.js',
            'js/future.js',
            'js/Chart.js'
        )));
        $this->load->view('airport/date', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */