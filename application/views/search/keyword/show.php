<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<div id="mainImage" class="sub main">
    <div id="gmap"></div>
</div>
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

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="/js/map.js"></script>

<script>
    $(document).ready(function(){
        googlemap_init('gmap', "<?php echo $yahoo_address; ?>");
    });
</script>

<?php $this->load->view('layout/footer/footer'); ?>
