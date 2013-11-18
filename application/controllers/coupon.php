<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon extends MY_Controller {

    /**
     * Index Page for this controller.
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
        $this->load->helper('form');
        $this->lang->load('setting');
        $this->lang->load('coupon');
        $this->load->library('tank_auth');
        $this->load->model('Coupon_model');
        //connect database
        //$this->load->database();
        
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['areas'] = $this->Area_model->getAllareas();
    }

    function show($coupon_id)
    {
        $data['gallerys'] = $this->Coupon_model->getCouponAndGalleriesByCouponId($coupon_id);
        //閲覧可能なクーポンがない場合は見せない
        if(count($data['gallerys']) == 0){
            show_404();
        }
        $data['coupon'] = $data['gallerys'][0];
        $data['related'] =$this->Coupon_model->getCouponsByNearPriceByCategoryIds($data['coupon']->id,$data['coupon']->price);//自分自身が入る可能性があるので
        
        $title_language = 'title_'.$this->config->item('language_min');
        $name_language = 'name_'.$this->config->item('language_min');
        $copy_language = 'copy_'.$this->config->item('language_min');
        
        //og
        $data['og']['locale'] = 'ja_JP';
        $data['og']['site_name'] = $this->lang->line('header_title');
        $data['og']['title'] = $data['coupon']->$title_language;
        $data['og']['type'] = 'article';
        $data['og']['url'] = site_url(lang_base_url('coupon/show/'.$data['coupon']->id));
        $data['og']['image'] = site_url(lang_base_url($data['coupon']->thumbnail_filepath));
        $data['og']['description'] = $data['coupon']->$copy_language;

        $data['topicpaths'][] = array('/',$this->lang->line('common_title_home'));
        $data['topicpaths'][] = array(null,$data['coupon']->$title_language);
        
        
        //coupon categories
        $coupon_categories =$this->Coupon_model->getCategoriesByCouponId($data['coupon']->id);
        $categories_name_array = array();
        if(!empty($coupon_categories)){
            foreach ($coupon_categories as $key => $coupon_category){
                $categories_name_array[] = $coupon_category->$name_language;
            }
        }
        //set header title
        $data['header_title'] = sprintf($this->lang->line('coupon_header_title'), $data['coupon']->$title_language, $this->config->item('website_name', 'tank_auth'));
        $data['header_keywords'] = sprintf($this->lang->line('coupon_header_keywords'), implode(',',$categories_name_array));
        $data['header_description'] = sprintf($this->lang->line('coupon_header_description'), $data['coupon']->$title_language, $this->config->item('website_name', 'tank_auth'), implode('/',$categories_name_array));
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/detail.css','css/jquery.ad-gallery.css','css/prettyPopin.css','css/abox.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.ad-gallery.js','js/jquery.prettyPopin.js')));
        
        $this->load->view('coupon/show', array_merge($this->data,$data));
    }

    public function share($coupon_id)
    {
        $this->load->model('tank_auth/users');
        $data['user'] = $this->users->get_user_by_id($this->tank_auth->get_user_id(), TRUE);
        
        $data['coupon'] = $this->Coupon_model->getCouponById($coupon_id);
        $title_language = 'title_'.$this->config->item('language_min');
        $data['header_title'] = sprintf('%s | [%s]', $data['coupon']->$title_language, $this->lang->line('header_title'));
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/abox.css')));

        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $this->load->view('coupon/share', array_merge($this->data,$data));
    }

    public function send($coupon_id)
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $data = array();
        
        $this->form_validation->set_rules('emails', $this->lang->line('magazine_input_title'), "required|xss_clean|htmlspecialchars|strip_tags|valid_emails");
        $this->form_validation->set_rules('comment', $this->lang->line('magazine_input_description'), "required|htmlspecialchars");

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if($this->form_validation->run() == TRUE){
                $coupon = $this->Coupon_model->getCouponById($coupon_id);
                if(empty($coupon)){
                    print $this->lang->line('coupon_share_send_error');
                    die();
                }
                $shareData = array(
                    'emails' => explode(',',$this->input->post('emails')),
                    'comment' => $this->input->post('comment'),
                    'coupon'=>$coupon
                    //'description' => $this->input->post('description')
                );
                $this->_send_email($shareData['emails'], $shareData);
                print 'success';
                die();
            }else{
                print $this->lang->line('coupon_share_send_error_2');
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
    function _send_email($emails, &$data,$charset = 'utf-8')
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('tank_auth/users');
        $data['user'] = $this->users->get_user_by_id($this->tank_auth->get_user_id(), TRUE);

        $config = array(
            'charset' => $charset,
            'mailtype' => 'text'
        );
        $this->load->library('email', $config); 
        $this->email->from($data['user']->email, $data['user']->username);
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($emails);
        
        $subject = sprintf($this->lang->line('coupon_share_mail_subject'), $data['user']->username);
        $this->email->subject($subject);
        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
        $this->email->message($this->load->view('email/share-text', $data, TRUE));
        $this->email->send();
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */