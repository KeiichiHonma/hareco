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
        $this->lang->load('setting');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->library('weather_lib');
        $this->load->library('yahoo_lib');
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
        $data['isIndex'] = TRUE;
        /*
        トップのスライド
        箱根の次の連休の晴れ
        ・17 箱根・湯河原（箱根湯本温泉）
        
        ゴルフ場
        ・15 隨縁カントリークラブセンチュリー富士コース（山梨）
        */
        $spring_id = 15;
        //$data['slides']['spring'] =$this->Future_model->getSpringFuturesGoupByAreaByHolidayBySequenceForSlide($spring_id);
        
        /*
        天気予想
        */
        $orderExpression = "area_id ASC,date ASC";
        $page = 1;
        $sequence = null;
        $day_type = array('type'=>'index','value'=>1);//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('index', null, $orderExpression, $page, $sequence, $day_type, $start_date);
        $data['futures'] = $futuresData['data'];

        /*
        連休提案
        百万都市を表示
        */
        $holiday_sequence = 2;
        $shine_sequence = 2;
        $million_city_holiday_futures = $this->Future_model->getFuturesGoupByAreaByHolidaySequenceByMillionCity($holiday_sequence,$shine_sequence);
        $data['million_city_holiday_futures'] = array_chunk($million_city_holiday_futures,3);

        //温泉地一覧///////////////////////////////////////////////////////////////////////////
        $this->load->model('Todoufuken_model');
        $this->data['springs'] = $this->Spring_model->getAllSpringsOrderTodoufukenId();
        
        //ゴルフ都道府県一覧///////////////////////////////////////////////////////////////////////////
        $this->data['regions'] = $this->Region_model->getAllregions();
        $this->data['golf_areas'] = $this->Area_model->getAllAreasOrderRegionIdOrderRakutenTdoufukenId();
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/index.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.anythingslider.js','jquery.colorbox.js')));
        $this->load->view('home/index', array_merge($this->data,$data));
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */