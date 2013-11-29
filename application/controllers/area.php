<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area extends MY_Controller {

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
        $this->load->model('Weather_model');
        $this->load->library('weather_lib');
        $this->load->library('jalan_lib');
        $this->data['areas'] = $this->Area_model->getAllAreas();
    }

    /**
     * search area action
     *
     */
    function show($area_id)
    {
        if(!isset($this->data['areas'][$area_id])){
            show_404();
        }
        $data['area_id'] = $area_id;

        //未来データ/////////////////////////////////////////
        $orderExpression = "date ASC";
        $page = 1;
        $sequence = 1;
        $day_type = array('type'=>'holiday','value'=>1);//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $area_id, $orderExpression, $page, $sequence, $day_type = array('type'=>'holiday','value'=>1), $start_date);
        $data['futures'] = $futuresData['data'];

        //温泉
        $this->jalan_lib->makeSpringsPlansByAreaId($data,$area_id);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->data['areas'][$area_id]->area_name);
        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $this->data['areas'][$area_id]->area_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $this->data['areas'][$area_id]->area_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $this->data['areas'][$area_id]->area_name);

        $this->load->view('area/show', array_merge($this->data,$data));
    }

    function date($area_id,$date)
    {
        if(!isset($this->data['areas'][$area_id])){
            show_404();
        }
        $data['area_id'] = $area_id;
        
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();

        //未来データ
        $data['week_futures'] = $this->Future_model->getFuturesByAreaIdByDateForWeek($area_id,$date);
        if(empty($data['week_futures'])){
            show_404();
        }
        
        //天気の歴史
        $this->weather_lib->makeHistoricalWeatherByAreaIdByDate($data,$area_id,$date);

        //デフォルト
        $orderExpression = "date ASC";
        $page = 1;
        $sequence = 1;//
        $day_type = array('type'=>'holiday','value'=>1);//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $area_id, $orderExpression, $page, $sequence, $day_type = array('type'=>'holiday','value'=>1), $start_date);
        $data['etc_futures'] = $futuresData['data'];
        
        //温泉
        $this->jalan_lib->makeSpringsPlansByAreaIdByDate($data,$area_id,$date);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->data['areas'][$area_id]->area_name);
        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $this->data['areas'][$area_id]->area_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $this->data['areas'][$area_id]->area_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $this->data['areas'][$area_id]->area_name);

        $this->load->view('area/date', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */