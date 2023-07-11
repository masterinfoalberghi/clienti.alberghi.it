@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-list-alt" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection


@section('title')
  @if(Request::is('admin/stats/vetrine/vaatPage'))
    Vetrine Bambini Gratis Top
  @elseif(Request::is('admin/stats/vetrine/votPage'))
    Vetrine Offerte Top
  @elseif(Request::is('admin/stats/vetrine/vstPage'))
    Vetrine Servizi Top
  @elseif(Request::is('admin/stats/vetrine/vttPage'))
    Vetrine Trattamenti Top
  @endif
  : filtra pagina
@endsection

@section('content')

{!! Form::open(array('class' => 'form-horizontal')) !!}

<div class="panel panel-default">

	<div class="panel-heading">
		<div class="panel-title">Selezionare i parametri di ricerca</div>
		<div class="panel-options"></div>
	</div>

	<div class="panel-body">
		
		<div class="col-sm-3">
			{!! Form::label('anno', 'Anno', ['class' => 'control-label']) !!}
			{!! Form::selectYear('anno',$min_val_year,$max_val_year, date('Y'),["id" => "anno_select", "class"=>"form-control"]) !!}
		</div>

		<div class="col-sm-3">
			{!! Form::label('mese', 'A partire dal mese di', ['class' => 'control-label']) !!}<br />
			{!! Form::selectMonth('mese',null,["id" => "mese_select", "class"=>"form-control"]) !!}
		</div>


		<div class="col-sm-3">
			{!! Form::label('pagina_id', 'Seleziona la pagina', array( 'class' => 'control-label') ) !!}<br />
			{!! Form::select('pagina_id', ["" => "Seleziona la pagina"] + $pages, isset($pagina_id) ? $pagina_id : null, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-3">
			<div class="control-label">&nbsp;</div>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Calcola</button>
		</div>

	</div>
</div>

{!! Form::close() !!}

@if ($stats)
	
	<div class="row">
	  <div class="col-sm-12">
		<div class="alert alert-warning">
		  ATTENZIONE: <b>anche se viene selezionata una pagina in lingua</b> i conteggi sono effettuati sulla medesima pagina <b>in tutte le lingue</b>
		</div>
	  </div>
	</div>
	
  @foreach($stats as $pagina_url => $pagina_stats)
	  	
    <div class="panel panel-default" data-collapsed="0">
		
     
        @foreach($pagina_stats as $periodo => $hotel_stats)
			 <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th width="15%">{{ $periodo }}</th>
			<th >Click</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($hotel_stats as $hotel_id => $val)
              <tr>
                <td>
                  ({{$hotel_id}}) {{$hotels[$hotel_id]}}
                </td>
                <td>
                  {{$val}}
                </td>
              </tr>
            @endforeach
        </tbody>
			</table><br/><br />
        @endforeach
	
	
	 <table class="table table-striped table-hover">
        @if(isset($totali[$pagina_url]))
            <thead>
              <tr>
                <th width="15%">Totali</th>
				<th >Click</th>
              </tr>
		  	</thead>
			<tbody>
              @foreach ($totali[$pagina_url] as $hotel_id => $tot)
                <tr>
                  <td>
                    #{{$hotel_id}}-{{$hotels[$hotel_id]}}
                  </td>
                  <td>
                    {{$tot}}
                  </td>
                </tr>
              @endforeach
		  </tbody>
            
        @endif
      </table>

    </div>
  @endforeach

@endif

@endsection

@section('onheadclose')
	
	{{-- {!! HTML::style('neon/js/daterangepicker/daterangepicker-bs3.css'); !!} --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	
@endsection

@section('onbodyclose')
	
	{{-- {!! HTML::script('neon/js/daterangepicker/moment.js'); !!} --}}
	{{-- {!! HTML::script('neon/js/daterangepicker/daterangepicker.js'); !!} --}}
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	
@endsection