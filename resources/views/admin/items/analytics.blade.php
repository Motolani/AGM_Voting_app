@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if(count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <br>
            <a href="{{ url('viewItems/'.$meetingId) }}" class="btn btn-dark">Back</a>
            <br>
            <br>
            
            <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
            
            <br>
            
            <div id="barchart_material" style="width: 900px; height: 500px;"></div>
        </div>
        

@endsection
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {

    var data = google.visualization.arrayToDataTable({{ Js::from($result) }});

    var options = {
      title: 'Votes',
      is3D: true,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);
  }
</script>

    
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable({{ Js::from($result2) }});
     
        var options = {
            chart: {
                title: 'Votes per Shareholder',
                subtitle: 'Click and Views',
            },
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>