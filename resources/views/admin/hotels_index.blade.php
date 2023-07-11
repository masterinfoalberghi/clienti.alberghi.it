@extends('templates.admin')
@php $path404 = config("app.cdn_online") . "/images/404.jpg"; @endphp
@section('title')
Hotels
@endsection

@section('content')
<div>
  <span class="badge badge-info" style="font-size: 14px;">{{$data["attivi"]}}</span> attivi
</div>

{!! $data["records"]->appends(Request::except('page'))->render() !!}
<form>
<table id="records" class="table table-hover table-bordered table-responsive datatable dataTable" >
  <thead>
    <tr>
        <th>Immagine</th>
        <?php 
        echo Utility::viewThOrderBy('ID', 'id');
        echo Utility::viewThOrderBy('Nome', 'nome');
        echo Utility::viewThOrderBy('Località', 'localita_id');
        echo Utility::viewThOrderBy('Attivo', 'attivo');
        echo Utility::viewThOrderBy('Chiuso Temp', 'chiuso_temp');
        echo Utility::viewThOrderBy('Annuale', 'annuale');
        ?>
        <th>Rating</th>
        <th>Recensioni</th>
        <th>Sorgenti</th>
        <th></th>
    </tr>
    <tr role="row">
        <th></th>
        <th>{!! Form::text("id", $old["id"], ['class' => 'form-control']) !!}</th>
        <th>{!! Form::text("nome", $old["nome"], ['class' => 'form-control']) !!}</th>
        <th>{!! Form::select("localita_id", $data["localita"], "localita_id", ['class' => 'form-control']) !!}</th>
        <th>{!! Form::select("attivo", ["" => "", 1 => "si", 0 => "no"], "attivo", ['class' => 'form-control']) !!}</th>
        <th>{!! Form::select("chiuso_temp", ["" => "", 1 => "si", 0 => "no"], "chiuso_temp", ['class' => 'form-control']) !!}</th>
        <th>{!! Form::select("annuale", ["" => "", 1 => "si", 0 => "no"], "annuale", ['class' => 'form-control']) !!}</th>
        <th></th>
        <th></th>
        <th></th>
        <th class="text-center">
            <button type="submit" class="btn btn-blue">Cerca</button>
        </th>
    </tr>
  </thead>
  <tbody>
  @foreach($data["records"] as $hotel)
    <tr>
      <td width="80">
        @if($hotel->attivo)
            <img src="{{ $hotel->getListingImg('360x320', true) }}" style="width:100%; height:auto;">
        @else
            <img src="{{$path404}}" style="width:100%; height:auto;">
        @endif
      </td>
      <td width="80">
      <b >{{ $hotel->id }}</b>
      </td>
      <td colspan="2">
        <span style="color:#990000; font-size:16px">{{ $hotel->nome }}</span> - 
        
        @if (gettype(json_decode($hotel->localita->nome))== 'object')
            {{json_decode($hotel->localita->nome)->it }}
        @else
            {{ $hotel->localita->nome }}
        @endif
        - {{$hotel->indirizzo}}
        <br />@if (isset($hotel->revisions[0])) Rev: <b>{{\Carbon\Carbon::parse($hotel->revisions[0]->updated_at)->format("j F Y H:i:s")}}</b>, @endif <span>{{$hotel->editor == NULL ? "Aggiornato da CRM" : (!is_null($hotel->editors->nome) ? $hotel->editors->name : $hotel->editors->email) }}</span>
      </td>
        
      <td>{{ Utility::viewBool($hotel->attivo) }}</td>
      <td>{{ Utility::viewBool($hotel->chiuso_temp) }}</td>
      <td>{{ Utility::viewBool($hotel->annuale) }}</td>
      <td><b class="rating">{{ $hotel->rating_ia }}</a></td>
      <td><b>{{ $hotel->n_rating_ia }}</b></td>
      <td>{{ $hotel->n_source_rating_ia }}</td>
      <td class="text-center">
        <a href="{{ url("admin/hotels/".$hotel->id."/edit") }}" class="btn btn-primary">Modifica</a>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</form>
{!! $data["records"]->appends(Request::except('page'))->render() !!}

@endsection

@section('onheadclose')
  
<style>
table > thead > tr > th{
  cursor: pointer;
}

table > thead > tr > th.nm-order-desc, table > thead > tr > th.nm-order-asc{
  color: #303641;
  font-weight: bold;
}

.nm-order-desc::before, .nm-order-asc::before{
  display: block;
  float: right;
  color: #303641;
  font-family: 'Entypo';
  content: '\e873';  
}  

.nm-order-asc::before{
  content: '\e876';  
}

.rating { padding:5px; border-radius:4px; color:#fff; background: #47A7E6 }
</style>

@endsection