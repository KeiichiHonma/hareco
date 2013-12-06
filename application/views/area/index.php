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
        <div id="searchBox">
            <div id="searchBoxInner">
                <ul class="menuCity">
                    <div>
                        <?php
                            $i = 0;
                            $count = count($areas);
                            $before_region_id = '';
                            $end_dl = FALSE;
                        ?>
                        <?php foreach ($areas as $area) : ?>
                        <?php
                            if($before_region_id != $area->region_id){
                                if($i != 0 || $i != $count) echo '</dl>';
                                if($i == $count) $end_dl = TRUE;
                                echo '<dl class="cf"><dt>'.$regions[$area->region_id]->region_name.'</dt>';
                            }
                            $before_region_id = $area->region_id;
                        ?>
                        <dd style="float:left;"><?php echo anchor('area/show'.$area->id,$area->area_name); ?></dd>
                        <?php if($end_dl) echo '</dl>'; ?>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                </ul>

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
        <div id="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
