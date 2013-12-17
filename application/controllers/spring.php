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
        $this->load->helper('weather');
        $this->lang->load('setting');
        $this->lang->load('spring');
        $this->load->library('tank_auth');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->model('Weather_model');
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

        //温泉地一覧///////////////////////////////////////////////////////////////////////////
        $this->load->model('Todoufuken_model');

        $data['spring_slide'] =array_rand($this->data['all_springs'],5);
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/spring/',$this->lang->line('topicpath_spring'));

        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $this->lang->line('topicpath_spring'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $this->lang->line('topicpath_spring'));
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $this->lang->line('topicpath_spring'));

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.easing.1.3.js','js/jquery.bxslider.js','js/scrolltop.js',)));
        $this->load->view('spring/index', array_merge($this->data,$data));
    }

    function show($spring_id)
    {
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['bodyId'] = 'area';
        //$data['leisure_type'] = 'area';
        $data['leisure_type'] = 'spring';
        $data['springs'][] = $spring;
        $data['area_id'] = $spring->area_id;

        //じゃらんホテル
        $data['hotel_title'] = '晴れの日に'.$spring->spring_name.'へ行く';
        $this->jalan_lib->makeSpringHotelsByOAreaId($data,$spring_id,$spring->jalan_o_area);
        $data['stop_line'] = 3;

        //未来データ/////////////////////////////////////////
        $data['recommend_futures_title'] = $spring->spring_name.'のおでかけプランニング';
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 1';//指定なし
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $spring->area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/spring/',$this->lang->line('topicpath_spring'));
        $data['topicpaths'][] = array('/spring/show/'.$spring_id,$spring->spring_name);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array(
            'css/future.css',
            'css/add.css',
            'css/add_sp.css',
            'css/slimmenu.css',
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
            'js/jquery.slimmenu.min.js'
        )));
        $this->load->view('spring/show', array_merge($this->data,$data));
    }

    function hotel($spring_id,$jalan_h_id,$area_id,$is_s_area = null)
    {
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['bodyId'] = 'leisure';
        $data['leisure_type'] = 'spring';
        $data['springs'][] = $spring;
        $data['area_id'] = $spring->area_id;
        $data['is_s_area'] = $is_s_area == 's_area' ? TRUE : FALSE;//s_area指定の場合は温泉地がわかっていない。箱根温泉ではあるが、強羅温泉かはわかっていない状態

        //ホテル詳細
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);

        //未来データ/////////////////////////////////////////
        $data['recommend_futures_title'] = $data['hotel']['HotelName'].'に晴れでいける休日';
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $shine_sequence = 2;
        $daytime_shine_sequenceExpression = " >= $shine_sequence";//晴れ数
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = date("Y-m-d",strtotime("+1 month"));//1ヶ月後から
        $futuresData = $this->Future_model->getFutures('area', $spring->area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        //$data['futures'] = $futuresData['data'];
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
        //future data
/*
        $holiday = 1;
        $sequence = 2;
        $orderExpression = "date ASC";
        $page = 1;
        $youbi = 5;//金曜日
        
        //休日+休前日限定で取得。土日はけっこう空いていない・・・
        $holiday_futures_data = $this->Future_model->getFuturesByAreaIdByHolidayByYoubiBySequence($area_id, $orderExpression, $page, $holiday, $sequence, $youbi);
        $data['holiday_futures'] = $holiday_futures_data['data'];
*/

        $data['plan_title'] = $data['hotel']['HotelName'].'の晴れでいける休日温泉プラン';
        //$this->jalan_lib->makeHotelPlansByJalan_h_idByShineSequence($data,$jalan_h_id,$shine_sequence);
        $data['use_image_type'] = 'plan';
        $data['stop_line'] = 3;

        //空き室検索
        /*
            この段階で日付のプランを提案しないと永遠に日付を選ぶ行動を取れない。ホテル選択→空き確認は当然の流れ。
            処理は非常に思いがチャレンジ
        */

        $shine_sequence = 2;//2泊
        $stop_loop = 6;
        $i = 0;
        $plans = array();
        foreach ($futuresData['data'] as $future){
            if($i == $stop_loop) break;
            $planData = $this->jalan_lib->getStocksByHotelIdByShineSequenceByDate($jalan_h_id,$shine_sequence,str_replace('-','',$future->date));
            if( !empty($planData) ) $plans[$future->date] =  $planData[0];//最初の1つ目だけ
            $i++;
        }
        
        if(!empty($plans))$data['plans'] = array_chunk($plans,3,TRUE);

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/spring/',$this->lang->line('topicpath_spring'));
        $data['topicpaths'][] = array('/spring/show/'.$spring_id,$spring->spring_name);
        $data['topicpaths'][] = array('/spring/hotel/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id,$data['hotel']['HotelName']);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array(
            'css/future.css',
            'css/add.css',
            'css/add_sp.css',
            'css/slimmenu.css',
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
            'js/jquery.slimmenu.min.js'
        )));
        $this->load->view('spring/hotel', array_merge($this->data,$data));
    }

    function date($spring_id,$jalan_h_id,$area_id,$date)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        /*
        
        温泉トップから日付を選んだ場合
            $jalan_h_idはありません
        ホテルページから日付を選んだ場合
            $jalan_h_idがあります
        */
        
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['bodyId'] = 'leisure';
        $data['leisure_type'] = 'spring';
        $data['springs'][] = $spring;
        $data['area_id'] = $spring->area_id;

        //dateページでは全て指定日表示なので、この段階で生成
        $data['target_date'] = $date;
        $data['from_ymd'] = explode('-',$date);
        $data['from_datetime'] = mktime(0,0,0,$data['from_ymd'][1],$data['from_ymd'][2],$data['from_ymd'][0]);
        $data['from_display_date'] = date("n/j",$data['from_datetime']);
        $data['from_youbi'] = get_day_of_the_week(date("N",$data['from_datetime']),array_key_exists($data['target_date'],$this->data['all_holidays']),TRUE);
        $data['jalan_date'] = $data['from_ymd'][0].$data['from_ymd'][1].$data['from_ymd'][2];
        $data['display_date'] = date("Y年n月j日",$data['from_datetime']);
        
        $data['to_datetime'] = $data['from_datetime'] + 86400;
        $data['to_display_date'] = date("n/j",$data['to_datetime']);
        $data['to_youbi'] = get_day_of_the_week(date("N",$data['to_datetime']),array_key_exists(date("Y-m-d",$data['to_datetime']),$this->data['all_holidays']),TRUE);

        //未来データ
        $data['week_futures'] = $this->Future_model->getFuturesByAreaIdByDateForWeek($spring->area_id,$date);
        if(empty($data['week_futures'])){
            show_404();
        }

        //天気の歴史
        $this->weather_lib->makeHistoricalWeatherByAreaIdByDate($data,$spring->area_id,$date);

        //デフォルト
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 2';//2日晴れ
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        $futuresData = $this->Future_model->getFutures('area', $spring->area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['etc_futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

        /*
        jalan data
        */
        $is_etc_plan = FALSE;
        $shine_sequence = 2;
        if($jalan_h_id > 0){
            //ホテル詳細
            $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);
            //指定日空き室検索
            //$jalan_date = str_replace('-','',$date);
            $hotel_plans = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$shine_sequence,$data['jalan_date']);
            //予約できない場合が多々あるはず
            if( empty($hotel_plans) ){
                $is_etc_plan = TRUE;
            }else{
                $data['hotel_plans_title'] = date("n月j日",$data['from_datetime']).'の晴れで'.$data['hotel']['HotelName'].'にいけるプラン';
                $data['hotel_plans'] = array_chunk($hotel_plans,3,TRUE);
                $data['hotel_plans_stop_line'] = 3;
            }
        }else{
            $is_etc_plan = TRUE;
        }
        //予約できない場合やホテル指定がない場合
        if( $is_etc_plan ){
            //指定日、同じエリアで空いているプランを表示
            $data['o_area_plans_title'] = date("n月j日",$data['from_datetime']).'の晴れで'.$spring->spring_name.'にいけるプラン';
            $o_area_etc_plans = $this->jalan_lib->getStocksByOAreaIdBySequenceByDate($spring->jalan_o_area,$shine_sequence,$data['jalan_date']);
            $data['o_area_plans'] = array_chunk($o_area_etc_plans,3,TRUE);
            $data['o_area_plans_stop_line'] = 3;
        }
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/spring/',$this->lang->line('topicpath_spring'));
        $data['topicpaths'][] = array('/spring/show/'.$spring->id,$spring->spring_name);
        if($jalan_h_id > 0){
            $data['topicpaths'][] = array('/spring/hotel/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id,$data['hotel']['HotelName']);
            $data['topicpaths'][] = array('/spring/plan/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id.'/'.$date,$date);
        }else{
            $data['topicpaths'][] = array('/spring/date/'.$spring->id.'/0/'.$spring->area_id.'/'.$date,$date);
        }
        
        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/scrolltop.js','js/Chart.js')));

        if($jalan_h_id > 0){
            $page = 'hotel_date';
            $page_title = $data['hotel']['HotelName'];//ホテル名
        }else{
            $page = 'spring_date';
            $page_title = $data['springs'][0]->spring_name;//温泉名
        }
        $this->load->view("spring/$page", array_merge($this->data,$data));
    }

/*
    大前提
    じゃらんAPIにはプラン詳細の取得機能がないため、
    大きく取得してplan_cdで合致させる方法をとります
*/
    function plan($spring_id,$jalan_h_id,$area_id,$date,$jalan_plan_cd)
    {
        //書式：2012/01/01
        if(preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $spring = $this->Spring_model->getSpringById($spring_id);
        if(empty($spring)){
            show_404();
        }
        $data['bodyId'] = 'leisure';
        $data['leisure_type'] = 'spring';
        $data['springs'][] = $spring;
        $data['area_id'] = $spring->area_id;
        
        //planページでは全て指定日表示なので、この段階で生成
        $data['target_date'] = $date;
        $data['from_ymd'] = explode('-',$date);
        $data['from_datetime'] = mktime(0,0,0,$data['from_ymd'][1],$data['from_ymd'][2],$data['from_ymd'][0]);
        $data['from_display_date'] = date("n/j",$data['from_datetime']);
        $data['from_youbi'] = get_day_of_the_week(date("N",$data['from_datetime']),array_key_exists($data['target_date'],$this->data['all_holidays']),TRUE);
        $data['jalan_date'] = $data['from_ymd'][0].$data['from_ymd'][1].$data['from_ymd'][2];
        $data['display_date'] = date("Y年n月j日",$data['from_datetime']);
        
        $data['to_datetime'] = $data['from_datetime'] + 86400;
        $data['to_display_date'] = date("n/j",$data['to_datetime']);
        $data['to_youbi'] = get_day_of_the_week(date("N",$data['to_datetime']),array_key_exists(date("Y-m-d",$data['to_datetime']),$this->data['all_holidays']),TRUE);


        
        //未来データ
        $data['week_futures'] = $this->Future_model->getFuturesByAreaIdByDateForWeek($area_id,$date);

        //ホテル詳細
        $data['hotel'] = $this->jalan_lib->getHotelByHotelId($jalan_h_id);

        //指定日空き室検索
        $shine_sequence = 2;
        $jalan_date = str_replace('-','',$date);
        $data['plans'] = $this->jalan_lib->getStocksByHotelIdBySequenceByDate($jalan_h_id,$shine_sequence,$jalan_date);
        //この段階で存在しないのは、相手側で急遽空きがなくなった
        if( empty($data['plans']) ){
            
        }
        //対象のプランを検索
        foreach ($data['plans'] as $plan){
            if($plan['PlanCD'] == $jalan_plan_cd){
                $data['target_plan'] = $plan;
            }else{
                $hotel_etc_plans[] = $plan;//指定日、同じホテルで空いているプランを表示
            }
        }

        $data['hotel_etc_plans_title'] = $data['display_date'].'に晴れでいける他のプラン';
        $data['hotel_etc_plans'] = array_chunk($hotel_etc_plans,3,TRUE);
        $data['hotel_etc_plans_stop_line'] = 2;
        
        //指定日、同じエリアで空いているプランを表示
        $data['o_area_etc_plans_title'] = $spring->spring_name.'に晴れでいける休日温泉プラン';
        $o_area_etc_plans = $this->jalan_lib->getStocksByOAreaIdBySequenceByDate($spring->jalan_o_area,$shine_sequence,$jalan_date);
        if(!empty($o_area_etc_plans)){
            $data['o_area_etc_plans'] = array_chunk($o_area_etc_plans,3,TRUE);
            $data['o_area_etc_plans_stop_line'] = 1;
        }


        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/spring/',$this->lang->line('topicpath_spring'));
        $data['topicpaths'][] = array('/spring/show/'.$spring->id,$spring->spring_name);
        $data['topicpaths'][] = array('/spring/hotel/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id,$data['hotel']['HotelName']);
        //$data['topicpaths'][] = array('/spring/plan/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id.'/'.$date.'/'.$data['target_plan']['PlanCD'],$data['target_plan']['PlanName']);
        $data['topicpaths'][] = array('/spring/plan/'.$spring->id.'/'.$data['hotel']['HotelID'].'/'.$spring->area_id.'/'.$date.'/'.$data['target_plan']['PlanCD'],'プラン詳細');

        //set header title
        $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $spring->spring_name, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $spring->spring_name);
        $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $spring->spring_name);
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/future.css','css/add.css','css/add_sp.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array(
            
            'js/jquery.form.js',
            'js/jquery.blockUI.js',
            'js/jquery.easing.1.3.js',
            'js/scrolltop.js',
            'js/future.js'
        )));
        
        $this->load->view('spring/plan', array_merge($this->data,$data));
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */