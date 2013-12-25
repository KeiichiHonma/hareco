<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<style type="text/css">
<!--
#ind #slideImage .photo01{ background-image:url(/images/search/big/search1.jpg); }
#ind #slideImage .photo02{ background-image:url(/images/search/big/search2.jpg); }
#ind #slideImage .photo03{ background-image:url(/images/search/big/search3.jpg); }
-->
</style>
<div id="slideImage">
<div id="slideImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>検索候補が複数あります。</h2>
        <h3>「<?php echo $keyword; ?>」の検索結果</h3>
        <div class="topNavPc">
        <div class="searchBox">
            <div class="searchBoxInner">
                <ul class="menuBox">
                    <div>
                        <dl class="cf">
                        <dt>検索候補</dt>
            <?php foreach ($tagsData as $key => $tag) : ?>
<?php
    switch ($tag->tag_type){
        case 0://area
            $url = $date != '' ? 'area/date/'.$tag->object_id.'/'.$date : 'area/show/'.$tag->object_id;
        break;
        case 1://spring
            $url = $date != '' ? 'spring/date/'.$tag->object_id.'/0/'.$tag->area_id.'/'.$date : 'spring/show/'.$tag->object_id;
        break;
        case 3://airport
            $url = $date != '' ? 'airport/date/'.$tag->object_id.'/'.$date : 'airport/show/'.$tag->object_id;
        break;
        case 4://leisure
            $url = $date != '' ? 'leisure/date/'.$tag->object_id.'/'.$date : 'leisure/show/'.$tag->object_id;
        break;
    }
?>
            <dd><?php echo anchor($url, $tag->tag_name); ?></dd>
            <?php endforeach; ?>
                        </dl>
                    </div>
                </ul>
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
                        <dl class="cf">
                        <dt>検索候補</dt>
            <?php foreach ($tagsData as $key => $tag) : ?>
<?php
    switch ($tag->tag_type){
        case 0://area
            $url = $date != '' ? 'area/date/'.$tag->object_id.'/'.$date : 'area/show/'.$tag->object_id;
        break;
        case 1://spring
            $url = $date != '' ? 'spring/date/'.$tag->object_id.'/0/'.$tag->area_id.'/'.$date : 'spring/show/'.$tag->object_id;
        break;
        case 3://airport
            $url = $date != '' ? 'airport/date/'.$tag->object_id.'/'.$date : 'airport/show/'.$tag->object_id;
        break;
        case 4://leisure
            $url = $date != '' ? 'leisure/date/'.$tag->object_id.'/'.$date : 'leisure/show/'.$tag->object_id;
        break;
    }
?>
            <dd><?php echo anchor($url, $tag->tag_name); ?></dd>
            <?php endforeach; ?>
                        </dl>
                    </div>
                </ul>
            </div>
        </div>

        </div>
        <div class="guide">
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
