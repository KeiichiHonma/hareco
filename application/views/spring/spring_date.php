<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<div id="mainImage" class="sub main">
    <div id="mainImageInner">
        <div class="gradationLeft"></div>
        <div class="gradationRight"></div>
        <div id="copy">
            <div id="innerBox">
                <h2><?php echo $spring->spring_name; ?> <?php echo $display_date; ?></h2>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
<!--
#mainImage.main { background-image:url(/images/spring/<?php echo $spring->id; ?>_main.jpg) ; }
-->
</style>
<?php $this->load->view('layout/common/date'); ?>
<?php $this->load->view('layout/footer/footer'); ?>
