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
                <li class="date"><input id="spDate" name="sp_date" type="text" placeholder="日付を選択してください" value="" /></li>

                <li class="ttl">天気</li>
                <li class="radio"><input type="radio" id="sp_weather9" name="sp_weather" value="9" />指定なし</li>
                <li class="radio"><input type="radio" id="sp_weather0" name="sp_weather" value="0" checked />晴</li>
                <li class="radio"><input type="radio" id="sp_weather1" name="sp_weather" value="1" />雨</li>
                <li class="radio"><input type="radio" id="sp_weather2" name="sp_weather" value="2" />曇</li>
                <li class="radio"><input type="radio" id="sp_weather3" name="sp_weather" value="3" />雷</li>
                <li class="radio"><input type="radio" id="sp_weather4" name="sp_weather" value="4" />雪</li>

                <li class="ttl">晴数</li>
                <li class="radio"><input type="radio" id="sp_sequence1" name="sp_daytime_shine_sequence" value="1" checked />指定なし</li>
                <li class="radio"><input type="radio" id="sp_sequence2" name="sp_daytime_shine_sequence" value="2" />2日連続</li>
                <li class="radio"><input type="radio" id="sp_sequence3" name="sp_daytime_shine_sequence" value="3" />3日連続</li>
                <li class="radio"><input type="radio" id="sp_sequence4" name="sp_daytime_shine_sequence" value="4" />4日連続</li>
                
                <li class="ttl">曜日</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type0" name="dummy[]" value="0" />指定なし</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type1" name="dummy[]" value="1" />月</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type2" name="dummy[]" value="2" />火</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type3" name="dummy[]" value="3" />水</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type4" name="dummy[]" value="4" />木</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type5" name="dummy[]" value="5" />金</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type6" name="dummy[]" value="6" checked="check" />土</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type7" name="dummy[]" value="7" checked="check"/>日</li>
                <li class="checkbox"><input type="checkbox" id="sp_day_type8" name="dummy[]" value="8" checked="check" />祝日</li>
                
                <li class="searchBtn"><input type="submit" value="検索する" /></li>
                </ul>
            </div>
        
        <input type="hidden" name="sp" id="sp" value="1" />
        <input type="hidden" name="page" id="page" value="" />
        <input type="hidden" name="type" value="area" />
        <input type="hidden" name="day_type" id="day_type" value="" />
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
