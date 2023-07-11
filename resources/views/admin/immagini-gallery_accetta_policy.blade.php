@extends('templates.admin')

@section('title')
Gallery
@endsection

@section('content')
{!! Form::open(['role' => 'form', 'url' => 'admin/hotel/acceptPolicyGallery', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
<div class="row">
    <div class="col-lg-12">
      <div class="alert alert-warning ">
        <strong>ATTENZIONE!!</strong> Leggi le condizioni di <strong>Info Alberghi Srl</strong> per la gestione dei titoli delle foto che appariranno sul sito
      </div>
    </div>
    <div class="row">
        <div class="col-lg-11"> 
          <div class="col-lg-offset-1 alert alert-default">
          <div class="form-horizontal">
            1. Accettando i termini e le presenti condizioni dell'offerta, Lei si impegna ed obbliga in nome e per conto della Sua società e struttura alberghiera , a riconoscere al cliente titolare del "buono sconto – coupon", uno sconto sul prezzo totale d'acquisto del soggiorno, secondo i termini da Lei prescelti ai punti n. 1, 2, 3, 4, 5 che precedono e secondo le presenti condizioni.
            <br><br>
            2. L'offerta è da Lei proposta sotto la Sua esclusiva responsabilità. Info Alberghi S.r.l. non agisce in Suo nome e conto, ma si limita a promuovere l'offerta ed a raccogliere le adesioni, senza impegnarsi in alcun modo nei confronti dell'utente/cliente, né fornire od offrire a questi alcun tipo di servizio. Pertanto, accettando le presenti condizioni dell'offerta Lei dichiara e garantisce piena ed irrevocabile manleva in favore di Info Alberghi S.r.l. per ogni pretesa e/o rivalsa di terzi.
            <br><br>
            3. Info Alberghi S.r.l. non ha alcun obbligo di promozione del "buono sconto – coupon" e si riserva di non pubblicarlo, qualora ravvisi, a suo insindacabile giudizio, che l'offerta, a causa delle condizioni da Lei prescelte ai punti n. 1, 2, 3, 4 e 5 che precedono, non rappresenti alcun concreto vantaggio a favore del cliente.
            <br><br>
            4. Lo sconto sarà applicato ai prezzi di listino relativi al periodo del soggiorno, pubblicati sul Suo sito al momento del conseguimento del "buono sconto – coupon" da parte del cliente.
            <br><br>
            5. L'utilizzo del "buono sconto – coupon" è subordinato alla disponibilità presso la Sua struttura alberghiera nel periodo di validità del buono.
            <br><br>
            6. Il "buono sconto – coupon" è cumulabile con  le altre  eventuali e concomitanti offerte promosse dalla Sua struttura alberghiera. I "buoni sconto – coupon" non sono cumulabili tra loro per una singola prenotazione, né sono cumulabili con altre promozioni di "advance booking", quali, a titolo meramente esemplificativo, booking.com, expedia.com, groupon.com, tippest.it, etc.
            <br><br>
            7. Il "buono sconto – coupon" è valido solo per prenotazioni aventi ad oggetto una camera per un minimo di due adulti.
            <br><br>
            8. Una volta pubblicatosu www.info-alberghi.com, il "buono-sconto – coupon" non potrà essere annullato, ma potrà essere sospesa la pubblicazione.
            <br><br>
            9. Con l'accettazione delle presenti condizioni, l'offerta si intende irrevocabile, fatta salva la facoltà di fermare la pubblicazione del "buono-sconto – coupon"su develop.info-alberghi.com".
            <br><br>
            10. Il"buono-sconto – coupon" è utilizzabile una sola volta e ha un limitato periodo di validità, entro il quale dovrà essere presentato per beneficiare dello sconto.
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
</div>
{!! Form::close() !!}
@endsection