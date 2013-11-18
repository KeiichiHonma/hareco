<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>タイ・バンコク発のフリーペーパー「Wise」のクーポンサイト「Balloooooon!」</title>
<meta name="keywords" content="タイ,バンコク,チケット,購入,割引券,割引,クーポン,クーポンサイト,バウチャー" />
<meta name="description" content="タイ・バンコクのチケットをサイト名で購入！タイ・バンコクのクーポンがとても安い！お得なクーポン/バウチャーサイトです。" />
<script src="http://mangahack.apollon.corp.813.co.jp/js/jquery-1.7.2.min.js" language="javascript" type="text/javascript" /></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type='text/javascript'>
    $(function() {
        $('#ft_send').click(function() {
            var keyword = document.getElementById('keyword').value;
            alert(keyword);
            var address = '';
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                address: keyword,
                region: 'jp'
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var bounds = new google.maps.LatLngBounds();
                    for (var r in results) {
                        if (results[r].geometry) {
                            var latlng = results[r].geometry.location;
                            bounds.extend(latlng);
                            address = results[r].formatted_address.replace(/^日本, /, '');
                            $.post("/search/address",{<?php echo $csrf_token; ?>:"<?php echo $csrf_hash; ?>",keyword:keyword,address:address},function(result){
                                if(result != 'error'){
                                    alert('search/keyword/'+ encodeURI(keyword)+'/'+result);
                                    //location.href = 'search/keyword/'+ encodeURI(keyword)+'/'+result;
                                }else{
                                    alert(result);
                                }
                            });
                        }
                    }
                    //map.fitBounds(bounds);
                } else if (status == google.maps.GeocoderStatus.ERROR) {
                    alert("サーバとの通信時に何らかのエラーが発生！");
                } else if (status == google.maps.GeocoderStatus.INVALID_REQUEST) {
                    alert("リクエストに問題アリ！geocode()に渡すGeocoderRequestを確認せよ！！");
                } else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                    alert("短時間にクエリを送りすぎ！落ち着いて！！");
                } else if (status == google.maps.GeocoderStatus.REQUEST_DENIED) {
                    alert("このページではジオコーダの利用が許可されていない！・・・なぜ！？");
                } else if (status == google.maps.GeocoderStatus.UNKNOWN_ERROR) {
                    alert("サーバ側でなんらかのトラブルが発生した模様。再挑戦されたし。");
                } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    alert("見つかりません");
                } else {
                    alert("えぇ～っと・・、バージョンアップ？");
                }
            });
        });
    });
</script>
</head>
<body>
    <div style="text-align: center;">
        <form action="#">
        住所 or ランドマーク：<input type='text' id='keyword' style='width: 400px' value="定山渓">
        <input type='submit' id="ft_send" value='検索'>
        </form>
    </div>
<div id='map' style='width:730px; height:400px;'><br></div>

</body>
</html>
