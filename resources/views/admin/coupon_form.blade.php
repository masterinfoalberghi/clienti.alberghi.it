@extends('templates.admin')

@section('title')
  @if (isset($coupon))
    Modifica il tuo Coupon
  @else
    Crea il tuo coupon
  @endif
@endsection


@section('content')
<div class="row">
  <div class="col-lg-12">

    @if (isset($coupon))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/coupon/'.$coupon->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
    @endif

   @if (isset($coupon))
      {!! Form::model($coupon, ['role' => 'form', 'route'=>['coupon.update',$coupon->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'form_modifica_offerta','files' => true]) !!}
      {{-- passo $coupon->id per la validazione--}}
      <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
      <input type="hidden" name="old_periodo_dal" value="{{Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%Y')}}">
      <input type="hidden" name="old_periodo_al" value="{{Utility::getLocalDate($coupon->periodo_al, '%d/%m/%Y')}}">

    @else
      {!! Form::open(['role' => 'form', 'route'=>['coupon.store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
    @endif

      <div class="form-horizontal">
      
        <div class="form-group form-group-coupon">
              {!! Form::label('valore', '1. Di quanti Euro vuoi che sia il buono sconto ?', ['class' => 'col-md-3 control-label pull-left tal']) !!}
            <div class="col-md-9">
               {!! Form::text('valore', isset($coupon) ? $coupon->valore : null, ['class' => 'form-control', 'id' => 'valore']) !!}
            </div>
        </div>
        
        <div class="form-group  form-group-coupon">
          {!! Form::label('periodo_dal', '2. Qual\'è il periodo di validità del buono sconto ?', ['class' => 'col-lg-3 control-label pull-left tal']) !!}
          <div class="col-lg-9">

             {!! Form::label('periodo_dal', 'Dal', ['class' => 'col-lg-1 control-label']) !!}
              <div class="col-lg-3">
                {!! Form::input('text','periodo_dal',(isset($coupon)) ? Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%Y') : null,["class"=>"", "id" => "periodo_dal","size"=>"8"]) !!}
              </div>

              {!! Form::label('periodo_al', 'Al', ['class' => 'col-lg-1 control-label']) !!}
              <div class="col-lg-3">
                {!! Form::input('text','periodo_al',(isset($coupon)) ? Utility::getLocalDate($coupon->periodo_al, '%d/%m/%Y') : null,["class"=>"", "id" => "periodo_al","size"=>"8"]) !!}
              </div>

          </div>
        </div>

        <div class="form-group  form-group-coupon">
          {!! Form::label('durata_min', '3. Quanti sono le notti minime di permanenza per far valere il buono sconto?', ['class' => 'col-md-3 control-label pull-left tal']) !!}
          <div class="col-md-9">
             {!! Form::select('durata_min',[0 => 'seleziona',1,2,3,4,5,6,7],isset($coupon) ? $coupon->durata_min : null,["class"=>"form-control"]) !!}
          </div>
        </div>
        
        <div class="form-group  form-group-coupon">
              {!! Form::label('numero', '4. Quanti coupon vuoi mettere online su info-alberghi.com? ', ['class' => 'col-md-3 control-label pull-left tal']) !!}
            <div class="col-md-9">
               {!! Form::text('numero', isset($coupon) ? $coupon->numero : null, ['class' => 'form-control', 'id' => 'numero']) !!}
            </div>
        </div>

        <div class="form-group  form-group-coupon">
              {!! Form::label('referente', '5. Si prega di inserire nome e cognome della persona che sta compilando questo modulo', ['class' => 'col-md-3 control-label pull-left tal']) !!}
            <div class="col-md-9">
               {!! Form::text('referente', isset($coupon) ? $coupon->referente : null, ['class' => 'form-control', 'id' => 'referente']) !!}
            </div>
        </div>
  </div>
</div>
<div class="row">
    <div class="col-lg-12">
          {!! Form::label('termini', 'Termini dell’offerta “buono sconto – coupon”', ['class' => 'col-lg-offset-4 control-label']) !!}
    </div>
</div>
<div class="row">
    <div class="col-lg-11"> 
      <div class="col-lg-offset-1 alert alert-default">
      <div class="form-horizontal">
        6. Accettando i termini e le presenti condizioni dell'offerta, Lei si impegna ed obbliga in nome e per conto della Sua società e struttura alberghiera , a riconoscere al cliente titolare del "buono sconto – coupon", uno sconto sul prezzo totale d'acquisto del soggiorno, secondo i termini da Lei prescelti ai punti n. 1, 2, 3, 4, 5 che precedono e secondo le presenti condizioni.
        <br><br>
        7. L'offerta è da Lei proposta sotto la Sua esclusiva responsabilità. Info Alberghi S.r.l. non agisce in Suo nome e conto, ma si limita a promuovere l'offerta ed a raccogliere le adesioni, senza impegnarsi in alcun modo nei confronti dell'utente/cliente, né fornire od offrire a questi alcun tipo di servizio. Pertanto, accettando le presenti condizioni dell'offerta Lei dichiara e garantisce piena ed irrevocabile manleva in favore di Info Alberghi S.r.l. per ogni pretesa e/o rivalsa di terzi.
        <br><br>
        8. Info Alberghi S.r.l. non ha alcun obbligo di promozione del "buono sconto – coupon" e si riserva di non pubblicarlo, qualora ravvisi, a suo insindacabile giudizio, che l'offerta, a causa delle condizioni da Lei prescelte ai punti n. 1, 2, 3, 4 e 5 che precedono, non rappresenti alcun concreto vantaggio a favore del cliente.
        <br><br>
        9. Lo sconto sarà applicato ai prezzi di listino relativi al periodo del soggiorno, pubblicati sul Suo sito al momento del conseguimento del "buono sconto – coupon" da parte del cliente.
        <br><br>
        10. L'utilizzo del "buono sconto – coupon" è subordinato alla disponibilità presso la Sua struttura alberghiera nel periodo di validità del buono.
        <br><br>
        11. Il "buono sconto – coupon" è cumulabile con  le altre  eventuali e concomitanti offerte promosse dalla Sua struttura alberghiera. I "buoni sconto – coupon" non sono cumulabili tra loro per una singola prenotazione, né sono cumulabili con altre promozioni di "advance booking", quali, a titolo meramente esemplificativo, booking.com, expedia.com, groupon.com, tippest.it, etc.
        <br><br>
        12. Il "buono sconto – coupon" è valido solo per prenotazioni aventi ad oggetto una camera per un minimo di due adulti.
        <br><br>
        13. Una volta pubblicatosu www.info-alberghi.com, il "buono-sconto – coupon" non potrà essere annullato, ma potrà essere sospesa la pubblicazione.
        <br><br>
        14. Con l'accettazione delle presenti condizioni, l'offerta si intende irrevocabile, fatta salva la facoltà di fermare la pubblicazione del "buono-sconto – coupon"su develop.info-alberghi.com".
        <br><br>
        15. Il"buono-sconto – coupon" è utilizzabile una sola volta e ha un limitato periodo di validità, entro il quale dovrà essere presentato per beneficiare dello sconto.
      </div>
      </div> 
    </div>
</div>

<div class="row">
<div class="col-lg-12">
     {!! Form::label('accettazione', 'dichiaro di avere letto e di accettare i temini e le condizioni dell\'offerta "buono sconto coupon"', ['class' => 'col-lg-offset-4 col-md-4 control-label pull-left tal']) !!}
    <div class="col-md-4">
       {!! Form::checkbox('accettazione', "1", null) !!}
    </div>
</div>
</div>

<div class="row">
<div class="form-group form-group-coupon">
  <div class="col-sm-12">
    @include('templates.admin_inc_record_buttons')
  </div>
</div>
</div>
{{-- Questo campo serve alla validazione del campo durata_min che deve essere different da il valore di un altro campo --}}
{!! Form::hidden('seleziona',0) !!}

{!! Form::close() !!}


@endsection

@section('onheadclose')
  <link href="{{ Utility::assets('oldbrowser/css/jquery-ui.min.css') }}" rel="stylesheet">
  <script src="{{ Utility::assets('oldbrowser/js/jquery-ui.min.js') }}" type="text/javascript"></script>
  <script src="{{ Utility::assets('oldbrowser/js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>
@endsection
@section('onbodyclose')
  

  <script type="text/javascript">

    jQuery(document).ready(function() {

       jQuery("#valore").keydown(function(event) {
           // Allow:       delete,             backspace         tab,                    escape,                       enter                  
           if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||  
                // Allow: home, end, left, right                          insert                substract               dash
               (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 45)
            {
                    // let it happens, don't do anything
                    return;
           }
           else {
               // Ensure that it is a number and stop the keypress
               if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                   event.preventDefault(); 
               }   
           }
       });


       jQuery("#numero").keydown(function(event) {
           // Allow:       delete,             backspace         tab,                    escape,                       enter                  
           if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||  
                // Allow: home, end, left, right                          insert                substract               dash
               (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 45)
            {
                    // let it happens, don't do anything
                    return;
           }
           else {
               // Ensure that it is a number and stop the keypress
               if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                   event.preventDefault(); 
               }   
           }
       });



       // DATEPICKER
       
       // Set all date pickers to have Italian text date.
       jQuery.datepicker.setDefaults(jQuery.datepicker.regional["it"]);
       

       jQuery('#periodo_dal').datepicker({
           defaultDate: "+0d",
           showOn: "both",
           buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif', true)}}",
           numberOfMonths: 1,
           autoSize: true,
           showAnim: "clip",
           dateFormat: "dd/mm/yy",
           onSelect: function(selectedDate) {
               jQuery("#periodo_al" ).datepicker("option", "minDate", selectedDate);
               jQuery("#periodo_al").datepicker("option", "defaultDate", selectedDate);
              
              var date = jQuery(this).datepicker('getDate');
              if(date)
                {
                  date.setDate(date.getDate() + 1);
                  jQuery("#periodo_al").datepicker('setDate', date);
                }
               
           }
       }); 


       jQuery('#periodo_al').datepicker({
           showOn: "both",
           buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif', true)}}",
           numberOfMonths: 1,
           autoSize: true,
           showAnim: "clip",
           dateFormat: "dd/mm/yy",
           onSelect: function( selectedDate ) {
                  jQuery( "#periodo_dal" ).datepicker("option", "maxDate", selectedDate);
                  
                  var date = jQuery(this).datepicker('getDate');
                  if(date)
                    {
                      date.setDate(date.getDate() - 1);
                      jQuery("#periodo_dal").datepicker("option", "maxDate", date);
                    }
           }
       });

       
       // FINE DATEPICKER



    });

  </script>

  

@endsection