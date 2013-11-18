<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotion extends MY_Controller {

    /**
     * Index Gallery for this controller.
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
        force_ssl();
        $this->load->helper('form');
        $this->lang->load('setting');
        $this->lang->load('promotion');
        $this->load->library('tank_auth');
        //connect database
        $this->load->database();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $user_id = $this->tank_auth->get_user_id();

        // get promotions
        $this->load->model('Promotion_model');
        
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
        $promotionsResult = $this->Promotion_model->getPromotionsOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['promotions'] = $promotionsResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/promotion/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($promotionsResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        $data['isPromotionSearch'] = TRUE;
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));
        $this->load->view('console/promotion/manage', $data);
    }

    public function show($promotion_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        // get promotions
        $this->load->model('Promotion_model');
        $promotion_coupons = $this->Promotion_model->getPromotionAndCouponsById($promotion_id);

        $data['promotion'] = reset($promotion_coupons);
        $data['promotion_coupons'] = $promotion_coupons;
        $data['isPromotionSearch'] = TRUE;
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));
        $this->load->view('console/promotion/show', $data);
    }

    /**
     * search keyword action
     *
     */
    function search()
    {
        $keywords = $this->input->get('keyword');
        $keywords = preg_replace('/@+/', ' ', mb_convert_kana($keywords, 's'));
        $keywords = array_filter(explode(' ', $keywords), 'strlen');
        $page = intval($this->input->get('page')) < 1 ? 1 : intval($this->input->get('page'));

        $this->load->model('Promotion_model');
        $promotionsResult = $this->Promotion_model->getPromotionsAndCouponsByKeywords($keywords, $page);

        $data['search_keywords'] = implode($keywords, ' ');
        $data['promotions'] = $promotionsResult['data'];
        $data['page'] = $page;
        $data['pageFormat'] = "console/promotion/search?keyword={$data['search_keywords']}&page=%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['maxPageCount'] = (int) ceil(intval($promotionsResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $this->lang->load('cart');
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));
        $this->load->view('console/promotion/search', $data);
    }

    public function add()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        //$this->form_validation->set_rules('category_id', $this->lang->line('promotion_input_category'), 'required|xss_clean');
        //$this->form_validation->set_rules('coupon_id', $this->lang->line('promotion_input_coupon'), 'required|xss_clean');
        $this->form_validation->set_rules('title_ja', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
        $this->form_validation->set_rules('title_en', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
        $this->form_validation->set_rules('title_th', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
        $this->form_validation->set_rules('save', $this->lang->line('promotion_input_save'), 'required|xss_clean');
        $this->form_validation->set_rules('user_use_limit', $this->lang->line('promotion_input_user_use_limit'), 'required|xss_clean');
        $this->form_validation->set_rules('total_use_limit', $this->lang->line('promotion_input_total_use_limit'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        $this->form_validation->set_rules('start', $this->lang->line('promotion_input_date_start'), "required|xss_clean|htmlspecialchars|strip_tags|date_format");
        $this->form_validation->set_rules('end', $this->lang->line('promotion_input_date_end'), "required|xss_clean|htmlspecialchars|strip_tags|date_format|date_from_today|date_from_start[{$this->input->post('start')}]");
        
        
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $coupon_ids = $this->input->post('coupon_id');
            if($this->form_validation->run() == TRUE){
                $start_date = explode('/',$this->input->post('start'));
                $start_time = mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]);
                $end_date = explode('/',$this->input->post('end'));
                $end_time = mktime(23, 59, 59, $end_date[1], $end_date[2], $end_date[0]);
                $promotionData = array(
                    'title_ja' => $this->input->post('title_ja'),
                    'title_en' => $this->input->post('title_en'),
                    'title_th' => $this->input->post('title_th'),
                    'save' => $this->input->post('save'),
                    'stock' => $this->input->post('total_use_limit'),
                    'user_use_limit' => $this->input->post('user_use_limit'),
                    'total_use_limit' => $this->input->post('total_use_limit'),
                    'start' => $start_time,
                    'end' => $end_time
                );
                $this->load->model('Promotion_model');
                $promotion_id = $this->Promotion_model->insertPromotion($promotionData,$coupon_ids);
                redirect("console/promotion/manage");
                return;
            }else{
                $category_ids = $this->input->post('category_id');
                $judge=array_filter($category_ids);
                if( !empty($judge) ){
                    // get coupons
                    $this->load->model('Coupon_model');
                    $this->Coupon_model->setBannedQuery(FALSE);
                    //ミス用にカテゴリ内のクーポンを取得
                    foreach ($category_ids as $category_id){
                        $data['category_coupons'][] = $this->Coupon_model->getCouponsByCategoryId($category_id);
                    }
                }
                $data['category_id'] = $category_ids;
                $data['coupon_id'] = $coupon_ids;
                $data['save'] = $this->input->post('save');
                $data['user_use_limit'] = $this->input->post('user_use_limit');
            }
        }
        
        $data['categories'] = $this->Category_model->getAllCategories();
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-ui.js','js/jquery.ui.datepicker-ja.js')));
        $this->load->view('console/promotion/add', $data);
    }

    public function edit($promotion_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Promotion_model');
        $this->Promotion_model->setBannedQuery(FALSE);
        $promotion_id = intval($promotion_id);
        $promotion_coupons = $this->Promotion_model->getPromotionCouponsById($promotion_id);
        if (empty($promotion_coupons)) {
            $this->logger->err(sprintf('failed to get promotion $d', $promotion_id));
            show_404();
        }

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            //$this->form_validation->set_rules('category_id', $this->lang->line('promotion_input_category'), 'required|xss_clean');
            //$this->form_validation->set_rules('coupon_id', $this->lang->line('promotion_input_coupon'), 'required|xss_clean');
            $this->form_validation->set_rules('title_ja', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
            $this->form_validation->set_rules('title_en', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
            $this->form_validation->set_rules('title_th', $this->lang->line('promotion_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('promotion_title_min_length')}]|max_length[{$this->config->item('promotion_title_max_length')}]");
            $this->form_validation->set_rules('save', $this->lang->line('promotion_input_save'), 'required|xss_clean');
            $this->form_validation->set_rules('user_use_limit', $this->lang->line('promotion_input_user_use_limit'), 'required|xss_clean');
            $this->form_validation->set_rules('total_use_limit', $this->lang->line('promotion_input_total_use_limit'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
            $this->form_validation->set_rules('start', $this->lang->line('promotion_input_date_start'), "required|xss_clean|htmlspecialchars|strip_tags|date_format");
            $this->form_validation->set_rules('end', $this->lang->line('promotion_input_date_end'), "required|xss_clean|htmlspecialchars|strip_tags|date_format|date_from_today|date_from_start[{$this->input->post('start')}]");
            
            $coupon_ids = $this->input->post('coupon_id');

            if($this->form_validation->run() == TRUE){
                $start_date = explode('/',$this->input->post('start'));
                $start_time = mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]);
                $end_date = explode('/',$this->input->post('end'));
                $end_time = mktime(23, 59, 59, $end_date[1], $end_date[2], $end_date[0]);
                $promotionData = array(
                    'title_ja' => $this->input->post('title_ja'),
                    'title_en' => $this->input->post('title_en'),
                    'title_th' => $this->input->post('title_th'),
                    'save' => $this->input->post('save'),
                    'stock' => '`stock` + '.$this->input->post('total_use_limit').' - `total_use_limit`',
                    'user_use_limit' => $this->input->post('user_use_limit'),
                    'total_use_limit' => $this->input->post('total_use_limit'),
                    'start' => $start_time,
                    'end' => $end_time
                );
                $this->load->model('Promotion_model');
                $promotion_id = $this->Promotion_model->updatePromotion($promotion_id,$promotionData,$coupon_ids,$promotion_coupons);
                redirect("console/promotion/manage");
                return;
            }else{
                $category_ids = $this->input->post('category_id');
                $judge=array_filter($category_ids);
                if( !empty($judge) ){
                    // get coupons
                    $this->load->model('Coupon_model');
                    $this->Coupon_model->setBannedQuery(FALSE);
                    //ミス用にカテゴリ内のクーポンを取得
                    foreach ($category_ids as $category_id){
                        $data['category_coupons'][] = $this->Coupon_model->getCouponsByCategoryId($category_id);
                    }
                }
                $data['category_id'] = $category_ids;
                $data['coupon_id'] = $coupon_ids;
                $data['save'] = $this->input->post('save');
                $data['user_use_limit'] = $this->input->post('user_use_limit');
            }
        }else{
            $data['coupon_id'] = array_keys($promotion_coupons);
            $data['save'] = reset($promotion_coupons)->save;
        }
        
        $this->load->model('Coupon_model');
        $this->Coupon_model->setBannedQuery(FALSE);
        $first_promotion_coupons = reset($promotion_coupons);
        if(is_null($first_promotion_coupons->coupon_id)){
            //指定なしでOK
        }else{
            foreach ($promotion_coupons as $promotion_coupon){
                $coupon_categories = $this->Coupon_model->getCouponCategoriesByCouponId($promotion_coupon->coupon_id);
                $data['category_id'][] = $coupon_categories[0]->category_id;
                $data['category_coupons'][] = $this->Coupon_model->getCouponsByCategoryId($coupon_categories[0]->category_id);
            }
        }
        
        $data['promotion'] = $first_promotion_coupons;
        $data['promotion_id'] = $promotion_id;
        $data['categories'] = $this->Category_model->getAllCategories();

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-ui.js','js/jquery.ui.datepicker-ja.js')));

        $this->load->view('console/promotion/edit', $data);
    }

    public function delete($promotion_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Promotion_model');
        $this->Promotion_model->setBannedQuery(FALSE);
        $promotion_id = intval($promotion_id);
        $promotion = $this->Promotion_model->getPromotionById($promotion_id);

        if ( !empty($promotion) ) {
            //delete isssue
            if ($this->Promotion_model->deletePromotion($promotion_id)) {
                //success to delete category
                redirect("console/promotion/manage");
            } else {
                //failed to delete category
                $this->logger->err(sprintf('Could not delete promotion: promotion id $d.', $promotion_id));
                show_404();
            }
        }
    }

    public function export($promotion_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Promotion_model');
        $csv_data = $this->Promotion_model->getCodeAndUsersCSVById($promotion_id);
        $this->load->helper('download');
        force_download('promotion_code_'.date('Ymd_Hi').".csv", $csv_data);
        return;
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */