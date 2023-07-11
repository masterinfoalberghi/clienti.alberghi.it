@extends('templates.admin')

@section('title')
Mail Schede Giornaliere totali, Statistiche mail schede giornaliere totali
@endsection

@section('content')

@if ($stats)
  <div class="panel panel-default" data-collapsed="0">
  <div class="panel-heading"><div class="panel-title">Statistiche</div></div>
    <div class="panel-body no-padding">
      <table id="stats_results" class="table table-striped table-hover">
        <thead>
          <th>Giorno</th>
          <th>N.</th>
        </thead>
          @if (isset($stats))
        <tbody>
            @foreach ($stats as $row_stats)
        <tr>
          <td data-order="{{ strtotime($row_stats['giorno']) }}">{{ date('d/m/Y', strtotime($row_stats['giorno'])) }}</td>
          <td>{{ $row_stats['n_mail'] }}</td>
        </tr>
            @endforeach
        </tbody>
          @endif
      </table>
    </div>
  </div>
@endif

@endsection


@section('onheadclose')

@endsection
@section('onbodyclose')

<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/datatables/TableTools.min.js')}}"></script>

<script type="text/javascript">
jQuery(document).ready(function($) {
  if ($('#stats_results').length)
  {
    var dt_stats = $('#stats_results').dataTable({
      "paging": false,
      "info": false,
      "order": [[0, "asc"]]
    });
    dt_stats.closest( '.dataTables_wrapper' ).find( 'select' ).select( {
      minimumResultsForSearch: -1
    });
  }
});
</script>
@endsection