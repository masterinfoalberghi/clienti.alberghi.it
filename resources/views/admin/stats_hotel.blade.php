@extends('templates.admin')

@section('icon')
	<i class="glyphicon glyphicon-stats" style="color:#ddd;"></i>&nbsp;&nbsp;
@endsection

@section('title')
	Generali Hotel, Media click per categoria
@endsection

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-info">
				<ul>
					 <li>le statistiche mostrate in questa pagina si aggiornano una volta al giorno, alle ore 01:00</li>        
					 <li>a partire dal 28/01/2016 la visita ad una scheda hotel viene registrata solo se l'hotel in quel momento risulta attivo</li>
					 <li>prima del 28/01/2016 le visite venivano conteggiate quale che fosse lo stato in quel momento dell'hotel</li>
					 <li>queste statistiche vanno solo a vedere la presenza o meno della visita, senza fare valutazioni sullo stato (presente o passato) dell'hotel</li>
				</ul>
			</div>
		</div>
    </div>

  @if ($totalStats)
    
  @if (key($totalStats) == 'visite')
      @php
          $stats = $totalStats['visite']; 
      @endphp

    <div class="row">

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-red">
          <div class="icon"><i class="entypo-users"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_hotel'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_hotel'] }}</div>
          <h3>Hotel</h3>
          <p>Numero di hotel cliccati.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-blue">
          <div class="icon"><i class="entypo-arrow-combo"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_click'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_click'] }}</div>
          <h3>Click Totali</h3>
          <p>Numero di click totali.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-green">
          <div class="icon"><i class="entypo-gauge"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['media'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['media'] }}</div>
          <h3>Media per Hotel</h3>
          <p>Media di click per Hotel.</p>
        </div>
      </div>
      
    </div><br />
  
  @elseif(key($totalStats) == 'telefonate')

      @php
          $stats = $totalStats['telefonate']; 
      @endphp

      <div class="row">

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-red">
          <div class="icon"><i class="entypo-users"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_hotel'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_hotel'] }}</div>
          <h3>Hotel</h3>
          <p>Numero di hotel contattati.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-blue">
          <div class="icon"><i class="entypo-arrow-combo"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_click'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_click'] }}</div>
          <h3>Telefonate Totali</h3>
          <p>Numero di telefonate totali.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-green">
          <div class="icon"><i class="entypo-gauge"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['media'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['media'] }}</div>
          <h3>Media per Hotel</h3>
          <p>Media di telefonate per Hotel.</p>
        </div>
      </div>
      
    </div><br />
  @elseif(key($totalStats) == 'whatsapp')

      @php
          $stats = $totalStats['whatsapp']; 
      @endphp

      <div class="row">

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-red">
          <div class="icon"><i class="entypo-users"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_hotel'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_hotel'] }}</div>
          <h3>Hotel</h3>
          <p>Numero di hotel contattati.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-blue">
          <div class="icon"><i class="entypo-arrow-combo"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_click'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_click'] }}</div>
          <h3>Whatsapp Totali</h3>
          <p>Numero di whatsapp totali.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-green">
          <div class="icon"><i class="entypo-gauge"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['media'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['media'] }}</div>
          <h3>Media per Hotel</h3>
          <p>Media di condivisioni con whatsapp per Hotel.</p>
        </div>
      </div>
      
    </div><br />
  @elseif(key($totalStats) == 'mail')

      @php
          $multi_stats = $totalStats['mail']; 
      @endphp
    
    @foreach ($multi_stats as $key => $stats)
        
    
    <div class="row">

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-red">
          <div class="icon"><i class="entypo-users"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_hotel'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_hotel'] }}</div>
          <h3>Hotel</h3>
          <p>Numero di hotel contattati via mail ({{$key}}).</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-blue">
          <div class="icon"><i class="entypo-arrow-combo"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['n_click'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['n_click'] }}</div>
          <h3>Mail Totali ({{$key}})</h3>
          <p>Numero di mail totali.</p>
        </div>
      </div>

      <div class="col-sm-4 col-xs-6">
        <div class="tile-stats tile-green">
          <div class="icon"><i class="entypo-gauge"></i></div>
          <div class="num" data-start="0" data-end="{{ $stats['media'] }}" data-postfix="" data-duration="500" data-delay="0">{{ $stats['media'] }}</div>
          <h3>Media per Hotel ({{$key}})</h3>
          <p>Media di mail per Hotel.</p>
        </div>
      </div>
      
    </div><br />

    @endforeach
  @endif

@endif

{!! Form::open(array('action' => 'Admin\StatsHotelController@generali', 'class' => 'form-horizontal')) !!}
	
  {!! csrf_field() !!}

<div class="panel panel-default">
	
	<div class="panel-heading">
		<div class="panel-title">Selezionare l'hotel, l'anno e il mese di partenza</div>
		<div class="panel-options"></div>
	</div>

	<div class="panel-body">
    
    <div class="form-group">
      <div class="col-sm-4">
        {!! Form::label('tipo', 'Tipo statistica', array( 'class' => 'control-label') ) !!}<br />
        {!! Form::select('tipo', ["" => "Seleziona il tipo statistica"] + ['visite' => 'visite','mail' => 'mail', 'telefonate' => 'telefonate', 'whatsapp' => 'whatsapp'], null, ['class' => 'form-control']) !!}
      </div>
    </div>

		<div class="form-group">
			
    <div class="col-sm-4">
		{!! Form::label('date_range', 'Scegli le date', array( 'class' => 'control-label') ) !!}
		{!! Form::text('date_range', null, array('class' => 'daterange daterange-inline', 'data-format' => 'DD/MM/YYYY')) !!}
		<i class="entypo-calendar"></i>
    </div>
 
 	<div class="col-sm-2">
		{!! Form::label('categoria', 'Categoria', array( 'class' => 'control-label') ) !!}<br />
		{!! Form::select('categoria', ["" => "Seleziona categoria"] + $data['categorie'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="col-sm-2">
		{!! Form::label('macrolocalita', 'Macrolocalità', array( 'class' => 'control-label') ) !!}<br />
		{!! Form::select('macrolocalita', ["" => "Seleziona macrolocalità"] + $data['macrolocalita'], null, ['class' => 'form-control']) !!}<br />
    </div>

	<div class="col-sm-2">
		{!! Form::label('localita', 'Località', array( 'class' => 'control-label') ) !!}<br />
		{!! Form::select('localita', ["" => "Seleziona località"] + $data['localita'], null, ['class' => 'form-control']) !!}
	</div>

    <div class="col-sm-2">
		<div class="control-label">&nbsp;</div >
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
	
@endsection

@section('onbodyclose')
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


  <script type="text/javascript">
    jQuery( document ).ready(function( $ ) {
      if ($('select#macrolocalita').val() != "")
        $('select#localita').val("").attr('disabled', true);

      $('select#macrolocalita').on('change', function() {
        if ($(this).val() != "")
          $('select#localita').val("").attr('disabled', true);
        else
          $('select#localita').attr('disabled', false);
      });
    });
  </script>
@endsection