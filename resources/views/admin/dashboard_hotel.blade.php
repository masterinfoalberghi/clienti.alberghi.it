@extends('templates.admin')

@section('title')
{{$hotel->nome}}
@endsection

@section('content')

    <div class="alert alert-warning">
        <p>
            <span style="font-size: 13px">
                <b>Per aggiungere, modificare o eliminare informazioni</b> presenti nel testo descrittivo della tua scheda inviare una mail a <a href="mailto:supporto@info-alberghi.com">supporto@info-alberghi.com</a> oppure contattare il numero 0541 29187 (dal lunedì al venerdì - orario: 9-13 / 14-18).
            </span>
        </p>
    </div><br /><br />

	@include('admin.avvisi_inc', ["statisticheMese" => $statisticheMese])

	{{-- @include('admin.newsletterLink_inc') --}}
  
@endsection

@section('onbodyclose')

@endsection