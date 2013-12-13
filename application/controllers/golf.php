<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Golf extends MY_Controller {

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
        $this->load->model('Future_model');
        $this->load->library('rakuten_lib');
        $this->data['all_areas'] = $this->Area_model->getAllAreas();
    }

    function area($area_id)
    {
        $data['area'] = $this->Area_model->getAreaById($area_id);
        if(empty($data['area'])){
            show_404();
        }
        if($data['area']->rakuten_area_code == 1){
            //北海道は緯度経度で
            $data['courses'] = $this->rakuten_lib->getCoursesByLatitudeByLongitude($data['area']->latitude,$data['area']->longitude);
        }else{
            $data['courses'] = $this->rakuten_lib->getCoursesByRakutenAreaCode($data['area']->rakuten_area_code);
        }

        //future data
        $page = 1;
        $holiday = 1;
        $sequence = 1;//ゴルフは休日でOK
        $orderExpression = "date ASC";
        $youbi = null;
        //晴れの日付を提案。プラン紐付け
        $data['holiday_futures'] = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($data['area']->id, $orderExpression, $page, $holiday,$sequence);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$data['area']->todoufuken_name);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('golf_header_title'), $data['area']->todoufuken_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('golf_header_keywords'), $data['area']->todoufuken_name);
        $data['header_description'] = sprintf($this->lang->line('golf_header_description'), $data['area']->todoufuken_name);
        
        $this->load->view('golf/area', array_merge($this->data,$data));
    }
    
    function show($area_id,$rakuten_golfCourseId)
    {
        $data['area'] = $this->Area_model->getAreaById($area_id);
        
        if(empty($data['area'])){
            show_404();
        }

        $data['course'] = $this->rakuten_lib->getCourseByRakutenGolfCourseId($rakuten_golfCourseId);
        
        //future data
        $page = 1;
        $holiday = 1;
        $sequence = 1;//ゴルフは休日でOK
        $orderExpression = "date ASC";
        $youbi = null;
        //晴れの日付を提案して、日付に紐付いたプランに誘導
        $holiday_futures_data = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($data['area']->id, $orderExpression, $page, $holiday,$sequence);
        $data['holiday_futures'] = $holiday_futures_data['data'];
        if(empty($data['holiday_futures'])){
            show_404();
        }
        /*
        温泉検索とは違い、
        この段階で晴れの日を元にしたプラン検索をする。楽天のAPIが日付必須のためである
        */
/*
        foreach ($data['holiday_futures']['data'] as $holiday_future){
            $data['plans'][] = $this->rakuten_lib->getPlansByRakutenGolfCourseIdBydate($rakuten_golfCourseId,$holiday_future->date);
        }
*/
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$data['area']->todoufuken_name);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('golf_header_title'), $data['area']->todoufuken_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('golf_header_keywords'), $data['area']->todoufuken_name);
        $data['header_description'] = sprintf($this->lang->line('golf_header_description'), $data['area']->todoufuken_name);
        
        $this->load->view('golf/show', array_merge($this->data,$data));
    }

    function date($area_id,$rakuten_golfCourseId,$date)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $data['area'] = $this->Area_model->getAreaById($area_id);
        
        if(empty($data['area'])){
            show_404();
        }
        //future data
        $data['future'] = $this->Future_model->getFutureByAreaIdByDate($data['area']->id,$date);
        if(empty($data['future'])){
            show_404();
        }

        //コース詳細
        $data['course'] = $this->rakuten_lib->getCourseByRakutenGolfCourseId($rakuten_golfCourseId);

        //指定日空き室検索
        $data['plans'] = $this->rakuten_lib->getPlansByRakutenGolfCourseIdBydate($rakuten_golfCourseId,$date);

        //指定日、同じエリアで空いているプランを表示。北海道の場合は少し広くでるかも。プラン検索に緯度経度はないため
        $data['etc_area_plans'] = $this->rakuten_lib->getPlansByRakutenAreaCodeBydate($data['area']->rakuten_area_code,$date);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$data['area']->todoufuken_name);
        $data['topicpaths'][] = array(null,$data['course']['golfCourseName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('golf_header_title'), $data['area']->todoufuken_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('golf_header_keywords'), $data['area']->todoufuken_name);
        $data['header_description'] = sprintf($this->lang->line('golf_header_description'), $data['area']->todoufuken_name);

        $this->load->view('golf/date', array_merge($this->data,$data));
    }

    function plan($area_id,$rakuten_golfCourseId,$date,$rakuten_planId)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $data['area'] = $this->Area_model->getAreaById($area_id);
        
        if(empty($data['area'])){
            show_404();
        }
        //future data
        $data['future'] = $this->Future_model->getFutureByAreaIdByDate($data['area']->id,$date);
        if(empty($data['future'])){
            show_404();
        }

        //コース詳細
        $data['course'] = $this->rakuten_lib->getCourseByRakutenGolfCourseId($rakuten_golfCourseId);

        //指定日空き室検索
        $data['plans'] = $this->rakuten_lib->getPlansByRakutenGolfCourseIdBydate($rakuten_golfCourseId,$date);
        //対象のプランを検索
        foreach ($data['plans'] as $plan){
            if($plan['planInfo']['planId'] == $rakuten_planId){
                $data['target_plan'] = $plan;
            }else{
                $data['etc_plan'][] = $plan;
            }
        }

        //指定日、同じエリアで空いているプランを表示。北海道の場合は少し広くでるかも。プラン検索に緯度経度はないため
        $data['etc_area_plans'] = $this->rakuten_lib->getPlansByRakutenAreaCodeBydate($data['area']->rakuten_area_code,$date);

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$data['area']->todoufuken_name);
        $data['topicpaths'][] = array(null,$data['course']['golfCourseName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('golf_header_title'), $data['area']->todoufuken_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('golf_header_keywords'), $data['area']->todoufuken_name);
        $data['header_description'] = sprintf($this->lang->line('golf_header_description'), $data['area']->todoufuken_name);

        $this->load->view('golf/plan', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */