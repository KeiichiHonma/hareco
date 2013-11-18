<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area extends MY_Controller {

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
        $this->lang->load('area');
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
        $areasResult = $this->Area_model->getAreasOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['areas'] = $areasResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/area/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($areasResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/area/manage', $data);
    }

    public function add()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $this->form_validation->set_rules('name_ja', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");
        $this->form_validation->set_rules('name_en', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");
        $this->form_validation->set_rules('name_th', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if ( $this->form_validation->run() == TRUE ) {
                $areaData = array(
                    'name_ja' => $this->input->post('name_ja'),
                    'name_en' => $this->input->post('name_en'),
                    'name_th' => $this->input->post('name_th')
                );
                $this->Area_model->insertArea($areaData);
                redirect("console/area/manage");
                return;
            }
        }
        $this->load->view('console/area/add', $data);
    }

    public function edit($area_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $area_id = intval($area_id);
        $area = $this->Area_model->getAreaById($area_id);

        if (empty($area)) {
            $this->logger->err(sprintf('failed to get area $d', $area_id));
            show_404();
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $this->form_validation->set_rules('name_ja', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");
            $this->form_validation->set_rules('name_en', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");
            $this->form_validation->set_rules('name_th', $this->lang->line('area_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('area_title_min_length')}]|max_length[{$this->config->item('area_title_max_length')}]");

            if( $this->form_validation->run() != FALSE ){
                $areaData = array(
                    'name_ja' => $this->input->post('name_ja'),
                    'name_en' => $this->input->post('name_en'),
                    'name_th' => $this->input->post('name_th')
                );

                if ( $this->Area_model->updateArea($area_id, $areaData) )
                {
                    redirect("console/area/manage");
                } else {
                    $this->logger->err("edit area error.");
                    $data['error'] = $this->lang->line('area_edit_area_error');
                }
            }

        }
        
        $data = array(
            'area' => $area,
            'area_id' => $area_id,
        );

        $this->load->view('console/area/edit', $data);
    }

    public function delete($area_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $area_id = intval($area_id);
        $area = $this->Area_model->getAreaById($area_id);

        if ( !empty($area) ) {
            //delete isssue
            if ($this->Area_model->deleteArea($area_id)) {
                //success to delete area
                redirect("console/area/manage");
            } else {
                //failed to delete area
                $this->logger->err(sprintf('Could not delete area: area id $d.', $area_id));
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