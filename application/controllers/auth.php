<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller
{
    private $data;
    function __construct()
    {
        parent::__construct();

        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        force_ssl();
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('setting');

        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/inner_main.css', 'css/signup.css')));
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/signup.css')));
        
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    function index()
    {
        redirect('auth/login/');
    }

    /**
     * Login user on the site
     *
     * @return void
     */
    function login()
    {
        if ($this->tank_auth->is_logged_in()) {                                    // logged in
            redirect('');

        } else {
            $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
            $this->config->item('use_username', 'tank_auth'));
            $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

            $this->form_validation->set_rules('login', $this->lang->line('Login'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', $this->lang->line('Password'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', $this->lang->line('Remember me'), 'integer');

            // Get login for counting attempts to login
            if ($this->config->item('login_count_attempts', 'tank_auth') AND
            ($login = $this->input->post('login'))) {
                $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            $data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                if ($data['use_recaptcha'])
                $this->form_validation->set_rules('recaptcha_response_field', $this->lang->line('Confirmation Code'), 'trim|xss_clean|required|callback__check_recaptcha');
                else
                $this->form_validation->set_rules('captcha', $this->lang->line('Confirmation Code'), 'trim|xss_clean|required|callback__check_captcha');
            }
            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->login(
                $this->form_validation->set_value('login'),
                $this->form_validation->set_value('password'),
                $this->form_validation->set_value('remember'),
                $data['login_by_username'],
                $data['login_by_email'])) {                                // success
                    $user_id = $this->tank_auth->get_user_id();
                    if($user_id == 1){
                        redirect('console/coupon/manage');
                    }else{
                        redirect('user/manage');
                    }
                    

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    if (isset($errors['banned'])) {                                // banned user
                        $this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

                    } else {                                                    // fail
                        foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                    }
                }
            }
            $data['show_captcha'] = FALSE;
            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                $data['show_captcha'] = TRUE;
                if ($data['use_recaptcha']) {
                    $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->lang->line('auth_login_title'));

            //set header title
            $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_login_title'), $this->config->item('website_name', 'tank_auth'));
            $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_login_title'));

            $this->load->view('auth/login_form', array_merge($this->data,$data));
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    function logout()
    {
        $this->tank_auth->logout();

        $this->_show_message($this->lang->line('auth_message_logged_out'));
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    function register()
    {
        if ($this->tank_auth->is_logged_in()) {                                    // logged in
            redirect('');
        } elseif (!$this->config->item('allow_registration', 'tank_auth')) {    // registration is off
            $this->_show_message($this->lang->line('auth_message_registration_disabled'));

        } else {
            
            $this->form_validation->set_rules('email', $this->lang->line('Email'), 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('password', $this->lang->line('Password'), 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('Confirm Password'), 'trim|required|xss_clean|matches[password]');
            $this->form_validation->set_rules('first_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('first_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('first_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('middle_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('middle_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('middle_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('last_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('last_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('last_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('address', $this->lang->line('Address'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('address_min_length', 'tank_auth')}]|max_length[{$this->config->item('address_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('zip', $this->lang->line('Zip'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('zip_min_length', 'tank_auth')}]|max_length[{$this->config->item('zip_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('phone', $this->lang->line('Phone'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('phone_min_length', 'tank_auth')}]|max_length[{$this->config->item('phone_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('sex', '', 'required|xss_clean');
            $this->form_validation->set_rules('birthday_year', '', 'required|xss_clean');
            $this->form_validation->set_rules('need_notify', '', 'required|xss_clean');
            $this->form_validation->set_rules('interest', '', 'xss_clean');
            
            
            $captcha_registration    = $this->config->item('captcha_registration', 'tank_auth');
            $use_recaptcha            = $this->config->item('use_recaptcha', 'tank_auth');
            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $this->form_validation->set_rules('recaptcha_response_field', $this->lang->line('Confirmation Code'), 'trim|xss_clean|required|callback__check_recaptcha');
                } else {
                    $this->form_validation->set_rules('captcha', $this->lang->line('Confirmation Code'), 'trim|xss_clean|required|callback__check_captcha');
                }
            }
            $data['errors'] = array();

            $email_activation = $this->config->item('email_activation', 'tank_auth');

            
            if ($this->form_validation->run()) {                                // validation ok
                $userData['email'] = $this->form_validation->set_value('email');
                $userData['password'] = $this->form_validation->set_value('password');
                $userData['first_name'] = $this->form_validation->set_value('first_name');
                $userData['middle_name'] = $this->form_validation->set_value('middle_name');
                $userData['last_name'] = $this->form_validation->set_value('last_name');
                $userData['address'] = $this->form_validation->set_value('address');
                $userData['zip'] = $this->form_validation->set_value('zip');
                $userData['phone'] = $this->form_validation->set_value('phone');
                $userData['sex'] = $this->form_validation->set_value('sex');
                $userData['birthday_year'] = $this->form_validation->set_value('birthday_year');
                $userData['need_notify'] = $this->form_validation->set_value('need_notify');
                $interest = $this->input->post('interest');
                $userData['interest'] = empty($interest) ? '' : implode(',',$interest);
                if($this->config->item('language') == 'japanese'){
                    $language = 0;
                }elseif($this->config->item('language') == 'english'){
                    $language = 1;
                }elseif($this->config->item('language') == 'thai'){
                    $language = 2;
                }else{
                    $language = 0;
                }
                $userData['language'] = $language;
                
                // success
                if (!is_null($data = $this->tank_auth->create_user($userData,$email_activation))) {

                    $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                    if ($email_activation) {                                    // send "activate" email
                        $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                        $this->_send_email('activate', $data['email'], $data);

                        unset($data['password']); // Clear password (just for any case)

                        // redirect to register complete page
                        redirect('auth/register_complete');
                    } else {
                        if ($this->config->item('email_account_details', 'tank_auth')) {    // send "welcome" email

                            $this->_send_email('welcome', $data['email'], $data);
                        }
                        unset($data['password']); // Clear password (just for any case)

                        $this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('auth/login/', 'Login'));
                    }
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                }
            }

            if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
                $data['sex'] = $this->input->post('sex');
                $data['birthday_year'] = $this->input->post('birthday_year');
                $data['need_notify'] = $this->input->post('need_notify');
                $data['interest'] = $this->input->post('interest');
            }else{
                $data['sex'] = 0;
                $data['birthday_year'] = $this->config->item('default_birthday_year', 'tank_auth');
                $data['need_notify'] = 0;
                $data['interest'] = array();
            }
            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }
            $data['captcha_registration'] = $captcha_registration;
            $data['use_recaptcha'] = $use_recaptcha;

            $errors = $this->tank_auth->get_error_message();
            foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
            
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->lang->line('auth_register_title'));
            
            $this->lang->load('site');
            
            //set header title
            $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_register_title'), $this->config->item('website_name', 'tank_auth'));
            $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_register_title'));
            
            $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/setting.css')));
            $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.placeholder.js')));
            
            $this->load->view('auth/register_form', array_merge($this->data,$data));
        }
    }

    /**
     * setting action
     *
     */
    function setting()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('auth/login/');
        } else {
            
            $this->load->model('tank_auth/users');
            $data['user'] = $this->users->get_user_by_id($this->tank_auth->get_user_id(), TRUE);
            $data['email'] = $data['user']->email;
            
            $this->form_validation->set_rules('first_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('first_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('first_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('middle_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('middle_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('middle_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('last_name', $this->lang->line('Username'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('last_name_min_length', 'tank_auth')}]|max_length[{$this->config->item('last_name_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('address', $this->lang->line('Address'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('address_min_length', 'tank_auth')}]|max_length[{$this->config->item('address_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('zip', $this->lang->line('Zip'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('zip_min_length', 'tank_auth')}]|max_length[{$this->config->item('zip_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('phone', $this->lang->line('Phone'), "required|trim|xss_clean|strip_tags|htmlspecialchars|min_length[{$this->config->item('phone_min_length', 'tank_auth')}]|max_length[{$this->config->item('phone_max_length', 'tank_auth')}]");
            $this->form_validation->set_rules('sex', '', 'required|xss_clean');
            $this->form_validation->set_rules('birthday_year', '', 'required|xss_clean');
            $this->form_validation->set_rules('need_notify', '', 'required|xss_clean');
            $this->form_validation->set_rules('interest', '', 'xss_clean');
            $this->form_validation->set_rules('language', '', 'required|xss_clean');
            $data['errors'] = array();
            
            if  ( $this->form_validation->run() != FALSE )
            {
                // change email
/*
                if ($data['user']->email != $this->input->post('email')) {
                    if (!is_null($data = $this->tank_auth->set_new_email_no_password($this->form_validation->set_value('email')))) {
                        // success
                        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
                        // Send email with new email address and its activation link
                        $this->_send_email('change_email', $data['new_email'], $data);
                        //$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));
                        redirect('auth/reset_email_complete');
                    } else {
                        // error
                        $errors = $this->tank_auth->get_error_message();
                        foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                    }
                }
*/
                $userData['first_name'] = $this->form_validation->set_value('first_name');
                $userData['middle_name'] = $this->form_validation->set_value('middle_name');
                $userData['last_name'] = $this->form_validation->set_value('last_name');
                $userData['username'] = $userData['first_name'].' '.$userData['last_name'];
                $userData['address'] = $this->input->post('address');
                $userData['zip'] = $this->input->post('zip');
                $userData['phone'] = $this->input->post('phone');
                $userData['sex'] = $this->input->post('sex');
                $userData['birthday_year'] = $this->input->post('birthday_year');
                $userData['need_notify'] = $this->input->post('need_notify');
                $interest = $this->input->post('interest');
                $userData['interest'] = empty($interest) ? '' : implode(',',$interest);
                $userData['language'] = $this->input->post('language');

                $this->users->update_user_info($this->tank_auth->get_user_id(), $userData);
                $this->session->set_flashdata('message', $this->lang->line('auth_message_setting_complete'));
                redirect("auth/setting");
            }
            // update setting failed
            else
            {
                if ($this->input->post('editing')) {
                    $data['first_name'] = $this->input->post('first_name');
                    $data['middle_name'] = $this->input->post('middle_name');
                    $data['last_name'] = $this->input->post('last_name');
                    $data['address'] = $this->input->post('address');
                    $data['zip'] = $this->input->post('zip');
                    $data['phone'] = $this->input->post('phone');
                    $data['sex'] = $this->input->post('sex');
                    $data['birthday_year'] = $this->input->post('birthday_year');
                    $data['need_notify'] = $this->input->post('need_notify');
                    $data['interest'] = $this->input->post('interest');
                    // for check first view or not
                    $data['editing'] = $this->input->post('editing');
                } else {
                    $data = array_merge($data, get_object_vars($data['user']));
                    $interest = explode(',',$data['interest']);
                    $data['interest'] = is_array($interest) ? $interest : array($interest);
                    $data['editing'] = 1;
                }

                $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/setting.css')));
                $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
                $data['topicpaths'][] = array(null,$this->lang->line('auth_setting_title'));

                //set header title
                $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_setting_title'), $this->config->item('website_name', 'tank_auth'));
                $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_setting_title'));
                $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.placeholder.js')));
                $this->load->view('auth/setting_form', array_merge($this->data,$data));
            }
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function activate()
    {
        $user_id        = $this->uri->segment(5);
        $new_email_key    = $this->uri->segment(6);
        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) {        // success
            //$this->tank_auth->logout();
            $this->session->set_flashdata('message', $this->lang->line('auth_message_activation_completed'));
            redirect('auth/login/');
        } else {                                                                // fail
            $this->_show_message($this->lang->line('auth_message_activation_failed'));
        }
    }

    /**
     * @return void
     */
    function register_complete()
    {
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('auth_register_complete_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_register_complete_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_register_complete_title'));

        $this->load->view('auth/register_complete', array_merge($this->data,$data));
    }

    /**
     * @return void
     */
    function forgot_password_complete()
    {
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('auth_forgot_password_complete_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_forgot_password_complete_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_forgot_password_complete_title'));

        $this->load->view('auth/forgot_password_complete', array_merge($this->data,$data));
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    function forgot_password()
    {
        $this->form_validation->set_rules('login', $this->lang->line('Email or login'), 'trim|required|xss_clean');

        $data['errors'] = array();

        if ($this->form_validation->run()) {                                // validation ok
            if (!is_null($data = $this->tank_auth->forgot_password(
            $this->form_validation->set_value('login')))) {

                $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                // Send email with password activation link
                $this->_send_email('forgot_password', $data['email'], $data);

                // change forgot password redirect url
                //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                redirect('auth/forgot_password_complete');
            } else {
                $errors = $this->tank_auth->get_error_message();
                foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
            }
        }
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$this->lang->line('auth_forgot_password_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_reset_password_title'), $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_reset_password_title'));

        $this->load->view('auth/forgot_password_form', array_merge($this->data,$data));
    }

    /**
     * Change user password
     *
     * @return void
     */
    function change_password()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('auth/login/');

        } else {
            $this->form_validation->set_rules('old_password', $this->lang->line('auth_old_password'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('new_password', $this->lang->line('auth_new_password'), 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_new_password', $this->lang->line('auth_new_password_confirm'), 'trim|required|xss_clean|matches[new_password]');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->change_password(
                $this->form_validation->set_value('old_password'),
                $this->form_validation->set_value('new_password'))) {    // success
                    $this->_show_message($this->lang->line('auth_message_password_changed'));

                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->lang->line('auth_change_password_title'));
            $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/setting.css')));

            //set header title
            $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_change_password_title'), $this->config->item('website_name', 'tank_auth'));
            $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_change_password_title'));

            $this->load->view('auth/change_password_form', array_merge($this->data,$data));
        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    function change_email()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('auth/login/');

        } else {
            $this->load->model('tank_auth/users');
            //$data['user'] = $this->users->get_user_profile($this->tank_auth->get_user_id());
            $data['user'] = $this->users->get_user_by_id($this->tank_auth->get_user_id(), TRUE);
            $data['email'] = $data['user']->email;
            
            $this->form_validation->set_rules('email', $this->lang->line('Email'), 'trim|required|xss_clean|valid_email');
            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                // change email
                if ($data['user']->email != $this->input->post('email')) {
                    if (!is_null($success_data = $this->tank_auth->set_new_email_no_password($this->form_validation->set_value('email')))) {
                        // success
                        $success_data['site_name'] = $this->config->item('website_name', 'tank_auth');
                        // Send email with new email address and its activation link
                        $this->_send_email('change_email', $success_data['new_email'], $success_data);
                        $this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $success_data['new_email']));
                        //redirect('auth/reset_email_complete');
                    } else {
                        // error
                        $errors = $this->tank_auth->get_error_message();
                        foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                    }
                }
            }
            // update setting failed
            else
            {
                if ($this->input->post('editing')) {
                    $data['email'] = $this->input->post('email');
                    // for check first view or not
                    $data['editing'] = $this->input->post('editing');
                } else {
                    $data = array_merge($data, get_object_vars($data['user']));
                    $data['editing'] = 1;
                }
            }
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->lang->line('auth_change_email_title'));
            $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/setting.css')));

            //set header title
            $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('auth_change_email_title'), $this->config->item('website_name', 'tank_auth'));
            $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('auth_change_email_title'));

            $this->load->view('auth/change_email_form', array_merge($this->data,$data));
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_email()
    {
        $user_id        = $this->uri->segment(5);
        $new_email_key    = $this->uri->segment(6);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {    // success
            $this->tank_auth->logout();
            $this->_show_message($this->lang->line('auth_message_new_email_activated'));

        } else {                                                                // fail
            $this->_show_message($this->lang->line('auth_message_new_email_failed'));
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    function unregister()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('auth/login/');

        } else {
            $this->form_validation->set_rules('password', $this->lang->line('Password'), 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->delete_user(
                $this->form_validation->set_value('password'))) {        // success
                    $this->_show_message($this->lang->line('auth_message_unregistered'));

                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)    $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
            $data['topicpaths'][] = array(null,$this->lang->line('auth_unregister_title'));
            $this->load->view('auth/unregister_form', array_merge($this->data,$data));
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
        redirect($this->config->item('show_message_url'));
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param    string
     * @param    string
     * @param    array
     * @return    void
     */
    function _send_email($type, $email, &$data)
    {
        $config = array(
            'charset' => 'utf-8',
            'mailtype' => 'text'
        );
        $this->load->library('email',$config);
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $subject = sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth'));
        $this->email->subject($subject);
        $this->email->message($this->load->view('email/'.$type.'-txt', $data, TRUE));
        $this->email->send();
    }

    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return    string
     */
    function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(array(
            'img_path'        => './'.$this->config->item('captcha_path', 'tank_auth'),
            'img_url'        => base_url().$this->config->item('captcha_path', 'tank_auth'),
            'font_path'        => './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
            'font_size'        => $this->config->item('captcha_font_size', 'tank_auth'),
            'img_width'        => $this->config->item('captcha_width', 'tank_auth'),
            'img_height'    => $this->config->item('captcha_height', 'tank_auth'),
            'show_grid'        => $this->config->item('captcha_grid', 'tank_auth'),
            'expiration'    => $this->config->item('captcha_expire', 'tank_auth'),
        ));

        // Save captcha params in session
        $this->session->set_flashdata(array(
                'captcha_word' => $cap['word'],
                'captcha_time' => $cap['time'],
        ));

        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param    string
     * @return    bool
     */
    function _check_captcha($code)
    {
        $time = $this->session->flashdata('captcha_time');
        $word = $this->session->flashdata('captcha_word');

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
            return FALSE;

        } elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
        $code != $word) OR
        strtolower($code) != strtolower($word)) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Create reCAPTCHA JS and non-JS HTML to verify user as a human
     *
     * @return    string
     */
    function _create_recaptcha()
    {
        $this->load->helper('recaptcha');

        // Add custom theme so we can get only image
        $options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

        // Get reCAPTCHA JS and non-JS HTML
        $html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

        return $options.$html;
    }

    /**
     * Callback function. Check if reCAPTCHA test is passed.
     *
     * @return    bool
     */
    function _check_recaptcha()
    {
        $this->load->helper('recaptcha');

        $resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
        $_SERVER['REMOTE_ADDR'],
        $_POST['recaptcha_challenge_field'],
        $_POST['recaptcha_response_field']);

        if (!$resp->is_valid) {
            $this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
            return FALSE;
        }
        return TRUE;
    }

    function _check_website($url)
    {
        // empty is ok
        if (strlen(trim($url)) == 0) return TRUE;

        if (preg_match('/^(https?)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $url)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('_check_website', $this->lang->line('auth_url_is_invalid'));
            return FALSE;
        }
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */