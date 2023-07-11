@extends('templates.admin')

@section('icon')
    <i class="glyphicon glyphicon-star" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
Andamento Reputazione Online <sup style='color:#ff0000;'>NEW</sup>
@endsection

@section('content')

    @if($stats)
        <div class="row">

            <div class="col-md-12">
                <div id="chart_div" style="width: 100%; height: 500px;"></div>
            </div>

           
        </div>
    @endif

    {!! Form::open(array('class' => 'form-horizontal')) !!}
        {!! csrf_field() !!}
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Selezionare l'hotel, il mese e l'anno di partenza</div>
                <div class="panel-options"></div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-4">
                        {!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
                        {!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
                        <i class="entypo-calendar"></i>
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('hotel', 'Hotel', array( 'class' => 'control-label') ) !!}<br />
                        {!! Form::text('hotel', null, array('class' => 'form-control typeahead ricerca-hotel', 'data-local' => implode(',', Utility::getUsersHotels()), 'autocomplete' => 'off', 'placeholder' => "Scrivi l'id o il nome dell'hotel") ) !!}
                    </div>
                    <div class="col-sm-2">
                        <div class="control-label">&nbsp;</div>
                        <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

@endsection

@section('onheadclose')

    {{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @if($stats)

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['', 'Rating'],
                    @foreach($stats as $key => $value)
                        ['{{substr($key,6,2)}}-{{substr($key,4,2)}}-{{substr($key,0,4)}}\n{{substr($key,8,2)}}:{{substr($key,10,2)}}', {{round($value["rating"],1)}}],
                    @endforeach
                ]);

                var options = {
                    title: '',
                    hAxis: {title: '',  titleTextStyle: {color: '#333'}},
                    vAxis: {minValue: 0},
                    legend: {position: 'top'}
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>

    @endif
@endsection

@section('onbodyclose')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

@endsection