<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('setting');
    }

    /**
     * index page
     *
     */
    function index()
    {
        $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        //$languages = array_reverse($languages);

        $result = null;

        foreach ($languages as $language) {
            if (preg_match('/^ja/i', $language)) {
                header("Location: /intl/ja/", TRUE, 302);
                die();
            } elseif (preg_match('/^en/i', $language)) {
                header("Location: /intl/en/", TRUE, 302);
                die();
            } elseif (preg_match('/^th/i', $language)) {
                header("Location: /intl/th/", TRUE, 302);
                die();
            } else {
                header("Location: /intl/ja/", TRUE, 302);
                die();
            }
        }
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */