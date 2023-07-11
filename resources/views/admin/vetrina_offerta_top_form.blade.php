@extends('templates.admin')

@section('title')
  @if (isset($offerta))
    Modifica Offerta "Top"
  @else
    Nuova Offerta "Top"
  @endif
@endsection



@section('content')
<div class="row">
  <div class="col-lg-12">

    <p class="message-box info">
      LE OFFERTE VERRANNO <strong>AUTOMATICAMENTE TRADOTTE</strong> MEDIANTE IL SERVIZIO GOOGLE TRANSLATE. LE PAROLE CHE <strong>NON VUOI CHE SIANO TRADOTTE</strong> DEVONO ESSERE COMPRESE TRA IL SIMBOLO # (ES: Benvenuti all'#Hotel Sabrina Rimini#, offerte per #Mirabilandia#) <br><br> <strong>P.S.</strong> <span style="text-decoration: underline; font-weight: bold;">Verifica sempre la correttezza delle traduzioni automatiche</span> e ricorda che le puoi cambiare in ogni momento selezionando "Offerte" nel menu a sinistra e scegliendo il pulsante MODIFICA sull'offerta che vuoi editare
    </p>

    @if (isset($offerta))
    
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/vetrine-offerte-top/'.$offerta->id, 'method' => 'DELETE']) !!}
        {{-- questo hidden lo uso per sapere se DOPO IL DELETE devo fare un redirect negli attivi o negli archiviati --}}
        <input type="hidden" name="attivo" value="{{!$offerta->attivo}}">
      {!! Form::close() !!}
      
    @endif

   @if (isset($offerta))
   
      {!! Form::model($offerta, ['role' => 'form', 'route'=>['vetrine-offerte-top.update',$offerta->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'form_modifica_offerta','files' => true]) !!}
      {{-- passo $offerta->id per la validazione--}}
      <input type="hidden" name="offerta_id" value="{{$offerta->id}}">

     
      {{-- questo hidden lo uso solo nelle offerte archiviate e mi dice se devo fare "salva e pubblica" --}}
      <input type="hidden" name="salva_e_pubblica" id="salva_e_pubblica" value="0">

    @else
      {!! Form::open(['role' => 'form', 'route'=>['vetrine-offerte-top.store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
    @endif

      <div class="form-horizontal">
      	  
		  @if (!isset($offerta))
		  
		  <div class="form-group">
              {!! Form::label('tipo', 'Tipologia', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-4">
                 {!! Form::select('tipo',$tipi, null , ["class"=>"form-control"]) !!}
              </div>
          </div>
		  
          <div class="form-group">
              <label for="titolo" class="col-sm-2 control-label">Titolo <span style="font-size=1px; margin:0 10px;"><input readonly type="text" name="countdown_titolo" size="3" value="{{$LIMIT_TITOLO}}" class="caratteri_rimasti">caratteri rimasti</span></label>
              <div class="col-sm-10">
                 {!! Form::text('titolo', null, ['id' => 'titolo', 'class' => 'form-control', 'onKeyDown' => "limitText(this.form.titolo,this.form.countdown_titolo,$LIMIT_TITOLO)", 'onKeyUp'=> "limitText(this.form.titolo,this.form.countdown_titolo,$LIMIT_TITOLO)"]) !!}
              </div>
          </div>
		 
		 @else
		 		 
		 <div class="form-group">
              {!! Form::label('tipo', 'Tipologia', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-4">
                 {!! Form::select('tipo',$tipi, $offerta->tipo , ["class"=>"form-control"]) !!}
              </div>
          </div>
                    
        @endif

        <div class="form-group">
          {!! Form::label('mese', 'Mesi validità', ['class' => 'col-sm-2 control-label']) !!}
          @foreach ($mesi as $anno => $mese)
            <div class="col-sm-2">
               {{$anno}}
               {!! Form::select('mese'.$anno.'[]',$mese,isset($offerta) ? explode(',', $offerta->mese)  : null,["multiple", "id" => "mesi_select", "class"=>"form-control"]) !!}
            </div>
          @endforeach
        </div>

        <div class="form-group">
          
          {!! Form::label('valido_dal', 'Per soggiorni validi dal / al ', ['class' => 'col-sm-2 control-label']) !!}
      
          <div class="col-sm-6">
            {!! Form::text('valido_dal',  (isset($offerta) && Utility::isValidYear($offerta->valido_dal->format('Y')))  ? $offerta->valido_dal->format('d/m/Y') : "", ['id' => 'valido_dal', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}          
            &rarr;
            {!! Form::text('valido_al',   (isset($offerta) && Utility::isValidYear($offerta->valido_al->format('Y')))   ? $offerta->valido_al->format('d/m/Y')  : "", ['id' => 'valido_al',  'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
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
      
        <div class="prenotaprima">
          <div class="form-group">

            {!! Form::label('prenota_entro', 'Prenota entro', ['class' => 'col-sm-2 control-label']) !!}

            <div class="col-sm-2">  
              {!! Form::text('prenota_entro', isset($offerta) && Utility::isValidDate($offerta->prenota_entro) ? $offerta->prenota_entro->format('d/m/Y') : '', ['id' => 'prenota_entro', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
            </div>
          </div>
        </div>
       
       
        <div class="offerta">
        <div class="form-group">
            
           
            {!! Form::label('prezzo_a_partire_da', 'Il prezzo scelto è a partite da', ['class' => 'col-sm-2 control-label']) !!}
            
            <div class="col-sm-10">
	            
               {!! Form::radio('prezzo_a_partire_da', "1", isset($offerta) ? $offerta->prezzo_a_partire_da : null,['id' => 'prezzo_a_partire_da']) !!} Sì
               &nbsp;&nbsp;&nbsp;
               {!! Form::radio('prezzo_a_partire_da', "0", isset($offerta) ? $offerta->prezzo_a_partire_da : 1,['id' => 'prezzo_a_partire_da']) !!} No
               
            </div>
        
        </div>
        
        <div class="form-group" @if($hotel->tipologia_id != "2") style="display:none;" @endif>
	        
            {!! Form::label('label_costo_a_persona', 'Il prezzo scelto è a "persona al giorno?"', ['class' => 'col-sm-2 control-label']) !!} 
            
            <div class="col-sm-10">
	            {!! Form::radio('label_costo_a_persona', "1", isset($offerta) ? $offerta->label_costo_a_persona : 1,['id' => 'label_costo_a_persona']) !!} Sì &nbsp;&nbsp;&nbsp;
	            {!! Form::radio('label_costo_a_persona', "0", isset($offerta) ? $offerta->label_costo_a_persona : null,['id' => 'label_costo_a_persona']) !!} No
	            <span class="dal" style="float:none; display: inline; "> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;* Se marcato come "No" il prezzo non è più da considerarsi a persona al giorno ma per tutte le persone e per i giorni dell'offerta</span>
            </div>
        
        </div>
       
       <div class="form-group">
	         
            {!! Form::label('prezzo_a_persona', 'Prezzo €', ['class' => 'col-sm-2 control-label']) !!}
            
            <div class="col-sm-3">
	            
               {!! Form::text('prezzo_a_persona', isset($offerta) ? $offerta->prezzo_a_persona : null, ['class' => 'form-control', 'id' => 'prezzo_a_persona']) !!}
               
            </div>
            
			<div class="col-sm-7">
				<span style="line-height: 29px; "> @if($hotel->tipologia_id != "2") Il prezzo &egrave; da considerarsi al giorno a persona @endif</span>
			</div>
            
         </div>
        </div>
         
        <div class="prenotaprima">
	        <div class="form-group">	
		          
	              {!! Form::label('perc_sconto', 'Per avere uno sconto % del', ['class' => 'col-sm-2 control-label']) !!}
	              <div class="col-md-3">
	                 {!! Form::text('perc_sconto', null, ['class' => 'form-control', 'id' => 'perc_sconto','maxlength' => 2]) !!}
	              </div>
	              
	        </div>
        </div>
	<div class="formula">
		<div class="form-group">
			 
            {!! Form::label('formula', 'Formula di trattamento', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-3">
               {!! Form::select('formula',$formule,isset($offerta) ? $offerta->formula : null,["class"=>"form-control"]) !!}
            </div>
            
        </div>
      </div>
		
		<div class="form-group">
            {!! Form::label('per_persone', 'Numero minimo adulti', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-3">
               {!! Form::select('per_persone',['0' => '']+Utility::offerte_numero_persone_select(),isset($offerta) ? $offerta->per_persone : 0,["class"=>"form-control"]) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('per_giorni', 'Numero minimo notti di permanenza', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-3">
               {!! Form::select('per_giorni',['0' => '', '1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15'],isset($offerta) ? $offerta->per_giorni : 0,["class"=>"form-control"]) !!}
            </div>
        </div>
        
        {{--  SE SONO MACRO BELLARIA/IGEA-MARINA DEVO POTER NASCONDERE LE VOT DALLA SCHEDA--}}
        @if ($macrolocalita_id == 6)
          <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
              <div class="checkbox">
                <label class="label-alert">
                  {!! Form::checkbox('nascondi_in_scheda', "1", null) !!} Nascondi questa offerta NELLA SCHEDA HOTEL
                </label>
              </div>
            </div>
          </div>
        @else
          {!! Form::hidden('nascondi_in_scheda', 0) !!}
        @endif
  
        @if (isset($offerta))
      
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
	                {{-- visualizzo in tutte lingue TITOLO e TESTO --}}
	                @foreach (Langs::getAll() as $lang)
	                  <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            
	
	                    <?php 
	                    $LIMIT_TITOLO_LANG =  'LIMIT_TITOLO_'.$lang; 
	                    $LIMIT_TESTO_LANG =  'LIMIT_TESTO_'.$lang;
	                    ?>
	
	                    @foreach ($offertaLingua[$lang] as $offerta_lingua)
	                      <?php 
	
	                      // il reale valore del limite da inserire del titolo/testo è quello di default - quello GIA' INSERITO
	                      $REAL_LIMIT_TITOLO_LANG = $$LIMIT_TITOLO_LANG - strlen($offerta_lingua['titolo']);
	                      $REAL_LIMIT_TESTO_LANG = $$LIMIT_TESTO_LANG - strlen($offerta_lingua['testo']);
	
	                      $js_onKeyTitolo_args = ['1' => 'this.form.titolo'.$lang.$offerta->id, '2' => 'this.form.countdown_titolo'.$lang, '3' => $$LIMIT_TITOLO_LANG];
	                      $js_onKeyTitolo = 'limitText('.$js_onKeyTitolo_args[1].','.$js_onKeyTitolo_args[2].','.$js_onKeyTitolo_args[3].')';
	
	                      $js_onKeyTesto_args = ['1' => 'this.form.testo'.$lang.$offerta->id, '2' => 'this.form.countdown_testo'.$lang, '3' => $$LIMIT_TESTO_LANG];
	                      $js_onKeyTesto = 'limitText('.$js_onKeyTesto_args[1].','.$js_onKeyTesto_args[2].','.$js_onKeyTesto_args[3].')';
	
	                      ?>
	                      <div class="form-group">
	                          <label for="titolo{{$lang.$offerta->id}}" class="col-sm-2 control-label">Titolo<span style="font-size=1px; margin:0 10px;"><input readonly type="text" name="countdown_titolo{{$lang}}" id="countdown_titolo{{$lang}}" size="3" value="{{$REAL_LIMIT_TITOLO_LANG}}" class="caratteri_rimasti">caratteri rimasti</span></label>
	                          <div class="col-sm-10">
	                            {!! Form::text('titolo'.$lang.$offerta->id, $offerta_lingua['titolo'], ['id' => 'titolo'.$lang.$offerta->id, 'class' => 'form-control', 'onKeyDown' => $js_onKeyTitolo, 'onKeyUp'=> $js_onKeyTitolo ]) !!}
	                          </div>
	                      </div>
	                      
	                      <div class="form-group">
	                        <label for="testo{{$lang.$offerta->id}}" class="col-sm-2 control-label">Testo dell'offerta<span style="font-size=1px; margin:0 10px;"><input readonly type="text" name="countdown_testo{{$lang}}" id="countdown_testo{{$lang}}" size="3" value="{{$REAL_LIMIT_TESTO_LANG}}" class="caratteri_rimasti">caratteri rimasti</span></label>
	                        <div class="col-sm-10">
	                           {!! Form::textarea('testo'.$lang.$offerta->id, $offerta_lingua['testo'], ['id' => 'testo'.$lang.$offerta->id, 'class' => 'form-control', "rows" => 15, 'onKeyDown' => $js_onKeyTesto, 'onKeyUp'=> $js_onKeyTesto ]) !!}
	                        </div>
	                      </div>
	
	                      <div class="form-group">
	                          {!! Form::label('pagina_id'.$lang.$offerta->id, 'pagina associata', ['class' => 'col-sm-2 control-label']) !!}
	                          <div class="col-sm-4">
	                             {!! Form::select('pagina_id'.$lang.$offerta->id,['0' => '']+$pagine[$lang]->toArray(), $offerta_lingua['pagina_id'], ["class"=>"form-control ipo"]) !!}
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
	               
	              </div>
	          </div> {{-- .col-lg-12 --}}
	        </div> {{-- .row --}}
	        
        @else
        
          <div class="form-group">
            <label for="testo" class="col-sm-2 control-label">Testo dell'offerta<span style="font-size=1px; margin:0 10px;"><input readonly type="text" name="countdown_testo" size="3" value="{{$LIMIT_TESTO}}" class="caratteri_rimasti">caratteri rimasti</span></label>
            <div class="col-sm-10">
               {!! Form::textarea('testo', null, ['id' => 'testo', 'class' => 'form-control', "rows" => 15, 'onKeyDown' => "limitText(this.form.testo,this.form.countdown_testo,$LIMIT_TESTO)", 'onKeyUp'=> "limitText(this.form.testo,this.form.countdown_testo,$LIMIT_TESTO)"]) !!}
            </div>
          </div>
          <div class="form-group">
              {!! Form::label('pagina_id', 'pagina associata', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
               {!! Form::select('pagina_id',$pagine, null, ["class"=>"form-control ipo"]) !!}
            </div>
          </div>
          
        @endif
        
        <div class="form-group">
          <div class="col-sm-12">
            {{-- SE sono in un'offerta ARCHIVIATA --}}
            @if (isset($offerta) && !$offerta->attivo)

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

@section('onheadclose')
  
  <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
  <script type="text/javascript" src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>

  <style>
		.prenotaprima { display:none; }
	</style>
	
@endsection
@section('onbodyclose')
  

<script type="text/javascript">

    jQuery(document).ready(function($) {

      $("#tipo").change(function () {

        if ($(this).val() == "prenotaprima") {

          $(".prenotaprima").show();
          $(".formula").hide();

        } else {

          $(".prenotaprima").hide();
          $(".formula").show();

        }

        if ($(this).val() == "offerta" || $(this).val() == "lastminute") {
            $(".offerta").show();
        } else {
            $(".offerta").hide();
        }

      }).trigger("change");

      $("#prezzo_a_persona").keydown(function(event) {
      
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 45) {
          return;
        } else {
          if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault(); 
          }   
        }
      });

      $("#perc_sconto").keydown(function(event) {
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||  (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 45){
          return;
        } else {
          if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault(); 
          }   
        }
      });

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

      $("#prenota_entro").datepicker({ 

        format: 'dd/mm/yyyy', 
        weekStart:1,
        startDate: moment().format("D/M/Y"),
        language: "it",
        orientation: "bottom left",
        todayHighlight: true,
        autoclose: true

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


function limitText(limitField, limitCount, limitNum) {
  if (limitField.value.length > limitNum) {
    limitField.value = limitField.value.substring(0, limitNum);
  } else {
    limitCount.value = limitNum - limitField.value.length;
  }
}

function limitTextOnSubmit(limitField, limitCount, limitNum) {
    if (limitField.value.length > limitNum) {
  limitField.value = limitField.value.substring(0, limitNum);
  } else {
    limitCount.value = limitNum - limitField.value.length;
  }
}

</script>


  
  @if (isset($offerta))
    
    <script type="text/javascript">
    jQuery(document).ready(function() {  
      
      @foreach (Langs::getAll() as $lang)
        <?php 
        $LIMIT_TITOLO_LANG =  'LIMIT_TITOLO_'.$lang; 
        $LIMIT_TESTO_LANG =  'LIMIT_TESTO_'.$lang;
        ?>
        limitText(document.getElementById('titolo{{$lang}}{{$offerta->id}}'), document.getElementById('countdown_titolo{{$lang}}'), {{$$LIMIT_TITOLO_LANG}});
        limitText(document.getElementById('testo{{$lang}}{{$offerta->id}}'), document.getElementById('countdown_testo{{$lang}}'), {{$$LIMIT_TESTO_LANG}});      
      @endforeach
    
    });   
 
    </script>
  @endif

@endsection


 