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