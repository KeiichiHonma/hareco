<?php $this->load->view('layout/parts/adsense'); ?>
<?php //エリアなので複数の温泉地が来る可能性がある ?>
    <?php if(!empty($springs)) : ?>
        <div class="guide">
            <div class="leisure">
        <?php if(!empty($plans)) : ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $plan_title; ?></h2>
            <?php foreach ($plans as $key => $chunk) : ?>
            <?php if($key == $stop_line) break;?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php foreach ($chunk as $plan) : ?>
                    <div class="box">
                        <a href="<?php echo '/spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
                        <?php if($use_image_type == 'plan'): ?>
                            <div class="photo spot"><?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><div class="shadow">&nbsp;</div></div>
                        <?php else: ?>
                            <div class="photo spot"><?php echo img(array('src' => $plan['Hotel']['PictureURL'], 'alt' => $plan['Hotel']['HotelName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $plan['Hotel']['HotelName']; ?></span></div>
                        <?php endif; ?>
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
        <?php else: ?>
            <?php if(!empty($o_area_plans)) : ?>
                <!-- 下段(スマホは非表示) -->
                <h2><?php echo $plan_title; ?></h2>
                <?php foreach ($o_area_plans as $o_area_spring_id => $o_area_plan) : ?>
                    <h3 class="center_dot"><span><?php echo $all_springs[$o_area_spring_id]->spring_name; ?></span></h3>
                    <?php foreach ($o_area_plan as $key => $chunk) : ?>
                    <?php if($key == $stop_line) break 1;?>
                        <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                        <?php foreach ($chunk as $plan) : ?>
                            <div class="box">
                                <a href="<?php echo '/spring/plan/'.$all_springs[$o_area_spring_id]->id.'/'.$plan['Hotel']['HotelID'].'/'.$all_springs[$o_area_spring_id]->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
                                <div class="photo spot"><?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $plan['Hotel']['HotelName']; ?></span></div>
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
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>