<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('setting');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->model('Weather_model');
        $this->load->library('yahoo_lib');
        $this->load->library('weather_lib');
        $this->load->library('jalan_lib');
        $this->data['areas'] = $this->Area_model->getAllAreas();
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
    }

    function index()
    {
        $data = array();

        if(!isset($_GET['keyword'])) show_404();
        $data['keyword'] = $_GET['keyword'];
        
        //書式：2012/01/01
        if(isset($_GET['date']) && preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_GET['date'])) $data['date'] = $_GET['date'];

        $yahoo_address = $this->yahoo_lib->getGeoCode($data['keyword']);
        $search_simple_area = $this->config->item('search_simple_area');
        $search_hokkaido_area = $this->config->item('search_hokkaido_area');
        $search_kagoshima_area = $this->config->item('search_kagoshima_area');
        $search_okinawa_area = $this->config->item('search_okinawa_area');
        $search_tokyo_area = $this->config->item('search_tokyo_area');

        $area_id = 0;
        preg_match('/^(北海道|青森県|岩手県|秋田県|山形県|宮城県|福島県|新潟県|栃木県|群馬県|茨城県|埼玉県|千葉県|東京都|神奈川県|山梨県|静岡県|長野県|富山県|石川県|福井県|岐阜県|愛知県|三重県|奈良県|和歌山県|滋賀県|京都府|大阪府|兵庫県|岡山県|広島県|鳥取県|島根県|山口県|香川県|徳島県|愛媛県|高知県|福岡県|佐賀県|長崎県|熊本県|大分県|宮崎県|鹿児島県|沖縄県)(北松浦郡鹿町町|.+?郡.+?町|.+?郡.+?村|宇陀市|奥州市|上越市|黒部市|豊川市|姫路市|.+?[^0-9一二三四五六七八九十]区|四日市市|廿日市市|.+?市|.+?町|.+?村)(.*)$/u',$yahoo_address,$match);

        $address['pref'] = !empty($match[1]) ? $match[1] : '';
        $address['city'] = !empty($match[2]) ? $match[2] : '';
        $address['town'] = !empty($match[3]) ? $match[3] : '';

        /*
        複数ある特殊なエリア
        ・名瀬
        　鹿児島県奄美市名瀬
        ・石垣島
        　沖縄県石垣市平得
        ・南大東島
        　沖縄県島尻郡南大東村新東
        */
        //if(preg_match('/奄美/u', $address['city'])) $area_id = 54;//奄美を含む
        //if(preg_match('/南大東/u', $address['city'])) $area_id = 58;//南大東を含む
        //if(preg_match('/北大東/u', $address['city'])) $area_id = 58;//北大東を含む
        //if(preg_match('/石垣市/u', $address['city'])) $area_id = 55;//石垣市を含む
        
        /*
        北海道の判定
        */
        if($address['pref'] == '北海道'){
            foreach ($search_hokkaido_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
            //return 'error';
        }elseif ($address['pref'] == '鹿児島県'){
            foreach ($search_kagoshima_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }elseif ($address['pref'] == '沖縄県'){
            foreach ($search_okinawa_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }elseif ($address['pref'] == '東京都'){
            foreach ($search_tokyo_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }

        if( FALSE !== ($index = array_search($address['pref'],$search_simple_area)) ) $area_id = $index;//特殊、北海道以外のエリアID
        //エリアと日付判定終了///////////////////////////////////////////

        if(!isset($this->data['areas'][$area_id])){
            show_404();
        }
        $data['area_id'] = $area_id;
        
        //ここから表示画面の分岐
        if(isset($data['date'])){//日付がある場合
            //未来データ
            $data['week_futures'] = $this->Future_model->getFuturesByAreaIdByDateForWeek($area_id,$data['date']);
            if(empty($data['week_futures'])){
                show_404();
            }
            
            //天気の歴史
            $this->weather_lib->makeHistoricalWeatherByAreaIdByDate($data,$area_id,$data['date']);

            //デフォルト
            $orderExpression = "date ASC";
            $page = 1;
            $sequence = 1;//
            $day_type = array('type'=>'holiday','value'=>1);//休日+祝日
            $start_date = null;//指定なし。直近
            $futuresData = $this->Future_model->getFutures('area', $area_id, $orderExpression, $page, $sequence, $day_type = array('type'=>'holiday','value'=>1), $start_date);
            $data['etc_futures'] = $futuresData['data'];
            
            //温泉
            $this->jalan_lib->makeSpringsPlansByAreaIdByDate($data,$area_id,$data['date']);

            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->data['areas'][$area_id]->area_name);
            
            //set header title
            $data['header_title'] = sprintf($this->lang->line('spring_header_title'), $this->data['areas'][$area_id]->area_name, $this->config->item('website_name', 'tank_auth'));
            $data['header_keywords'] = sprintf($this->lang->line('spring_header_keywords'), $this->data['areas'][$area_id]->area_name);
            $data['header_description'] = sprintf($this->lang->line('spring_header_description'), $this->data['areas'][$area_id]->area_name);

            $this->load->view('search/keyword/date', array_merge($this->data,$data));
        }else{//キーワードだけ
            //未来データ
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

            $this->load->view('search/keyword/show', array_merge($this->data,$data));
        }
    }
    
    function s(){
        $keyword = $_POST['keyword'];
        $date = $_POST['date'];
        $url = 'search/keyword/'.urlencode($keyword);
        if(isset($_POST['date']) && !empty($_POST['date'])) $url = $url.'/'.urlencode($_POST['date']);
        redirect($url);
    }
    
    function address()
    {
        /*
        キーワードからチェック
        完全一致した場合はそのページに飛ばす
        とはいえパフォーマンスに影響がありそう
        関東、という地名もあるみたいだし
        
        //region 広すぎる。何もしない
        
        //area
        
        //spring
        
        //golf
        
        */
        $keyword = $_POST['keyword'];
        //書式：2012/01/01
        $date = null;
        if(isset($_POST['date']) && preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_POST['date'])) $date = $_POST['date'];
        
        $yahoo_address = $this->yahoo_lib->getGeoCode($_POST['keyword']);
        //$google_address = '北海道岩見沢市東山町263-8';
        $search_simple_area = $this->config->item('search_simple_area');
        $search_hokkaido_area = $this->config->item('search_hokkaido_area');
        $search_kagoshima_area = $this->config->item('search_kagoshima_area');
        $search_okinawa_area = $this->config->item('search_okinawa_area');
        $search_tokyo_area = $this->config->item('search_tokyo_area');

        $area_id = 0;
        preg_match('/^(北海道|青森県|岩手県|秋田県|山形県|宮城県|福島県|新潟県|栃木県|群馬県|茨城県|埼玉県|千葉県|東京都|神奈川県|山梨県|静岡県|長野県|富山県|石川県|福井県|岐阜県|愛知県|三重県|奈良県|和歌山県|滋賀県|京都府|大阪府|兵庫県|岡山県|広島県|鳥取県|島根県|山口県|香川県|徳島県|愛媛県|高知県|福岡県|佐賀県|長崎県|熊本県|大分県|宮崎県|鹿児島県|沖縄県)(北松浦郡鹿町町|.+?郡.+?町|.+?郡.+?村|宇陀市|奥州市|上越市|黒部市|豊川市|姫路市|.+?[^0-9一二三四五六七八九十]区|四日市市|廿日市市|.+?市|.+?町|.+?村)(.*)$/u',$yahoo_address,$match);

        $address['pref'] = !empty($match[1]) ? $match[1] : '';
        $address['city'] = !empty($match[2]) ? $match[2] : '';
        $address['town'] = !empty($match[3]) ? $match[3] : '';

        /*
        複数ある特殊なエリア
        ・名瀬
        　鹿児島県奄美市名瀬
        ・石垣島
        　沖縄県石垣市平得
        ・南大東島
        　沖縄県島尻郡南大東村新東
        */
        //if(preg_match('/奄美/u', $address['city'])) $area_id = 54;//奄美を含む
        //if(preg_match('/南大東/u', $address['city'])) $area_id = 58;//南大東を含む
        //if(preg_match('/北大東/u', $address['city'])) $area_id = 58;//北大東を含む
        //if(preg_match('/石垣市/u', $address['city'])) $area_id = 55;//石垣市を含む
        
        /*
        北海道の判定
        */
        if($address['pref'] == '北海道'){
            foreach ($search_hokkaido_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
            //return 'error';
        }elseif ($address['pref'] == '鹿児島県'){
            foreach ($search_kagoshima_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }elseif ($address['pref'] == '沖縄県'){
            foreach ($search_okinawa_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }elseif ($address['pref'] == '東京都'){
            foreach ($search_tokyo_area as $array){
                if( FALSE !== strstr($address['city'],$array[0])){
                    $area_id = $array[1];
                }
            }
        }

        if( FALSE !== ($index = array_search($address['pref'],$search_simple_area)) ) $area_id = $index;//特殊、北海道以外のエリアID
        $url = 'search/keyword/'.urlencode($keyword).'/'.$area_id;
        if(!is_null($date)) $url = $url.'/'.str_replace('/','-',$date);
        redirect($url);
        //echo $area_id;
        die();
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */