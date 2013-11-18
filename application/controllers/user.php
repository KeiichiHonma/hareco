<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

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
var_dump('test');
die();
        //add helper
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('image');
        $this->lang->load('setting');
        $this->lang->load('user');
        $this->lang->load('cart');
        $this->load->library('tank_auth');
        $this->load->model('Purchase_model');
        //connect database
        //$this->load->database();

        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $user_id = $this->tank_auth->get_user_id();
        
        $page = intval($page);
        if ($order == "modified") {
            $orderExpression = "modified DESC";
        } else if ($order == "modifiedRev") {
            $orderExpression = "modified ASC";
        } else if ($order == "created") {
            $orderExpression = "created DESC";
        } else if ($order == "createdRev") {
            $orderExpression = "created ASC";
        } else {
            $order = "modified";
            $orderExpression = "modified DESC";
        }
        $purchasesResult = $this->Purchase_model->getPurchasesOrderByUserId($user_id, $orderExpression, $page);
        $data['purchases'] = $purchasesResult['data'];
        if(!empty($data['purchases'])){
            $purchase_ids = array_keys($data['purchases']);
            $data['purchases_coupons'] = $this->Purchase_model->getPurchasesCouponsByPurchaseIds($purchase_ids);
        }

        
        $data['user_id'] = $user_id;
        $data['page'] = $page;
        $data['order'] = $order;
        
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "user/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($purchasesResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/manage.css')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('header_mypage_title'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('header_title_history_cart'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('header_title_history_cart'));
        
        $this->load->view('user/manage', array_merge($this->data,$data));
/*
お知らせと購入履歴を表示するのをペンド
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        // get news
        $this->load->model('News_model');
        $data['newsResult'] = $this->News_model->getValidateNewsByTime(time());
        
        $user_id = $this->tank_auth->get_user_id();

        $purchasesResult = $this->Purchase_model->getPurchasesOrderByUserId($user_id, "modified DESC", 1);
        $data['purchases'] = $purchasesResult['data'];
        if(!empty($data['purchases'])){
            $purchase_ids = array_keys($data['purchases']);
            $data['purchases_coupons'] = $this->Purchase_model->getPurchasesCouponsByPurchaseIds($purchase_ids);
        }

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('header_mypage_title'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_news'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_news'));
        
        $this->load->view('user/manage', array_merge($this->data,$data));
*/
    }

    public function history($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $user_id = $this->tank_auth->get_user_id();
        
        $page = intval($page);
        if ($order == "modified") {
            $orderExpression = "modified DESC";
        } else if ($order == "modifiedRev") {
            $orderExpression = "modified ASC";
        } else if ($order == "created") {
            $orderExpression = "created DESC";
        } else if ($order == "createdRev") {
            $orderExpression = "created ASC";
        } else {
            $order = "modified";
            $orderExpression = "modified DESC";
        }
        $purchasesResult = $this->Purchase_model->getPurchasesOrderByUserId($user_id, $orderExpression, $page);
        $data['purchases'] = $purchasesResult['data'];
        if(!empty($data['purchases'])){
            $purchase_ids = array_keys($data['purchases']);
            $data['purchases_coupons'] = $this->Purchase_model->getPurchasesCouponsByPurchaseIds($purchase_ids);
        }

        
        $data['user_id'] = $user_id;
        $data['page'] = $page;
        $data['order'] = $order;
        
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "user/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($purchasesResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/manage.css')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('header_mypage_title'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('header_title_history_cart'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('header_title_history_cart'));
        
        $this->load->view('user/history', array_merge($this->data,$data));
    }

    function purchase_show($purchase_id)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $data['purchases'] = $this->Purchase_model->getPurchaseAndCouponsAndPromotionsById($purchase_id);

        //閲覧可能なクーポンがない場合は見せない
        if(empty($data['purchases'])){
            show_404();
        }
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/manage.css')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array('/user/manage',$this->lang->line('header_mypage_title'));
        $data['topicpaths'][] = array('/user/history',$this->lang->line('header_title_history_cart'));
        $data['topicpaths'][] = array(null,$data['purchases'][0]->order);
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['purchases'][0]->order.' '.$this->lang->line('header_title_history_cart'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('header_title_history_cart'));

        $this->load->view('user/purchase_show', array_merge($this->data,$data));
    }

    /**
     * @return void
     */
    function message()
    {
        $message = $this->session->flashdata('message');
        if( $message !== FALSE ){
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array('/user/manage',$this->lang->line('header_mypage_title'));
            $data['topicpaths'][] = array(null,$this->lang->line('user_show_message'));
            
            $data['message'] = $this->lang->line('user_show_message');

            //set header title
            $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('user_show_message'), $this->config->item('website_name', 'tank_auth'));

            $this->load->view('user/message', array_merge($this->data,$data));
        }else{
            if ($this->tank_auth->is_logged_in()) {                                // not logged in or not activated
                redirect('user/manage');
            }
            redirect('');
        }
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */