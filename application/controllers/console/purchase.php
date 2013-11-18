<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase extends MY_Controller {

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
        $this->lang->load('purchase');
        $this->load->library('tank_auth');
        //connect database
        $this->load->database();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        // get purchases
        $this->load->model('Purchase_model');
        
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
        $purchasesResult = $this->Purchase_model->getPurchasesAndCouponAndPromotionsAndUserOrder($orderExpression, $page);
        if(!empty($purchasesResult['data'])){
            $purchase_ids = array_keys($purchasesResult['data']);
            $data['purchases_coupons'] = $this->Purchase_model->getPurchasesCouponsByPurchaseIds($purchase_ids);
        }
        
        $data['page'] = $page;
        $data['order'] = $order;
        $data['purchases'] = $purchasesResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/purchase/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($purchasesResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/purchase/manage', $data);
    }
    
    function show($purchase_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $this->load->model('Purchase_model');
        $data['purchases'] = $this->Purchase_model->getPurchaseAndCouponsAndPromotionsUserById($purchase_id);
        //閲覧可能なクーポンがない場合は見せない
        if(empty($data['purchases'])){
            show_404();
        }

        

        //set header title
        $data['header_title'] = sprintf('%s | [%s]', $data['purchases'][0]->order, $this->lang->line('header_title'));

        $this->load->view('console/purchase/show', $data);
    }

    public function export()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->form_validation->set_rules('start', $this->lang->line('purchase_input_date_start'), "required|xss_clean|htmlspecialchars|strip_tags|date_format");
        $this->form_validation->set_rules('end', $this->lang->line('purchase_input_date_end'), "required|xss_clean|htmlspecialchars|strip_tags|date_format|date_from_today|date_from_start[{$this->input->post('start')}]");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $start_date = str_replace('/','-',$this->input->post('start'));
                $end_date = str_replace('/','-',$this->input->post('end'));
                
                $this->load->model('Purchase_model');
                $csv_data = $this->Purchase_model->getPurchasesCSVByCreated($start_date,$end_date);
                $this->load->helper('download');
                force_download('purchase_'.date('Ymd_Hi').".csv", $csv_data);
                return;
            }
        }

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-ui.js','js/jquery.ui.datepicker-ja.js')));

        //set header title
        $data['header_title'] = sprintf('%s | [%s]', 'purchase csv', $this->lang->line('header_title'));

        $this->load->view('console/purchase/export', $data);
    }

    public function export_coupons()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->form_validation->set_rules('start', $this->lang->line('purchase_input_date_start'), "required|xss_clean|htmlspecialchars|strip_tags|date_format");
        $this->form_validation->set_rules('end', $this->lang->line('purchase_input_date_end'), "required|xss_clean|htmlspecialchars|strip_tags|date_format|date_from_today|date_from_start[{$this->input->post('start')}]");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $start_date = str_replace('/','-',$this->input->post('start'));
                $end_date = str_replace('/','-',$this->input->post('end'));
                
                $this->load->model('Purchase_model');
                $csv_data = $this->Purchase_model->getPurchasesAndCouponsCSVByCreated($start_date,$end_date);
                $this->load->helper('download');
                force_download('purchase_coupons_'.date('Ymd_Hi').".csv", $csv_data);
                return;
            }
        }

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-ui.js','js/jquery.ui.datepicker-ja.js')));

        //set header title
        $data['header_title'] = sprintf('%s | [%s]', 'purchase csv', $this->lang->line('header_title'));

        $this->load->view('console/purchase/export_coupons', $data);
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */