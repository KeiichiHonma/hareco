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
        <div id="searchBox">
            <div id="searchBoxInner">
                <ul class="menuBox">
                    <div>
                        <?php
                            $i = 0;
                            $count = count($all_airports);
                            $before_region_id = '';
                            $end_dl = FALSE;
                        ?>
                        <?php foreach ($all_airports as $airport) : ?>
                        <?php
                            if($before_region_id != $airport->region_id){
                                if($i != 0 || $i != $count) echo '</dl>';
                                if($i == $count) $end_dl = TRUE;
                                echo '<dl class="cf"><dt>'.$all_regions[$airport->region_id]->region_name.'</dt>';
                            }
                            $before_region_id = $airport->region_id;
                        ?>
                        <dd style="float:left;"><?php echo anchor('airport/show/'.$airport->id,$airport->airport_name); ?></dd>
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
