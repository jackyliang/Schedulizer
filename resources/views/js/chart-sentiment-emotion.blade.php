<script>

    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Year', 'Negativity', 'Anger', 'Cheerfulness'],
            ["Mon, Aug 10 2015",43.2,8.799999999999999,56.8],
            ["Tue, Aug 11 2015",41.84782608695652,4.3478260869565215,58.152173913043484],
            ["Wed, Aug 12 2015",42.4812030075188,4.887218045112782,57.5187969924812],
            ["Thu, Aug 13 2015",39.21568627450981,8.49673202614379,60.78431372549019],
            ["Fri, Aug 14 2015",40.869565217391305,11.304347826086957,60.0],
            ["Mon, Aug 17 2015",50.34013605442177,7.482993197278912,49.65986394557823],
            ["Tue, Aug 18 2015",35.11450381679389,10.687022900763358,64.8854961832061],
            ["Wed, Aug 19 2015",39.705882352941174,10.784313725490197,60.78431372549019],
            ["Thu, Aug 20 2015",40.476190476190474,9.226190476190476,59.523809523809526],
            ["Fri, Aug 21 2015",43.112701252236135,5.724508050089446,56.88729874776386],
            ["Mon, Aug 24 2015",6.27,53.2,40.5],
            ["Tue, Aug 25 2015",56.194125159642404,12.643678160919542,36.65389527458493],
            ["Wed, Aug 26 2015",55.29753265602322,9.143686502177069,40.05805515239477],
            ["Thu, Aug 27 2015",55.02471169686986,11.037891268533773,38.38550247116969]
        ]);

        var options = {
            curveType: 'function',
            legend: { position: 'bottom' },
            title : 'StockTwits Emotion Tone from Aug 10th 2015 to Aug 27th 2015',
            vAxis: {title: "Emotion Percentage (%)"},
            hAxis: {title: "Day"},
            series: {
                0: { color: '#e2431e' },
                1: { color: '#e7711b' },
                2: { color: '#00E676' }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart_2'));

        chart.draw(data, options);
    }

</script>