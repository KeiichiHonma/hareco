<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<div id="mainImage" class="sub main">
    <div id="mainImageInner">
        <div id="gmap" style="width: 100%; height: 460px; border:none;"></div>
    </div>
</div>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

    条件に該当する結果が見つかりませんでした。以下のヒントを参考に、検索方法を変更してみてください。<br />
    ・検索の対象範囲を広げてください。<br />
    ・都市名、住所、またはランドマークで検索してください。
        <div id="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>