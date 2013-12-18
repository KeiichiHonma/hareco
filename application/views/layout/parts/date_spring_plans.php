        <div id="guide">
            <div id="leisure">
        <?php if(!empty($hotel_plans)) : ?>
            <?php $image_number = 9; ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $hotel_plans_title; ?></h2>
            <?php foreach ($hotel_plans as $key => $chunk) : ?>
            <?php if($key == $hotel_plans_stop_line) break;?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php foreach ($chunk as $key => $plan) : ?>
                    <?php
                        //使用する画像決定
                        $count = count($plan['PlanPicture']);//最大3
                        if($image_number == 9){
                            $image_number = 0;
                        }elseif ($image_number == 0 && $count > 1){
                            $image_number = 1;
                        }elseif ($image_number == 1 && $count > 2){
                            $image_number = 2;
                        }else{
                            $image_number = 0;
                        }
                    ?>
                    <div class="box">
                        <a href="<?php echo '/spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
                        <div class="photo spot"><?php echo img(array('src' => $plan['PlanPicture'][$image_number]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $plan['PlanName']; ?></span></div>
                        <div class="text">
                            <div class="date"><?php echo $from_display_date.$from_youbi; ?>～<?php echo $to_display_date.$to_youbi; ?></div>
                            <?php
                                $stock_text = '';
                                if(isset($jalan_date) && isset($plan['Stay']['Date'][$jalan_date])){
                                    $stock_text .= $plan['Stay']['Date'][$jalan_date]['Rate'].'円';
                                    $stock_text .=  ' / 空き<em class='.($plan['Stay']['Date'][$jalan_date]['Stock'] < 5 ? 'red' : 'blue').'>'.$plan['Stay']['Date'][$jalan_date]['Stock'].'</em>部屋';
                                }else{
                                    $stock_text .= $plan['SampleRate'].'円';
                                }
                            ?>
                            <div class="stock"><?php echo $stock_text; ?></div>
                            <div class="catch"><?php echo $plan['Hotel']['HotelType']; ?></div>&nbsp;<div class="catch">In:<?php echo $plan['PlanCheckIn']; ?></div>&nbsp;<div class="catch">Out:<?php echo $plan['PlanCheckOut']; ?></div>
                        </div>
                        <img src="/images/icon_leisure_01.png" alt="温泉" class="category" />
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if(!empty($o_area_plans)) : ?>
            <?php $image_number = 9; ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $o_area_plans_title; ?></h2>
            <?php foreach ($o_area_plans as $key => $chunk) : ?>
            <?php if($key == $o_area_plans_stop_line) break;?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php foreach ($chunk as $key => $plan) : ?>
                    <?php
                        //使用する画像決定
                        $count = count($plan['PlanPicture']);//最大3
                        if($image_number == 9){
                            $image_number = 0;
                        }elseif ($image_number == 0 && $count > 1){
                            $image_number = 1;
                        }elseif ($image_number == 1 && $count > 2){
                            $image_number = 2;
                        }else{
                            $image_number = 0;
                        }
                    ?>
                    <div class="box">
                        <a href="<?php echo '/spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
                        <div class="photo spot"><?php echo img(array('src' => $plan['Hotel']['PictureURL'], 'alt' => $plan['Hotel']['HotelName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $plan['Hotel']['HotelName']; ?></span></div>
                        <div class="text">
                            <p><?php echo $plan['PlanName']; ?></p>
                            <div class="date"><?php echo $from_display_date.$from_youbi; ?>～<?php echo $to_display_date.$to_youbi; ?></div>
                            <?php
                                $stock_text = '';
                                if(isset($jalan_date) && isset($plan['Stay']['Date'][$jalan_date])){
                                    $stock_text .= $plan['Stay']['Date'][$jalan_date]['Rate'].'円';
                                    $stock_text .=  ' / 空き<em class='.($plan['Stay']['Date'][$jalan_date]['Stock'] < 5 ? 'red' : 'blue').'>'.$plan['Stay']['Date'][$jalan_date]['Stock'].'</em>部屋';
                                }else{
                                    $stock_text .= $plan['SampleRate'].'円';
                                }
                            ?>
                            <div class="stock"><?php echo $stock_text; ?></div>
                        </div>
                        <img src="/images/icon_leisure_01.png" alt="温泉" class="category" />
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

            </div>
        </div>