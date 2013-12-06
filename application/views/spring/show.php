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
                <h2><?php echo $springs[0]->spring_name; ?></h2>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
<!--
#mainImage.main { background-image:url(/images/spring/<?php echo $springs[0]->id; ?>_main.jpg) ; }
-->
</style>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
<?php $this->load->view('layout/common/recommend_futures'); ?>
<?php $this->load->view('layout/common/hotels'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>

</html>