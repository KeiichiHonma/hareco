<?php
$name_language = 'name_'.$this->config->item('language_min');
$title_language = 'title_'.$this->config->item('language_min');
$description_language = 'description_'.$this->config->item('language_min');
?>
<?php $this->load->view('layout/header/header'); ?>
            <script type="text/javascript">
                $(function() {
                    $(".l_tn").tile();
                    $(".l_tb").tile();
                    $(".s_tn").tile();
                    $(".s_tb").tile(3); //要素数を指定
                });
                $('#contents').corner("round 8px").parent().css('padding', '4px').corner("round 10px");
                $('#page h1').corner("6px");
                $('.sort a').corner("4px");
                $('.pager a').corner("6px");
            </script>

<div id="contents">
    <div id="contents_inner">
        <!-- ■ MAIN CONTENTS ■ -->
        <div id="page">
            <div class="c_wrapper">
                <h1 class="l1"><?php echo $categories[$category_id]->$name_language ?></h1>
                <div class="sort">
                    <ul class="clearfix">
                        <?php $page_format = $page > 1 ? '/'.$page : ''; ?>
                        <?php foreach ($orderSelects as $key => $orderSelect) : ?>
                        <li><?php echo anchor('search/category/'.$category_id.'/'.$key.$page_format, $orderSelect,($key == $order ? 'class="active"' : '')); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- ■ 商品 3カラム　■ -->
                <?php foreach ($coupons['common'] as $coupon) : ?>
                <div class="box_s">
                    <div class="ribbon">
                        <img src="/images/rb_new.png" width="72" height="73" alt="NEW">
                    </div>
                    <div class="s_tn">
                        <?php echo anchor('coupon/show/' . $coupon->id, '<div class="tn_img"><img src="/'.$coupon->thumbnail_filepath.'?'.strtotime($coupon->modified).'" class="image_resize"></div>'); ?>
                    </div>
                    <div class="d_price">
                        <p><?php echo $coupon->save; ?>%OFF</p>
                    </div>
                    <?php echo $coupon->stock == 0 ? '<div class="soldout"><p>SOLDOUT</p></div>' : ''; ?>
                    
                    <div class="v_price">
                        <p><?php echo $coupon->value.$this->lang->line('unit'); ?></p>
                    </div>
                    <div class="n_price">
                        <p><?php echo $coupon->price.$this->lang->line('unit'); ?></p>
                    </div>
                    <div class="s_tb_wrapper">
                        <div class="s_tb">
                            <div class="s_tb_inner">
                                <h4>
                                    <?php echo anchor('coupon/show/' . $coupon->id, $coupon->$title_language); ?>
                                </h4>
                                <p><?php echo char_count_strimwidth(strip_tags(htmlspecialchars_decode($coupon->$description_language)),48); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!--/c_wrapper-->
            <div class="c_wrapper">
                <div class="pager">
                    <?php $this->load->view('common/pager'); ?>
                </div>
            </div>
            <!--/c_wrapper-->
        </div>
        <!--/page-->
        <?php $this->load->view('layout/sidebar/sidebar'); ?>
    </div>
</div>
<!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>