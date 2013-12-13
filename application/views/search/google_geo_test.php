<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>出かけるなら晴れがいい-ハレコ</title>
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
              // ジオコーダのコンストラクタ
              var geocoder = new google.maps.Geocoder();

              // geocodeリクエストを実行。
              // 第１引数はGeocoderRequest。住所⇒緯度経度座標の変換時はaddressプロパティを入れればOK。
              // 第２引数はコールバック関数。
              geocoder.geocode({
                address: place
              }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                  // 結果の表示範囲。結果が１つとは限らないので、LatLngBoundsで用意。
                  var bounds = new google.maps.LatLngBounds();

                  for (var i in results) {
                    if (results[i].geometry) {

                      // 緯度経度を取得
                      var latlng = results[i].geometry.location;

                      // 住所を取得(日本の場合だけ「日本, 」を削除)
                      var address = results[i].formatted_address.replace(/^日本, /, '');

                      // 検索結果地が含まれるように範囲を拡大
                      bounds.extend(latlng);

                      // あとはご自由に・・・。
                      new google.maps.InfoWindow({
                        content: address + "<br>(Lat, Lng) = " + latlng.toString()
                      }).open(map, new google.maps.Marker({
                        position: latlng,
                        map: map
                      }));
                    }
                  }

                  // 範囲を移動
                  map.fitBounds(bounds);

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
