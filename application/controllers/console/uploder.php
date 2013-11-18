<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dragdropuploder extends MY_Controller {

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
     public $uploadFiles = array();
    function __construct(){
        parent::__construct();

        //add helper
        $this->load->helper('html');
        $this->load->helper('url');
        force_ssl();
        $this->load->helper('form');
        $this->load->library('tank_auth');
        $this->load->library('uploadfile_validate');
        $this->lang->load('upload');
        //connect database
        $this->load->database();
    }
    
    function uploder()
    {
var_dump('test');
die();
/*
array(1) {
  ["files"]=>
  array(5) {
    ["name"]=>
    string(15) "P21-570x380.jpg"
    ["type"]=>
    string(10) "image/jpeg"
    ["tmp_name"]=>
    string(14) "/tmp/phpW5WP8W"
    ["error"]=>
    int(0)
    ["size"]=>
    int(43335)
  }
}

*/
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print 'deny';
            die();
        }
        $is_success = TRUE;
        $upload_files = array;
        if ( TRUE === ( $validateResult = $this->uploadfile_validate->multiple_validate() ) && count($_FILES) > 0 ) {
            if ( TRUE === ( $validateResult = $this->validate($_FILES['files']) ) ) {
                if (count($this->uploadFiles) == 0) {
                    print 'no file';
                    die();
                }else{
                    foreach ($this->uploadFiles as $key=> $file) {
                        $filePrefix = md5(uniqid(mt_rand()));
                        //ファイル名のナンバリング命名規則は無視。意味が無い。
                        $newFilename = 'images/files/'.$filePrefix.'.'.strtolower(array_pop(explode('.', $file['name'])));
                        @move_uploaded_file($file['tmp_name'], $newFilename);
                        if(!is_file($newFilename) $is_success = FALSE;
                        $upload_files[] = $newFilename;
                    }
                    //delete
                    if(!$is_success){
                        foreach ($upload_files as $value){
                            @unlink($value);
                        }
                        
                    }
                }
            }
        }
        if(isset($validateResult)) $data['error'] = $validateResult === TRUE ? 'no file' : $validateResult;
        print 'deny';
        die();
    }

    /**
     * @return    rebuild array
     */
    private function rebuild($files)
    {
        $result = array();
        $count = count($files['name']);
        for($i = 0; $i < $count; $i++) {
            $item = array();
            foreach($files as $key => $value) {
                if($count == 1){
                    $item[$key] = $value;
                }else{
                    $item[$key] = $value[$i];
                }
            }
            $result[] = $item;
        }

        return $result;
    }

    function validate($files)
    {
        if (count($files) < 1) {
            $error_msg[] = array("There are no files uploaded.");
            return $error_msg;
        }
        return $this->check_file_size_rect($files);
    }

    /**
     * @param rebuilded & filtered array
     */
    private function check_file_size_rect($files)
    {
        $files = $this->rebuild($files);
        $result = true;
        $size = 0;
        foreach($files as $key =>  $file) {
            if (empty($file['name']) || empty($file['tmp_name'])) continue;
            //size
            $size = $size + $file['size'];
            //type
            if ($this->is_image && ! in_array($file['type'], $this->config->config['upload_editor_image_allowed_types'])) {
                $result = '['.$file['name'].']'.$this->lang->language['coupon_incorrect_types'];
                break;
            }
            
            //width height
            if (function_exists('getimagesize')) {
                $D = @getimagesize($file['tmp_name']);
                $image_width        = $D['0'];
                $image_height        = $D['1'];
                
                if ($image_width < $this->config->config['upload_editor_image_min_width'] && $image_height < $this->config->config['upload_editor_image_min_height']) {
                    $result = '['.$file['name'].']'.$this->lang->language['coupon_incorrect_minimum'];
                    break;
                }

                if ($image_width > $this->config->config['upload_editor_image_max_width'] || $image_height > $this->config->config['upload_editor_image_max_height']) {
                    $result = '['.$file['name'].']'.$this->lang->language['coupon_incorrect_maximum'];
                    break;
                }
            }else{
                $result = $this->lang->language['coupon_unclear'];
                break;
            }
            $this->uploadFiles[] = $file;
        }
        if ($size > $this->config->config['upload_editor_max_filesize']) {
            $result = $this->lang->language['coupon_incorrect_filesize'];
        }
        return $result;
    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */