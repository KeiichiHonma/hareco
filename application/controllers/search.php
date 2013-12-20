<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
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
        $this->load->model('Tag_model');
        $this->load->library('weather_lib');
        $this->load->library('jalan_lib');
        $this->load->library('yahoo_lib');
        $this->data['all_regions'] = $this->Region_model->getAllregions();
        $this->data['all_areas'] = $this->Area_model->getAllAreas();
        $this->data['all_holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
        $this->data['all_springs'] = $this->Spring_model->getAllSpringsOrderSpringAreaId();
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
    }

    function index()
    {
        $data = array();

        if(!isset($_GET['keyword']) || $_GET['keyword'] == '') show_404();
        $data['keyword'] = $_GET['keyword'];
        $data['strim_keyword'] = char_count_strimwidth($_GET['keyword'],10);

        $data['date'] = '';
        $data_etc_string = '';
        $data_etc_url = '';
        $data['bodyId'] = 'area';
        
        //書式：2012/01/01
        if(isset($_GET['date']) && preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_GET['date'])){
            $data['date'] = str_replace('/','-',$_GET['date']);
            $data_etc_string = '-';
            $data_etc_url = '&date='.urlencode($_GET['date']);
        }
        //先にタグDBをチェック
        $tags = preg_replace('/@+/', ' ', mb_convert_kana($data['keyword'], 's'));
        $tags = array_filter(explode(' ', $tags), 'strlen');

        $tagsData = $this->Tag_model->getTagsByTagNames($tags);
        if(!empty($tagsData)){
            switch ($tagsData[0]->tag_type){
                case 0://area
                    $url = $data['date'] != '' ? 'area/date/'.$tagsData[0]->object_id.'/'.$data['date'] : 'area/show/'.$tagsData[0]->object_id;
                break;
                case 1://spring
                    $url = $data['date'] != '' ? 'spring/date/'.$tagsData[0]->object_id.'/0/'.$tagsData[0]->area_id.'/'.$data['date'] : 'spring/show/'.$tagsData[0]->object_id;
                break;
                case 3://airport
                    $url = $data['date'] != '' ? 'airport/date/'.$tagsData[0]->object_id.'/'.$data['date'] : 'airport/show/'.$tagsData[0]->object_id;
                break;
            }
            redirect($url);
        }

        $data['yahoo_address'] = $this->yahoo_lib->getContentsGeoCode($data['keyword']);
        if(empty($data['yahoo_address'])){
            $show_page = 'empty';
        }else{
            $search_simple_area = $this->config->item('search_simple_area');
            $search_hokkaido_area = $this->config->item('search_hokkaido_area');
            $search_kagoshima_area = $this->config->item('search_kagoshima_area');
            $search_okinawa_area = $this->config->item('search_okinawa_area');
            $search_tokyo_area = $this->config->item('search_tokyo_area');

            $area_id = 0;
            preg_match('/^(北海道|青森県|岩手県|秋田県|山形県|宮城県|福島県|新潟県|栃木県|群馬県|茨城県|埼玉県|千葉県|東京都|神奈川県|山梨県|静岡県|長野県|富山県|石川県|福井県|岐阜県|愛知県|三重県|奈良県|和歌山県|滋賀県|京都府|大阪府|兵庫県|岡山県|広島県|鳥取県|島根県|山口県|香川県|徳島県|愛媛県|高知県|福岡県|佐賀県|長崎県|熊本県|大分県|宮崎県|鹿児島県|沖縄県)(北松浦郡鹿町町|.+?郡.+?町|.+?郡.+?村|宇陀市|奥州市|上越市|黒部市|豊川市|姫路市|.+?[^0-9一二三四五六七八九十]区|四日市市|廿日市市|.+?市|.+?町|.+?村)(.*)$/u',$data['yahoo_address'],$match);

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

            if(!isset($this->data['all_areas'][$area_id])){
                show_404();
            }
            $data['area_id'] = $area_id;
            $data['search_type'] = 'search';//sp
            $data['search_object_id'] = $area_id;//sp
            $data['search_keyword'] = $data['strim_keyword'];//sp
            
            //ここから表示画面の分岐
            if(!empty($data['date'])){//日付がある場合
                $show_page = 'date';
                $data['target_date'] = $data['date'];
                $data['from_ymd'] = explode('-',$data['date']);
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
                $this->weather_lib->getTitlesForDate($data,$data['strim_keyword']);

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
                $weather = 'shine';
                $daytime_shine_sequenceExpression = ' >= 1';//指定なし
                $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
                $start_date = null;//指定なし。直近
                $futuresData = $this->Future_model->getFutures('area', $area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
                $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));
                
                //温泉
                $data['stop_line'] = 2;
                $data['plan_title'] = $data['strim_keyword'].'-'.date("n月j日",$data['from_datetime']).'の温泉プラン';
                $this->jalan_lib->makeSpringsPlansByAreaIdByDate($data,$area_id,$data['date']);
                $data['use_image_type'] = 'hotel';//ホテル画像の方が映える
                //set header title
                $data['header_title'] = sprintf($this->lang->line('common_date_header_title'), $data['keyword'], $data['display_date'], $this->lang->line('header_website_name'));
                $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['keyword']);
                $data['header_description'] = sprintf($this->lang->line('common_date_header_description'), $data['display_date'], $data['keyword']);
            }else{//キーワードだけ
                $show_page = 'show';
                $data['recommend_futures_title'] = $data['strim_keyword'].'の'.$this->lang->line('recommend_futures_title_default');
                //デフォルト
                $orderExpression = "date ASC";
                $page = 1;
                $weather = 'shine';
                $daytime_shine_sequenceExpression = ' >= 1';//指定なし
                $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
                $start_date = null;//指定なし。直近
                $futuresData = $this->Future_model->getFutures('area', $area_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
                $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

                //じゃらんホテル
                $data['hotel_title'] = '晴れの日に'.$data['strim_keyword'].'近辺の温泉へ行く';
                $this->jalan_lib->makeSpringsHotelsByAreaId($data,$area_id);
                $data['stop_line'] = 2;
                //set header title
                $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['keyword'], $this->lang->line('header_website_name'));
                $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['keyword']);
                $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['keyword']);
            }

        }
        
        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/search?keyword='.urlencode($data['keyword']).$data_etc_url,$data['strim_keyword'].$data_etc_string.$data['date']);

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
            'js/Chart.js',
            'js/jquery.slimmenu.min.js'
        )));

        $this->load->view("search/keyword/$show_page", array_merge($this->data,$data));
    }
    
    function weather($type="area",$object_id,$keyword = '')
    {
        $data['bodyId'] = 'area';
        $data['recommend_futures_title'] = $this->lang->line('recommend_futures_title_default');

        //未来データ/////////////////////////////////////////
        $orderExpression = "date ASC";
        $page = 1;
        $weather = 'shine';
        $daytime_shine_sequenceExpression = ' >= 1';//指定なし
        $day_type = array('type'=>'multi','value'=>array(6,7,8));//休日+祝日
        $start_date = null;//指定なし。直近
        
        if($type == 'search'){
            $data['area_id'] = $object_id;
            $data['recommend_futures_title'] = urldecode($keyword).'の'.$this->lang->line('recommend_futures_title_default');
        }elseif($type == 'area'){
            $data['area_id'] = $object_id;
            $data['recommend_futures_title'] = $this->data['all_areas'][$object_id]->area_name.'の'.$this->lang->line('recommend_futures_title_default');
        }elseif ($type == 'spring'){
            $data['area_id'] = $this->data['all_springs'][$object_id]->area_id;
            $data['recommend_futures_title'] = $this->data['all_springs'][$object_id]->spring_name.'の'.$this->lang->line('recommend_futures_title_default');
        }elseif ($type == 'airport'){
            $this->data['all_airports'] = $this->Airport_model->getAllAirports();
            $data['area_id'] = $this->data['all_springs'][$object_id]->area_id;
            $data['recommend_futures_title'] = $this->data['all_airports'][$object_id]->airport_name.'の'.$this->lang->line('recommend_futures_title_default');
        }
        $futuresData = $this->Future_model->getFutures('area', $object_id, $orderExpression, $page, $weather, $daytime_shine_sequenceExpression, $day_type, $start_date);
        $data['futures'] = array_chunk($futuresData['data'],$this->config->item('paging_day_row_count'));

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/area/',$this->lang->line('topicpath_about'));

        //set header title
        $data['header_title'] = '天気検索｜未来の天気なら'.$this->lang->line('header_website_name');
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), '天気検索');
        $data['header_description'] = '天気を検索して晴れの日にでかけるならハレコ。世界初、天気予測エンジンで晴れを提案するサービス「ハレコ」';

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
            'js/future.js'
        )));

        $this->load->view('search/weather', array_merge($this->data,$data));
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */