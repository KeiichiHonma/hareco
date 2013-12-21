<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id="contentsInner">
        <h2><?php echo $hotel['HotelName'] ?></h2>
        <div id="boxLeisure">
            <div class="title cf">
                <div class="photo"><img src="<?php echo $hotel['Picture'][0]['PictureURL']; ?>" alt="" /></div>
                <div class="text">
                    <h3><?php echo $hotel['HotelCatchCopy'] ?></h3>
                    <p><?php echo $hotel['HotelCaption'] ?></p>
                </div>
            </div>
            <div class="plan">
                <ul class="cf">
                <?php foreach ($hotel['Picture'] as $key => $picture) : ?>
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PictureURL']; ?>" alt="<?php echo $picture['PictureCaption']; ?>" width="270" /><br /><?php echo $picture['PictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="guide">
            <div class="leisure">
        <?php if(!empty($plans)) : ?>
            <?php $image_number = 9; ?>
            <!-- 下段(スマホは非表示) -->
            <h2><?php echo $plan_title; ?></h2>
            <?php foreach ($plans as $key => $chunk) : ?>
            <?php if($key == $stop_line) break;?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php foreach ($chunk as $key => $plan) : ?>
                    <?php
                        $target_date = $key;
                        $from_ymd = explode('-',$target_date);
                        $jalan_date = $from_ymd[0].$from_ymd[1].$from_ymd[2];
                        $from_datetime = mktime(0,0,0,$from_ymd[1],$from_ymd[2],$from_ymd[0]);
                        $from_display_date = date("n/j",$from_datetime);
                        $from_youbi = get_day_of_the_week(date("N",$from_datetime),array_key_exists($target_date,$all_holidays),TRUE);
                        
                        $to_datetime = $from_datetime + 86400;
                        //$from_ymd = explode('-',$target_date);
                        //$from_datetime = mktime(0,0,0,$from_ymd[1],$from_ymd[2],$from_ymd[0]);
                        $to_display_date = date("n/j",$to_datetime);
                        $to_youbi = get_day_of_the_week(date("N",$to_datetime),array_key_exists(date("Y-m-d",$to_datetime),$all_holidays),TRUE);
                        
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
<?php $this->load->view('layout/common/recommend_futures'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
