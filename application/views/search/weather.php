<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

    <div class="guide">
        <div id="btnSp">
        <?php if($bodyId == 'area'): ?>
        <h2><?php echo $recommend_futures_title; ?></h2>
        <?php echo form_open('/json/futures','method="post" id="futures_search" class="futures_search"'); ?>
            <div class="navSp">
                <ul>
                <li class="ttl">日付</li>
                <li class="date"><input id="spDate" name="date" type="text" placeholder="日付を選択してください" value="" /></li>
                <li><?php echo date("Y年n月j日",strtotime("+8 day")); ?>り前の日付は、<br />気象情報を元にした各所の天気予報をご確認ください。</li>
                
                <li class="ttl">天気</li>
                <li class="radio"><input type="radio" id="weather9" name="weather" value="9" />指定なし</li>
                <li class="radio"><input type="radio" id="weather0" name="weather" value="0" checked />晴</li>
                <li class="radio"><input type="radio" id="weather1" name="weather" value="1" />雨</li>
                <li class="radio"><input type="radio" id="weather2" name="weather" value="2" />曇</li>
                <li class="radio"><input type="radio" id="weather3" name="weather" value="3" />雷</li>
                <li class="radio"><input type="radio" id="weather4" name="weather" value="4" />雪</li>

                <li class="ttl">晴数</li>
                <li class="radio"><input type="radio" id="sequence1" name="daytime_shine_sequence" value="1" checked />指定なし</li>
                <li class="radio"><input type="radio" id="sequence2" name="daytime_shine_sequence" value="2" />2日連続</li>
                <li class="radio"><input type="radio" id="sequence3" name="daytime_shine_sequence" value="3" />3日連続</li>
                <li class="radio"><input type="radio" id="sequence4" name="daytime_shine_sequence" value="4" />4日連続</li>
                
                <li class="ttl">曜日</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type0" name="sp_day_type[]" value="0" />指定なし</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type1" name="sp_day_type[]" value="1" />月</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type2" name="sp_day_type[]" value="2" />火</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type3" name="sp_day_type[]" value="3" />水</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type4" name="sp_day_type[]" value="4" />木</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type5" name="sp_day_type[]" value="5" />金</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type6" name="sp_day_type[]" value="6" checked="check" />土</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type7" name="sp_day_type[]" value="7" checked="check"/>日</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type8" name="sp_day_type[]" value="8" checked="check" />祝日</li>
                
                <li class="searchBtn"><input type="button" value="検索する" /></li>
                </ul>
            </div>
        
        <input type="hidden" name="sp" id="sp" value="0" />
        <input type="hidden" name="page" id="page" value="" />
        <input type="hidden" name="type" value="area" />
        <input type="hidden" name="search_type" value="<?php echo $search_type; ?>" />
        <input type="hidden" name="search_object_id" value="<?php echo $search_object_id; ?>" />
        <input type="hidden" name="search_keyword" value="<?php echo isset($search_keyword) ? $search_keyword : ''; ?>" />
        <input type="hidden" name="h_id" value="<?php echo isset($jalan_h_id) ? $jalan_h_id : 0; ?>" />
        <input type="hidden" name="area_id" value="<?php echo $area_id ?>" />
        </form>

    

    <script src="/js/lib/picker.js"></script>
    <script src="/js/lib/picker.date.js"></script>
    <script src="/js/lib/picker.time.js"></script>
    <script src="/js/lib/legacy.js"></script>
    <script src="/js/lib/lang-ja.js"></script>
    <script src="/js/lib/app.js"></script>
    
        <?php endif; ?>
<?php $this->load->view('layout/parts/recommend_boxes'); ?>
        </div>
<?php $this->load->view('layout/common/leisure_guide'); ?>
    </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
