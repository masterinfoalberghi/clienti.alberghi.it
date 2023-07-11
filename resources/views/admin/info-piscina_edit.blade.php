@extends('templates.admin')

@section('title')
Info Piscina
@endsection

@section('content')


<div class="row">
  <div class="col-lg-12">
    
    <div class="alert alert-warning">
      <span style="font-size: 13px">
      <strong>Compila i campi richiesti per descrivere la tua piscina</strong>.<br> 
      Le informazioni verranno elaborate per essere pubblicate <strong>sulla tua scheda hotel</strong> e nell'<strong>elenco degli hotel con piscina</strong>.<br>
      Le modifiche saranno visibili appena tutti gli hotel con piscina avranno compilato le nuove informazioni. <br><strong>Se non intendi inserire questi dati</strong> (sono obbligatori solo: superficie, altezza e posizione) <strong>la tua struttura conserverà le informazioni sulla piscina nella scheda, ma non potrà essere mostrata nell'elenco insieme alle altre</strong>.<br>
      Se hai <strong>più di una piscina</strong>, inserisci le informazioni principali che riguardano entrambe. Ci occuperemo di descrivere ciascuna piscina nel dettaglio nel testo descrittivo del tuo hotel.
      </span>
    </div>
    

    <div>
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/azzera-info-piscina', 'method' => 'POST']) !!}
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
              Confermi di voler azzerare in maniera definitiva tutti campi della sezione piscina?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
              <button type="button" class="btn btn-primary" onclick="jQuery('#record_delete').submit();">Conferma</button>
            </div>
          </div>
        </div>
      </div>
    </div>


    {!! Form::open(['role' => 'form', 'url'=>['admin/info-piscina'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}

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
          {!! Form::label('h', 'altezza unica (cm.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('h', isset($info) && $info->h > 0 ? $info->h : null, ['id' => 'h', 'class' => 'form-control misure', 'maxlength' => '3']) !!}
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('h_min', 'altezza min. (cm.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('h_min', isset($info) && $info->h_min > 0 ? $info->h_min : null, ['id' => 'h_min','class' => 'form-control misure', 'maxlength' => '3']) !!}
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('h_max', 'altezza max. (cm.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('h_max', isset($info) && $info->h_max > 0 ? $info->h_max : null, ['id' => 'h_max', 'class' => 'form-control misure', 'maxlength' => '3']) !!}
          </div>
        </div>
        
        <div class="form-group">
          {!! Form::label('aperto_dal', 'Apertura', ['class' => 'col-md-2  col-sm-12 col-sx-12 control-label']) !!}
          
          <div class="col-md-12 col-sm-12">

          {!! Form::label('aperto_al', 'da', ['class' => 'control-label  apertura-piscina']) !!}&nbsp;
           {!! Form::select('aperto_dal',$mesi, isset($info) ? $info->aperto_dal  : null,["id" => "aperto_dal", "class"=>"mesi_select form-control"]) !!}&nbsp;&nbsp;&nbsp;
          {!! Form::label('aperto_al', 'a', ['class' => 'control-label apertura-piscina']) !!}&nbsp;
           {!! Form::select('aperto_al',$mesi, isset($info) ? $info->aperto_al  : null,["id" => "aperto_al", "class"=>"mesi_select form-control"]) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          {!! Form::label('annuale', 'oppure', ['class' => 'control-label apertura-piscina']) !!}
          {!! Form::label('aperto_annuale', 'apertura annuale', ['class' => 'control-label apertura-piscina']) !!} &nbsp;
          {!! Form::checkbox('aperto_annuale', "1", isset($info) ? $info->aperto_annuale : null, ['id' => 'aperto_annuale']) !!} 

        </div>

      </div> {{-- /panel-body --}}
    </div> {{-- panel-default --}}


    <div class="panel panel-default" data-collapsed="0">
      <div class="panel-heading"><div class="panel-title">Posizione - obbligatorio</div></div>  
      <div class="panel-body">
        
        <div class="row text-center">
          
        <div class="col-sm-2">
          <label>
            {!! Form::radio('posizione', "pos_giardino", isset($info) && $info->pos_giardino ? $info->pos_giardino : null,['id' => 'pos_giardino']) !!} 
            giardino
          </label>
        </div>
        <div class="col-sm-2">  
          <label>
            {!! Form::radio('posizione', "pos_rialzata",isset($info) && $info->pos_rialzata ? $info->pos_rialzata : null, ['id' => 'pos_rialzata']) !!} 
            piano rialzato
          </label>
        </div>
        <div class="col-sm-2">  
          <label>
            {!! Form::radio('posizione', "pos_panoramica",isset($info) && $info->pos_panoramica ? $info->pos_panoramica : null, ['id' => 'pos_panoramica']) !!} 
            panoramica sul tetto
          </label>
        </div>
        <div class="col-sm-2">  
          <label>
            {!! Form::radio('posizione', "pos_spiaggia", isset($info) && $info->pos_spiaggia ? $info->pos_spiaggia : null,['id' => 'pos_spiaggia']) !!} 
            in spiaggia
          </label>
        </div>

        <div class="col-sm-2">  
          <label>
            {!! Form::radio('posizione', "pos_interna",isset($info) && $info->pos_interna ? $info->pos_interna : null, ['id' => 'pos_interna']) !!} 
            interna
          </label>
        </div>

        <div class="col-sm-2">  
          <label>
            {!! Form::radio('posizione', "pos_esterna", isset($info) && $info->pos_esterna ? $info->pos_esterna : null,['id' => 'pos_esterna']) !!} 
            interna ed esterna
          </label>
        </div>

        
        </div>
        <div class="row suggerimento">
            <div class="form-group">
              {!! Form::label('suggerimento_posizione', 'Hai una specifica da aggiungere ?', ['class' => 'col-sm-2 control-label', 'style' => 'margin-top: 70px;']) !!}
              <div class="col-sm-5">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" >
                  @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                      <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                      </a>
                    </li>
                  @endforeach
                </ul>
                <div class="tab-content">

                  @foreach (Langs::getAll() as $lang)
                    @php
                      $field = 'suggerimento_posizione_'.$lang;
                    @endphp
                    <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}">
                    {!! Form::text($field, isset($info) && $info->$field ? $info->$field : null, ['class' => 'form-control']) !!}
                    </div>
                  @endforeach
                </div>
              </div>
              
              <div class="checkbox col-sm-5" style="margin-top: 70px;">
                <label>
                  {!! Form::checkbox('suggerimento_visibile', "1", isset($info) ? $info->suggerimento_visibile : null) !!} rendi visibile questa specifica
                </label>
              </div>
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
                {!! Form::checkbox('coperta', "1", isset($info) ? $info->coperta : null) !!} coperta
              </label>
            </div>
          </div>

          <div class="col-sm-2">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('riscaldata', "1", isset($info) ? $info->riscaldata : null) !!} riscaldata
              </label>
            </div>
          </div>

          <div class="col-sm-2">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('salata', "1", isset($info) ? $info->salata : null) !!} salata
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
                {!! Form::checkbox('idro_cervicale', "1", isset($info) ? $info->idro_cervicale : null) !!} idromassaggio cervicale
              </label>
            </div>
          </div>

          <div class="col-sm-2">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('scivoli', "1", isset($info) ? $info->scivoli : null) !!} scivoli
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
              {!! Form::checkbox('trampolino', "1", isset($info) ? $info->trampolino : null) !!} trampolino
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('aperitivi', "1", isset($info) ? $info->aperitivi : null) !!} aperitivi in piscina
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('getto_bolle', "1", isset($info) ? $info->getto_bolle : null) !!} getto di bolle
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('cascata', "1", isset($info) ? $info->cascata : null) !!} cascata d'acqua
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('musica_sub', "1", isset($info) ? $info->musica_sub : null) !!} musica subacquea
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('wi_fi', "1", isset($info) ? $info->wi_fi : null) !!} zona wi-fi
            </label>
          </div>
        </div>
        {{--  2 GRUPPO --}}
      </div> {{-- form-group --}}
    </div> {{-- row --}}

    <div class="row">
      <div class="form-group">
        {{--  3 GRUPPO --}}
        <div class="col-sm-1 col-sm-offset-1">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('pagamento', "1", isset($info) ? $info->pagamento : null) !!} a pagamento
            </label>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('salvataggio', "1", isset($info) ? $info->salvataggio : null) !!} bagnino di salvataggio
            </label>
          </div>
        </div>


        <div class="col-sm-2">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('nuoto_contro', "1", isset($info) ? $info->nuoto_contro : null) !!} nuoto controcorrente
            </label>
          </div>
        </div>

        {{--  3 GRUPPO --}}
      </div> {{-- form-group --}}
    </div> {{-- row --}}
      

    <div class="row">
      <div class="form-group">
        {!! Form::label('lettini_dispo', 'N. lettini prendisole disponibili', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-3">
          {!! Form::text('lettini_dispo', isset($info) && $info->lettini_dispo > 0 ? $info->lettini_dispo : null, ['class' => 'form-control misure', 'maxlength' => '3']) !!}
        </div>
      </div> {{-- form-group --}}
    </div> {{-- row --}}

  <div class="row">
    <div class="form-group">    
        {!! Form::label('espo_sole', 'quante ore è esposta al sole ?', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-md-1 col-sm-1">
          {!! Form::text('espo_sole', isset($info) && $info->espo_sole > 0 ? $info->espo_sole : null, ['class' => 'form-control misure', 'id' => 'espo_sole', 'maxlength' => '2']) !!}
        </div>
        {!! Form::label('oppure', 'oppure', ['class' => 'control-label caratteristica']) !!}
        {!! Form::label('espo_sole_tutto_giorno', 'tutto il giorno', ['class' => 'control-label']) !!}&nbsp;
        {!! Form::checkbox('espo_sole_tutto_giorno', "1", isset($info) ? $info->espo_sole_tutto_giorno : null, ['id' => 'espo_sole_tutto_giorno']) !!}
    </div> {{-- form-group --}}
  </div> {{-- row --}}
    
  {{-- SE SEI HOTEL E LI PUOI SOLO VEDERE LI VEDI IN ITALIANO E BASTA --}}
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
          {!! Form::label('suggerimento_caratteristica', 'Hai qualcosa da aggiungere ?', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-5">
            {!! Form::text('suggerimento_caratteristica', isset($info) && $info->suggerimento_caratteristica ? $info->suggerimento_caratteristica : null, ['class' => 'form-control']) !!}
          </div>
        </div>
    </div>

      </div> {{-- /panel-body --}}
    </div> {{-- panel-default --}}


    <div class="panel panel-default" data-collapsed="0">
      <div class="panel-heading"><div class="panel-title">Vasca bambini</div></div>  
      <div class="panel-body">    
      <div class="row">

        <div class="form-group">
          {!! Form::label('vasca_bimbi_sup', 'superficie (mq.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('vasca_bimbi_sup', isset($info) && $info->vasca_bimbi_sup > 0 ? $info->vasca_bimbi_sup : null, ['class' => 'form-control misure', 'maxlength' => '3']) !!}
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('vasca_bimbi_h', 'altezza (cm.)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
            {!! Form::text('vasca_bimbi_h', isset($info) && $info->vasca_bimbi_h > 0 ? $info->vasca_bimbi_h : null, ['class' => 'form-control', 'maxlength' => '10']) !!}
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-2 col-sm-offset-1">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('vasca_bimbi_riscaldata', "1", isset($info) ? $info->vasca_bimbi_riscaldata : null) !!} riscaldata
              </label>
            </div>
          </div>
        </div>

      </div>

      <div class="row suggerimento">
          <div class="form-group">
            {!! Form::label('suggerimento_vasca_bimbi', 'Hai qualcosa da aggiungere ?', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-5">
              {!! Form::text('suggerimento_vasca_bimbi', isset($info) && $info->suggerimento_vasca_bimbi ? $info->suggerimento_vasca_bimbi : null, ['class' => 'form-control']) !!}
            </div>
          </div>
      </div>

      </div> {{-- /panel-body --}}
    </div> {{-- panel-default --}}    

    
    <div class="panel panel-default" data-collapsed="0">
      <div class="panel-heading"><div class="panel-title">Vasca idromassaggio a parte</div></div>  
      <div class="panel-body">    
      
      <div class="row">
          <div class="form-group">         
            {!! Form::label('vasca_idro_n_dispo', 'N. vasche disponibili', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-3">
              {!! Form::text('vasca_idro_n_dispo', isset($info) && $info->vasca_idro_n_dispo > 0 ? $info->vasca_idro_n_dispo : null, ['class' => 'form-control misure', 'maxlength' => '3']) !!}
            </div>
          </div> {{-- form-group --}}
          <div class="form-group">         
            {!! Form::label('vasca_idro_posti_dispo', 'N. posti disponibili', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-3">
              {!! Form::text('vasca_idro_posti_dispo', isset($info) && $info->vasca_idro_posti_dispo > 0 ? $info->vasca_idro_posti_dispo : null, ['class' => 'form-control misure', 'maxlength' => '3']) !!}
            </div>
          </div> {{-- form-group --}}
      </div>

      <div class="row text-center">
        
      <div class="col-sm-2">
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_giardino", isset($info) && $info->vasca_idro_pos_giardino ? $info->vasca_idro_pos_giardino : null,['id' => 'vasca_idro_pos_giardino']) !!} 
          giardino
        </label>
      </div>
      <div class="col-sm-2">  
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_rialzata",isset($info) && $info->vasca_idro_pos_rialzata ? $info->vasca_idro_pos_rialzata : null, ['id' => 'vasca_idro_pos_rialzata']) !!} 
          piano rialzato
        </label>
      </div>
      <div class="col-sm-2">  
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_panoramica",isset($info) && $info->vasca_idro_pos_panoramica ? $info->vasca_idro_pos_panoramica : null, ['id' => 'vasca_idro_pos_panoramica']) !!} 
          panoramica sul tetto
        </label>
      </div>
      <div class="col-sm-2">  
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_spiaggia", isset($info) && $info->vasca_idro_pos_spiaggia ? $info->vasca_idro_pos_spiaggia : null,['id' => 'vasca_idro_pos_spiaggia']) !!} 
          in spiaggia
        </label>
      </div>

      <div class="col-sm-2">  
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_interna",isset($info) && $info->vasca_idro_pos_interna ? $info->vasca_idro_pos_interna : null, ['id' => 'vasca_idro_pos_interna']) !!} 
          interna
        </label>
      </div>

      <div class="col-sm-2">  
        <label>
          {!! Form::radio('posizione_vasca', "vasca_idro_pos_esterna", isset($info) && $info->vasca_idro_pos_esterna ? $info->vasca_idro_pos_esterna : null,['id' => 'vasca_idro_pos_esterna']) !!} 
          esterna
        </label>
      </div>

      
      </div>


      <div class="row text-center" style="margin-top: 20px;">
          
          <div class="col-sm-2">
                <label>
                  {!! Form::checkbox('vasca_idro_riscaldata', "1", isset($info) ? $info->vasca_idro_riscaldata : null) !!} riscaldata
                </label>
          </div>
          
          <div class="col-sm-2">
                <label>
                  {!! Form::checkbox('vasca_pagamento', "1", isset($info) ? $info->vasca_pagamento : null) !!} a pagamento
                </label>
          </div>

      </div>
      
      <div class="row suggerimento">

          <div class="form-group">
            {!! Form::label('suggerimento_vasca_idro', 'Hai qualcosa da aggiungere ?', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-5">
              {!! Form::text('suggerimento_vasca_idro', isset($info) && $info->suggerimento_vasca_idro ? $info->suggerimento_vasca_idro : null , ['class' => 'form-control']) !!}
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

        $("#espo_sole_tutto_giorno").change(function(){
            if($(this).is(":checked")) {
               $("#espo_sole").prop('disabled', 'disabled');
            } 
            else {
               $("#espo_sole").prop('disabled', false);
               
            }
        }).change();


        $("#h").blur(function(){
          if ( $(this).val().length > 0 ) {
            if ( $("#h_min").val().length == 0 ) {
             $("#h_min").prop('disabled', 'disabled');
           }
            if ( $("#h_max").val().length == 0 ) {   
             $("#h_max").prop('disabled', 'disabled');
           }
          } else {
            $("#h_min").prop('disabled', false);
            $("#h_max").prop('disabled', false);
          }
        }).blur();


        $("#h_min").blur(function(){
          if ( $(this).val().length > 0 ) {
            if ( $("#h").val().length == 0 ) {
             $("#h").prop('disabled', 'disabled');
            }
          } else {
            if ($("#h_max").val().length == 0) {
              $("#h").prop('disabled', false);
            }
          }
        }).blur();

        $("#h_max").blur(function(){
          if ( $(this).val().length > 0 ) {
            if ( $("#h").val().length == 0 ) {
               $("#h").prop('disabled', 'disabled');
            }
          } else {
            if ($("#h_min").val().length == 0) {
                $("#h").prop('disabled', false);
              }
          }
        }).blur();

    });

  </script>
@stop

