<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/user/show/[id]
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/user/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct(){
        parent::__construct();

        //add helper
        $this->load->helper('html');
        $this->load->helper('url');
        force_ssl();
        $this->load->helper('form');
        $this->load->helper('image');
        $this->lang->load('setting');
        $this->lang->load('promotion');
        $this->lang->load('cart');
        $this->load->model('Coupon_model');
        $this->load->model('Promotion_model');
        $this->load->model('Purchase_model');
        $this->load->library('tank_auth');
        $this->load->library('cart_lib');
        //connect database
        //$this->load->database();
var_dump('test');
die();        
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    public function manage()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $cart = $this->session->userdata('cart');
        $data = array();
        
        if($cart !== FALSE && !empty($cart)){
            //クーポン
            $cart_coupon_ids = array_keys($cart);
            $data['cart_coupon_id'] = end($cart_coupon_ids);
            $data['coupons'] = $this->Coupon_model->getCouponsByCartCouponIds($cart_coupon_ids);

            //数量変更
            if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
                if(!empty($_POST['coupon_numbers'])){
                    foreach ($_POST['coupon_numbers'] as $coupon_id => $coupon_number){
                        //在庫チェック
                        if($data['coupons'][$coupon_id]->stock < $coupon_number){
                            $error['coupons'][$coupon_id] = $this->lang->line('cart_message_not_edit_number');
                            
                        }else{
                            $cart[$coupon_id]['number'] = $coupon_number;
                        }
                    }
                    if(isset($error)) $this->session->set_flashdata('error',$error);
                    $this->session->set_userdata('cart', $cart);
                    redirect("cart/manage");
                }
            }
            
            $promotions_and_promotions_coupons = $this->cart_lib->check($data['coupons']);
            $data['cart'] = $this->cart_lib->cart;
            $data['cart_promotion_ids'] = $this->cart_lib->cart_promotion_ids;
            $data['promotions'] = $promotions_and_promotions_coupons['promotions'];
        }else{
            $data['coupons'] = array();
        }
        

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('cart_title'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('cart_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('cart_title'));

        $this->load->view('cart/manage', array_merge($this->data,$data));
    }

    function add($coupon_id)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $coupon = $this->Coupon_model->getCouponById($coupon_id);

        //閲覧可能なクーポンがない場合は見せない
        if(empty($coupon)){
            show_404();
        }

        if($coupon->stock == 0){
            $this->_show_message($this->lang->line('cart_message_not_add_stock'));
        }

        $cart = $this->session->userdata('cart');
        if($cart === FALSE){
            $cart = array($coupon->id=>array('number'=>1,'promotion'=>null));
        }else{
            $cart[$coupon->id]['number'] = 1;
            $cart[$coupon->id]['promotion'] = null;
        }
        $this->session->set_userdata('cart', $cart);
        redirect("cart/manage");
    }

    function promotion($code)
    {

        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $data = array();
        //指定のプロモーションコード
        $promotion_and_promotion_coupons = $this->cart_lib->promotion_check($code);
        
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if(!empty($promotion_and_promotion_coupons['promotion_coupons'])){
                foreach ($promotion_and_promotion_coupons['promotion_coupons'] as $promotion_coupon){
                    $this->cart_lib->cart_promotion_ids[$promotion_coupon->coupon_id] = $promotion_coupon->promotion_id;
                    $cart[$promotion_coupon->coupon_id]['promotion_id'] = $promotion_coupon->promotion_id;
                }
            }else{
                //合計金額割引プロモーションコード
                $this->cart_lib->cart_promotion_ids['all'] = $promotion_and_promotion_coupons['promotion']->id;
            }
            $this->session->set_userdata('cart', $this->cart_lib->cart);
            $this->session->set_userdata('cart_promotion_ids', $this->cart_lib->cart_promotion_ids);
            redirect("cart/manage");
        }

        //クーポン
        $cart_coupon_ids = array_keys($this->cart_lib->cart);
        $data['cart'] = $this->cart_lib->cart;
        
        //指定コードの情報セット
        $data['coupons'] = $this->Coupon_model->getCouponsByCartCouponIds($cart_coupon_ids);
        $promotion_coupons = $promotion_and_promotion_coupons['promotion_coupons'];
        $data['use_promotion'] = $promotion_and_promotion_coupons['promotion'];

        //既存のプロモーションコードをチェックして取得
        $promotions_and_promotions_coupons = $this->cart_lib->check($data['coupons']);
        $data['cart_promotion_ids'] = $this->cart_lib->cart_promotion_ids;
        
        $data['promotions'] = $promotions_and_promotions_coupons['promotions'];
        $data['promotions'][$data['use_promotion']->id] = $data['use_promotion'];
        
        //クーポン指定タイプのプロモーションコードの場合、カートにあるクーポンIDとプロモーションコードIDを紐付ける
        if(!empty($promotion_coupons)){
            foreach ($promotion_coupons as $promotion_coupon){
                if(in_array($promotion_coupon->coupon_id,$cart_coupon_ids)) $data['cart_promotion_ids'][$promotion_coupon->coupon_id] = $data['use_promotion']->id;
            }
        }else{
            $data['cart_promotion_ids']['all'] = $data['use_promotion']->id;
        }
        
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array('/cart/manage',$this->lang->line('cart_title'));
        $data['topicpaths'][] = array(null,$this->lang->line('cart_confirm_promotion_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('cart_confirm_promotion_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('cart_confirm_promotion_title'));

        $this->load->view('cart/promotion', array_merge($this->data,$data));
    }

    public function confirm()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $cart = $this->session->userdata('cart');
        $data = array();
        
        if($cart !== FALSE){
            $cart_coupon_ids = array_keys($cart);
            $data['coupons'] = $this->Coupon_model->getCouponsByCartCouponIds($cart_coupon_ids);

            $promotions_and_promotions_coupons = $this->cart_lib->check($data['coupons']);
            $data['cart'] = $this->cart_lib->cart;
            $data['cart_promotion_ids'] = $this->cart_lib->cart_promotion_ids;
            $data['promotions'] = $promotions_and_promotions_coupons['promotions'];
        }else{
            $data['coupons'] = array();
        }
        

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array('/cart/manage',$this->lang->line('cart_title'));
        $data['topicpaths'][] = array(null,$this->lang->line('cart_confirm_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('cart_confirm_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('cart_confirm_title'));

        $this->load->view('cart/confirm', array_merge($this->data,$data));
    }

    public function buy()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $user_id = $this->tank_auth->get_user_id();
            $cart = $this->session->userdata('cart');

            if($cart !== FALSE){
                $cart_coupon_ids = array_keys($cart);
                $coupons = $this->Coupon_model->getCouponsByCartCouponIds($cart_coupon_ids);
                
                $promotions_and_promotions_coupons = $this->cart_lib->check($coupons);
                $cart = $this->cart_lib->cart;
                //$cart_promotion_ids = $this->cart_lib->cart_promotion_ids;
                $promotions = $promotions_and_promotions_coupons['promotions'];
                
                //purchases data create
                $purchases = array();
                $order = uniqid();
                $all_promotion_id = is_array($this->cart_lib->cart_promotion_ids) && array_key_exists('all',$this->cart_lib->cart_promotion_ids) ? $this->cart_lib->cart_promotion_ids['all'] : FALSE;
                $purchases['payment'] = 0;
                
                foreach ($coupons as $coupon){
                    $promotion = is_array($this->cart_lib->cart_promotion_ids) && array_key_exists($coupon->id,$this->cart_lib->cart_promotion_ids) && isset($promotions[$this->cart_lib->cart_promotion_ids[$coupon->id]]) ? $promotions[$this->cart_lib->cart_promotion_ids[$coupon->id]] : FALSE;
                    if($promotion !== FALSE){
                        $promotion_save = $promotion->save;
                        $promotion_type = $promotion->type;
                        $promotion_title_ja = $promotion->title_ja;
                        $promotion_title_en = $promotion->title_en;
                        $promotion_title_th = $promotion->title_th;
                    }else{
                        $promotion_save = 0;
                        $promotion_type = 99;
                        $promotion_title_ja = '';
                        $promotion_title_en = '';
                        $promotion_title_th = '';
                    }
                    
                    
                    $purchases['coupons'][$coupon->id]['coupon_id'] = $coupon->id;
                    $purchases['coupons'][$coupon->id]['promotion_id'] = $promotion !== FALSE ? $promotion->id : 0;
                    $purchases['coupons'][$coupon->id]['coupon_title_ja'] = $coupon->title_ja;
                    $purchases['coupons'][$coupon->id]['coupon_title_en'] = $coupon->title_en;
                    $purchases['coupons'][$coupon->id]['coupon_title_th'] = $coupon->title_th;
                    $purchases['coupons'][$coupon->id]['value'] = $coupon->value;
                    $purchases['coupons'][$coupon->id]['price'] = $coupon->price;
                    $purchases['coupons'][$coupon->id]['number'] = $cart[$coupon->id]['number'];
                    $purchases['coupons'][$coupon->id]['coupon_save'] = $coupon->save;
                    $purchases['coupons'][$coupon->id]['promotion_title_ja'] = $promotion_title_ja;
                    $purchases['coupons'][$coupon->id]['promotion_title_en'] = $promotion_title_en;
                    $purchases['coupons'][$coupon->id]['promotion_title_th'] = $promotion_title_th;
                    $purchases['coupons'][$coupon->id]['promotion_save'] = $promotion_save;
                    $purchases['coupons'][$coupon->id]['promotion_type'] = $promotion_type;
                    $subtotal = $this->cart_lib->get_payment($coupon->price,$cart[$coupon->id]['number'],$promotion_save);
                    $purchases['coupons'][$coupon->id]['subtotal'] = $subtotal;
                    $purchases['payment'] = $purchases['payment'] + $subtotal;
                }
                
                $purchases['order'] = $order;
                $purchases['user_id'] = $user_id;
                
                if($all_promotion_id !== FALSE){
                    $purchases['all'] = $promotions[$all_promotion_id];
                    $purchases['payment'] = $purchases['payment'] - floor( $purchases['payment'] * $promotions[$all_promotion_id]->save / 100 );
                }else{
                    $purchases['all'] = FALSE;
                }
                //$purchases['all'] = array_key_exists('all',$cart_promotion_ids) ? $promotions[$all_promotion_id] : FALSE;//allフラグ
                
                $purchase_id = $this->Purchase_model->insertPurchases($user_id,$purchases);
                if($purchase_id !== FALSE){

                    $this->load->model('tank_auth/users');
                    $data['user'] = $this->users->get_user_by_id($this->tank_auth->get_user_id(), TRUE);
                    $email = $data['user']->email;
                    $config = array(
                        'charset' => 'utf-8',
                        'mailtype' => 'text'
                    );
                    $this->load->library('email', $config);
                    $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
                    $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
                    $this->email->to($email);
                    $this->email->subject('['.$this->config->item('website_name', 'tank_auth').']'.$this->lang->line('cart_mail_title'));
                    $this->email->message($this->load->view('email/buy-txt', array('order'=>$order,'coupons'=>$coupons,'purchases'=>$purchases), TRUE));
                    $this->email->send();

                    unset($this->session->userdata['cart']);
                    unset($this->session->userdata['cart_promotion_ids']);
                    $this->session->sess_write();
                    redirect("cart/thanks/".$purchase_id);
                }

            }else{
                $data['coupons'] = array();
            }

        }
        $this->_show_message($this->lang->line('cart_message_not_buy'));
    }

    public function delete($coupon_id)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $coupon_id = intval($coupon_id);

        $cart = $this->session->userdata('cart');
        if($cart !== FALSE && array_key_exists($coupon_id,$cart) !== FALSE){
            unset($this->session->userdata['cart'][$coupon_id]);
        }
        $cart_promotion_ids = $this->session->userdata('cart_promotion_ids');
        if($cart_promotion_ids !== FALSE && array_key_exists($coupon_id,$cart_promotion_ids)){
            unset($this->session->userdata['cart_promotion_ids'][$coupon_id]);
        }
        $this->session->sess_write();
        redirect("cart/manage");
    }

    public function thanks($purchase_id)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $user_id = $this->tank_auth->get_user_id();

        $this->load->model('Purchase_model');
        $data['purchases'] = $this->Purchase_model->getPurchaseById($purchase_id);
        if($data['purchases']->user_id != $user_id){
            show_404();
        }
        

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array('/cart/manage',$this->lang->line('cart_title'));
        $data['topicpaths'][] = array(null,$this->lang->line('cart_thanks_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('cart_thanks_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('cart_thanks_title'));

        $this->load->view('cart/thanks', array_merge($this->data,$data));
    }

    /**
     * @return void
     */
    function message()
    {
        $message = $this->session->flashdata('message');
        if( $message !== FALSE ){
            $this->load->view('cart/message', array('message' => $this->lang->line('cart_show_message')));
        }else{
            if ($this->tank_auth->is_logged_in()) {                                // not logged in or not activated
                redirect('cart/manage');
            }
            redirect('');
        }
    }

    /**
     * Show info message
     *
     * @param    string
     * @return    void
     */
    function _show_message($message)
    {
        $this->session->set_flashdata('message', $message);
        redirect($this->config->item('show_message_cart_url'));
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */