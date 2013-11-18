<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel extends MY_Controller {

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
        $this->lang->load('spring');
        $this->load->library('tank_auth');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->library('jalan_lib');
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    function show($area_id,$spring_id,$jalan_h_id)
    {
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['spring'] = $spring;
        //future data
        
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $page = 1;
        $youbi = 5;//金曜日
        
        //休日+休前日限定で取得。土日はけっこう空いていない・・・
        $data['holiday_futures'] = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($area_id,$holiday,$sequence,$orderExpression, $page,$youbi);

        if(empty($data['holiday_futures']['data'])){
            //show_404();
        }
        /*
        jalan data
        */
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);
        
        $sequence = 2;
        foreach ($data['holiday_futures']['data'] as $holiday_future){
            $stockData = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$sequence,str_replace('-','',$holiday_future->date));
            if( !empty($stockData) ) $data['stocks'][$holiday_future->date] =  $stockData;
            
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

        $this->load->view('hotel/show', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */