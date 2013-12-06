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
            <li><a href="javascript:void(0)" class="ttl nav03"><span>▼祝日から探す</span></a>
                <ul>
                    <?php $i = 0; ?>
                    <?php foreach($holidays as $holiday_date =>  $holiday_name) : ?>
                    <?php if($i == 9) break; ?>
                    <li><a href="/date/show/<?php echo $holiday_date; ?>"><?php echo date('n/j',strtotime('+0 day', strtotime($holiday_date))).' '.$holiday_name; ?></a></li>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="ttl nav01"><span>▼エリアから探す</span></a>
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
            </li>
        </ul>

        <!-- スマホ用ナビゲーション -->
        <div class="navSp">
            <span><a id="right-menu" href="javascript:void(0)">スマホ用ナビゲーション</a></span>
            <div id="sidr-right">
                <ul>
                    <li class="ttl">エリアから探す</li>
                        <?php foreach($areas as $area) : ?>
                        <li><a href="/area/show/<?php echo $area->id; ?>"><?php echo $area->area_name; ?>エリア</a></li>
                        <?php endforeach; ?>
                    <li class="ttl">祝日から探す</li>
                        <?php $i = 0; ?>
                        <?php foreach($holidays as $holiday_date =>  $holiday_name) : ?>
                        <?php if($i == 9) break; ?>
                        <li><a href="/date/show/<?php echo $holiday_date; ?>"><?php echo date('n/j',strtotime('+0 day', strtotime($holiday_date))).' '.$holiday_name; ?></a></li>
                        <?php $i++; ?>
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
                    <form>
                        <input type="text" value="どこの晴れをみる？　ex.伊豆、釧路、別府、熱海" class="focus" /><input type="image" src="/images/btn_search_min.png" align="top" alt="検索" class="btnSearch" />
                    </form>
                </div>
            </div>
    <?php endif; ?>
        </div>
    </div>
</div>