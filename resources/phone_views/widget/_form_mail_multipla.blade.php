@extends('templates.cms_pagina_statica')

@section('content')

<script src="{{ Utility::asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ Utility::asset('js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>

<h1>Invia una richiesta preventivo a piu strutture</h1>

<div class="clearfix"></div>

<div class="panel-body" id="body-hotel">

  @include('errors')

  @if ($wishlist)
  
    <div>
      {{ trans('listing.mail_da_inviare') }}:
      <ul>
        @foreach ($clienti as $cliente)
        <li>{{$cliente->nome}} {{{$cliente->stelle->nome}}} - {{{ $cliente->localita->macrolocalita->nome }}}</li>
        @endforeach
      </ul>
    </div>

  @endif

  @if ($wishlist)
    {!! Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta_wishlist']) !!}
  @else
    {!! Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta_mailmultipla']) !!}
  @endif

  @if (!$wishlist)
    <div class="row">
        <p class="message success"></p>
        <div id="target"></div>
    </div>
    <div class="form-group">
      {!! Form::label('localita',trans('listing.localita').': ') !!}
      @include('composer.mailMultiplaSelectLocalita')
    </div>
    
    @if (isset($stelle))
      <div class="form-group">
        
        {!! Form::label('categoria',trans('listing.categoria').': ') !!}
        @foreach ($stelle as $id => $nome)
          {!! Form::checkbox("cat_".$id,$id,false,["id"=>"cat_".$id]) !!} {!! $nome !!} &nbsp;&nbsp;
        @endforeach
        
      </div>
     @endif

  @endif
  
  <div class="form-group">
    {!! Form::label('nome',trans('listing.nome_cognome').': ') !!}
    {!! Form::text('nome',null,["class"=>"form-control"]) !!}
  </div>
  <span class="form-inline">
    @include('composer.formDatePicker')
  </span>
  <span class="form-inline">
    @include('composer.formSelectAdultiBambini')
  </span>
  <div class="form-group">
    {!! Form::label('trattamento',trans('listing.trattamento').'*') !!}
    {!! Form::select('trattamento',Utility::Trattamenti(trans('listing.seleziona').'....'),null,["class"=>"form-control","id"=>"trattamento"]) !!}
  </div>
  <div class="form-group">
    {!! Form::label('email','Email: ') !!}
    {!! Form::text('email',null,["class"=>"form-control"]) !!}
  </div>
  <div class="form-group">
    {!! Form::label('telefono','Telefono: ') !!}
    {!! Form::text('telefono',null,["class"=>"form-control"]) !!}
  </div>
  <div class="form-group">
    {!! Form::label('richiesta',trans('listing.comunicazioni').': ') !!}
    {!! Form::textarea('richiesta',null,["class"=>"form-control", "placeholder" => trans('listing.com_palceholder')]) !!}
  </div>
  
  @include('esca_snippet')
  
  {!! Form::hidden('locale',$locale) !!}
  {!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
  {!! Form::hidden('referer',isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '' )!!}
  {{-- Questo campo serve alla validazione del campo trattamento che deve essere different da il valore di un altro campo --}}
  {!! Form::hidden('seleziona',0) !!}
  
  @if ($wishlist)
    {!! Form::hidden('ids_send_mail',$ids_send_mail) !!}
  @endif
  {!! Form::submit('Invia',["class"=>"fbtn btn-default mailmultipla"]) !!}
  {!! Form::close() !!}

</div>


{{-- se mail multipla --}}
@if (!$wishlist)
  @include('widget.script_for_multi_localita')
@endif

@endsection