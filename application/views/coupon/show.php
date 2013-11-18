<?php
$title_language = 'title_'.$this->config->item('language_min');
$copy_language = 'copy_'.$this->config->item('language_min');
$description_language = 'description_'.$this->config->item('language_min');
$info_language = 'info_'.$this->config->item('language_min');
$rule_language = 'rule_'.$this->config->item('language_min');
$shop_language = 'shop_'.$this->config->item('language_min');
$address_language = 'address_'.$this->config->item('language_min');
?>
<?php $this->load->view('layout/header/header'); ?>

<script type="text/javascript">
    $(function() {
        var galleries = $('.ad-gallery').adGallery({
            update_window_hash: false, 
            display_next_and_prev: false, 
            display_back_and_forward: true, 
            scroll_jump: 0,
            slideshow: {
            enable: true,
            autostart: true,
            speed: 5000,
            start_label: 'Start',
            stop_label: 'Stop',
            stop_on_scroll: true
            },
            effect: 'slide-hori', 
            enable_keyboard_move: true, 
            cycle: true
        });
    });
    $('#contents').corner("round 8px").parent().css('padding', '4px').corner("round 10px");
    $(function() {
        $(".c2_box").tile();
    });
    $(document).ready(function(){
        $("a[rel^='prettyPopin']").prettyPopin({
            modal : true,
            width : 500,
            height: 450,
            opacity: 0.5,
            animationSpeed: 'fast',
            followScroll: false,
            loader_path: '/images/prettyPopin/loader.gif',
            callback: function(){
            }
        });
    });
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var my_google_map;
    var my_google_geo;

    function googlemap_init( id_name, addr_name ) {
        var latlng = new google.maps.LatLng(41, 133);
        var opts = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: latlng
        };
        my_google_map = new google.maps.Map(document.getElementById(id_name), opts);

        my_google_geo = new google.maps.Geocoder();
        var req = {
            address: addr_name ,
        };
        my_google_geo.geocode(req, geoResultCallback);
    }


    function geoResultCallback(result, status) {
        if (status != google.maps.GeocoderStatus.OK) {
            alert(status);
        return;
        }
        var latlng = result[0].geometry.location;
        my_google_map.setCenter(latlng);
        var marker = new google.maps.Marker({position:latlng, map:my_google_map, title:latlng.toString(), draggable:true});
        google.maps.event.addListener(marker, 'dragend', function(event){
            marker.setTitle(event.latLng.toString());
        });
    }
</script>

<script>
    $(document).ready(function(){
        googlemap_init('gmap', "<?php echo $coupon->$address_language; ?>");
    });
</script>
<?php $this->load->view('layout/common/topicpath'); ?>

<div id="contents">
    <div id="contents_inner">
        <!-- ■ MAIN CONTENTS ■ -->
        <div id="main">
            <h1><?php echo $coupon->$title_language; ?></h1>
            <div class="c_wrapper">
                <div class="p_box">
                    <div class="n_price"><?php echo $coupon->price.$this->lang->line('unit'); ?></div>
                    <div class="v_price"><span class="label">Value</span><span class="price"><?php echo $coupon->value.$this->lang->line('unit'); ?></span></div>
                    <div class="d_price"><span class="label">Discount</span><span class="price"><?php echo $coupon->save; ?>%OFF</span></div>
                    <div class="button"><?php echo $coupon->stock == 0 ? '<img src="/images/bt_soldout.jpg" width="180" height="44" alt="SOLD OUT">' : force_anchor('cart/add/'.$coupon->id, '<img src="/images/bt_buy_'.$this->config->item('language_min').'_off.jpg" width="180" height="44" alt="'.$this->lang->line("btn_cart").'">',TRUE); ?></div>

                    <div class="share">
                        <div class="h_nav clearfix">
                            <ul class="clearfix">
                                <li><h5>Share</h5></li>
                                <li><a href="http://www.facebook.com/share.php?u=<?php echo site_url(lang_base_url('coupon/show/'.$coupon->id)); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/images/ico_fb_off.png" width="35" height="34" alt="facebook"></a></li>
                                <li><a href="http://twitter.com/share?count=horizontal&original_referer=<?php echo site_url(lang_base_url('coupon/show/'.$coupon->id)); ?>&text=<?php echo urlencode($coupon->$title_language); ?>&url=<?php echo site_url(lang_base_url('coupon/show/'.$coupon->id)); ?>" onclick="window.open(this.href, 'tweetwindow', 'width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;"><img src="/images/ico_tt_off.png" width="35" height="34" alt="twitter"></a></li>
                                <li><?php echo anchor(sprintf('coupon/share/'.$coupon->id), '<img src="/images/ico_mail_off.png" width="35" height="34" alt="mail">','rel="prettyPopin"'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="gallery" class="ad-gallery">
                    <div class="ad-image-wrapper"></div>
                    <div class="ad-controls"></div>
                    <div class="ad-nav">
                        <div class="ad-thumbs">
                            <ul class="ad-thumb-list">
                                <?php foreach ($gallerys as $gallery) : ?>
                                <li><a href="/<?php echo $gallery->image_filepath.'?'.strtotime($gallery->gallery_modified); ?>"><img src="/<?php echo $gallery->image_filepath.'?'.strtotime($gallery->gallery_modified); ?>"></a></li>
                                <?php endforeach ; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="ag">
                    <p><?php echo nl2br($coupon->$copy_language); ?></p>
                    </div>
                </div>
            </div>
            <!--/c_wrapper-->
            <div class="c_wrapper">
                <h2><span><?php echo $this->lang->line('coupon_description'); ?></span></h2>
                <?php echo nl2br(htmlspecialchars_decode($coupon->$description_language)); ?>
            </div>
            <!--/c_wrapper-->
            <div class="c_wrapper">
                <h2>
                    <span><?php echo $this->lang->line('coupon_rule'); ?></span>
                </h2>
                <?php echo nl2br($coupon->$rule_language); ?>
            </div>
            <!--/c_wrapper-->
            <!--/c_wrapper-->
            <div class="c_wrapper">
                
                    <h2><span><?php echo $this->lang->line('coupon_shop'); ?></span></h2>
                    <div class="c2_box c2_box_l">
                        <div id="gmap" style="width: 400px; height: 260px; border:none; margin-bottom:15px;"></div>
                    </div>
                    <div class="c2_box c2_box_r">
                        <?php echo nl2br($coupon->$shop_language); ?>
                    </div>
            </div>
            <!--/c_wrapper-->
        </div>
        <!--/main-->
        <?php $this->load->view('layout/sidebar/sidebar'); ?>
    </div>
</div>
<!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
