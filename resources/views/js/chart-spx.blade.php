<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>

<script>
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
//            ["Wed, Aug 5 2015",2095.27,2095.27,2099.84,2112.66],
//            ["Thu, Aug 6 2015",2075.53,2100.75,2083.56,2103.32],
//            ["Fri, Aug 7 2015",2067.91,2082.61,2077.57,2082.61],
            ["Mon, Aug 10 2015",2080.98,2080.98,2104.18,2105.35],
            ["Tue, Aug 11 2015",2076.49,2102.66,2084.07,2102.66],
            ["Wed, Aug 12 2015",2052.09,2081.10,2086.05,2089.06],
            ["Thu, Aug 13 2015",2078.26,2086.19,2083.39,2092.93],
            ["Fri, Aug 14 2015",2080.61,2083.15,2091.54,2092.45],
            ["Mon, Aug 17 2015",2079.30,2089.70,2102.44,2102.87],
            ["Tue, Aug 18 2015",2094.14,2101.99,2096.92,2103.47],
            ["Wed, Aug 19 2015",2070.53,2095.69,2079.61,2096.17],
            ["Thu, Aug 20 2015",2035.73,2076.61,2035.73,2076.61],
            ["Fri, Aug 21 2015",1970.89,2034.08,1970.89,2034.08],
            ["Mon, Aug 24 2015",1867.01,1965.15,1893.21,1965.15],
            ["Tue, Aug 25 2015",1867.08,1898.08,1867.61,1948.04],
            ["Wed, Aug 26 2015",1872.75,1872.75,1940.51,1943.09],
            ["Thu, Aug 27 2015",1942.77,1942.77,1987.66,1989.60],
//            ["Fri, Aug 28 2015",1975.19,1986.06,1988.87,1993.48],
//            ["Mon, Aug 31 2015",1965.98,1986.73,1972.18,1986.73],
//            ["Tue, Sep 1 2015",1903.07,1970.09,1913.85,1970.09],
//            ["Wed, Sep 2 2015",1916.52,1916.52,1948.86,1948.91],
//            ["Thu, Sep 3 2015",1944.72,1950.79,1951.13,1975.01],
//            ["Fri, Sep 4 2015",1911.21,1947.76,1921.22,1947.76]
        ], true);

        var options = {
            legend:'none',
            colors: ['black'],
            candlestick: {
                fallingColor: { strokeWidth: 0, fill: '#D32F2F', stroke:'black' }, // red
                risingColor: { strokeWidth: 0, fill: '#388E3C', stroke:'black' }   // green
            },
            title : 'Standard and Poor 500 (SPX) Stock Prices Aug 10th 2015 to Aug 27th 2015',
            vAxis: {title: "Price in USD ($)"},
            hAxis: {title: "Day"},
            displayAnnotations: true,

        };

        var chart = new google.visualization.CandlestickChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

</script>