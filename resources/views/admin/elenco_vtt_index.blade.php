@extends('templates.admin')

@section('title')
Scadenza evidenze bambini gratis
@endsection

@section('content')

@if ($scadenze->count())
  <table class="table table-hover table-bordered table-responsive datatable">
    <thead>
      <tr>
        <th>Data reminder</th>
        <th>Note</th>
        <th>Scadenza</th>
        <th>Hotel</th>
        <th>ID</th>
        <th>Località</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($scadenze as $scadenza)
        <tr>
          <td>{{ $scadenza->scadenza_al->formatLocalized("%x") }}</td>
          <td>{{ $scadenza->offertaTop->translate->first()->note }}</td>
          <td>{{ $scadenza->offertaTop->getMesiValiditaAsStr() }}</td>
          <td>{{ $scadenza->offertaTop->cliente->nome }}</td>
          <td>{{ $scadenza->offertaTop->cliente->id }}</td>
          <td>{{ $scadenza->offertaTop->cliente->localita->nome }}</td>
          <td class="text-center">
            <a data-id="{{$scadenza->offertaTop->cliente->id}}" data-id-offerta="{{$scadenza->offertaTop->id}}" class="modifica btn btn-success">Modifica</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
    <p><h4>Nessun reminder impostato</h4></p>
@endif
@endsection


@section('onheadclose')

<script type="text/javascript">

jQuery(function() {
    
    /* modifica e approva offerta */
    jQuery(".modifica").click(function() {
        var id_offerta = jQuery(this).data("id-offerta");

        jQuery.ajax({
                url: '<?php echo url("admin/seleziona-hotel-da-id-ajax") ?>/'+id_offerta,
                type: "post",
                async: false,
                data : {
                        'id': jQuery(this).data("id"),
                        '_token': jQuery('input[name=_token]').val()
                        },
                success: function(id_offerta) {
                  // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                  // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                  // return(val) viene eseguito prima che val prenda il nuovo valore
                
                  // location.href = '/admin/'+ uri +'/' + id_offerta + '/edit';
                  
                  window.open(
                      '/admin/vetrine-bg-top/' + id_offerta + '/edit',
                      '_blank'
                    );
              }
          });

    });

});
  
</script>

@endsection
