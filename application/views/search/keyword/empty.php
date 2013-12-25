<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
    <h2>条件に該当する結果が見つかりませんでした。</h2>
    以下のヒントを参考に、検索方法を変更してみてください。<br />
    ・検索の対象範囲を広げてください。<br />
    ・都市名、住所、またはランドマークで検索してください。
        <div class="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
