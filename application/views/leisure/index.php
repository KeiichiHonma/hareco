<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<style type="text/css">
<!--
#ind #slideImage .photo01{ background-image:url(/images/leisure/big/leisure1.jpg); }
#ind #slideImage .photo02{ background-image:url(/images/leisure/big/leisure2.jpg); }
#ind #slideImage .photo03{ background-image:url(/images/leisure/big/leisure3.jpg); }
-->
</style>
<div id="slideImage">
<div id="slideImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>レジャー・行楽地ガイド</h2>
        <h3>レジャー・行楽地の天気予測から、晴れの日を探す</h3>
        <div class="topNavPc">
            <div class="searchBox">
                <div class="searchBoxInner">
<?php $this->load->view('layout/parts/leisure_navi'); ?>
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
        <!-- 画像03 -->
        <div class="boxPhoto photo03">

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
<?php $this->load->view('layout/parts/leisure_navi'); ?>
                </div>
            </div>
        </div>
        <div class="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
