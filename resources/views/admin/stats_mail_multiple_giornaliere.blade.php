@extends('templates.admin')

@section('title')
Mail Multiple/Wishlist/Mobile Giornaliere, Statistiche mail multiple giornaliere/wishlist/mobile
@endsection

@section('content')

{!! Form::open(array('action' => 'Admin\StatsMailMultiplaController@giornaliere', 'class' => 'form-horizontal')) !!}

  {!! csrf_field() !!}

  <div class="form-group">
    {!! Form::label('tipologia', 'Tipo Mail', array( 'class' => 'col-sm-2 control-label') ) !!}
    <div class="col-sm-10">
      {!! Form::select('tipologia', ['-1' => 'TUTTE'] + $data['tipologie'], null, array('class' => 'form-control' ) ) !!}
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
    </div>
  </div>

{!! Form::close() !!}

@if ($stats)
  <div class="panel panel-default" data-collapsed="0">
  <div class="panel-heading"><div class="panel-title">Statistiche</div></div>
    <div class="panel-body no-padding">
      <table id="stats_results" class="table table-striped table-hover" width="100%">
        <thead>
          <th>Giorno</th>
          <th>N.</th>
        </thead>
          @if ($stats['results'])
        <tbody>
            @foreach ($stats['results'] as $row_stats)
        <tr>
          <td data-order="{{ strtotime($row_stats['giorno']) }}">{{ date('d/m/Y', strtotime($row_stats['giorno'])) }}</td>
          <td>{{ $row_stats['n_mail'] }}</td>
        </tr>
            @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td>Totale</td>
            <td>{{ $stats['totale'] }}</td>
          </tr>
        </tfoot>
          @endif
      </table>
    </div>
  </div>
@endif

@endsection

@section('onheadclose')
<style type="text/css">
#stats_results thead th:last-child, #stats_results tbody td:last-child, #stats_results tfoot td:last-child { text-align:right; }
</style>

@endsection
@section('onbodyclose')

<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/datatables/TableTools.min.js')}}"></script>

<script type="text/javascript">
jQuery(document).ready(function($) {
  var dt_stats = $('#stats_results').dataTable({
    "paging": false,
    "info": false
  });
  dt_stats.closest( '.dataTables_wrapper' ).find( 'select' ).select( {
    minimumResultsForSearch: -1
  });
});
</script>
@endsection