<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {

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
        $this->lang->load('category');
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
        $categoriesResult = $this->Category_model->getCategoriesOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['categories'] = $categoriesResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/category/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($categoriesResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/category/manage', $data);
    }

    public function add()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $this->form_validation->set_rules('name_ja', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");
        $this->form_validation->set_rules('name_en', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");
        $this->form_validation->set_rules('name_th', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if ( $this->form_validation->run() == TRUE ) {
                $categoryData = array(
                    'name_ja' => $this->input->post('name_ja'),
                    'name_en' => $this->input->post('name_en'),
                    'name_th' => $this->input->post('name_th')
                );
                $this->Category_model->insertCategory($categoryData);
                redirect("console/category/manage");
                return;
            }
        }
        $this->load->view('console/category/add', $data);
    }

    public function edit($category_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $category_id = intval($category_id);
        $category = $this->Category_model->getCategoryById($category_id);

        if (empty($category)) {
            $this->logger->err(sprintf('failed to get category $d', $category_id));
            show_404();
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $this->form_validation->set_rules('name_ja', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");
            $this->form_validation->set_rules('name_en', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");
            $this->form_validation->set_rules('name_th', $this->lang->line('category_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('category_title_min_length')}]|max_length[{$this->config->item('category_title_max_length')}]");

            if( $this->form_validation->run() != FALSE ){
                $categoryData = array(
                    'name_ja' => $this->input->post('name_ja'),
                    'name_en' => $this->input->post('name_en'),
                    'name_th' => $this->input->post('name_th')
                );

                if ( $this->Category_model->updateCategory($category_id, $categoryData) )
                {
                    redirect("console/category/manage");
                } else {
                    $this->logger->err("edit category error.");
                    $data['error'] = $this->lang->line('category_edit_category_error');
                }
            }

        }
        
        $data = array(
            'category' => $category,
            'category_id' => $category_id,
        );

        $this->load->view('console/category/edit', $data);
    }

    public function delete($category_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $category_id = intval($category_id);
        $category = $this->Category_model->getCategoryById($category_id);

        if ( !empty($category) ) {
            //delete isssue
            if ($this->Category_model->deleteCategory($category_id)) {
                //success to delete category
                redirect("console/category/manage");
            } else {
                //failed to delete category
                $this->logger->err(sprintf('Could not delete category: category id $d.', $category_id));
                show_404();
            }
        }

        // there are some errors.
        $this->logger->warn(sprintf('coupon id %2$d is not user id %1$d coupon', $user_id, $coupon_id));
        redirect("console/coupon/edit/{$coupon_id}");
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */