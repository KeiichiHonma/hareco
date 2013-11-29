<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spring extends MY_Controller {

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
        $this->lang->load('setting');
        $this->lang->load('spring');
        $this->load->library('tank_auth');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->library('jalan_lib');
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    function show($spring_id)
    {
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring'] = $spring;
        /*
        jalan data
        この段階では晴れの日を元にした空き部屋検索はしない。日ごとにAPIを投げるのは非常に遅延しそう
        */
        
        $data['hotels'] = $this->jalan_lib->getHotelsByOAreaId($spring->jalan_o_area);
        //future data
        $page = 1;
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $youbi = null;
        //晴れの日付を提案して、日付に紐付いたプランに誘導
        $data['holiday_futures'] = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($spring->area_id,$orderExpression, $page, $holiday,$sequence);

        if(empty($data['holiday_futures']['data'])){
            show_404();
        }

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$spring->spring_name);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/detail.css','css/jquery.ad-gallery.css','css/prettyPopin.css','css/abox.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.ad-gallery.js','js/jquery.prettyPopin.js')));

        $this->load->view('spring/show', array_merge($this->data,$data));
    }

    function hotel($spring_id,$jalan_h_id,$area_id,$is_s_area = null)
    {
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring'] = $spring;
        $data['is_s_area'] = $is_s_area == 's_area' ? TRUE : FALSE;//s_area指定の場合は温泉地がわかっていない。箱根温泉ではあるが、強羅温泉かはわかっていない状態
        
        //future data
        
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $page = 1;
        $youbi = 5;//金曜日
        
        //休日+休前日限定で取得。土日はけっこう空いていない・・・
        $holiday_futures_data = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($area_id, $orderExpression, $page, $holiday, $sequence, $youbi);
        $data['holiday_futures'] = $holiday_futures_data['data'];
        if(empty($data['holiday_futures'])){
            //show_404();
        }
        /*
        jalan data
        */
        //ホテル詳細
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);
        $data['plans'] = $this->jalan_lib->getStocksByHotelIdBySequence($jalan_h_id,$sequence);
        //空き室検索
/*
一旦空き室と晴れの日を紐付けるのをやめる
        $sequence = 2;
        foreach ($data['holiday_futures']['data'] as $holiday_future){
            $stockData = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$sequence,str_replace('-','',$holiday_future->date));
            if( !empty($stockData) ) $data['stocks'][$holiday_future->date] =  $stockData;
            
        }
*/
        
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$spring->spring_name);
        $data['topicpaths'][] = array(null,$data['hotel']['HotelName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/detail.css','css/jquery.ad-gallery.css','css/prettyPopin.css','css/abox.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.ad-gallery.js','js/jquery.prettyPopin.js')));

        $this->load->view('spring/hotel', array_merge($this->data,$data));
    }

    function date($spring_id,$jalan_h_id,$area_id,$date)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring'] = $spring;
        //future data
        $data['future'] = $this->Future_model->getFutureByAreaIdByDate($spring->area_id,$date);
        if(empty($data['future'])){
            show_404();
        }

        /*
        jalan data
        */
        //ホテル詳細
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);

        //指定日空き室検索
        $sequence = 2;
        $jalan_date = str_replace('-','',$date);
        $data['stocks'] = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$sequence,$jalan_date);
        //予約できない場合が多々あるはず
        if( empty($data['stocks']) ){
            //指定日、同じエリアで空いているプランを表示
            $data['plans'] = $this->jalan_lib->getStocksByOAreaIdBySequenceByDate($spring->jalan_o_area,$sequence,$jalan_date);
        }
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$spring->spring_name);
        $data['topicpaths'][] = array(null,$data['hotel']['HotelName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/detail.css','css/jquery.ad-gallery.css','css/prettyPopin.css','css/abox.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.ad-gallery.js','js/jquery.prettyPopin.js')));

        $this->load->view('spring/date', array_merge($this->data,$data));
    }

    function plan($spring_id,$jalan_h_id,$area_id,$date,$jalan_plan_cd)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring'] = $spring;
        //future data
        $data['future'] = $this->Future_model->getFutureByAreaIdByDate($spring->area_id,$date);
        if(empty($data['future'])){
            show_404();
        }

        /*
        jalan data
        */
        //ホテル詳細
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);

        //指定日空き室検索
        $sequence = 2;
        $jalan_date = str_replace('-','',$date);
        $data['stocks'] = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$sequence,$jalan_date);
        //予約できない場合が多々あるはず
        if( empty($data['stocks']) ){
            show_404();
        }
        //対象のプランを検索
        foreach ($data['stocks'] as $stock){
            if($stock['PlanCD'] == $jalan_plan_cd){
                $data['target_plan'] = $stock;
            }else{
                $data['etc_plan'][] = $stock;
            }
        }
        //指定日、同じエリアで空いているプランを表示
        $data['plans'] = $this->jalan_lib->getStocksByOAreaIdBySequenceByDate($spring->jalan_o_area,$sequence,$jalan_date);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$spring->spring_name);
        $data['topicpaths'][] = array(null,$data['hotel']['HotelName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/detail.css','css/jquery.ad-gallery.css','css/prettyPopin.css','css/abox.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.ad-gallery.js','js/jquery.prettyPopin.js')));

        $this->load->view('spring/plan', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */