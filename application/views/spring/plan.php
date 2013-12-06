<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id="contentsInner">
        <h2><?php echo $hotel['HotelName'].'-'.$target_plan['PlanName']; ?></h2>
        <div id="boxLeisure">
            <div class="title cf">
                <div class="photo"><img src="<?php echo $hotel['Picture'][0]['PictureURL']; ?>" alt="" /></div>
                <div class="text">
                    <h3><?php echo $hotel['HotelCatchCopy'] ?></h3>
                    <p><?php echo $hotel['HotelCaption'] ?></p>
                    <h4>宿泊日 <?php echo $display_date; ?></h4>
                    <h4>プラン名：<?php echo $target_plan['PlanName']; ?></h4>
                    <h5>残室数：<?php echo $target_plan['Stay']['Date'][$jalan_date]['Stock']; ?>部屋</h5>
                    <div class="price">合計<span>￥<?php echo $target_plan['Stay']['Date'][$jalan_date]['Rate'].'円'; ?> </span>(税込・サービス料込)</div>
                    <div class="btnBooking"><a href="<?php echo $target_plan['PlanDetailURL']; ?>" target="_blank"><img src="/images/btn_booking.png" alt="この旅を予約する" /></a></div>
                </div>
            </div>
            <div class="plan">
                <ul class="cf">
                <?php foreach ($hotel['Picture'] as $key => $picture) : ?>
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PictureURL']; ?>" alt="<?php echo $picture['PictureCaption']; ?>" width="270" /><br /><?php echo $picture['PictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                <?php foreach ($target_plan['PlanPicture'] as $key => $picture) : ?>
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PlanPictureURL']; ?>" alt="<?php echo $picture['PlanPictureCaption']; ?>" width="270" /><br /><?php echo $picture['PlanPictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div id="boxLeisureDetail">
            <h3><?php echo $hotel['HotelName'] ?>の基本情報</h3>
            <table>
                <tr>
                    <th>チェックイン</th>
                    <td><?php echo $hotel['CheckInTime']; ?></td>
                </tr>
                <tr>
                    <th>チェックアウト</th>
                    <td><?php echo $hotel['CheckOutTime']; ?></td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td><?php echo $hotel['HotelAddress']; ?></td>
                </tr>
                <tr>
                    <th>アクセス</th>
                    <td><?php echo nl2br($hotel['AccessInformation']); ?></td>
                </tr>
            </table>
        </div>
        <div class="btnBooking"><a href="<?php echo $target_plan['PlanDetailURL']; ?>" target="_blank"><img src="/images/btn_booking.png" alt="この旅を予約する" /></a></div>

        <div id="guide">
            <div id="leisure">
        <?php if(!empty($hotel_etc_plans)) : ?>
            <?php $image_number = 9; ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $hotel_etc_plans_title; ?></h2>
            <?php foreach ($hotel_etc_plans as $key => $chunk) : ?>
            <?php if($key == $hotel_etc_plans_stop_line) break;?>
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
                        <a href="<?php echo '/spring/plan/'.$springs[0]->id.'/'.$plan['Hotel']['HotelID'].'/'.$springs[0]->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
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

        <?php if(!empty($o_area_etc_plans)) : ?>
            <?php $image_number = 9; ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $o_area_etc_plans_title; ?></h2>
            <?php foreach ($o_area_etc_plans as $key => $chunk) : ?>
            <?php if($key == $o_area_etc_plans_stop_line) break;?>
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
                        <a href="<?php echo '/spring/plan/'.$springs[0]->id.'/'.$plan['Hotel']['HotelID'].'/'.$springs[0]->area_id.'/'.$target_date.'/'.$plan['PlanCD']; ?>">
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

    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
