<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<style type="text/css">
<!--
#ind #slideImage .photo01{ background-image:url(/images/airport/big/airport1.jpg); }
#ind #slideImage .photo02{ background-image:url(/images/airport/big/airport2.jpg); }
-->
</style>
<div id="slideImage">
<div id="slideImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>空港ガイド</h2>
        <h3>空港の天気予測から、晴れの日のフライトを探す</h3>
        <div class="topNavPc">
        <div class="searchBox">
            <div class="searchBoxInner">
<?php $this->load->view('layout/parts/airport_navi'); ?>
            </div>
        </div>
        </div>
    </div>
    <div id="slider">
        <!-- 画像01 -->
        <div class="boxPhoto photo01">

        </div>
        <!-- 画像02 -->
        <div class="boxPhoto photo02">

        </div>
    </div>
</div>
</div>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <div class="topNavSp">
        <div class="searchBox">
            <div class="searchBoxInner">
<?php $this->load->view('layout/parts/airport_navi'); ?>
            </div>
        </div>

        </div>
        <div class="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
<?php $this->load->view('layout/parts/adsense'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
