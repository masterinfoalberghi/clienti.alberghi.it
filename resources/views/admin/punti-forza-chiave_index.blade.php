@extends('templates.admin')

@section('title')
Punti forza chiave
@endsection

@section('content')
<div class="row">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">Modifica Punti forza chiave</div>
        <div class="panel-options">
        </div>
      </div>
      <div class="panel-body">
        <table id="parolechiave" class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Punto forza chiave</th>
              <th>Lingua</th>
              <th>Modifica</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          @foreach($data["records"] as $keyword)
            <tr class="{{ (isset($data['record']->id) && $keyword->id == $data['record']->id) ? 'bg-warning' : '' }}">
              <td>{{ $keyword->id }}</td>
              <td>{{ $keyword->chiave }}</td>
              <td>{{ Utility::getLanguage($keyword->lang_id)[0] }}</td>
              <td data-order="{{ $keyword->updated_at->timestamp }}"><abbr title="{{ $keyword->updated_at->formatLocalized("%x %X") }}">{{ $keyword->updated_at->formatLocalized("%x") }}</abbr></td>
              <td class="text-center">
                <a href="{{ url("admin/punti-forza-chiave/".$keyword->id."/edit") }}" class="btn btn-primary btn-xs">Modifica</a>
              </td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5">
                {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/punti-forza-chiave/save', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                {!! csrf_field() !!}
                <div class="row">
                  
                  <div class="col-lg-4">
                      {!! Form::text('parola-chiave[0]', null, ['placeholder' => 'Inserisci nuova parola chiave', 'class' => 'form-control']) !!}
                  </div>
                  
                  <div class="col-lg-4">
                      {!! Form::select('lang_id', $data["langs"], null, ['class' => 'form-control']) !!}
                  </div>

                  <div class="col-lg-4">
                      <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-ok"></i> Salva</button>
                  </div>
                
                 </div> {{-- row  --}}
                {!! Form::close() !!}
              </td>
            </tr>
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

        {!! Form::open(['id' => 'record_delete', 'url' => 'admin/punti-forza-chiave/delete', 'method' => 'POST']) !!}
          <input type="hidden" name="id" value="<?=$data["record"]->id ?>">
        {!! Form::close() !!}


        {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/punti-forza-chiave/save', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

          {!! csrf_field() !!}

          {!! Form::hidden( 'id', $data['record']->id ) !!}

          <div class="form-group">
            {!! Form::label('parola-chiave', 'Parola chiave', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('parola-chiave', $data["record"]->chiave, ['placeholder' => 'Parola chiave', 'class' => 'form-control']) !!}
            </div>
          </div>

          <h3>Parole chiavi espanse</h3>

          <p>Modifica le parole chiave espanse e salva tutte le modifiche con il pulsante <strong>Salva</strong> in fondo al pannello.<br />
            Seleziona nella colonna di destra una o più parole chiave espanse e clicca su <strong>Salva</strong>, quelle selezionate verranno eliminate.<br />
            Il pulsante <strong>Elimina</strong> in fondo al pannello rimuove la parola chiave attuale e tutte le sue parole chiavi espanse.</p>

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

<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/datatables/TableTools.min.js')}}"></script>

<script type="text/javascript">
jQuery('domready', function($) {
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
});
</script>
<style type="text/css">
#parolechiave thead tr th:first-child, #parolechiave_espanse thead tr th:first-child { width: 50px; }
#parolechiave_espanse thead tr th:last-child { width: 72px; }
</style>
@endsection