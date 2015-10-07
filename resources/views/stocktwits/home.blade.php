@extends('app')

@section('title')
    GroupThink
@stop

@section('content')
    <div class="page-heading-results">
        <h1>S&P500 vs StockTwits Emotional Sentiment from Aug 10th - Aug 27th</h1>
        <p
            class="text-muted">
            Is there correlation between <a href="http://stocktwits.com/symbol/SPX">S&P 500 StockTwits sentiment</a> vs the <a href="https://www.google.com/finance/historical?cid=626307&startdate=Aug+5%2C+2015&enddate=Sep+5%2C+2015&num=30&ei=9c_rVbm3H9bCe4mkj-gH">S&P 500 stock index</a>?
            Maybe IBM Watson can answer this question
        </p>
    </div>

    <body>
        <div id="chart_div"></div>
        <div id="curve_chart_2" style="width:100%;height:500px"></div>
        <div id="curve_chart"></div>
    </body>

@stop

@include('js.chart-spx')
@include('js.chart-sentiment-emotion')
@include('js.chart-sentiment')
