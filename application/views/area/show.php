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
                <h2><?php echo $all_areas[$area_id]->area_name; ?>エリア</h2>
                <!--<h3>幻と現実がぶつかり合う。映画の舞台のようなニューヨークの街。</h3>-->
            </div>
        </div>
    </div>
</div>
<style type="text/css">
<!--
#mainImage.main { background-image:url(/images/area/<?php echo $area_id; ?>_main.jpg) ; }
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
