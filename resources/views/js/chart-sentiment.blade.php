<script>

    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Year', 'Analytical', 'Confidence', 'Tentative'],
            ["Mon, Aug 10 2015",57.75401069518716,13.368983957219251,33.155080213903744],
            ["Tue, Aug 11 2015",55.775577557755774,12.541254125412541,36.96369636963696],
            ["Wed, Aug 12 2015",54.9636803874092,10.653753026634384,39.95157384987893],
            ["Thu, Aug 13 2015",54.02298850574713,8.812260536398467,41.37931034482759],
            ["Fri, Aug 14 2015",55.69620253164557,6.329113924050633,43.037974683544306],
            ["Mon, Aug 17 2015",61.43497757847533,12.556053811659194,30.94170403587444],
            ["Tue, Aug 18 2015",58.5,10.5,34.0],
            ["Wed, Aug 19 2015",56.62650602409639,9.33734939759036,37.65060240963856],
            ["Thu, Aug 20 2015",52.71867612293144,12.056737588652481,41.13475177304964],
            ["Fri, Aug 21 2015",55.543595263724434,11.517761033369215,37.56727664155005],
            ["Mon, Aug 24 2015",51,12,37],
            ["Tue, Aug 25 2015",56.194125159642404,12.643678160919542,36.65389527458493],
            ["Wed, Aug 26 2015",55.29753265602322,9.143686502177069,40.05805515239477],
            ["Thu, Aug 27 2015",55.02471169686986,11.037891268533773,38.38550247116969]
        ]);

        var options = {
            curveType: 'function',
            legend: { position: 'bottom' },
            title : 'StockTwits Writing Tone from Aug 10th 2015 to Aug 27th 2015',
            vAxis: {title: "Emotion Percentage (%)"},
            hAxis: {title: "Day"},
            series: {
                0: { color: '#e2431e' },
                1: { color: '#e7711b' },
                2: { color: '#00E676' }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }

</script>