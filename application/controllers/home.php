<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->load->helper('weather');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('common');
        $this->lang->load('setting');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->library('weather_lib');
        $this->load->library('yahoo_lib');
        $this->data['regions'] = $this->Region_model->getAllregions();
        $this->data['areas'] = $this->Area_model->getAllAreas();
        $this->data['holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
    }

    /**
     * index page
     *
     */
    function index()
    {
        $data['isHome'] = TRUE;
        $data['isIndex'] = TRUE;
        $data['bodyId'] = 'ind';
        /*
        トップのスライド
        箱根の次の連休の晴れ
        ・17 箱根・湯河原（箱根湯本温泉）
        
        ゴルフ場
        ・15 隨縁カントリークラブセンチュリー富士コース（山梨）
        */
        $spring_id = 17;
        $data['slides']['spring'] =$this->Future_model->getSpringFuturesGoupByAreaByHolidayBySequenceForSlide($spring_id);

        /*
        天気予想
        */
        $orderExpression = "area_id ASC,date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = null;
        $day_type = array('type'=>'index','value'=>1);//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('index', null, $orderExpression, $page,$weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['futures'] = $futuresData['data'];

        /*
        連休提案
        百万都市を表示
        */
        $holiday_sequence = 2;
        $shine_sequence = 2;
        $million_city_holiday_futures = $this->Future_model->getFuturesGoupByAreaByHolidaySequenceByMillionCity($holiday_sequence,$shine_sequence);
        $data['million_city_holiday_futures'] = array_chunk($million_city_holiday_futures,3);
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','css/jquery-ui-1.10.3.custom.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js','js/jquery.easing.1.3.js','js/jquery.bxslider.js')));
        $this->load->view('home/index', array_merge($this->data,$data));
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */