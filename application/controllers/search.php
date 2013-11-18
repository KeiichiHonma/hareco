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
        $this->load->model('Future_model');
        //$this->load->model('Coupon_model');
        //$this->data['regions'] = $this->Region_model->getAllregions();
        $this->data['areas'] = $this->Area_model->getAllareas();
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
        //$data['holiday_futures'] =$this->Future_model->getFuturesGroupByAreaIdByHoliday();

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/index.css')));
        //$this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.anythingslider.js','jquery.colorbox.js')));
        $this->load->view('search/index', array_merge($this->data,$data));
    }

    function test(){
        //東京都港区六本木
        //北海道岩見沢市東山町263-8
        $this->_getGeoCode('定山渓');
    }

    /*
    パフォーマンス対策のためハードコーディングします
    */
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
        $yahoo_address = $this->_getGeoCode($_POST['keyword']);
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
        redirect('search/keyword/'.urlencode($keyword).'/'.$area_id);
        //echo $area_id;
        die();
    }

    function keyword($keyword,$area_id)
    {
        $data = array();
        $this->load->view('search/test', $data);
    }

    /**
     * search region action
     *
     */
    function region($region_id,$order = "date", $page = 1)
    {
        

        
        if ($order == "date") {
            $orderExpression = "date ASC";//日付古い
        } else if ($order == "dateRev") {
            $orderExpression = "modified DESC";//日付新しい
        } else {
            $order = "date";
            $orderExpression = "date ASC";//更新古い
        }
        
        $region_id = intval($region_id);
        $page = intval($page);
        
        $futuresResult = $this->Future_model->getFuturesByRegionIdByHoliday($region_id,$order,$page);
var_dump($futuresResult);
die();
        $data['region_id'] = $region_id;
        $data['futures']['common'] = $futuresResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "search/region/{$region_id}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($futuresResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');
        
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->data['regions'][$region_id]->region_name);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->data['categories'][$region_id]->$name_language, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['categories'][$region_id]->$name_language);
        $data['header_description'] = sprintf($this->lang->line('region_header_description'), $this->data['categories'][$region_id]->$name_language);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/list.css')));
        $this->load->view('search/region', array_merge($this->data,$data));
    }

    /**
     * search area action
     *
     */
    function area($area_id,$order = "modified", $page = 1)
    {

        if ($order == "modified") {
            $orderExpression = "modified DESC";//更新新しい
        } else if ($order == "modifiedRev") {
            $orderExpression = "modified ASC";//更新古い
        } else if ($order == "price") {
            $orderExpression = "price DESC";//価格高い
        } else if ($order == "priceRev") {
            $orderExpression = "price ASC";//価格安い
        } else {
            $order = "modified";
            $orderExpression = "modified DESC";//更新新しい
        }
        
        $area_id = intval($area_id);
        $page = intval($page);
        
        $couponsResult = $this->Coupon_model->getCouponsOrderByAreaId($area_id,$orderExpression,$page);

        $data['area_id'] = $area_id;
        $data['coupons']['common'] = $couponsResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "search/area/{$area_id}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($couponsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');
        
        
        $name_language = 'name_'.$this->config->item('language_min');
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->data['areas'][$area_id]->$name_language);

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->data['areas'][$area_id]->$name_language, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->data['areas'][$area_id]->$name_language);
        $data['header_description'] = sprintf($this->lang->line('area_header_description'), $this->data['areas'][$area_id]->$name_language);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/list.css')));
        $this->load->view('search/area', array_merge($this->data,$data));
    }

    /**
    * getGeoCode
    *
    * @param string address // 検索に使用する文字列
    * @return array ret // 結果配列(緯度、経度、住所、地図のURL)
    **/
    function _getGeoCode($address)
    {
        define("GEO_CODE_API_URL", "http://geo.search.olp.yahooapis.jp/OpenLocalPlatform/V1/geoCoder");
        define("MAP_API_URL", "http://map.olp.yahooapis.jp/OpenLocalPlatform/V1/static");
        //define("APP_ID", "dj0zaiZpPWgwWkN3SUtHbXpxViZzPWNvbnN1bWVyc2VjcmV0Jng9ODM-");
        define("APP_ID", "dj0zaiZpPVJOek93SUZwN0RJUCZzPWNvbnN1bWVyc2VjcmV0Jng9Yzc-");
        
        $to_url = GEO_CODE_API_URL;
        $to_url .= "?appid=" . APP_ID;
        $to_url .= "&query=" . urlencode($address);
        //$to_url .= "&ie=UTF-8";
        //$to_url .= "&datum=wgs";
        //$to_url .= "&results=5";
        $xml = @simplexml_load_file($to_url);
        return $xml->Feature->Property->Address;
/*
        if (!$data) return false;
        $ret = array();
        foreach ($data as $key => $value) {
            if ($key == "Feature") {
                list($longitude,$latitude) = explode(",", $value->Geometry->Coordinates);
                $ret[] = array(
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "image_url" => getImageUrl($latitude, $longitude),
                    "address" => $value->Property->Address,
                );
            }
        }
*/
        return $ret;
    }

    /**
    * getImageUrl
    *
    * @param string latitude
    * @param string longitude
    * @return string map_url
    **/
    function _getImageUrl($latitude, $longitude)
    {
        $map_url = MAP_API_URL;
        $map_url .= "?appid=" . APP_ID;
        $map_url .= "&lat=" . $latitude;
        $map_url .= "&lon=" . $longitude;
        $map_url .= "&z=10";
        $map_url .= "&width=300";
        $map_url .= "&height=200";
        $map_url .= "&pin1=" . $latitude . "," . $longitude;
        return $map_url;
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */