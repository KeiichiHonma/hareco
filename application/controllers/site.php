<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('setting');
        $this->lang->load('site');
        //$this->data['categories'] = $this->Category_model->getAllCategories();
        //$this->data['areas'] = $this->Area_model->getAllareas();
    }

    /**
     * Register pre user on the site
     *
     * @return void
     */
    function pre()
    {
        $data = array();
        
        $this->form_validation->set_rules('email', 'email', "required|xss_clean|htmlspecialchars|strip_tags|valid_email");
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                //
                $preData = array(
                    'email' => $this->input->post('email'),
                );
                $email = $preData['email'];
                $query = $this->db->query("SELECT *
                                            FROM pre
                                            WHERE pre.email >= '{$email}'"
                );
                if ($query->num_rows() != 0){
                    print 'registered';
                    die();
                }

                $preData['created'] = date("Y-m-d H:i:s", time());
                $this->db->insert('pre', $preData);
                $pre_id = $this->db->insert_id();
                
                if($pre_id){
                    $this->_send_email('pre',$preData['email'], $preData);
                    print 'success';
                    die();
                }
            }
            print '送信できませんでした。';
            die();
        }
            
        $this->lang->load('site');
        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_register_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_register_title'));
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/pre.css')));
        
        $this->load->view('site/pre', array_merge($this->data,$data));
    }

    /**
     * company page
     *
     */
    function company()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_company'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_company'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_company'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_company'));

        $this->load->view('site/company', array_merge($this->data,$data));
    }

    /**
     * guide page
     *
     */
    function guide()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_guide'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_guide'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_guide'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_guide'));

        $this->load->view('site/guide', array_merge($this->data,$data));
    }

    /**
     * faq page
     *
     */
    function faq()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_faq'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_faq'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_faq'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_faq'));

        $this->load->view('site/faq', array_merge($this->data,$data));
    }

    /**
     * rule page
     *
     */
    function rule()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_rule'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_rule'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_rule'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_rule'));

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/colorbox.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.colorbox.js')));

        $this->load->view('site/rule', array_merge($this->data,$data));
    }

    /**
     * privacy page
     *
     */
    function privacy()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_privacy'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_privacy'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_privacy'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_privacy'));

        $this->load->view('site/privacy', array_merge($this->data,$data));
    }

    /**
     * news page
     *
     */
    function news($order = "modified", $page = 1)
    {
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
        $this->load->model('News_model');
        $newsResult = $this->News_model->getNewsOrder($orderExpression, $page);

        $data['page'] = $page;
        $data['order'] = $order;
        $data['newsResult'] = $newsResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "site/news/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($newsResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        $data['orderSelect'] = $this->lang->line('order_select');

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_news'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_news'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_news'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_news'));

        $this->load->view('site/news', array_merge($this->data,$data));
    }

    /**
     * contact page
     *
     */
    function contact()
    {

        $this->form_validation->set_rules('username', $this->lang->line('contact_name'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('username_min_length', 'tank_auth')}]|max_length[{$this->config->item('username_max_length', 'tank_auth')}]");
        $this->form_validation->set_rules('email', $this->lang->line('contact_email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('description', $this->lang->line('contact_description'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('contact_description_max_length')}]");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $contactData = array(
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'description' => $this->input->post('description')
                );
                $this->load->model('Contact_model');
                $contact_id = $this->Contact_model->insertContact($contactData);
                if($contact_id !== FALSE){
                    $this->_send_email('contact',$this->input->post('email'), $contactData);
                    $this->_show_message($this->lang->line('contact_thanks'));
                }else{
                    $this->_show_message($this->lang->line('contact_thanks'));
                }
                return;
            }
        }

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_contact'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('common_title_contact'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_contact'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_contact'));

        $this->load->view('site/contact', array_merge($this->data,$data));
    }

    function not_found()
    {
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_404_error'), $this->lang->line('header_title'));
        

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '404 error', $this->config->item('website_name', 'tank_auth'));

        $this->load->view('site/error_404', array_merge($this->data,$data));
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param    string
     * @param    string
     * @param    array
     * @return    void
     */
    function _send_email($type,$email, &$data)
    {
        $config = array(
            'charset' => 'utf-8',
            'mailtype' => 'text'
        );
        $this->load->library('email',$config);
        //$this->load->library('email');
        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
        
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $subject = sprintf($this->lang->line($type.'_subject_user'), $this->config->item('website_name', 'tank_auth'));
        $this->email->subject($subject);
        $this->email->message($this->load->view('email/'.$type.'-txt', $data, TRUE));
        $this->email->send();
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
        redirect($this->config->item('show_message_url'));
    }
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */