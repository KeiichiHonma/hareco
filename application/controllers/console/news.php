<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {

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
        $this->lang->load('news');
        $this->load->library('tank_auth');
        $this->load->model('News_model');
        //connect database
        $this->load->database();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        // get news
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
        $this->News_model->setBannedQuery(FALSE);
        $newsResult = $this->News_model->getNewsOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['newsResult'] = $newsResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/news/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($newsResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/news/manage', $data);
    }

    function show($news_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $this->load->model('Magazine_model');
        $this->Magazine_model->setBannedQuery(FALSE);
        $data['news'] = $this->News_model->getNewsById($news_id);

        //閲覧可能なクーポンがない場合は見せない
        if(count($data['news']) == 0){
            show_404();
        }
        
        
        //set header title
        $data['header_title'] = sprintf('%s | [%s]', $data['news']->title_ja, $this->lang->line('header_title'));

        $this->load->view('console/news/show', $data);
    }

    public function add()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $this->form_validation->set_rules('title_ja', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");
        $this->form_validation->set_rules('title_en', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");
        $this->form_validation->set_rules('title_th', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");

        $this->form_validation->set_rules('description_ja', $this->lang->line('news_input_description'), "required|htmlspecialchars");
        $this->form_validation->set_rules('description_en', $this->lang->line('news_input_description'), "required|htmlspecialchars");
        $this->form_validation->set_rules('description_th', $this->lang->line('news_input_description'), "required|htmlspecialchars");
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $newsData = array(
                    'title_ja' => $this->input->post('title_ja'),
                    'title_en' => $this->input->post('title_en'),
                    'title_th' => $this->input->post('title_th'),
                    'description_ja' => $this->input->post('description_ja'),
                    'description_en' => $this->input->post('description_en'),
                    'description_th' => $this->input->post('description_th'),
                    'type' => $this->input->post('type'),
                    'banned' => $this->input->post('banned'),
                    //'start' => $start_time,
                    //'end' => $end_time
                );
                $this->load->model('News_model');
                $news_id = $this->News_model->insertNews($newsData);
                redirect("console/news/show/".$news_id);
                return;
            }else{
                $data['banned'] = $this->input->post('banned');
                $data['type'] = $this->input->post('type');
                $data['description_ja'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_ja')));
                $data['description_en'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_en')));
                $data['description_th'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_th')));
            }
        }else{
            $data['banned'] = 0;
            $data['type'] = 0;
        }
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/html5uploder.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/jquery.ui.datetimepicker.js','js/jquery.ui.datetimepicker-jp.js','js/jquery.ui.datepicker-ja.js','js/jquery.html5uploader.min.js','js/html5uploder.js')));
        $this->load->view('console/news/add', $data);
    }

    public function edit($news_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('News_model');
        $this->News_model->setBannedQuery(FALSE);
        $news_id = intval($news_id);
        $news = $this->News_model->getNewsById($news_id);
        if (empty($news)) {
            $this->logger->err(sprintf('failed to get news $d', $news_id));
            show_404();
        }

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $this->form_validation->set_rules('title_ja', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");
            $this->form_validation->set_rules('title_en', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");
            $this->form_validation->set_rules('title_th', $this->lang->line('news_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('news_title_min_length')}]|max_length[{$this->config->item('news_title_max_length')}]");

            $this->form_validation->set_rules('description_ja', $this->lang->line('news_input_description'), "required|htmlspecialchars");
            $this->form_validation->set_rules('description_en', $this->lang->line('news_input_description'), "required|htmlspecialchars");
            $this->form_validation->set_rules('description_th', $this->lang->line('news_input_description'), "required|htmlspecialchars");

            if($this->form_validation->run() == TRUE){
                $newsData = array(
                    'title_ja' => $this->input->post('title_ja'),
                    'title_en' => $this->input->post('title_en'),
                    'title_th' => $this->input->post('title_th'),
                    'description_ja' => $this->input->post('description_ja'),
                    'description_en' => $this->input->post('description_en'),
                    'description_th' => $this->input->post('description_th'),
                    'type' => $this->input->post('type'),
                    'banned' => $this->input->post('banned'),
                );
                $this->load->model('News_model');
                $this->News_model->updateNews($news_id,$newsData);
                redirect("console/news/show/".$news_id);
                return;
            }else{
                $data['description_ja'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_ja')));
                $data['description_en'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_en')));
                $data['description_th'] = trim(htmlspecialchars_decode($this->input->post('descriptionca_th')));
            }
        }else{
            $data['description_ja'] = htmlspecialchars_decode($news->description_ja);
            $data['description_en'] = htmlspecialchars_decode($news->description_en);
            $data['description_th'] = htmlspecialchars_decode($news->description_th);
        }
        
        $data['news'] = $news;
        $data['news_id'] = $news_id;
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/html5uploder.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/jquery.ui.datetimepicker.js','js/jquery.ui.datetimepicker-jp.js','js/jquery.ui.datepicker-ja.js','js/jquery.html5uploader.min.js','js/html5uploder.js')));
        $this->load->view('console/news/edit', $data);
    }

    public function delete($news_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('News_model');
        $this->News_model->setBannedQuery(FALSE);
        $news_id = intval($news_id);
        $news = $this->News_model->getNewsById($news_id);

        if ( !empty($news) ) {
            //delete isssue
            if ($this->News_model->deleteNews($news_id)) {
                //success to delete category
                redirect("console/news/manage");
            } else {
                //failed to delete category
                $this->logger->err(sprintf('Could not delete news: news id $d.', $news_id));
                show_404();
            }
        }
    }

    public function export($news_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('News_model');
        $csv_data = $this->News_model->getCodeAndUsersCSVById($news_id);
        $this->load->helper('download');
        force_download('news_code_'.date('Ymd_Hi').".csv", $csv_data);
        return;
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */