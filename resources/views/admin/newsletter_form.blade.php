@extends('templates.admin')

@section('title')
  @if (isset($newsletterLink))
    Modifica il link alla newsletter
  @else
    Inserisci un nuovo link alla newsletter
  @endif
@endsection


@section('content')
    
    @if (isset($newsletterLink))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/newsletterLink/'.$newsletterLink->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
    @endif

    @if (isset($newsletterLink))
      {!! Form::model($newsletterLink, ['role' => 'form', 'route'=>['newsletterLink.update',$newsletterLink->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
    @else
      {!! Form::open(['role' => 'form', 'route'=>['newsletterLink.store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @endif
    

      <div class="form-group">
          {!! Form::label('titolo', 'Titolo', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4"> 
            {!! Form::text('titolo', isset($newsletterLink) ? $newsletterLink->titolo : null, ['placeholder' => 'Es: Avviso di aggiornamento tecnico su info-alberghi.com', 'class' => 'form-control']) !!}
        </div>
      </div>
      
      <div class="form-group">
          {!! Form::label('url', 'URL', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-4">       
          {!! Form::text('url', isset($newsletterLink) ? $newsletterLink->url : null, ['class' => 'form-control']) !!}
        </div>
      </div>

      <div class="form-group">
         {!! Form::label('data_invio', 'Data Invio ', ['class' => 'col-sm-3 control-label']) !!}
         <div class="col-sm-2">  
         {!! Form::text('data_invio', ( isset($newsletterLink) && !is_null($newsletterLink->data_invio) )  ? $newsletterLink->data_invio->format('d/m/Y') : "", ['id' => 'data_invio', 'class' => ' form-control dateicon', 'style' => 'width:150px;display:inline-block;']) !!}
         </div>
      </div>

      <div class="form-group">
        <label for="note" class="col-sm-3 control-label">Note</label>
        <div class="col-sm-4">
           {!! Form::textarea('note', isset($newsletterLink) ? $newsletterLink->note : null, ['id' => 'testo', 'class' => 'form-control', "rows" => 10 ]) !!}
        </div>
      </div>

   <div class="form-group">
      <div class="col-sm-12">
        @include('templates.admin_inc_record_buttons')
      </div>
    </div>
    
    {!! Form::close() !!}
    
    <hr/><hr/>
    
@endsection


@section('onheadclose')
  
  <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
  <script type="text/javascript" src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
  <script type="text/javascript" src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>
  
@endsection


@section('onbodyclose') 

  <script type="text/javascript">
  jQuery(document).ready(function($)
    {
    
    $("#data_invio").datepicker({ 

      format: 'dd/mm/yyyy', 
      weekStart:1,
      language: "it",
      orientation: "bottom left",
      todayHighlight: true,
      autoclose: true

    });

    });
  </script>

@stop

