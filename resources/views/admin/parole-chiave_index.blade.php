@extends('templates.admin')

@section('title')Parole chiave
@endsection



@section('content')
<div class="row">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">Inserisci Parola chiave</div>
        <div class="panel-options">
        </div>
      </div>
      <div class="panel-body">
        <table class="table">
          <tr>
            <td>
              {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/parole-chiave/save', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
              {!! csrf_field() !!}
              <div class="input-group">
                {!! Form::text('parola-chiave[0]', null, ['placeholder' => 'Inserisci nuova parola chiave', 'class' => 'form-control input-sm']) !!}
                <span class="input-group-btn">
                  <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-ok"></i> Salva</button>
                </span>
              </div>
              {!! Form::close() !!}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">Modifica Parola chiave</div>
        <div class="panel-options">
        </div>
      </div>
      <div class="panel-body">
        <table id="parolechiave" class="table table-hover">
          <thead>
            <tr>
              <th>Parola chiave</th>
              <th>Mappa</th>
              <th width="50">Modifica</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

          @foreach($data["records"] as $keyword)
            @if (!$keyword->ricerca_mappa)
              <tr class="{{ (isset($data['record']->id) && $keyword->id == $data['record']->id) ? 'bg-warning' : '' }}">
            @else
              @if ($keyword->isValidInMappa())
                <tr class="bg-success">
              @else
                <tr class="{{ $keyword->isComingInMappa() ? 'bg-info' : 'bg-danger' }}">
              @endif
            @endif
              <td><a href="{{ url("admin/parole-chiave/".$keyword->id."/edit") }}" >{{ $keyword->chiave }}</a></td>
              <td>
                @if ($keyword->ricerca_mappa && $keyword->mappa_dal && $keyword->mappa_al)
					<img src="{{ Utility::assetsImage('markers/1.png') }}" alt="Mappa listing" id="mappa_listing_img" title="Valido dal {{ $keyword->mappa_dal->formatLocalized("%x") }} al {{ $keyword->mappa_al->formatLocalized("%x") }}" width="16px" height="16px">
                @else
                  &nbsp;
                @endif
              </td>
				<td data-order="{{ $keyword->updated_at->timestamp }}"><abbr title="{{ $keyword->updated_at->formatLocalized("%x %X") }}">{{ $keyword->updated_at->formatLocalized("%x") }}</abbr></td>
              <td class="text-center">
                <a href="{{ url("admin/parole-chiave/".$keyword->id."/edit") }}" class="btn btn-primary btn-xs">Modifica</a>
              </td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    @if (isset($data["record"]))
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">Modifica Parola chiave ({{ $data['record']->id }})</div>
        <div class="panel-options">
          <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
        </div>
      </div>
      <div class="panel-body">

        {!! Form::open(['id' => 'record_delete', 'url' => 'admin/parole-chiave/delete', 'method' => 'POST']) !!}
          <input type="hidden" name="id" value="<?=$data["record"]->id ?>">
        {!! Form::close() !!}


        {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/parole-chiave/save', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

          {!! csrf_field() !!}

          {!! Form::hidden( 'id', $data['record']->id ) !!}

          <div class="form-group">
            {!! Form::label('parola-chiave', 'Parola chiave', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('parola-chiave', $data["record"]->chiave, ['placeholder' => 'Parola chiave', 'class' => 'form-control']) !!}
            </div>
          </div>
          
          <h3>Abilita nella mappa di ricerca</h3>
          <label><input type="checkbox" name="ricerca_mappa" title="Abilita queste offerte" id="abilita_ricerca_mappa" @if ($data['record']->ricerca_mappa) checked="checked" @endif /> Abilita</label>

          <h3>Label ricerca mappa</h3>
          @foreach (Langs::getAll() as $lang)
            @php
              $col = 'nome_'.$lang;
            @endphp
            <div class="form-group">
              {{-- {!! Form::label('label'.$lang, 'Label '.$lang , ['class' => 'col-sm-2 control-label']) !!} --}}
              <div class="col-sm-10">
                <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                {!! Form::text($col, $data["record"]->$col, ['placeholder' => 'Label '.$lang, 'class' => 'form-control', 'id' => 'label_'.$lang]) !!}
              </div>
            </div>          
          @endforeach

          <h3>Periodo visibilità ricerca mappa</h3>
          <div class="form-group">
            <div class="col-sm-6 ">
              {!! Form::text('mappa_dal', ( isset($data["record"]) && !is_null($data["record"]->mappa_dal) && Utility::isValidYear($data["record"]->mappa_dal->format('Y')) )  ? $data["record"]->mappa_dal->format('d/m/Y') : "", ['id' => 'mappa_dal', 'class' => ' form-control dateicon', 'style' => 'width:100px;display:inline-block;']) !!}          
              &rarr;
              {!! Form::text('mappa_al',  ( isset($data["record"]) && !is_null($data["record"]->mappa_al) && Utility::isValidYear($data["record"]->mappa_al->format('Y')) )   ? $data["record"]->mappa_al->format('d/m/Y')  : "", ['id' => 'mappa_al',  'class' => ' form-control dateicon', 'style' => 'width:100px;display:inline-block;']) !!}
            </div>
          </div>

          <h3>Parole chiavi espanse</h3>

          <p>Modifica le parole chiave espanse e salva tutte le modifiche con il pulsante <strong>Salva</strong> in fondo al pannello.<br />
            Seleziona nella colonna di destra una o più parole chiave espanse e clicca su <strong>Salva</strong>, quelle selezionate verranno eliminate.<br />
            Il pulsante <strong>Elimina</strong> in fondo al pannello rimuove la parola chiave attuale e tutte le sue parole chiavi espanse.</p>

          <h3>Pagine trovate con queste parole chiave</h3>
          @foreach($data["pagine"] as $pagine)
            <a target="blank" href="/{{$pagine->uri}}">
              <img src="{{Utility::assetsImage("others/blank.gif", true)}}" class="flag flag-{{$pagine->lang_id}}" style="margin-right:5px;vertical-align:middle;"/> /{{$pagine->uri}} <i class="entypo-popup"></i></a>
            <a target="blank" style="float:right; color:#990000" href="/admin/pages/{{$pagine->id}}/edit">Modifica</a><br/>
          @endforeach

          <h3>Parole</h3>
          <table id="parolechiave_espanse" class="table table-hover">
            <thead>
              <tr>
                <th>Parola chiave espansa</th>
                <th><label><input type="checkbox" title="Seleziona tutti/nessuno" id="checkAll" /> Elimina</label></th>
              </tr>
            </thead>
            <tbody>
            @if ($data["record_keywords"])
              @foreach($data["record_keywords"] as $alias)
              <tr>
                <td>{!! Form::text('parola-chiave-ext[' .$alias->id .']', $alias->chiave, ['placeholder' => 'Parola chiave', 'class' => 'form-control input-sm']) !!}</td>
                <td class="text-center">{!! Form::checkbox('parola-chiave-ext-delete[]', $alias->id, false) !!}</td>
              </tr>
              @endforeach
            @endif
            </tbody>
            <tfoot>
              <tr>
                <td>{!! Form::text('parola-chiave-ext[0]', null, ['placeholder' => 'Inserisci nuova parola chiave', 'class' => 'form-control input-sm']) !!}</td>
                <td></td>
              </tr>
            </tfoot>
          </table>

          <div class="form-group">
            <div class="col-sm-12">
              @include('templates.admin_inc_record_buttons')
            </div>
          </div>

        {!! Form::close() !!}

      </div>
    </div>
    @endif
  </div>

</div>
@endsection

@section('onbodyclose')

<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
<link rel="stylesheet" type="text/css" href="{{Utility::assets('vendor/flags/flags.min.css', true)}}" />
<script type="text/javascript"          src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
<script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
<script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>
<script type="text/javascript"          src="{{Utility::assets('/vendor/neon/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript"          src="{{Utility::assets('/vendor/neon/js/datatables/TableTools.min.js')}}"></script>




<script type="text/javascript">




jQuery('domready', function($) {

  function disableFieldRicerca(state)
    {
    $("#label_it").prop('disabled', state);
    $("#label_en").prop('disabled', state);
    $("#label_fr").prop('disabled', state);
    $("#label_de").prop('disabled', state);
    $("#mappa_dal").prop('disabled', state);
    $("#mappa_al").prop('disabled', state);
    }


  @if (isset($data["record"]) && !$data['record']->ricerca_mappa)
    disableFieldRicerca(true);
  @endif

  $('#checkAll').on('click', function() {
    var all = $(this);
    $('input[type=checkbox][name="parola-chiave-ext-delete[]').each(function() {
      $(this).prop("checked", all.prop("checked"));
      $(this).trigger('click');
    });
  });

  $('input[type=checkbox][name="parola-chiave-ext-delete[]').on('click', function() {
    if (this.checked)
      $(this).parent().parent().addClass('bg-danger');
    else
      $(this).parent().parent().removeClass('bg-danger');

  });

  var pc = $('#parolechiave').dataTable({
    "paging": false,
    "info": false,
    language: {
      zeroRecords: "Nessun parola chiave trovata."
    }
  });
  pc.closest( '.dataTables_wrapper' ).find( 'select' ).select( {
    minimumResultsForSearch: -1
  });

  var pc_exp = $('#parolechiave_espanse').dataTable({
    "paging": false,
    "info": false,
    "columns": [
      null,
      {"width": "80px", "orderable": false}
    ],
    language: {
      zeroRecords: "Nessun parola chiave espansa trovata."
    }
  });
  pc_exp.closest( '.dataTables_wrapper' ).find( 'select' ).select( {
    minimumResultsForSearch: -1
  });



  $("#mappa_dal").datepicker({ 

    format: 'dd/mm/yyyy', 
    weekStart:1,
    //startDate: moment().format("D/M/Y"),
    language: "it",
    orientation: "bottom left",
    todayHighlight: true,
    autoclose: true

  }).on("changeDate", function(e) {
    
    var data_dal = moment($("#mappa_dal").datepicker("getDate"));
    var data_al = moment($("#mappa_al").datepicker("getDate"));
    if (data_al.isBefore(data_dal)) {

      data_al = data_dal.add(1, 'd');
      $("#mappa_al").datepicker("setDate", data_al.format("D/M/Y") );

    }

    $("#mappa_al").datepicker("setStartDate", moment(e.date).format("D/M/Y") );
    $("#mappa_dal").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
    $("#mappa_al").datepicker("setRange", [ data_dal, data_al]);
    $("#mappa_al").datepicker("show");

  });

  $("#mappa_al").datepicker({ 
    format: 'dd/mm/yyyy', 
    weekStart:1,
    startDate: moment().format("D/M/Y"),
    language: "it",
    orientation: "bottom left",
    todayHighlight: true,
    autoclose: true

  }).on("changeDate", function(e) {

    var data_dal = moment($("#mappa_dal").datepicker("getDate"));
    var data_al = moment($("#mappa_al").datepicker("getDate"));

    $("#mappa_dal").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
    $("#mappa_al").datepicker("setRange", [ data_dal, data_al]);

  });


  $("#abilita_ricerca_mappa").click(function() {

    if($(this).is(":checked"))
      {
      disableFieldRicerca(false);
      } 
    else
      {

      if(window.confirm('Sei sicuro?')) 
        {
        disableFieldRicerca(true);
        }

      }
  });


});
</script>

@endsection