$(document).ready( function () {
    var dataPoints = [];

    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "dark2",
        title: {
            text: "Počet študentov na prednáškach"
        },
        axisY: {
            title: "Počet študentov",
            titleFontSize: 24,
            includeZero: true
        },
        data: [{
            type: "column",
            yValueFormatString: "#,### ľudí",
            dataPoints: dataPoints
        }]
    });

    function addData(data) {
        for (var i = 0; i < data.length; i++) {
            dataPoints.push({
                // x: new Date(data[i].date),
                x: new Date(data[i].date),
                y: data[i].minutes
            });
        }
        chart.render();

    }

    $.getJSON("https://wt78.fei.stuba.sk/zadanie4/controllers/api_stats.php", addData);
});