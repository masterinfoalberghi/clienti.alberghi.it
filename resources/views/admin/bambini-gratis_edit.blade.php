@extends('templates.admin')

@section('title')
  @if ($data["record"]->exists)
    Modifica Offerta Bambini Gratis @if (!$data["record"]->attivo) <em>archiviata</em>@endif
  @else
    Nuovo Offerta Bambini Gratis
  @endif
@endsection

@section('content')

<div class="row">
  <div class="col-lg-12">

    <h4>Indica (se vi sono) i periodi in cui i bambini soggiornano gratis nel tuo hotel, l'età e le note dell'offerta</h4>

    @php
      $compiuti_selected = 'compiuti';
    @endphp
    @if ($data["record"]->exists)
      @php
      $compiuti_selected = $data["record"]->anni_compiuti;  
      @endphp
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/bambini-gratis/delete', 'method' => 'POST']) !!}
        <input type="hidden" name="id" value="<?=$data["record"]->id ?>">
      {!! Form::close() !!}
    @endif

    {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/bambini-gratis/store', 'method' => 'POST', 'id' => 'form_modifica_offerta']) !!}

      {!! csrf_field() !!}

      <input type="hidden" name="id" value="<?=($data["record"]->exists ? $data["record"]->id : 0)?>">
      {{-- campo usato dalla validazione dell'eta --}}
      <input type="hidden" name="eta_minima" value="0">

      <input type="hidden" name="solo_2_adulti" value="0">

      
      <input type="hidden" name="salva_e_pubblica" id="salva_e_pubblica" value="0">


      {{-- questo campo lo uso er archivare l'offerta dal metodo OfferteController@update --}}
      <input type="hidden" name="archiviazione" id="archiviazione" value="0">

      <div class="form-horizontal">
        <div class="form-group">
          <div class="col-sm-2 control-label">
            da 0 anni fino a
          </div>             
          <div class="col-sm-10">
            {!! Form::select('fino_a_anni', [1 => 1, 2 => 2 , 3 => 3 , 4 => 4, 5 => 5, 6 => 6, 7 => 7 , 8 =>8 , 9 => 9 , 10 => 10 , 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18], null, ['class' => 'form-control', 'style' => 'width:150px;display:inline-block;']) !!}  
            &nbsp;&nbsp;anni &nbsp;&nbsp;
            {!! Form::select('anni_compiuti', ['compiuti' => 'compiuti','non compiuti' => 'non compiuti'], $compiuti_selected, ['class' => 'form-control', 'style' => 'width:150px;display:inline-block;']) !!}
			&nbsp;&nbsp; ( {{$data["record"]->fino_a_mesi}} mesi )
          </div>
        </div>

        <div class="form-group">

          {!! Form::label('valido_dal', 'Per soggiorni validi dal / al ', ['class' => 'col-sm-2 control-label']) !!}

          <div class="col-sm-6">
            {!! Form::text('valido_dal',  Utility::isValidYear($data["valido_dal"]->format('Y')) ? $data["valido_dal"]->format('d/m/Y') : "", ['id' => 'valido_dal', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}          
            &rarr;
            {!! Form::text('valido_al', Utility::isValidYear($data["valido_al"]->format('Y')) ? $data["valido_al"]->format('d/m/Y')  : "", ['id' => 'valido_al',  'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
          </div>

        </div>


        <div class="row">
          <div class="col-sm-10 col-sm-offset-2">
            <div class="checkbox">
               <label  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Il numero min. di adulti in camera con il bambino è due? Clicca qui e l'informazione in automatico verrà riportata nel testo!" data-original-title="Solo se in camera con 2 adulti ?">
               <input type="checkbox" name="solo_2_adulti" value="1"  @if ($data["record"]->exists && $data["record"]->solo_2_adulti) checked @endif> solo se in camera con 2 adulti
               </label>
             </div>    
          </div>
        </div>
        
        @if ($data["record"]->exists)
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
                  <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}">

                      <?php
                      $LIMIT_TESTO_LANG =  'LIMIT_TESTO_'.$lang;
                      $note_lingua = $offertaLingua[$lang];
                      // il reale valore del limite da inserire del titolo/testo è quello di default - quello GIA' INSERITO
                      $REAL_LIMIT_TESTO_LANG = $data[$LIMIT_TESTO_LANG] - strlen($note_lingua);
                      $js_onKeyTesto_args = [ '1' => 'this.form.note'.$lang.$data["record"]->id, '2' => 'this.form.countdown_note'.$lang, '3' => $data[$LIMIT_TESTO_LANG] ];
                      $js_onKeyTesto = 'limitText('.$js_onKeyTesto_args[1].','.$js_onKeyTesto_args[2].','.$js_onKeyTesto_args[3].')';
                      ?>
                      <div class="form-group">
                        <label for="note{{$lang.$data["record"]->id}}" class="col-sm-2 control-label">Note<span style="font-size=1px; margin:0 10px;"><input readonly type="text" name="countdown_note{{$lang}}" id="countdown_note{{$lang}}" size="3" value="{{$REAL_LIMIT_TESTO_LANG}}" class="caratteri_rimasti">caratteri rimasti</span></label>
                        <div class="col-sm-10">
                           {!! Form::textarea('note'.$lang.$data["record"]->id, $note_lingua, 
                                [
                                'id' => 'note'.$lang.$data["record"]->id, 
                                'class' => 'form-control', 
                                "rows" => 6, 
                                'onKeyDown' => $js_onKeyTesto, 
                                'onKeyUp'=> $js_onKeyTesto 
                                ]) 
                            !!}
                        </div>
                      </div>

                  </div>
                @endforeach
              </div> {{-- .tab-content --}}



            </div>{{-- col-lg-12 --}}
          </div> {{-- form-group --}}

          <div class="form-group">
            <div class="col-sm-12">
              <div class="checkbox">
                 <label>
                 {!! Form::checkbox('traduci',1) !!} Traduci automaticamente dalla lingua italiana e sovrascrivi le traduzioni già presenti !!
                 </label>
               </div>
               <hr />
            </div>
          </div>
        @else
        <br>
          <div class="form-group">
            <?php $limit = $data["LIMIT_TESTO"]; ?>

            <label for="note" class="col-sm-2 control-label">Note <span style="font-size=1px; margin:0 10px;"><input readonly type="text" id="countdown_note" name="countdown_note" size="3" value="{{$limit}}" class="caratteri_rimasti">caratteri rimasti</span></label>

            <div class="col-sm-10">
            {!! Form::textarea('note', null, 
                  [
                  'id' => 'note', 
                  'class' => 'form-control', 
                  "rows" => 6, 
                  'onKeyDown' => "limitText(this.form.note,this.form.countdown_note,$limit)", 
                  'onKeyUp'=> "limitText(this.form.note,this.form.countdown_note,$limit)"
                  ]) 
            !!}
            
            </div>
          </div>
        @endif

        <div class="form-group">
          <div class="col-sm-12">
           {{-- SE sono in un'offerta ARCHIVIATA --}}
          @if (isset($data["record"]) && !$data["record"]->attivo)
            
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
          
            <?php 
            $showSaveWithoutEmail = "";
            if (isset($data["showSaveWithoutEmail"]))
            $showSaveWithoutEmail = $data["showSaveWithoutEmail"]; 
            $showArchivia = "";
            if (isset($data["showArchivia"]))
            $showArchivia = $data["showArchivia"]; 
            ?>

            @if (isset($data["showArchivia"]) && $data["showArchivia"] == 1)
              <div class="form-group" style="margin-top:20px;">
                <textarea class="form-control" rows="2" placeholder="Specifica qui le motivazioni dell'archiviazione e verranno aggiunta alla mail" name="note" id="note">@if(old('note') != ''){{ old('note') }}@endif</textarea>
              </div>
            @endif
            
            @include('templates.admin_inc_record_buttons')
          
          @endif
          </div>
        </div>          

      </div>

    {!! Form::close() !!}

    @if (isset($data["showArchivia"]) && $data["showArchivia"] == 1 && $data["record"]->attivo)
      {!! Form::open(['id' => 'form_archvia', 'url' => 'admin/bambini-gratis/'.$data["record"]->id.'/archivia', 'method' => 'POST']) !!}
      
      {!! Form::close() !!}
    @endif



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
	
	
			
		
		});

     
		function limitText(limitField, limitCount, limitNum) {
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			} else {
				limitCount.value = limitNum - limitField.value.length;
			}
		}
		
	</script>


  @if ($data["record"]->exists)
    
    <script type="text/javascript">
    
      jQuery(document).ready(function() {    

          @foreach (Langs::getAll() as $lang)
          
            <?php
            $LIMIT_TESTO_LANG =  'LIMIT_TESTO_'.$lang;
            ?>
            limitText(document.getElementById('note{{$lang}}{{$data["record"]->id}}'), document.getElementById('countdown_note{{$lang}}'), {{$data[$LIMIT_TESTO_LANG]}});
          
          @endforeach

      });   
  
    </script>
  @endif

@endsection