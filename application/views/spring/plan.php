<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id="contentsInner">
    <?php if(isset($target_plan['PlanName'])): ?>
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
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PictureURL']; ?>" alt="<?php echo $picture['PictureCaption']; ?>" /><br /><?php echo $picture['PictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                <?php foreach ($target_plan['PlanPicture'] as $key => $picture) : ?>
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PlanPictureURL']; ?>" alt="<?php echo $picture['PlanPictureCaption']; ?>" /><br /><?php echo $picture['PlanPictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php else: ?>
    <h2><?php echo $hotel['HotelName'].'-ご指定のプランに空きがありませんでした。'; ?></h2>
    <?php endif; ?>

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
    <?php if(isset($target_plan['PlanName'])): ?>
        <div class="btnBooking"><a href="<?php echo $target_plan['PlanDetailURL']; ?>" target="_blank"><img src="/images/btn_booking.png" alt="この旅を予約する" /></a></div>
    <?php endif; ?>
<?php $this->load->view('layout/parts/date_spring_plans'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
