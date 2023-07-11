@extends('templates.admin')

@section('title')
Info Centro Benessere
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    
    <div class="alert alert-warning">
      <span style="font-size: 13px">
      <strong>Compila i campi richiesti per descrivere il tuo centro benessere</strong>.<br> 
      Le informazioni verranno elaborate per essere pubblicate <strong>sulla tua scheda hotel</strong> e nell'<strong>elenco degli hotel con centro benessere</strong>.<br>
      Le modifiche saranno visibili appena tutti gli hotel con centro benessere avranno compilato le nuove informazioni. <br><strong>Se non intendi inserire questi dati</strong> (sono obbligatori solo: superficie e posizione) <strong>la tua struttura conserverà le informazioni sul centro benessere nella scheda, ma non potrà essere mostrata nell'elenco insieme alle altre</strong>.<br>
      Se hai <strong>più di un centro benessere</strong>, inserisci le informazioni principali che riguardano entrambi. Ci occuperemo di descrivere ciascun centro benessere nel dettaglio nel testo descrittivo del tuo hotel.
      </span>
    </div>


    <div>
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/azzera-info-benessere', 'method' => 'POST']) !!}
      {!! Form::close() !!}

      <button type="button" onclick="jQuery('#modal-confirm-delete').modal('show');" class="btn btn-danger" style="margin: 10px; margin-left: 0"><i class="glyphicon glyphicon-remove"></i>Azzera tutti i campi</button>


      <div class="modal fade" id="modal-confirm-delete" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title">Azzeramento dei campi</h4>
            </div>
            <div class="modal-body">
              Confermi di voler azzerare in maniera definitiva tutti campi della sezione benessere?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
              <button type="button" class="btn btn-primary" onclick="jQuery('#record_delete').submit();">Conferma</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    {!! Form::open(['role' => 'form', 'url'=>['admin/info-benessere'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}

    <div class="panel panel-default" data-collapsed="0">
      <div class="panel-heading"><div class="panel-title">Informazioni di base - obbligatorio</div></div>  
      <div class="panel-body">
        
        <div class="form-group">
          {!! Form::label('sup', 'superficie (mq.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('sup', isset($info) && $info->sup > 0 ? $info->sup : null, ['class' => 'form-control misure', 'maxlength' => '3']) !!}
          </div>
        </div>


        <div class="form-group">
          {!! Form::label('area_fitness', 'Ho un\'area fitness', ['class' => 'col-sm-2 control-label area_fitness']) !!} 
          
          <div class="col-md-10 col-sm-10">
          {!! Form::radio('area_fitness', "1", isset($info) ? $info->area_fitness : null,['class' => 'area_fitness']) !!} Sì
          &nbsp;&nbsp;&nbsp;
          {!! Form::radio('area_fitness', "0", isset($info) ? !$info->area_fitness : null,['class' => 'area_fitness']) !!} No
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       
          {!! Form::text('sup_area_fitness', isset($info) && $info->sup_area_fitness > 0 ? $info->sup_area_fitness : null, ['id' => 'sup_area_fitness', 'class' => 'form-control misure in_linea', 'maxlength' => '3']) !!}&nbsp;
          {!! Form::label('sup_area_fitness', 'superficie (mq.)', ['class' => 'control-label apertura-piscina']) !!}
          </div>
        
        </div>

        <div class="form-group">
          {!! Form::label('aperto_dal', 'Apertura', ['class' => 'col-md-2  col-sm-2 col-sx-2 control-label']) !!}
          
          <div class="col-md-10 col-sm-10">
          {!! Form::label('aperto_al', 'da', ['class' => 'control-label  apertura-piscina']) !!}&nbsp;
           {!! Form::select('aperto_dal',$mesi, isset($info) ? $info->aperto_dal  : null,["id" => "aperto_dal", "class"=>"mesi_select form-control"]) !!}&nbsp;&nbsp;&nbsp;
          {!! Form::label('aperto_al', 'a', ['class' => 'control-label apertura-piscina']) !!}&nbsp;
           {!! Form::select('aperto_al',$mesi, isset($info) ? $info->aperto_al  : null,["id" => "aperto_al", "class"=>"mesi_select form-control"]) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          {!! Form::label('annuale', 'oppure', ['class' => 'control-label apertura-piscina']) !!}
          {!! Form::label('aperto_annuale', 'apertura annuale', ['class' => 'control-label apertura-piscina']) !!} &nbsp;
          {!! Form::checkbox('aperto_annuale', "1", isset($info) ? $info->aperto_annuale : null, ['id' => 'aperto_annuale']) !!}
          </div>

        </div>

        <div class="form-group">
          {!! Form::label('a_pagamento', 'a pagamento', ['class' => 'col-md-2  col-sm-2 col-sx-2 control-label']) !!}
          <div class="col-md-10 col-sm-10">
            {!! Form::radio('a_pagamento', "1", isset($info) ? $info->a_pagamento : null,['id' => 'a_pagamento']) !!} Sì
            &nbsp;&nbsp;&nbsp;
            {!! Form::radio('a_pagamento', "0", isset($info) ? !$info->a_pagamento : null,['id' => 'a_pagamento']) !!} No
          </div>
        </div>


        <div class="form-group">
          {!! Form::label('in_hotel', 'Posizione', ['class' => 'col-sm-2 control-label in_hotel']) !!} 
          
          <div class="col-md-10 col-sm-10">
          {!! Form::label('in_hotel', 'in hotel ', ['class' => 'control-label']) !!}&nbsp;
          {!! Form::checkbox('in_hotel', "1", isset($info) ? $info->in_hotel : null, ['class' => 'control-label in_hotel']) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      
          {!! Form::label('distanza_hotel', 'a ', ['class' => 'control-label']) !!}&nbsp;
          {!! Form::text('distanza_hotel', isset($info) && $info->distanza_hotel > 0 ? $info->distanza_hotel : null, ['id' => 'distanza_hotel', 'class' => 'form-control misure in_linea', 'maxlength' => '5']) !!}&nbsp;
          {!! Form::label('distanza_hotel', 'metri dall\'hotel', ['class' => 'control-label']) !!}
          </div>
        
        </div>


        <div class="form-group">
          {!! Form::label('eta_minima', 'età minima per accedere', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('eta_minima', isset($info) && $info->eta_minima > 0 ? $info->eta_minima : null, ['class' => 'form-control misure in_linea', 'maxlength' => '2']) !!}&nbsp;
            {!! Form::label('eta_minima', 'anni', ['class' => 'control-label apertura-piscina']) !!}
          </div>
        </div>

      </div> {{-- /panel-body --}}
    </div> {{-- panel-default --}}

    <div class="panel panel-default" data-collapsed="0">
      <div class="panel-heading"><div class="panel-title">Caratteristiche</div></div>  
      <div class="panel-body">
          <div class="row">
            <div class="form-group">
              {{--  1 GRUPPO --}}
              <div class="col-sm-1 col-sm-offset-1">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('piscina', "1", isset($info) ? $info->piscina : null) !!} piscina
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('idro', "1", isset($info) ? $info->idro : null) !!} idromassaggio
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('sauna_fin', "1", isset($info) ? $info->sauna_fin : null) !!} sauna finlandese
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('b_turco', "1", isset($info) ? $info->b_turco : null) !!} bagno turco
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('docce_emo', "1", isset($info) ? $info->docce_emo : null) !!} docce emozionali
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('cascate_ghiaccio', "1", isset($info) ? $info->cascate_ghiaccio : null) !!} cascate di ghiaccio
                  </label>
                </div>
              </div>
              {{--  1 GRUPPO --}}
              </div> {{-- form-group --}}
          </div> {{-- row --}}
          
          <div class="row">
            <div class="form-group">
              {{--  2 GRUPPO --}}
              <div class="col-sm-1 col-sm-offset-1">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('aromaterapia', "1", isset($info) ? $info->aromaterapia : null) !!} aromaterapia
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('percorso_kneipp', "1", isset($info) ? $info->percorso_kneipp : null) !!} percorso kneipp
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('cromoterapia', "1", isset($info) ? $info->cromoterapia : null) !!} cromoterapia
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('massaggi', "1", isset($info) ? $info->massaggi : null) !!} massaggi
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('tratt_estetici', "1", isset($info) ? $info->tratt_estetici : null) !!} trattamenti estetici
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('area_relax', "1", isset($info) ? $info->area_relax : null) !!} area relax
                  </label>
                </div>
              </div>
              {{--  2 GRUPPO --}}


              </div> {{-- form-group --}}
          </div> {{-- row --}}


          <div class="row">
            <div class="form-group">
              {{--  3 GRUPPO --}}
              <div class="col-sm-3 col-sm-offset-1">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('letto_marmo_riscaldato', "1", isset($info) ? $info->letto_marmo_riscaldato : null) !!} letto in marmo riscaldato
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('stanza_sale', "1", isset($info) ? $info->stanza_sale : null) !!} stanza del sale
                  </label>
                </div>
              </div>


              <div class="col-sm-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('kit_benessere', "1", isset($info) ? $info->kit_benessere : null) !!} kit benessere in dotazione
                  </label>
                </div>
              </div>

              {{--  3 GRUPPO --}}
              </div> {{-- form-group --}}
          </div> {{-- row --}}
          
          <div class="form-group"></div>
          <div class="form-group"></div>
          
          <div class="form-group">
            {!! Form::label('obbligo_prenotazione', 'obbligo di prenotazione', ['class' => 'col-md-2  col-sm-2 col-sx-2 control-label']) !!}
            <div class="col-md-10 col-sm-10">
              {!! Form::radio('obbligo_prenotazione', "1", isset($info) ? $info->obbligo_prenotazione : null,['id' => 'obbligo_prenotazione']) !!} Sì
              &nbsp;&nbsp;&nbsp;
              {!! Form::radio('obbligo_prenotazione', "0", isset($info) ? !$info->obbligo_prenotazione : null,['id' => 'obbligo_prenotazione']) !!} No
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('uso_in_esclusiva', 'uso in esclusiva', ['class' => 'col-md-2  col-sm-2 col-sx-2 control-label']) !!}
            <div class="col-md-10 col-sm-10">
              {!! Form::radio('uso_in_esclusiva', "1", isset($info) ? $info->uso_in_esclusiva : null,['id' => 'uso_in_esclusiva']) !!} Sì
              &nbsp;&nbsp;&nbsp;
              {!! Form::radio('uso_in_esclusiva', "0", isset($info) ? !$info->uso_in_esclusiva : null,['id' => 'uso_in_esclusiva']) !!} No
               &nbsp;&nbsp;&nbsp;
              {!! Form::radio('uso_in_esclusiva', "2", isset($info) && $info->uso_in_esclusiva== 2 ? $info->uso_in_esclusiva : null,['id' => 'uso_in_esclusiva']) !!} A richiesta
            </div>
          </div>

          @if(Auth::user()->hasRole('hotel'))
          
            <div class="row peculiarita">
                <div class="form-group">
                  {!! Form::label('peculiarita', 'Altre caratteristiche peculiari', ['class' => 'col-sm-2 control-label']) !!}
                  <div class="col-sm-5">
                    {!! Form::textarea('peculiarita', isset($info) && $info->peculiarita ? $info->peculiarita : null, ['class' => 'form-control','disabled']) !!}
                  </div>
                </div>
            </div>
  
          @else

            <div class="row peculiarita">
              <div class="form-group">
                {!! Form::label('peculiarita', 'Altre caratteristiche peculiari', ['class' => 'col-sm-2 control-label', 'style' => 'margin-top: 70px;']) !!}

                <div class="col-sm-5">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    @foreach (Langs::getAll() as $lang)
                      <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                        <a class="tab_lang" data-id="{{ $lang }}" href="#peculiarita_{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                          <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                  <div class="tab-content">
                    @foreach (Langs::getAll() as $lang)
                      @php
                        if ($lang == 'it') {
                          $field = 'peculiarita';
                        
                        } else {
                        
                          $field = 'peculiarita_'.$lang;
                        }
                      @endphp
                      <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="peculiarita_{{ $lang }}">
                        {!! Form::textarea($field, isset($info) && $info->$field ? $info->$field : null, ['class' => 'form-control']) !!}
                      </div>
                    @endforeach
                  </div>
                  
                </div>

              </div>
            </div>

          @endif


          
                  
          <div class="row suggerimento">
              <div class="form-group">
                {!! Form::label('suggerimento', 'Hai qualcosa da aggiungere ?', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">

                    {!! Form::text('suggerimento', isset($info) && $info->suggerimento ? $info->suggerimento : null, ['class' => 'form-control']) !!}

                </div>
              </div>
          </div>

      </div> {{-- /panel-body --}}
    </div> {{-- panel-default --}}

    
    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>

        
    {!! Form::close() !!}


    

    </div>
  </div>

@endsection

@section('onbodyclose')
  <script type="text/javascript">
  
    jQuery(document).ready(function($ = jQuery) {

        $("#aperto_annuale").change(function(){
           if($(this).is(":checked")) {
              $("#aperto_dal").prop('disabled', true);
              $("#aperto_al").prop('disabled', true);
           } 
           else {
              $("#aperto_dal").prop('disabled', false);
              $("#aperto_al").prop('disabled', false);
              
           }
        }).change();

        $(".area_fitness").click(function(){
           if($(this).is(":checked") && $(this).val() == 1) {
              $("#sup_area_fitness").prop('disabled', false);
           } 
           else {
              $("#sup_area_fitness").val('');
              $("#sup_area_fitness").prop('disabled', true);              
           }
        }).change();

        $("#in_hotel").change(function(){
           if($(this).is(":checked")) {
              $("#distanza_hotel").val('');
              $("#distanza_hotel").prop('disabled', true);
           } 
           else {
              $("#distanza_hotel").prop('disabled', false);              
           }
        }).change();

        @if (count($errors))
          $('.area_fitness').prop('checked',false);
          $("#sup_area_fitness").prop('disabled', true);
        @endif

    });

  </script>
@stop

