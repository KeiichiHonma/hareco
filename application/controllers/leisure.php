<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leisure extends MY_Controller {

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
        $this->load->model('Leisure_model');
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
        
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/leisure/',$this->lang->line('topicpath_leisure'));

        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '各空港', $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('topicpath_leisure'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), '各空港');

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.easing.1.3.js','js/jquery.bxslider.js','js/scrolltop.js',)));
        $this->load->view('leisure/index', array_merge($this->data,$data));
    }

    function view($todoufuken_id)
    {
        $data['isSlide'] = TRUE;
        $data['isBigSlide'] = TRUE;
        $data['bodyId'] = 'ind';
        
        $this->load->model('Todoufuken_model');
        $this->data['todoufuken'] = $this->Todoufuken_model->getTodoufukenById($todoufuken_id);
        if(empty($this->data['todoufuken'])){
            show_404();
        }
        $this->data['leisures'] = $this->Leisure_model->getLeisuresByTodoufukenIdOrderKanaIndex($todoufuken_id);

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/leisure/',$this->lang->line('topicpath_leisure'));
        $data['topicpaths'][] = array('/leisure/view/'.$todoufuken_id,$this->data['todoufuken']->todoufuken_name);

        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '各空港', $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('topicpath_leisure'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), '各空港');

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.easing.1.3.js','js/jquery.bxslider.js','js/scrolltop.js',)));
        $this->load->view('leisure/view', array_merge($this->data,$data));
    }

    /**
     * search leisure action
     *
     */
    function show($leisure_id)
    {
        $this->data['leisure'] = $this->Leisure_model->getLeisureById($leisure_id);
        
        if(empty($this->data['leisure'])){
            show_404();
        }
        $this->load->model('Todoufuken_model');
        $this->data['todoufuken'] = $this->Todoufuken_model->getTodoufukenById($this->data['leisure']->todoufuken_id);
        $data['bodyId'] = 'area';
        $data['leisure_id'] = $leisure_id;
        $data['area_id'] = $this->data['leisure']->area_id;
        $data['search_type'] = 'leisure';//sp
        $data['search_object_id'] = $leisure_id;//sp
        
        //未来データ/////////////////////////////////////////
        $data['recommend_futures_title'] = $this->data['leisure']->leisure_name.'の'.$this->lang->line('recommend_futures_title_default');
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 1';//指定なし
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $data['area_id'], $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

        //じゃらんホテル
        $data['hotel_title'] = '晴れの日に'.$this->data['leisure']->leisure_name.'近辺の温泉へ行く';
        $this->jalan_lib->makeSpringsHotelsByAreaId($data,$data['area_id']);
        $data['stop_line'] = 2;
        
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/leisure/',$this->lang->line('topicpath_leisure'));
        $data['topicpaths'][] = array('/leisure/view/'.$this->data['leisure']->todoufuken_id,$this->data['todoufuken']->todoufuken_name);
        $data['topicpaths'][] = array('/leisure/show/'.$leisure_id,$this->data['leisure']->leisure_name);
        
        //set header title
        $data['og_image'] = site_url('/images/leisure/big/leisure1.jpg');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->data['leisure']->leisure_name, $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['leisure']->leisure_name);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->data['leisure']->leisure_name);
        
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

        $this->load->view('leisure/show', array_merge($this->data,$data));
    }

    function date($leisure_id,$date)
    {
        //書式：2012/01/01
        if(0 === preg_match('/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();

        $this->data['leisure'] = $this->Leisure_model->getLeisureById($leisure_id);
        
        if(empty($this->data['leisure'])){
            show_404();
        }
        $this->load->model('Todoufuken_model');
        $this->data['todoufuken'] = $this->Todoufuken_model->getTodoufukenById($this->data['leisure']->todoufuken_id);
        $data['bodyId'] = 'area';
        $data['leisure_id'] = $leisure_id;
        $data['area_id'] = $this->data['leisure']->area_id;
        $data['search_type'] = 'leisure';//sp
        $data['search_object_id'] = $leisure_id;//sp
        
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
        $this->weather_lib->getTitlesForDate($data,$this->data['leisure']->leisure_name);
        
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
        $data['topicpaths'][] = array('/leisure/',$this->lang->line('topicpath_leisure'));
        $data['topicpaths'][] = array('/leisure/view/'.$this->data['leisure']->todoufuken_id,$this->data['todoufuken']->todoufuken_name);
        $data['topicpaths'][] = array('/leisure/show/'.$leisure_id,$this->data['leisure']->leisure_name);
        $data['topicpaths'][] = array('/leisure/date/'.$leisure_id.'/'.str_replace('/','-',$date),$date);

        //set header title
        $data['og_image'] = site_url('/images/leisure/big/leisure2.jpg');
        $data['header_title'] = sprintf($this->lang->line('common_date_header_title'), $this->data['leisure']->leisure_name, $data['display_date'], $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['leisure']->leisure_name);
        $data['header_description'] = sprintf($this->lang->line('common_date_header_description'), $data['display_date'], $this->data['leisure']->leisure_name);

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
        $this->load->view('leisure/date', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */