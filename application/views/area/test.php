<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<style type="text/css">
body {
	margin: 0px;
	padding: 0px;
}
#graph {
	width : 600px;
	height: 400px;
	margin: 20px auto;
}
.graph-title {
	font-size:16px;
	font-weight:bold;
	text-align:center;
	margin:50px 0 0;
}
</style>
<script type="text/javascript" src="/js/flotr2.min.js"></script>
</head>
<body>
<p class="graph-title">紳さんの給料の使い道</p>
<div id="graph"></div>
<script type="text/javascript">
(function basic_pie(container) {
    var d1 = [
        [0, 51000]
        ],
        d2 = [
            [0, 28000]
        ],
        d3 = [
            [0, 30000]
        ],
        d4 = [
            [0, 15000]
        ],
		d5 = [
            [0, 15000]
        ],
		d6 = [
            [0, 10000]
        ],
		d7 = [
            [0, 5000]
        ],
        graph;
    graph = Flotr.draw(container, [{
        data: d1,
        label: "家賃"
    }, {
        data: d2,
        label: "車のローンの返済"
    }, {
        data: d3,
        label: "食費",
        pie: {
            explode: 50
        }
    }, {
		data: d4,
        label: "交際費"
    }, {
		data: d5,
        label: "コスプレ費用"
    }, {
		data: d6,
        label: "携帯代"
    }, {
        data: d7,
        label: "光熱費"
    }], {
        HtmlText: false,
        grid: {
            verticalLines: false,
            horizontalLines: false
        },
        xaxis: {
            showLabels: false
        },
        yaxis: {
            showLabels: false
        },
        pie: {
            show: true,
            explode: 6
        },
        mouse: {
            track: true
        },
        legend: {
            position: "se",
            backgroundColor: "#D2E8FF"
        }
    });
})(document.getElementById("graph"));
 </script>
</body>
</html>