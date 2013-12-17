    <div id="guide">
        <?php if($bodyId == 'area'): ?>
        <h2><?php echo $recommend_futures_title; ?></h2>
        <?php echo form_open('/json/futures','method="post" id="futures_search" class="futures_search"'); ?>
        <div id="btnPc">
                <dl class="btnPc01 cf gray">
                    <dt>日付：</dt>
                    <dd id="future_searchBox"><input type="text" name="date" value="日付を選択" id="datepicker" /></dd>
                </dl>

                <dl class="btnPc02 radio-group cf">
                    <dt>天気：</dt>
                    <dd><input type="radio" id="weather9" name="weather" value="9" /><label for="weather9">指定なし</label></dd>
                    <dd><input type="radio" id="weather0" name="weather" value="0" checked /><label for="weather0">晴</label></dd>
                    <dd><input type="radio" id="weather1" name="weather" value="1" /><label for="weather1">雨</label></dd>
                    <dd><input type="radio" id="weather2" name="weather" value="2" /><label for="weather2">曇</label></dd>
                    <dd><input type="radio" id="weather3" name="weather" value="3" /><label for="weather3">雷</label></dd>
                    <dd><input type="radio" id="weather4" name="weather" value="4" /><label for="weather4">雪</label></dd>
                </dl>
                <dl class="btnPc03 radio-group cf">
                    <dt>晴数：</dt>
                    <dd><input type="radio" id="sequence1" name="daytime_shine_sequence" value="1" checked /><label for="sequence1">指定なし</label></dd>
                    <dd><input type="radio" id="sequence2" name="daytime_shine_sequence" value="2" /><label for="sequence2">2日連続</label></dd>
                    <dd><input type="radio" id="sequence3" name="daytime_shine_sequence" value="3" /><label for="sequence3">3日連続</label></dd>
                    <dd><input type="radio" id="sequence4" name="daytime_shine_sequence" value="4" /><label for="sequence4">4日連続</label></dd>
                </dl>
                <dl class="btnPc04 check-group cf gray">
                    <dt>曜日：</dt>
                    <dd><input type="checkbox" id="day_type0" name="dummy[]" value="0" /><label for="day_type0">指定なし</label></dd>
                    <dd><input type="checkbox" id="day_type1" name="dummy[]" value="1" /><label for="day_type1">月</label></dd>
                    <dd><input type="checkbox" id="day_type2" name="dummy[]" value="2" /><label for="day_type2">火</label></dd>
                    <dd><input type="checkbox" id="day_type3" name="dummy[]" value="3" /><label for="day_type3">水</label></dd>
                    <dd><input type="checkbox" id="day_type4" name="dummy[]" value="4" /><label for="day_type4">木</label></dd>
                    <dd><input type="checkbox" id="day_type5" name="dummy[]" value="5" /><label for="day_type5">金</label></dd>
                    <dd><input type="checkbox" id="day_type6" name="dummy[]" value="6" checked="check" /><label for="day_type6">土</label></dd>
                    <dd><input type="checkbox" id="day_type7" name="dummy[]" value="7" checked="check"/><label for="day_type7">日</label></dd>
                    <dd><input type="checkbox" id="day_type8" name="dummy[]" value="8" checked="check" /><label for="day_type8">祝日</label></dd>
                </dl>
                <dl class="btnPc05 cf">
                <dd>例）土曜日、日曜日の両日とも晴れる日を探したい。　→　[2日連続]ボタンON　+　[土][日]ボタンON</dd>
                </dl>
        </div>
        
        <div id="btnSp">
            <div class="navSp">
                <span><a id="left-menu" href="javascript:void(0)">▼検索条件を変更する</a></span>
                <div id="sidr-left">
                    <ul>
                    <li class="ttl">日付</li>
                    <li class="date"><input id="spDate" name="sp_date" type="text" placeholder="日付を選択してください" value="" /></li>

                    <li class="ttl">天気</li>
                    <li><input type="radio" id="sp_weather9" name="sp_weather" value="9" />指定なし</li>
                    <li><input type="radio" id="sp_weather0" name="sp_weather" value="0" checked />晴</li>
                    <li><input type="radio" id="sp_weather1" name="sp_weather" value="1" />雨</li>
                    <li><input type="radio" id="sp_weather2" name="sp_weather" value="2" />曇</li>
                    <li><input type="radio" id="sp_weather3" name="sp_weather" value="3" />雷</li>
                    <li><input type="radio" id="sp_weather4" name="sp_weather" value="4" />雪</li>

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
                    
                    <li class="searchBtn">検索する</li>
                    <li class="close">閉じる</li>
                    </ul>
                </div>
            </div>

            <script>
                $('#left-menu').sidr({
                  name: 'sidr-left',
                  side: 'left'
                });
            </script>
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
        <?php if($bodyId == 'area'): ?><div class="nextBtn undisp"><a href="javascript:void(0)">次へ ></a></div><?php endif; ?>
        <div id="recommend">
            <div id="boxes">
            <!-- 下段(スマホは非表示) -->
            <?php foreach ($futures as $key => $chunk) : ?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php $i = 1; ?>
                <?php foreach ($chunk as $future) : ?>
                    <div class="box<?php if($i >= 7) echo ' undisp'; ?>">
                        <?php if($leisure_type == 'spring'): ?>
                            <?php if(isset($hotel['HotelID'])): ?>
                                <a href="<?php echo '/spring/date/'.$springs[0]->id.'/'.$hotel['HotelID'].'/'.$springs[0]->area_id.'/'.$future->date; ?>">
                            <?php else: ?>
                                <a href="<?php echo '/spring/date/'.$springs[0]->id.'/0/'.$springs[0]->area_id.'/'.$future->date; ?>">
                            <?php endif; ?>
                        <?php elseif($leisure_type == 'airport'): ?>
                        <a href="<?php echo '/airport/date/'.$future->area_id.'/'.$future->date; ?>">
                        <?php else: ?>
                        <a href="<?php echo '/area/date/'.$future->area_id.'/'.$future->date; ?>">
                        <?php endif; ?>
                        
                        <div class="weather"><img src="/images/weather/icon/<?php echo $future->daytime_icon_image; ?>" alt="<?php echo $future->daytime; ?>" /></div>
                        <div class="info">
                            <div class="date"><?php echo $future->month.'/'.$future->day; ?><?php echo get_day_of_the_week($future->day_of_the_week,array_key_exists($future->date,$all_holidays),TRUE); ?></div>
                            <div class="highTemp">最高気温 <em><?php echo $future->temperature_max; ?>°C</em></div>
                            <div class="lowTemp">最低気温 <em><?php echo $future->temperature_min; ?>°C</em></div>
                        </div>
                        </a>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php if($bodyId == 'area'): ?><div class="nextBtn"><a href="javascript:void(0)">次へ ></a></div><?php endif; ?>
    </div>