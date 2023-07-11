@extends('templates.admin')

@section('title')
  @if (isset($bambino_gratis))
    Modifica Offerta Bambini Gratis "Top"
  @else
    Nuova Offerta Bambini Gratis "Top"
  @endif
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">

    <p class="message-box info">
      LE NOTE DELLE OFFERTE VERRANNO <strong>AUTOMATICAMENTE TRADOTTE</strong> MEDIANTE IL SERVIZIO GOOGLE TRANSLATE. LE PAROLE CHE <strong>NON VUOI CHE SIANO TRADOTTE</strong> DEVONO ESSERE COMPRESE TRA IL SIMBOLO # (ES: Benvenuti all'#Hotel Sabrina Rimini#, offerte per #Mirabilandia#) <br><br> <strong>P.S.</strong> <span style="text-decoration: underline; font-weight: bold;">Verifica sempre la correttezza delle traduzioni automatiche</span> e ricorda che le puoi cambiare in ogni momento selezionando "Offerte" nel menu a sinistra e scegliendo il pulsante MODIFICA sull'offerta che vuoi editare
    </p>

    @if (isset($bambino_gratis))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/vetrine-bg-top/'.$bambino_gratis->id, 'method' => 'DELETE']) !!}
        {{-- questo hidden lo uso per sapere se DOPO IL DELETE devo fare un redirect negli attivi o negli archiviati --}}
        <input type="hidden" name="attivo" value="{{!$bambino_gratis->attivo}}">
      {!! Form::close() !!}
    @endif

   @if (isset($bambino_gratis))
      {!! Form::model($bambino_gratis, ['role' => 'form', 'route'=>['vetrine-bg-top.update',$bambino_gratis->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'form_modifica_offerta','files' => true]) !!}
      {{-- passo $bambino_gratis->id per la validazione--}}
      <input type="hidden" name="offerta_id" value="{{$bambino_gratis->id}}">
     
      {{-- questo hidden lo uso solo nelle offerte archiviate e mi dice se devo fare "salva e pubblica" --}}
      <input type="hidden" name="salva_e_pubblica" id="salva_e_pubblica" value="0">

    @else
      {!! Form::open(['role' => 'form', 'route'=>['vetrine-bg-top.store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
    @endif
      
      {{-- campo usato dalla validazione dell'eta --}}
      <input type="hidden" name="eta_minima" value="0">
      
      <div class="form-group">
        {!! Form::label('mese', 'Mesi validità', ['class' => 'col-sm-2 control-label']) !!}
        @foreach ($mesi as $anno => $mese)
          <div class="col-sm-2">
              {{$anno}}
             {!! Form::select('mese'.$anno.'[]',$mese,isset($bambino_gratis) ? explode(',', $bambino_gratis->mese)  : null,["multiple", "id" => "mesi_select", "class"=>"form-control"]) !!}
          </div>
        @endforeach
      </div>
	  
	   <div class="form-group">
	     
  <div class="form-group">
          
          {!! Form::label('valido_dal', 'Per soggiorni validi dal / al ', ['class' => 'col-sm-2 control-label']) !!}
      
          <div class="col-sm-6">
            {!! Form::text('valido_dal',  isset($bambino_gratis) ? $bambino_gratis->valido_dal->format('d/m/Y') : null, ['id' => 'valido_dal', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}          
            &rarr;
            {!! Form::text('valido_al',  isset($bambino_gratis) ? $bambino_gratis->valido_al->format('d/m/Y')  : null, ['id' => 'valido_al',  'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
          </div>
    
        {{-- SCADENZA --}}
         <span style="color:red;"> 
            
          {!! Form::label('scadenza_al', 'Reminder ', ['class' => 'col-sm-1 control-label']) !!}
          <div class="col-sm-2">  
          {!! Form::text('scadenza_al', ( isset($offerta) && !is_null($offerta->scadenza) && Utility::isValidDate($offerta->scadenza->scadenza_al->format('Y')))  ? $offerta->scadenza->scadenza_al->format('d/m/Y') : "", ['id' => 'scadenza_al', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
          </div>

        </span>
        {{-- \ SCADENZA --}}
                
        </div>

 
     


      @php
        if (isset($bambino_gratis)) 
          {
          $compiuti_selected = $bambino_gratis->anni_compiuti;
          } 
        else 
          {
          $compiuti_selected = 'compiuti';
          }
      @endphp

	   <div class="form-group">
          <div class="col-sm-2 control-label">
            da 0 anni fino a
          </div>             
          <div class="col-sm-10">
            {!! Form::select('fino_a_anni', [1 => 1, 2 => 2 , 3 => 3 , 4 => 4, 5 => 5, 6 => 6, 7 => 7 , 8 =>8 , 9 => 9 , 10 => 10 , 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18], null, ['class' => 'form-control', 'style' => 'width:150px;display:inline-block;']) !!}  
            &nbsp;&nbsp;anni &nbsp;&nbsp;
            {!! Form::select('anni_compiuti', ['compiuti' => 'compiuti','non compiuti' => 'non compiuti'], $compiuti_selected, ['class' => 'form-control', 'style' => 'width:150px;display:inline-block;']) !!}
			&nbsp; @if (isset($bambino_gratis)) ( {{$bambino_gratis->fino_a_mesi}} mesi ) @endif
          </div>
        </div>

    
        
        @if (isset($bambino_gratis))
          <div class="form-group">
            <div class="col-lg-12">
             <!-- Nav tabs -->
		      <ul class="nav nav-tabs col-lg-offset-2" role="tablist" >
		        @foreach (Langs::getAll() as $lang)
		          <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
		            <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
		              <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
		            </a>
		          </li>
		        @endforeach
		      </ul>
		            
                 <div class="tab-content">
                {{-- visualizzo in tutte lingue LE NOTE --}}
                @foreach (Langs::getAll() as $lang)
                  <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            

                    @foreach ($bambino_gratisLingua[$lang] as $bambino_gratis_lingua)
                      <div class="form-group">
                         {!! Form::label('note', 'Note', ['class' => 'col-sm-2 control-label']) !!}
                         
                         <div class="col-sm-10">
                         
                         {!! Form::textarea('note'.$lang.$bambino_gratis->id, $bambino_gratis_lingua['note'], ['id' => 'note'.$lang.$bambino_gratis->id, 'class' => 'form-control', "rows" => 15, "placeholder" => "Ad esempio: Solo se accompagnati da 2 adulti..., Escluso periodo alta stagione dal 20/07 al 18/08 ..."]) !!}

                         </div>
                      </div>

                      <div class="form-group">
                          {!! Form::label('pagina_id'.$lang.$bambino_gratis->id, 'pagina associata', ['class' => 'col-sm-2 control-label ipo']) !!}
                          <div class="col-sm-4">
                            
                             {!! Form::select('pagina_id'.$lang.$bambino_gratis->id,['0' => '']+$pagine[$lang]->toArray(), $bambino_gratis_lingua['pagina_id'], ["class"=>"form-control"]) !!}
                          </div>
                      </div>

                    @endforeach

                  </div>

                @endforeach
                
                <div class="form-group">
                    {!! Form::label('traduci', 'Traduci anche le lingue', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-4">
                       {!! Form::checkbox('traduci', "1", null,['id' => 'traduci']) !!} 
                    </div>
                </div>
              
              </div> {{-- .tab-content --}}
          </div> {{-- .col-lg-12 --}}
        </div> {{-- .row --}}
        @else
          <div class="form-group">
             {!! Form::label('note', 'Note', ['class' => 'col-sm-2 control-label']) !!}
             <div class="col-sm-10">
             {!! Form::textarea("note", null, ["class" => "form-control", "rows" => 15, "placeholder" => "Ad esempio: Solo se accompagnati da 2 adulti..., Escluso periodo alta stagione dal 20/07 al 18/08 ..."]) !!}
             </div>
          </div>
          <div class="form-group">
              {!! Form::label('pagina_id', 'pagina associata', ['class' => 'col-sm-2 control-label ipo']) !!}
            <div class="col-sm-4">
               {!! Form::select('pagina_id',['0' => '']+$pagine->toArray(), null, ["class"=>"form-control"]) !!}
            </div>
          </div>
        @endif
        
        
        <div class="form-group">
          <div class="col-sm-12">
            {{-- SE sono in un'offerta ARCHIVIATA --}}
            @if (isset($bambino_gratis) && !$bambino_gratis->attivo)

                <button type="submit" class="btn btn-primary">Salva e archivia</button>
                <button type="button" class="btn btn-primary" onclick="jQuery('#salva_e_pubblica').val(1); jQuery('#form_modifica_offerta').submit();">Salva e pubblica</button>
                <button type="button" onclick="jQuery('#modal-confirm-delete').modal('show');" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                <div class="modal fade" id="modal-confirm-delete" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Eliminazione record</h4>
                      </div>
                      <div class="modal-body">
                        Confermi di voler eliminare in maniera definitiva ed irreversibile il record?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" onclick="jQuery('#record_delete').submit();">Conferma</button>
                      </div>
                    </div>
                  </div>
                </div>
            @else
            
                @include('templates.admin_inc_record_buttons')
                        
            @endif
          </div>
        </div>


      </div>

    {!! Form::close() !!}

  </div>
</div>

@endsection

@section('onbodyclose')

  <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
  <script type="text/javascript" src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>

  <script type="text/javascript">

    jQuery(document).ready(function($) {
      
      /**
       * Data picker
       */

      $("#valido_dal").datepicker({ 

        format: 'dd/mm/yyyy', 
        weekStart:1,
        startDate: moment().format("D/M/Y"),
        language: "it",
        orientation: "bottom left",
        todayHighlight: true,
        autoclose: true

      }).on("changeDate", function(e) {

        var data_dal = moment($("#valido_dal").datepicker("getDate"));
        var data_al = moment($("#valido_al").datepicker("getDate"));

        if (data_al.isBefore(data_dal)) {

          data_al = data_dal.add(1, 'd');
          $("#valido_al").datepicker("setDate", data_al.format("D/M/Y") );

        }

        $("#valido_al").datepicker("setStartDate", moment(e.date).format("D/M/Y") );
        $("#valido_dal").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
        $("#valido_al").datepicker("setRange", [ data_dal, data_al]);
        $("#valido_al").datepicker("show");

      });

      $("#valido_al").datepicker({ 

        format: 'dd/mm/yyyy', 
        weekStart:1,
        startDate: moment().format("D/M/Y"),
        language: "it",
        orientation: "bottom left",
        todayHighlight: true,
        autoclose: true

      }).on("changeDate", function(e) {

        var data_dal = moment($("#valido_dal").datepicker("getDate"));
        var data_al = moment($("#valido_al").datepicker("getDate"));

        $("#valido_dal").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
        $("#valido_al").datepicker("setRange", [ data_dal, data_al]); 

      });

      $("#scadenza_al").datepicker({ 

        format: 'dd/mm/yyyy', 
        weekStart:1,
        startDate: moment().format("D/M/Y"),
        language: "it",
        orientation: "bottom left",
        todayHighlight: true,
        autoclose: true

      });

    });

  </script>

@endsection