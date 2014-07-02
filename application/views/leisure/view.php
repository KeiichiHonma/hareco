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
        <h2><?php echo $todoufuken->todoufuken_name; ?>レジャー・行楽地ガイド</h2>
        <h3>レジャー・行楽地の天気予測から、晴れの日を探す</h3>
        <div class="topNavPc">
            <div class="searchBox">
                <div class="searchBoxInner">
                    <table class="menuBox">
                        <?php
                            $i = 0;
                            $count = count($leisures) - 1;
                            $before_kana_index = '';
                            $end_td = FALSE;
                            $end_table = FALSE;
                        ?>
                        <?php foreach ($leisures as $leisure) : ?>
                        <?php
                            if($before_kana_index != $leisure->kana_index){
                                if($i != 0 && $i != $count){
                                    echo '</td></tr>';
                                    $end_td = TRUE;
                                }
                                if($i == $count) $end_table = TRUE;
                                echo '<tr><td class="kana_index">'.$leisure->kana_index.'</td><td>';
                            }
                            $before_kana_index = $leisure->kana_index;
                        ?>
                        <?php echo anchor('leisure/show/'.$leisure->id,$leisure->leisure_name); ?>
                        <?php if($end_table) echo '</td></tr>'; ?>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                    </table>
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
                    <ul class="menuBox">
                        <div>
                        <?php
                            $i = 0;
                            $count = count($leisures) - 1;
                            $before_kana_index = '';
                            $end_dd = TRUE;
                            $end_table = FALSE;
                        ?>
                        <?php foreach ($leisures as $leisure) : ?>
                        <?php
                            if($before_kana_index != $leisure->kana_index){
                                if($i != 0 && $i != $count){
                                    echo '</dd></dl>';
                                    $end_dd = TRUE;
                                }
                                if($i == $count) $end_table = TRUE;
                                echo '<dl class="cf"><dt>'.$leisure->kana_index.'</dt>';
                            }
                            $before_kana_index = $leisure->kana_index;
                        ?>
                        <dd<?php if($end_dd) echo ' class="first"';$end_dd = FALSE; ?>><?php echo anchor('leisure/show/'.$leisure->id,$leisure->leisure_name); ?></dd>
                        <?php if($end_table) echo '</dd></dl>'; ?>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                        </div>
                    </ul>
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
