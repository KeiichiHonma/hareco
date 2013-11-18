<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon extends MY_Controller {

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
        $this->lang->load('upload');
        $this->lang->load('coupon');
        $this->load->library('tank_auth');
        $this->load->library('uploadfile_validate');
        $this->load->model('Coupon_model');
        //connect database
        $this->load->database();
    }

    public function manage($order = "modified", $page = 1)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $user_id = $this->tank_auth->get_user_id();

        // get coupons
        
        $this->Coupon_model->setBannedQuery(FALSE);
        
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
        $couponsResult = $this->Coupon_model->getCouponsOrderGallery($orderExpression, $page);
        $data['user_id'] = $user_id;
        $data['page'] = $page;
        $data['order'] = $order;
        $data['coupons'] = $couponsResult['data'];
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));
        $data['pageFormat'] = "console/coupon/manage/{$order}/%d";
        $data['maxPageCount'] = (int) ceil(intval($couponsResult['count']) / intval($this->config->item('paging_count_per_manage_page')));
        //$data['category'] = $this->lang->line('comic_category');
        //$data['status'] = $this->lang->line('comic_status');
        $data['orderSelect'] = $this->lang->line('order_select');
        

        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('header_title_console'), $this->lang->line('header_title'));

        $this->load->view('console/coupon/manage', $data);
    }

    function show($coupon_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        
        
        $user_id = $this->tank_auth->get_user_id();
        $this->Coupon_model->setBannedQuery(FALSE);
        $data['gallerys'] = $this->Coupon_model->getCouponAndGalleriesByCouponId($coupon_id);

        // if there is no coupon, show 404
        if (empty($data['gallerys'])) {
            $this->logger->debug(sprintf("there is no coupon, coupon id:%s", $coupon_id));
            show_404();
        }
        
        $data['coupon'] = $data['gallerys'][0];
        $data['coupon_categories'] = $this->Category_model->getCategoriesByCouponId($coupon_id);
        $data['coupon_area'] = $this->Area_model->getAreaById($data['coupon']->area_id);
        //set header title
        $data['header_title'] = sprintf('%s | [%s]', $data['coupon']->title_ja, $this->lang->line('header_title'));

        $this->load->view('console/coupon/show', $data);
    }

    public function add()
    {
        $data = array();
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        $this->_form_validation();

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if ( $this->form_validation->run() == TRUE && TRUE === ( $validateResult = $this->uploadfile_validate->multiple_validate() ) && count($_FILES) > 0 ) {
                $this->load->library('multiple_upload');
                
                if ( TRUE === ( $validateResult = $this->multiple_upload->validate($_FILES['userfile']) ) ) {
                    if (count($this->multiple_upload->uploadFiles) == 0) {
                        $data['error'] = $this->lang->line('issue_no_file');
                    }else{
                        //start transaction manually
                        $this->db->trans_begin();
                        $db_trans = TRUE;
                        $couponData = array(
                            'area_id' => $this->input->post('area'),
                            'title_ja' => $this->input->post('title_ja'),
                            'title_en' => $this->input->post('title_en'),
                            'title_th' => $this->input->post('title_th'),
                            'copy_ja' => $this->input->post('copy_ja'),
                            'copy_en' => $this->input->post('copy_en'),
                            'copy_th' => $this->input->post('copy_th'),
                            'description_ja' => $this->input->post('description_ja'),
                            'description_en' => $this->input->post('description_en'),
                            'description_th' => $this->input->post('description_th'),
                            'value' => $this->input->post('value'),
                            'save' => $this->input->post('save'),
                            'price' => $this->input->post('price'),
                            'stock' => $this->input->post('stock'),
                            'limit' => $this->input->post('limit'),
                            'rule_ja' => $this->input->post('rule_ja'),
                            'rule_en' => $this->input->post('rule_en'),
                            'rule_th' => $this->input->post('rule_th'),
                            'shop_ja' => $this->input->post('shop_ja'),
                            'shop_en' => $this->input->post('shop_en'),
                            'shop_th' => $this->input->post('shop_th'),
                            'address_ja' => $this->input->post('address_ja'),
                            'address_en' => $this->input->post('address_en'),
                            'address_th' => $this->input->post('address_th'),
                            'popular' => $this->input->post('popular'),
                            'banned' => 1,
                            'created' => date("Y-m-d H:i:s", time())
                        );
                        
                        $create_coupon_files = $this->Coupon_model->createCoupon($couponData, $this->multiple_upload->uploadFiles , $this->input->post('categories'));
                        if(is_array($create_coupon_files)){
                            $upload_result = $this->multiple_upload->upload($this->multiple_upload->uploadFiles, $create_coupon_files);
                        }else{
                            $db_trans = FALSE;
                        }


                        // check transaction succeeded.
                        if (!$upload_result || $this->db->trans_status() === FALSE || !$db_trans) {
                            $this->db->trans_rollback();
                        } else {
                            $this->db->trans_commit();
                        }
                        redirect("console/coupon/show/{$create_coupon_files['coupon_id']}");
                        return;
                    }
                }
            }else{
                $data['popular'] = $this->input->post('popular');
            }
            $this->logger->debug(sprintf('uploadGallery error'));
            if(isset($validateResult)) $data['error'] = $validateResult === TRUE ? $this->lang->line('coupon_no_file') : $validateResult;
        }else{
                $data['popular'] = 0;
        }

        $data['categories'] = $this->Category_model->getAllCategories();
        $data['areas'] = $this->Area_model->getAllAreas();
        
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.pager.min.js','js/jquery.upload-1.0.2.min.js','js/jquery-ui.js')));
        $this->load->view('console/coupon/add', $data);
    }

    public function selectUpload($comic_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Comic_model');
        $user_id = $this->tank_auth->get_user_id();
        if ($this->Comic_model->isUserComic($user_id, $comic_id)) {
            
            $this->load->view('coupon/selectUpload', array('comic_id' => $comic_id));
        } else {
            // this comic is not user comic.
            $this->logger->debug(sprintf("[selectUpload] it is not user's comic,user id:%s, comic id:%s", $user_id, $comic_id));
        }
    }

    public function uploadGallery($comic_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $this->load->model('Comic_model');
        $user_id = $this->tank_auth->get_user_id();
        if ($this->Comic_model->isUserComic($user_id, $comic_id)) {
            if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
                if ( TRUE === ( $validateResult = $this->uploadfile_validate->multiple_validate() ) && count($_FILES) > 0 ) {
                    $this->load->library('multiple_upload');
                    
                    if ( TRUE === ( $validateResult = $this->multiple_upload->validate($_FILES['userfile']) ) ) {
                        if (count($this->multiple_upload->uploadFiles) == 0) {
                            $data['error'] = $this->lang->line('coupon_no_file');
                        }else{
                            $tempFolder = $this->multiple_upload->createTemporaryFolder();
                            $this->multiple_upload->upload($this->multiple_upload->uploadFiles, $tempFolder);

                            redirect("coupon/add/{$comic_id}/{$tempFolder}");
                            return;
                        }
                    }
                }
                $this->logger->debug(sprintf('uploadGallery error'));
                $data['error'] = $validateResult === TRUE ? $this->lang->line('coupon_no_file') : $validateResult;
            }
            $data['comic_id'] = $comic_id;
            
            $this->load->view('coupon/uploadGallery', $data);
        } else {
            // this comic is not user comic.
            $this->logger->debug(sprintf("[uploadGallery] it is not user's comic,user id:%s, comic id:%s", $user_id, $comic_id));
            show_404();
        }
    }

    public function edit($coupon_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        
        $this->Coupon_model->setBannedQuery(FALSE);
        $coupon_id = intval($coupon_id);
        $coupon = $this->Coupon_model->getCouponById($coupon_id);
        if (! $coupon) {
            $this->logger->err(sprintf('failed to get coupon $d', $coupon_id));
            show_404();
        }
        $data = array(
            'coupon' => $coupon,
            'coupon_id' => $coupon_id,
        );
        $this->_form_validation();
        if( $this->form_validation->run() != FALSE ){

            $couponData = array(
                'area_id' => $this->input->post('area'),
                'title_ja' => $this->input->post('title_ja'),
                'title_en' => $this->input->post('title_en'),
                'title_th' => $this->input->post('title_th'),
                'copy_ja' => $this->input->post('copy_ja'),
                'copy_en' => $this->input->post('copy_en'),
                'copy_th' => $this->input->post('copy_th'),
                'description_ja' => $this->input->post('description_ja'),
                'description_en' => $this->input->post('description_en'),
                'description_th' => $this->input->post('description_th'),
                'value' => $this->input->post('value'),
                'save' => $this->input->post('save'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'limit' => $this->input->post('limit'),
                'rule_ja' => $this->input->post('rule_ja'),
                'rule_en' => $this->input->post('rule_en'),
                'rule_th' => $this->input->post('rule_th'),
                'shop_ja' => $this->input->post('shop_ja'),
                'shop_en' => $this->input->post('shop_en'),
                'shop_th' => $this->input->post('shop_th'),
                'address_ja' => $this->input->post('address_ja'),
                'address_en' => $this->input->post('address_en'),
                'address_th' => $this->input->post('address_th'),
                'popular' => $this->input->post('popular'),
                'banned' => $this->input->post('banned'),
                'modified' => date("Y-m-d H:i:s", time())
            );

            
            if ( $this->Coupon_model->updateCoupon($coupon_id, $couponData, $this->input->post('categories')) )
            {
                redirect("console/coupon/show/".$coupon_id);
            } else {
                $this->logger->err("edit coupon error.");
                $data['error'] = $this->lang->line('coupon_edit_coupon_error');
            }
        }

        $data['categories'] = $this->Category_model->getAllCategories();
        $data['areas'] = $this->Area_model->getAllAreas();
        
        //選択系初期値設定
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $data['coupon']->banned = $this->input->post('banned');
            $data['coupon_categories'] = $this->input->post('categories');
        }else{
            $coupon_categories = $this->Category_model->getCouponCategoriesByCouponId($coupon_id);
            foreach ($coupon_categories as $key => $coupon_category){
                $data['coupon_categories'][] = $coupon_category->category_id;
            }
        }

        
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery.pager.min.js','js/jquery.upload-1.0.2.min.js','js/jquery-ui.js', 'js/jquery.nBox.min.js')));

        $this->load->model('Gallery_model');
        $data['galleries'] = $this->Gallery_model->getGalleryByCouponId($coupon_id);

        $data['csrf_token'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $this->load->view('console/coupon/edit', $data);
    }

    public function edit_description($coupon_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }
        //$description_lang = 'description_'.$lang;
        
        $this->Coupon_model->setBannedQuery(FALSE);
        $coupon_id = intval($coupon_id);
        $coupon = $this->Coupon_model->getCouponById($coupon_id);
        if (! $coupon) {
            $this->logger->err(sprintf('failed to get coupon $d', $coupon_id));
            show_404();
        }
        $data = array(
            'coupon' => $coupon,
            'coupon_id' => $coupon_id,
        );
        
        $this->form_validation->set_rules('description_ja', $this->lang->line('coupon_description'), "required|htmlspecialchars");
        $this->form_validation->set_rules('description_en', $this->lang->line('coupon_description'), "required|htmlspecialchars");
        $this->form_validation->set_rules('description_th', $this->lang->line('coupon_description'), "required|htmlspecialchars");
        
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if( $this->form_validation->run() != FALSE ){
                $couponData = array(
                    'description_ja' => $this->input->post('description_ja'),
                    'description_en' => $this->input->post('description_en'),
                    'description_th' => $this->input->post('description_th'),
                    'modified' => date("Y-m-d H:i:s", time())
                );

                
                if ( $this->Coupon_model->updateCoupon($coupon_id, $couponData, array()) )
                {
                    redirect("console/coupon/show/".$coupon_id);
                } else {
                    $this->logger->err("edit coupon error.");
                    $data['error'] = $this->lang->line('coupon_edit_coupon_error');
                }
            }else{
                $data['description_ja'] = trim(htmlspecialchars_decode($this->input->post('description_ja')));
                $data['description_en'] = trim(htmlspecialchars_decode($this->input->post('description_en')));
                $data['description_th'] = trim(htmlspecialchars_decode($this->input->post('description_th')));
            }
        }else{
            $data['description_ja'] = htmlspecialchars_decode($coupon->description_ja);
            $data['description_en'] = htmlspecialchars_decode($coupon->description_en);
            $data['description_th'] = htmlspecialchars_decode($coupon->description_th);
        }
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/smoothness/jquery-ui-1.8.13.custom.css','css/elrte.min.css','css/html5uploder.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.6.1.min.js','js/jquery-ui-1.8.13.custom.min.js','js/elrte.full.js','js/i18n/elrte.jp.js','js/jquery.ui.datetimepicker.js','js/jquery.ui.datetimepicker-jp.js','js/jquery.ui.datepicker-ja.js','js/jquery.html5uploader.min.js','js/html5uploder.js')));
        $this->load->view('console/coupon/edit_description', $data);
    }

    public function delete($coupon_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        
        $this->Coupon_model->setBannedQuery(FALSE);
        $coupon_id = intval($coupon_id);
        $coupon = $this->Coupon_model->getCouponById($coupon_id);
        $this->load->model('Gallery_model');
        $gallries = $this->Gallery_model->getGalleryByCouponId($coupon_id);
        if ( !empty($coupon) ) {
            //delete isssue
            if ($this->Coupon_model->deleteCouponAndGallerysById($coupon_id)) {
                
                //delete files
                //purchase対応のため、消さない
/*
                @unlink($coupon->thumbnail_filepath);
                if ( !empty($gallries) ) {
                    foreach ($gallries as $gallry){
                        @unlink($gallry->image_filepath);
                    }
                }
*/
                //success to delete coupon
                redirect("console/coupon/manage");
            } else {
                //failed to delete coupon
                $this->logger->err(sprintf('Could not delete coupon: coupon id $d.', $coupon_id));
                show_404();
            }
        }

        // there are some errors.
        $this->logger->warn(sprintf('coupon id %2$d is not user id %1$d coupon', $user_id, $coupon_id));
        redirect("console/coupon/edit/{$coupon_id}");
    }

    public function updateOrder()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $user_id = $this->tank_auth->get_user_id();
        $comic_id = intval($this->input->post('comic_id'));
        $order = array_filter(explode(',', $this->input->post('order')), 'strlen');

        $this->load->model('Comic_model');
        if ($this->Comic_model->isUserComic($user_id, $comic_id)) {
            
            if ( $this->Coupon_model->updateOrder($comic_id, $order) ) {
                echo "update success";
            } else {
                // update failed!
                $this->logger->err(sprintf('update order error, comic id:%d, order:%s.', $comic_id, $this->input->post('order')));
                show_error('failed!', 400);
            }
        } else {
            // this comic id isn't user comic
            $this->logger->debug(sprintf("[updateOrder] it is not user's comic,user id:%s, comic id:%d", $user_id, $comic_id));
            show_error('This is not your comic!', 400);
        }
    }

    public function updateGalleryOrder()
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            redirect($this->config->item('login_url', 'tank_auth'));
        }

        $coupon_id = intval($this->input->post('coupon_id'));
        $order = array_filter(explode(',', $this->input->post('order')), 'strlen');

        $this->load->model('Gallery_model');
        if ( $this->Gallery_model->updateGalleryOrder($coupon_id, $order) ) {
            echo "update success";
        } else {
            // update failed!
            $this->logger->err(sprintf('update order error, coupon id:%d, order:%s.', $coupon_id, $this->input->post('order')));
            show_error('failed!', 400);
        }
    }

    public function addGallery($coupon_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_create_gallery_error')) );//for javascript
            die();
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $_POST['coupon_id'] = $coupon_id;//エラー回避用
            if ( TRUE === ( $validateResult = $this->uploadfile_validate->validate() ) && count($_FILES) > 0 ) {
                $this->load->library('multiple_upload');
                if ( TRUE === ( $validateResult = $this->multiple_upload->validate($_FILES['userfile']) ) ) {
                    if (count($this->multiple_upload->uploadFiles) == 0) {
                        print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_no_file')) );//for javascript
                        die();
                    }else{
                        //start transaction manually
                        $this->db->trans_begin();
                        $db_trans = TRUE;
                        $this->load->model('Gallery_model');
                        $create_coupon_files = $this->Gallery_model->createGalleryByCouponId($coupon_id, $this->multiple_upload->uploadFiles);
                        
                        if(is_array($create_coupon_files)){
                            $upload_result = $this->multiple_upload->upload($this->multiple_upload->uploadFiles, $create_coupon_files,array('action'=>'addGallery','pathinfo'=>null));
                        }else{
                            $db_trans = FALSE;
                        }
                        
                        // check transaction succeeded.
                        if (!$upload_result || $this->db->trans_status() === FALSE || !$db_trans) {
                            $this->db->trans_rollback();
                            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_create_gallery_error')) );//for javascript
                            die();
                        } else {
                            $this->db->trans_commit();
                            print json_encode( array('result'=>'success','gallery_id'=>$create_coupon_files['galleryData']['id'],'order'=>$create_coupon_files['galleryData']['order'],'image_filepath'=>$create_coupon_files['filesArray'][0]['image_filepath'],'modified'=>strftime($this->lang->line('setting_datetime_format'), strtotime($create_coupon_files['galleryData']['created']))) );//for javascript
                            die();
                        }
                    }
                }
            }
            $this->logger->debug(sprintf('uploadGallery error'));
            $data['error'] = $validateResult === TRUE ? $this->lang->line('coupon_no_file') : $validateResult;
            print json_encode( array('result'=>'error','message'=>$data['error']) );//for javascript
            die();
        }
    }

    public function updateGallery($gallery_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_gallery_error')) );//for javascript
            die();
        }

        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $_POST['gallery_id'] = $gallery_id;//エラー回避用
            if ( TRUE === ( $validateResult = $this->uploadfile_validate->validate() ) && count($_FILES) > 0 ) {
                $this->load->library('multiple_upload');
                if ( TRUE === ( $validateResult = $this->multiple_upload->validate($_FILES['userfile']) ) ) {
                    if (count($this->multiple_upload->uploadFiles) == 0) {
                        print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_no_file')) );//for javascript
                        die();
                    }else{
                        //start transaction manually
                        $this->db->trans_begin();
                        $db_trans = TRUE;
                        
                        $this->load->model('Gallery_model');
                        $create_coupon_files = $this->Gallery_model->updateGalleryByGalleryId($gallery_id, $this->multiple_upload->uploadFiles);
                        if(is_array($create_coupon_files)){
                            $upload_result = $this->multiple_upload->upload($this->multiple_upload->uploadFiles, $create_coupon_files,array('action'=>'updateGallery'));
                        }else{
                            $db_trans = FALSE;
                            $upload_result = FALSE;
                        }
                        
                        // check transaction succeeded.
                        if (!$upload_result || $this->db->trans_status() === FALSE || !$db_trans) {
                            $this->db->trans_rollback();
                            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_create_gallery_error')) );//for javascript
                            die();
                        } else {
                            $this->db->trans_commit();
                            print json_encode( array('result'=>'success','gallery_id'=>$create_coupon_files['galleryData']['id'],'order'=>$create_coupon_files['galleryData']['order'],'image_filepath'=>$create_coupon_files['filesArray'][0]['image_filepath'],'modified'=>strftime($this->lang->line('setting_datetime_format'), strtotime($create_coupon_files['galleryData']['modified']))) );//for javascript
                            die();
                        }
                    }
                }
            }
            $this->logger->debug(sprintf('uploadGallery error'));
            $data['error'] = $validateResult === TRUE ? $this->lang->line('coupon_no_file') : $validateResult;
            print json_encode( array('result'=>'error','message'=>$data['error']) );//for javascript
            die();
        }
    }

    public function deleteGallery($gallery_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_gallery_error')) );//for javascript
            die();
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $_POST['gallery_id'] = $gallery_id;//エラー回避用
            $this->load->library('multiple_upload');
            
            $this->load->model('Gallery_model');
            $gallery = $this->Gallery_model->getGalleryById($gallery_id);
            
            if(strcasecmp($gallery->face,1) == 0){
                if (function_exists('imagecreatetruecolor'))
                {
                    $create    = 'imagecreatetruecolor';
                    $copy    = 'imagecopyresampled';
                }
                else
                {
                    $create    = 'imagecreate';
                    $copy    = 'imagecopyresized';
                }
                
                //coupon
                
                $this->Coupon_model->setBannedQuery(FALSE);
                $coupon = $this->Coupon_model->getCouponById($gallery->coupon_id);
                
                //minimum order gallery
                $min_order = $gallery->order != 1 ? 1 : 2;
                $min_order_gallery = $this->Gallery_model->getGalleryByOrder($min_order);

                $pathinfo = pathinfo($min_order_gallery->image_filepath);
                $thumb_file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'thumb.'.$pathinfo['extension'];
                @copy($min_order_gallery->image_filepath, $thumb_file);
                if(!is_file($thumb_file)) return FALSE;
                
                // use gd library
                $gd_config['image_library'] = 'gd2';
                $gd_config['source_image'] = $thumb_file;
                $gd_config['create_thumb'] = FALSE;
                $gd_config['maintain_ratio'] = TRUE;
                // if you want to align to width, then set master_dim 'width'.
                // else if you want to align to hight, then set master_dim 'height'.
                // else you set to 'auto'.
                $gd_config['master_dim'] = 'height';
                // need both setting, width & height
                $gd_config['width'] = $this->config->item('upload_thumb_image_resize_width');
                $gd_config['height'] = $this->config->item('upload_thumb_image_resize_height');
                $this->load->library('image_lib', $gd_config);
                $this->image_lib->resize();
                
                //coupon update
                
                if ( $this->Coupon_model->updateCouponThumbnail($gallery->coupon_id,$thumb_file) )
                {
                    $this->Gallery_model->updateFaceByGalleryId($min_order_gallery->id,1);
                    
                } else {
                    $this->logger->err("edit comic error.");
                    print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_delete_gallery_error')) );//for javascript
                    die();
                }
            }
            
            // delete Gallery record.
            if ( $this->Gallery_model->deleteGalleryById($gallery_id) !== FALSE ) {
                $this->multiple_upload->delete( $gallery->image_filepath );
                if(strcasecmp($gallery->face,1) == 0) $this->multiple_upload->delete( $coupon->thumbnail_filepath );
                print json_encode( array('result'=>'success','gallery_id'=>$gallery_id,'order'=>$gallery->order,'image_filepath'=>null,'modified'=>null) );//for javascript
                die();
            } else {
                print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_delete_gallery_error')) );//for javascript
                die();
            }
        }
    }

    public function updateThumbnailGallery($gallery_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_gallery_error')) );//for javascript
            die();
        }
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            if (function_exists('imagecreatetruecolor'))
            {
                $create    = 'imagecreatetruecolor';
                $copy    = 'imagecopyresampled';
            }
            else
            {
                $create    = 'imagecreate';
                $copy    = 'imagecopyresized';
            }
            $_POST['gallery_id'] = $gallery_id;//エラー回避用
            $this->load->model('Gallery_model');
            $gallery = $this->Gallery_model->getGalleryById($gallery_id);
            
            if(strcasecmp($gallery->face,1) != 0){
                //coupon
                
                $this->Coupon_model->setBannedQuery(FALSE);
                $coupon = $this->Coupon_model->getCouponById($gallery->coupon_id);
                
                $pathinfo = pathinfo($gallery->image_filepath);
                $thumb_file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'thumb.'.$pathinfo['extension'];
                @copy($gallery->image_filepath, $thumb_file);
                if(!is_file($thumb_file)) return FALSE;
                
                // use gd library
                $gd_config['image_library'] = 'gd2';
                $gd_config['source_image'] = $thumb_file;
                $gd_config['create_thumb'] = FALSE;
                $gd_config['maintain_ratio'] = TRUE;
                // if you want to align to width, then set master_dim 'width'.
                // else if you want to align to hight, then set master_dim 'height'.
                // else you set to 'auto'.
                $gd_config['master_dim'] = 'height';
                // need both setting, width & height
                $gd_config['width'] = $this->config->item('upload_thumb_image_resize_width');
                $gd_config['height'] = $this->config->item('upload_thumb_image_resize_height');
                $this->load->library('image_lib', $gd_config);
                $this->image_lib->resize();
                
                //coupon update
                
                if ( $this->Coupon_model->updateCouponThumbnail($gallery->coupon_id,$thumb_file) )
                {
                    $this->Gallery_model->updateFaceByGalleryId($gallery->id,1);
                    //old thumbnail delete
                    if(is_file($coupon->thumbnail_filepath)) @unlink($coupon->thumbnail_filepath);
                    print json_encode( array('result'=>'success','gallery_id'=>$gallery_id,'order'=>$gallery->order,'image_filepath'=>null,'modified'=>null) );//for javascript
                    die();
                } else {
                    $this->logger->err("edit comic error.");
                    print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_thumbnail_gallery_error')) );//for javascript
                    die();
                }
            }
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_thumbnail_gallery_error')) );//for javascript
            die();
        }
    }

    public function category_select($category_id)
    {
        if (!$this->tank_auth->is_console_logged_in()) {                                // not logged in or not activated
            print json_encode( array('result'=>'error','message'=>$this->lang->line('coupon_update_gallery_error')) );//for javascript
            die();
        }
        // get coupons
        
        $this->Coupon_model->setBannedQuery(FALSE);
        $coupons = $this->Coupon_model->getCouponsByCategoryId($category_id);

        if(empty($coupons)){
            print '';
            die();
        }
        $html = '';
        $html .= '<option value="">クーポンを選択</option>';
        foreach ($coupons as $coupon){
            $html .= '<option value="'.$coupon->id.'">'.$coupon->title_ja.'</option>';
        }
        print $html;
    }
    
    function _form_validation(){
        $this->form_validation->set_rules('categories', $this->lang->line('coupon_category'), 'required|xss_clean');
        $this->form_validation->set_rules('popular', $this->lang->line('coupon_popular'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        
        $this->form_validation->set_rules('title_ja', $this->lang->line('coupon_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_title_min_length')}]|max_length[{$this->config->item('coupon_title_max_length')}]");
        $this->form_validation->set_rules('title_en', $this->lang->line('coupon_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_title_min_length')}]|max_length[{$this->config->item('coupon_title_max_length')}]");
        $this->form_validation->set_rules('title_th', $this->lang->line('coupon_title'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_title_min_length')}]|max_length[{$this->config->item('coupon_title_max_length')}]");

        $this->form_validation->set_rules('copy_ja', $this->lang->line('coupon_copy'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_copy_min_length')}]|max_length[{$this->config->item('coupon_copy_max_length')}]");
        $this->form_validation->set_rules('copy_en', $this->lang->line('coupon_copy'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_copy_min_length')}]|max_length[{$this->config->item('coupon_copy_max_length')}]");
        $this->form_validation->set_rules('copy_th', $this->lang->line('coupon_copy'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_copy_min_length')}]|max_length[{$this->config->item('coupon_copy_max_length')}]");

        $this->form_validation->set_rules('value', $this->lang->line('coupon_pricing_value'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        $this->form_validation->set_rules('save', $this->lang->line('coupon_pricing_save'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        $this->form_validation->set_rules('price', $this->lang->line('coupon_pricing_price'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        $this->form_validation->set_rules('stock', $this->lang->line('coupon_stock'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");
        $this->form_validation->set_rules('limit', $this->lang->line('coupon_limit'), "required|xss_clean|htmlspecialchars|strip_tags|numeric");

        $this->form_validation->set_rules('description_ja', $this->lang->line('coupon_description'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_description_max_length')}]");
        $this->form_validation->set_rules('description_en', $this->lang->line('coupon_description'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_description_max_length')}]");
        $this->form_validation->set_rules('description_th', $this->lang->line('coupon_description'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_description_max_length')}]");

        $this->form_validation->set_rules('shop_ja', $this->lang->line('coupon_shop'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_shop_max_length')}]");
        $this->form_validation->set_rules('shop_en', $this->lang->line('coupon_shop'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_shop_max_length')}]");
        $this->form_validation->set_rules('shop_th', $this->lang->line('coupon_shop'), "required|xss_clean|htmlspecialchars|strip_tags|max_length[{$this->config->item('coupon_shop_max_length')}]");

        $this->form_validation->set_rules('address_ja', $this->lang->line('coupon_address'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_address_min_length')}]|max_length[{$this->config->item('coupon_address_max_length')}]");
        $this->form_validation->set_rules('address_en', $this->lang->line('coupon_address'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_address_min_length')}]|max_length[{$this->config->item('coupon_address_max_length')}]");
        $this->form_validation->set_rules('address_th', $this->lang->line('coupon_address'), "required|xss_clean|htmlspecialchars|strip_tags|min_length[{$this->config->item('coupon_address_min_length')}]|max_length[{$this->config->item('coupon_address_max_length')}]");

    }
}

/* End of file theme.php */
/* Location: ./application/controllers/theme.php */