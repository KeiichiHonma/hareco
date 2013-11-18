<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Magazine extends MY_Controller {

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
        $this->lang->load('magazine');
        $this->load->library('tank_auth');
        //connect database
        $this->load->database();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        // get magazines
        $this->load->model('Magazine_model');
        
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
        $magazinesResult = $this->Magazine_model->getMagazinesOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['magazines'] = $magazinesResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/magazine/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($magazinesResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');
        

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/magazine/manage', $data);
    }

    function show($magazine_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        $this->load->model('Magazine_model');
        $this->Magazine_model->setBannedQuery(FALSE);
        $data['magazine'] = $this->Magazine_model->getMagazineById($magazine_id);

        //閲覧可能なクーポンがない場合は見せない
        if(count($data['magazine']) == 0){
            show_404();
        }
        
        
        //set header title
        $data['header_title'] = sprintf('%s | [%s]', $data['magazine']->title, $this->lang->line('header_title'));

        $this->load->view('console/magazine/show', $data);
    }

    public function add($type = 'html')
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            //$this->form_validation->set_value('send_time',$this->input->post('send_date').' '.$this->input->post('send_hour_minutes'));
            //$this->form_validation->set_rules('send_date', $this->lang->line('magazine_input_send_date'), 'required|xss_clean|date_format|date_from_today');
            
            $this->form_validation->set_rules('send_hour_minutes', $this->lang->line('magazine_input_send_hour_minutes'), 'required|xss_clean|date_hour_minutes_format');
            $this->form_validation->set_rules('send_date', $this->lang->line('magazine_input_send_date'),"'required|xss_clean|date_format|date_time_from_current_by_date_hour_minutes[{$this->input->post('send_hour_minutes')}]");
            //$this->form_validation->set_rules('send_time', $this->lang->line('magazine_input_sendtime'), "xss_clean|htmlspecialchars|strip_tags|date_time_format|date_time_from_current_by_date_hour_minutes[{$this->input->post('send_date')},{$this->input->post('send_hour_minutes')}]");
            
            $this->form_validation->set_rules('where_sex', $this->lang->line('magazine_input_where_sex'), 'xss_clean|numeric');
            $this->form_validation->set_rules('where_language', $this->lang->line('magazine_input_where_language'), 'required|xss_clean|numeric');
            $this->form_validation->set_rules('where_from_birthday_year', $this->lang->line('magazine_input_where_from_birthday_year'), 'xss_clean|numeric');
            $this->form_validation->set_rules('where_to_birthday_year', $this->lang->line('magazine_input_where_to_birthday_year'), "xss_clean|numeric|more_than[{$this->input->post('where_from_birthday_year')}]");
            $this->form_validation->set_rules('where_from_created', $this->lang->line('magazine_input_where_from_created'), 'xss_clean|date_format|date_to_today');
            $this->form_validation->set_rules('where_to_created', $this->lang->line('magazine_input_where_to_created'), "xss_clean|date_format|date_to_today|date_from_start[{$this->input->post('where_from_created')}]");
            $this->form_validation->set_rules('title', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('magazine_title_min_length')}]|max_length[{$this->config->item('magazine_title_max_length')}]");
            $this->form_validation->set_rules('description', $this->lang->line('magazine_input_description'), "required|htmlspecialchars");

            if($this->form_validation->run() == TRUE){
                //送信日時
/*
                $send_date_time = explode(' ',$this->input->post('sendtime'));
                if(!$send_date_time) return;
                $send_date_block = explode('/',$send_date_time[0]);
                if(!$send_date_block) return;
                $send_time_block = explode(':',$send_date_time[1]);
                if(!$send_time_block) return;
                $send_time = mktime($send_time_block[0], 0, 0, $send_date_block[1], $send_date_block[2], $send_date_block[0]);
*/
                //送信日時
                if($this->input->post('send_date') != '' && $this->input->post('send_hour_minutes') != ''){
                    $send_date = explode('/',$this->input->post('send_date'));
                    $send_hour_minutes = explode(':',$this->input->post('send_hour_minutes'));
                    $send_time = mktime($send_hour_minutes[0], $send_hour_minutes[1], 0, $send_date[1], $send_date[2], $send_date[0]);
                }else{
                    $send_time = 0;
                }
                
                //性別
                if($this->input->post('where_sex') != ''){
                    $where_sex = $this->input->post('where_sex');
                }else{
                    $where_sex = 9;
                }

                //言語
                if($this->input->post('where_language') != ''){
                    $where_language = $this->input->post('where_language');
                }else{
                    $where_language = 9;
                }

                //ユーザー登録日-From
                if($this->input->post('where_from_created') != ''){
                    $where_from_created = explode('/',$this->input->post('where_from_created'));
                    $where_from_created_time = mktime(0, 0, 0, $where_from_created[1], $where_from_created[2], $where_from_created[0]);
                }else{
                    $where_from_created_time = 0;
                }
                // ユーザー登録日-To 
                if($this->input->post('where_from_created') != ''){
                    $where_to_created = explode('/',$this->input->post('where_to_created'));
                    $where_to_created_time = mktime(23, 59, 59, $where_to_created[1], $where_to_created[2], $where_to_created[0]);
                }else{
                    $where_to_created_time = 0;
                }

                $magazineData = array(
                    'type' => $type == 'html' ? 0 : 1,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'sendtime' => $send_time,
                    'where_sex' => $where_sex,
                    'where_language' => $where_language,
                    'where_from_birthday_year' => $this->input->post('where_from_birthday_year') == '' ? 0 : $this->input->post('where_from_birthday_year'),
                    'where_to_birthday_year' => $this->input->post('where_to_birthday_year') == '' ? 0 : $this->input->post('where_to_birthday_year'),
                    'where_from_created' => $where_from_created_time,
                    'where_to_created' => $where_to_created_time
                );
                $this->load->model('Magazine_model');
                $magazine_id = $this->Magazine_model->insertMagazine($magazineData);
                redirect("console/magazine/show/".$magazine_id);
                return;
            }else{
                $data['send_date'] = $this->input->post('send_date');
                $data['send_hour_minutes'] = $this->input->post('send_hour_minutes');
                $data['where_sex'] = $this->input->post('where_sex');
                $data['where_language'] = $this->input->post('where_language');
                $data['where_from_created'] = $this->input->post('where_from_created');
                $data['where_to_created'] = $this->input->post('where_to_created');
                $data['description'] = trim(htmlspecialchars_decode($this->input->post('description')));
            }
        }else{
            $data['send_date'] = '';
            $data['send_hour_minutes'] = '';
            $data['where_sex'] = '';
            $data['where_language'] = 0;
            $data['where_from_created'] = '';
            $data['where_to_created'] = '';
        }
        $data['type'] = $type;
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $this->config->set_item('javascripts', array());
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/prettyPopin.css','css/abox.css','css/html5uploder.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/jquery.ui.datepicker-ja.js','js/timePicker.js','/js/jquery.prettyPopin.js','js/jquery.html5uploader.min.js','js/html5uploder.js')));
        $this->load->view('console/magazine/add', $data);
    }

    public function edit($magazine_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Magazine_model');
        $this->Magazine_model->setBannedQuery(FALSE);
        $magazine_id = intval($magazine_id);
        $magazine = $this->Magazine_model->getMagazineById($magazine_id);
        if (empty($magazine)) {
            $this->logger->err(sprintf('failed to get magazine $d', $magazine_id));
            show_404();
        }
        $data['magazine'] = $magazine;
        $this->form_validation->set_rules('send_hour_minutes', $this->lang->line('magazine_input_send_hour_minutes'), 'required|xss_clean|date_hour_minutes_format');
        $this->form_validation->set_rules('send_date', $this->lang->line('magazine_input_send_date'),"'required|xss_clean|date_format|date_time_from_current_by_date_hour_minutes[{$this->input->post('send_hour_minutes')}]");
        //$this->form_validation->set_rules('sendtime', $this->lang->line('magazine_input_sendtime'), "required|xss_clean|htmlspecialchars|strip_tags|date_time_format|date_time_from_current");
        $this->form_validation->set_rules('where_sex', $this->lang->line('magazine_input_where_sex'), 'xss_clean|numeric');
        $this->form_validation->set_rules('where_language', $this->lang->line('magazine_input_where_language'), 'required|xss_clean|numeric');
        $this->form_validation->set_rules('where_from_birthday_year', $this->lang->line('magazine_input_where_from_birthday_year'), 'xss_clean|numeric');
        $this->form_validation->set_rules('where_to_birthday_year', $this->lang->line('magazine_input_where_to_birthday_year'), "xss_clean|numeric|more_than[{$this->input->post('where_from_birthday_year')}]");
        $this->form_validation->set_rules('where_from_created', $this->lang->line('magazine_input_where_from_created'), 'xss_clean|date_format|date_to_today');
        $this->form_validation->set_rules('where_to_created', $this->lang->line('magazine_input_where_to_created'), "xss_clean|date_format|date_to_today|date_from_start[{$this->input->post('where_from_created')}]");
        $this->form_validation->set_rules('title', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('magazine_title_min_length')}]|max_length[{$this->config->item('magazine_title_max_length')}]");
        $this->form_validation->set_rules('description', $this->lang->line('magazine_input_description'), "required|htmlspecialchars");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                //送信日時
/*
                $send_date_time = explode(' ',$this->input->post('sendtime'));
                if(!$send_date_time) return;
                $send_date_block = explode('/',$send_date_time[0]);
                if(!$send_date_block) return;
                $send_time_block = explode(':',$send_date_time[1]);
                if(!$send_time_block) return;
                $send_time = mktime($send_time_block[0], 0, 0, $send_date_block[1], $send_date_block[2], $send_date_block[0]);
*/

                //送信日時
                if($this->input->post('send_date') != '' && $this->input->post('send_hour_minutes') != ''){
                    $send_date = explode('/',$this->input->post('send_date'));
                    $send_hour_minutes = explode(':',$this->input->post('send_hour_minutes'));
                    $send_time = mktime($send_hour_minutes[0], $send_hour_minutes[1], 0, $send_date[1], $send_date[2], $send_date[0]);
                }else{
                    $send_time = 0;
                }
                
                //性別
                if($this->input->post('where_sex') != ''){
                    $where_sex = $this->input->post('where_sex');
                }else{
                    $where_sex = 9;
                }

                //言語
                if($this->input->post('where_language') != ''){
                    $where_language = $this->input->post('where_language');
                }else{
                    $where_language = 9;
                }

                //ユーザー登録日-From
                if($this->input->post('where_from_created') != ''){
                    $where_from_created = explode('/',$this->input->post('where_from_created'));
                    $where_from_created_time = mktime(0, 0, 0, $where_from_created[1], $where_from_created[2], $where_from_created[0]);
                }else{
                    $where_from_created_time = 0;
                }
                // ユーザー登録日-To 
                if($this->input->post('where_from_created') != ''){
                    $where_to_created = explode('/',$this->input->post('where_to_created'));
                    $where_to_created_time = mktime(23, 59, 59, $where_to_created[1], $where_to_created[2], $where_to_created[0]);
                }else{
                    $where_to_created_time = 0;
                }

                $magazineData = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'sendtime' => $send_time,
                    'where_sex' => $where_sex,
                    'where_language' => $where_language,
                    'where_from_birthday_year' => $this->input->post('where_from_birthday_year') == '' ? 0 : $this->input->post('where_from_birthday_year'),
                    'where_to_birthday_year' => $this->input->post('where_to_birthday_year') == '' ? 0 : $this->input->post('where_to_birthday_year'),
                    'where_from_created' => $where_from_created_time,
                    'where_to_created' => $where_to_created_time
                );
                $this->load->model('Magazine_model');
                $this->Magazine_model->updateMagazine($magazine_id,$magazineData);
                redirect("console/magazine/show/".$magazine_id);
                return;
            }else{
                $data['send_date'] = $this->input->post('send_date');
                $data['send_hour_minutes'] = $this->input->post('send_hour_minutes');
                $data['description'] = trim(htmlspecialchars_decode($this->input->post('description')));
                $data['where_sex'] = $this->input->post('where_sex');
                $data['where_language'] = $this->input->post('where_language');
                $data['where_from_birthday_year'] = $this->input->post('where_from_birthday_year');
                $data['where_to_birthday_year'] = $this->input->post('where_to_birthday_year');
                $data['where_from_created'] = $this->input->post('where_from_created');
                $data['where_to_created'] = $this->input->post('where_to_created');
                $data['description'] = trim(htmlspecialchars_decode($this->input->post('description')));
            }
        }else{
            $data['send_date'] = date("Y/m/d",$magazine->sendtime);
            $data['send_hour_minutes'] = date("H:i",$magazine->sendtime);
            $data['description'] = htmlspecialchars_decode($magazine->description);
            $data['where_sex'] = $magazine->where_sex;
            $data['where_language'] = $magazine->where_language;
            $data['where_from_birthday_year'] = $magazine->where_from_birthday_year == 0 ? '' : $magazine->where_from_birthday_year;
            $data['where_to_birthday_year'] = $magazine->where_to_birthday_year == 0 ? '' : $magazine->where_to_birthday_year;
            $data['where_from_created'] = $magazine->where_from_created == 0 ? '' : date("Y/m/d",$magazine->where_from_created);
            $data['where_to_created'] = $magazine->where_to_created == 0 ? '' : date("Y/m/d",$magazine->where_to_created);
        }
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->config->set_item('javascripts', array());
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/prettyPopin.css','css/abox.css','css/html5uploder.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/timePicker.js','js/jquery.ui.datepicker-ja.js','/js/jquery.prettyPopin.js','js/jquery.html5uploader.min.js','js/html5uploder.js')));
        $this->load->view('console/magazine/edit', $data);
    }

    public function reuse($magazine_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Magazine_model');
        $this->Magazine_model->setBannedQuery(FALSE);
        $magazine_id = intval($magazine_id);
        $magazine = $this->Magazine_model->getMagazineById($magazine_id);
        if (empty($magazine)) {
            $this->logger->err(sprintf('failed to get magazine $d', $magazine_id));
            show_404();
        }
        $data['magazine'] = $magazine;
        $this->form_validation->set_rules('send_hour_minutes', $this->lang->line('magazine_input_send_hour_minutes'), 'required|xss_clean|date_hour_minutes_format');
        $this->form_validation->set_rules('send_date', $this->lang->line('magazine_input_send_date'),"'required|xss_clean|date_format|date_time_from_current_by_date_hour_minutes[{$this->input->post('send_hour_minutes')}]");
        //$this->form_validation->set_rules('sendtime', $this->lang->line('magazine_input_sendtime'), "required|xss_clean|htmlspecialchars|strip_tags|date_time_format|date_time_from_current");
        $this->form_validation->set_rules('where_sex', $this->lang->line('magazine_input_where_sex'), 'xss_clean|numeric');
        $this->form_validation->set_rules('where_language', $this->lang->line('magazine_input_where_language'), 'required|xss_clean|numeric');
        $this->form_validation->set_rules('where_from_birthday_year', $this->lang->line('magazine_input_where_from_birthday_year'), 'xss_clean|numeric');
        $this->form_validation->set_rules('where_to_birthday_year', $this->lang->line('magazine_input_where_to_birthday_year'), "xss_clean|numeric|more_than[{$this->input->post('where_from_birthday_year')}]");
        $this->form_validation->set_rules('where_from_created', $this->lang->line('magazine_input_where_from_created'), 'xss_clean|date_format|date_to_today');
        $this->form_validation->set_rules('where_to_created', $this->lang->line('magazine_input_where_to_created'), "xss_clean|date_format|date_to_today|date_from_start[{$this->input->post('where_from_created')}]");
        $this->form_validation->set_rules('title', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('magazine_title_min_length')}]|max_length[{$this->config->item('magazine_title_max_length')}]");
        $this->form_validation->set_rules('description', $this->lang->line('magazine_input_description'), "required|htmlspecialchars");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                //送信日時
                if($this->input->post('send_date') != '' && $this->input->post('send_hour_minutes') != ''){
                    $send_date = explode('/',$this->input->post('send_date'));
                    $send_hour_minutes = explode(':',$this->input->post('send_hour_minutes'));
                    $send_time = mktime($send_hour_minutes[0], $send_hour_minutes[1], 0, $send_date[1], $send_date[2], $send_date[0]);
                }else{
                    $send_time = 0;
                }
                
                //性別
                if($this->input->post('where_sex') != ''){
                    $where_sex = $this->input->post('where_sex');
                }else{
                    $where_sex = 9;
                }

                //言語
                if($this->input->post('where_language') != ''){
                    $where_language = $this->input->post('where_language');
                }else{
                    $where_language = 9;
                }

                //ユーザー登録日-From
                if($this->input->post('where_from_created') != ''){
                    $where_from_created = explode('/',$this->input->post('where_from_created'));
                    $where_from_created_time = mktime(0, 0, 0, $where_from_created[1], $where_from_created[2], $where_from_created[0]);
                }else{
                    $where_from_created_time = 0;
                }
                // ユーザー登録日-To 
                if($this->input->post('where_from_created') != ''){
                    $where_to_created = explode('/',$this->input->post('where_to_created'));
                    $where_to_created_time = mktime(23, 59, 59, $where_to_created[1], $where_to_created[2], $where_to_created[0]);
                }else{
                    $where_to_created_time = 0;
                }

                $magazineData = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'sendtime' => $send_time,
                    'where_sex' => $where_sex,
                    'where_language' => $where_language,
                    'where_from_birthday_year' => $this->input->post('where_from_birthday_year') == '' ? 0 : $this->input->post('where_from_birthday_year'),
                    'where_to_birthday_year' => $this->input->post('where_to_birthday_year') == '' ? 0 : $this->input->post('where_to_birthday_year'),
                    'where_from_created' => $where_from_created_time,
                    'where_to_created' => $where_to_created_time
                );
                $this->load->model('Magazine_model');
                $magazine_id = $this->Magazine_model->insertMagazine($magazineData);
                redirect("console/magazine/show/".$magazine_id);
                return;
            }else{
                $data['send_date'] = $this->input->post('send_date');
                $data['send_hour_minutes'] = $this->input->post('send_hour_minutes');
                $data['description'] = trim(htmlspecialchars_decode($this->input->post('description')));
                $data['where_sex'] = $this->input->post('where_sex');
                $data['where_language'] = $this->input->post('where_language');
                $data['where_from_created'] = $this->input->post('where_from_created');
                $data['where_to_created'] = $this->input->post('where_to_created');
                $data['description'] = trim(htmlspecialchars_decode($this->input->post('description')));
            }
        }else{
            $data['send_date'] = date("Y/m/d",$magazine->sendtime);
            $data['send_hour_minutes'] = date("H:i",$magazine->sendtime);
            $data['description'] = htmlspecialchars_decode($magazine->description);
            $data['where_sex'] = $magazine->where_sex;
            $data['where_language'] = $magazine->where_language;
            $data['where_from_birthday_year'] = $magazine->where_from_birthday_year == 0 ? '' : $magazine->where_from_birthday_year;
            $data['where_to_birthday_year'] = $magazine->where_to_birthday_year == 0 ? '' : $magazine->where_to_birthday_year;
            $data['where_from_created'] = $magazine->where_from_created == 0 ? '' : date("Y/m/d",$magazine->where_from_created);
            $data['where_to_created'] = $magazine->where_to_created == 0 ? '' : date("Y/m/d",$magazine->where_to_created);
        }
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->config->set_item('javascripts', array());
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/prettyPopin.css','css/abox.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/jquery.ui.datepicker-ja.js','js/timePicker.js','/js/jquery.prettyPopin.js')));
        $this->load->view('console/magazine/reuse', $data);
    }

    public function delete($magazine_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Magazine_model');
        $this->Magazine_model->setBannedQuery(FALSE);
        $magazine_id = intval($magazine_id);
        $magazine = $this->Magazine_model->getMagazineById($magazine_id);
        if (empty($magazine)) {
            $this->logger->err(sprintf('failed to get magazine $d', $magazine_id));
            show_404();
        }
        if ( !empty($magazine) ) {
            //delete isssue
            if ($this->Magazine_model->deleteMagazine($magazine_id)) {
                //success to delete category
                redirect("console/magazine/manage");
            } else {
                //failed to delete category
                $this->logger->err(sprintf('Could not delete magazine: magazine id $d.', $magazine_id));
                show_404();
            }
        }
    }
    public function user_count()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print 'deny';
            die();
        }

        //性別
        if($this->input->post('where_sex') != ''){
            $where_sex = $this->input->post('where_sex');
        }else{
            $where_sex = 9;
        }

        //言語
        if($this->input->post('where_language') != ''){
            $where_language = $this->input->post('where_language');
        }else{
            $where_language = 9;
        }

        //ユーザー登録日-From
        if($this->input->post('where_from_created') != ''){
            $where_from_created = str_replace('/','-',$this->input->post('where_from_created'));
        }else{
            $where_from_created = null;
        }

        // ユーザー登録日-To 
        if($this->input->post('where_to_created') != ''){
            $where_to_created = str_replace('/','-',$this->input->post('where_to_created'));
        }else{
            $where_to_created = null;
        }

        $condition['where_sex'] = $where_sex;
        $condition['where_language'] = $where_language;
        $condition['where_from_birthday_year'] = $this->input->post('where_from_birthday_year') == '' ? 0 : $this->input->post('where_from_birthday_year');
        $condition['where_to_birthday_year'] = $this->input->post('where_to_birthday_year') == '' ? 0 : $this->input->post('where_to_birthday_year');
        $condition['where_from_created'] = $where_from_created;
        $condition['where_to_created'] = $where_to_created;

        //user
        $this->load->model('tank_auth/users');
        $users = $this->users->get_user_magazine_by_condition($condition);
        print !empty($users) ? count($users) : 0;
    }
    public function check_dialog()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print 'deny';
            die();
        }
        $data = array();
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/abox.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js')));

        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $this->load->view('console/magazine/check_dialog', $data);
    }
    
    public function send_debug()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print 'deny';
            die();
        }
        $this->form_validation->set_rules('emails', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|valid_emails");
        $this->form_validation->set_rules('title', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('magazine_title_min_length')}]|max_length[{$this->config->item('magazine_title_max_length')}]");
        $this->form_validation->set_rules('description', $this->lang->line('magazine_input_description'), "required|htmlspecialchars");
        $this->form_validation->set_rules('type', $this->lang->line('magazine_input_type'), "required|xss_clean|htmlspecialchars");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $magazineData = array(
                    'emails' => explode(',',$this->input->post('emails')),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('type') == 'html' ? htmlspecialchars_decode($this->input->post('description')) : $this->input->post('description'),
                    'type' => $this->input->post('type'),
                );
                $this->_send_email($magazineData['emails'], $magazineData);
                die();
            }else{
                print '入力している値を確認してください。';
                die();
            }
        }
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param    string
     * @param    string
     * @param    array
     * @return    void
     */
    function _send_email($emails, &$data)
    {
        $config = array(
            'charset' => 'utf-8',
            'mailtype' => $data['type']
        );
        $this->load->library('email', $config); 
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($emails);
        
        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
        $data['site_url'] = site_url('/intl/ja/');
        $subject = $data['title'];
        $this->email->subject($subject);
        $this->email->message($this->load->view('email/magazine-'.$data['type'], $data, TRUE));
        $this->email->send();
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */