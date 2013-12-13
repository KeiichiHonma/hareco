<!-- 
//////////////////////////////////////////////////////////////////////////////
header
//////////////////////////////////////////////////////////////////////////////
-->
<div id="header" class="cf">
    <div id="headerInner" class="cf">
        <h1><a href="#">ハレコ</a></h1>
        <h2>晴れてよかった！を創るレコメンドサービス</h2>
        <!-- PC用ナビゲーション -->
        <ul class="navPc">
            <li><a href="javascript:void(0)" class="ttl nav04"><span>▼温泉地から探す</span></a>
                <ul class="menuBox">
                    <div>
                    <?php
                        $i = 0;
                        $count = count($all_springs);
                        $before_spring_area_id = '';
                        $end_dl = FALSE;
                    ?>
                    <?php foreach ($all_springs as $spring) : ?>
                    <?php
                        if($before_spring_area_id != $spring->spring_area_id){
                            if($i != 0 || $i != $count) echo '</dl>';
                            if($i == $count) $end_dl = TRUE;
                            echo '<dl class="cf"><dt'.($spring->spring_area_id == 5 ? ' class="hakone"' : ' class="spring_area"') .'>'.$spring->spring_area_name.'</dt>';
                        }
                        $before_spring_area_id = $spring->spring_area_id;
                    ?>
                    <dd style="float:left;"><?php echo anchor('spring/show/'.$spring->id,$spring->spring_name); ?></dd>
                    <?php if($end_dl) echo '</dl>'; ?>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                    </div>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="ttl nav01"><span>▼エリアから探す</span></a>
                <ul class="menuBox">
                    <div>
                        <?php
                            $i = 0;
                            $count = count($all_areas);
                            $before_region_id = '';
                            $end_dl = FALSE;
                        ?>
                        <?php foreach ($all_areas as $area) : ?>
                        <?php
                            if($before_region_id != $area->region_id){
                                if($i != 0 || $i != $count) echo '</dl>';
                                if($i == $count) $end_dl = TRUE;
                                echo '<dl class="cf"><dt>'.$all_regions[$area->region_id]->region_name.'</dt>';
                            }
                            $before_region_id = $area->region_id;
                        ?>
                        <dd style="float:left;"><?php echo anchor('area/show/'.$area->id,$area->area_name); ?></dd>
                        <?php if($end_dl) echo '</dl>'; ?>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                </ul>
            </li>
        </ul>

        <!-- スマホ用ナビゲーション -->
        <div class="navSp">
            <span><a id="right-menu" href="javascript:void(0)">スマホ用ナビゲーション</a></span>
            <div id="sidr-right">
                <ul>
                    <li class="ttl">エリアから探す</li>
                        <?php foreach($all_areas as $area) : ?>
                        <li><a href="/area/show/<?php echo $area->id; ?>"><?php echo $area->area_name; ?>エリア</a></li>
                        <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php if(isset($isHome)) : ?>
        <div id="cloud">
            <h3>予測正解率</h3>
            <span>67%</span>
        </div>
    <?php endif; ?>
    </div>
    <!-- パンクズ -->
    <div id="breadcrumb" class="scrolltop">
        <div id="breadcrumbInner" class="cf">
            <?php if(isset($topicpaths)) : ?>
            <?php foreach ($topicpaths as $key => $topicpath) : ?>
                <span><?php echo is_null($topicpath[0]) ? $topicpath[1] :  anchor($topicpath[0], $topicpath[1]); ?></span>
            <?php endforeach; ?>
            <?php endif; ?>
    <?php if(!isset($isIndex)) : ?>
            <div id="searchBox">
                <div id="searchBoxInner">
                <?php echo form_open('/search','method="get" onsubmit="s_confirm();return false;" id="search"'); ?>
                    <input type="text" name="keyword" value="<?php echo !empty($keyword) ? $keyword : $this->lang->line('search_box_default'); ?>" class="focus" /><input type="image" src="/images/btn_search_min.png" align="top" alt="検索" class="btnSearch" />
                <?php echo form_close(); ?>
                </div>
            </div>
    <?php endif; ?>
        </div>
    </div>
</div>