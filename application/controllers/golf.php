<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends MY_Controller {

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
        $this->lang->load('setting');
        $this->lang->load('golf');
        $this->load->library('tank_auth');
        $this->load->model('Golf_model');
        $this->load->model('Future_model');
        $this->load->library('rakuten_lib');
        $this->data['areas'] = $this->Area_model->getAllareas();
    }
    function area($rakuten_areaCode)
    {
        $data['courses'] = $this->rakuten_lib->getCoursesByRakutenAreaCode($rakuten_areaCode);
        $data['plans'] = getPlansByRakutenAreaCode($rakuten_AreaCode);
        $data['area'] = $this->Area_model->getAreaByTodoufukenId($rakuten_areaCode);
        
        //future data
        $page = 1;
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $youbi = null;
        //晴れの日付を提案。プラン紐付け
        $data['holiday_futures'] = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($data['area']->id,$holiday,$sequence,$orderExpression, $page);
    }
    
    function show($rakuten_golfCourseId)
    {
        $data['course'] = $this->rakuten_lib->getCourseByRakutenGolfCourseId($rakuten_golfCourseId);
        
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring_id'] = $spring->id;
        /*
        jalan data
        この段階では晴れの日を元にした空き部屋検索はしない。日ごとにAPIを投げるのは非常に遅延しそう
        */
        
        $data['hotels'] = $this->jalan_lib->getHotelsByOarea($spring->jalan_o_area);
        $data['area_id'] = $spring->area_id;
        //future data
        $page = 1;
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $youbi = null;
        //晴れの日付を提案して、日付に紐付いたプランに誘導
        $data['holiday_futures'] = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($spring->area_id,$holiday,$sequence,$orderExpression, $page);

        if(empty($data['holiday_futures']['data'])){
            show_404();
        }

        foreach ($data['holiday_futures']['data'] as $holiday_future){
            $planData = getPlansByRakutenGolfCourseIdByDate($rakuten_golfCourseId,$holiday_future->date);
            //$stockData = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$sequence,str_replace('-','',$holiday_future->date));
            if( !empty($stockData) ) $data['plans'][$holiday_future->date] =  $planData;
            
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
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */