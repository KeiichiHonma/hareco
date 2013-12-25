<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<style type="text/css">
<!--
<?php foreach ($area_slide as $key => $area_slide_id) : ?>
<?php echo '#ind #slideImage .photo0'.($key+1).'{ background-image:url(/images/area/big/'.$area_slide_id.'_big.jpg); }'; ?>
<?php endforeach; ?>
-->
</style>
<div id="slideImage">
<div id="slideImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>エリアガイド</h2>
        <h3>各エリアの天気予測から、晴れの日にお出かけ</h3>
        <div class="topNavPc">
            <div class="searchBox">
                <div class="searchBoxInner">
<?php $this->load->view('layout/parts/area_navi'); ?>
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
        <!-- 画像04 -->
        <div class="boxPhoto photo04">

        </div>
        <!-- 画像05 -->
        <div class="boxPhoto photo05">

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
        <!-- スマホ用ナビゲーション -->
        <div class="topNavSp">
            <div class="searchBox">
                <div class="searchBoxInner">
<?php $this->load->view('layout/parts/area_navi'); ?>
                </div>
            </div>
        </div>
        <div class="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
