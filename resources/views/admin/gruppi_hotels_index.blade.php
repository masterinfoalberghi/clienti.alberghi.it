@extends('templates.admin')

@section('title')
Gruppi hotel
@endsection

@section('content')
<div style="margin-bottom: 20px;">
<span class="badge badge-info" style="font-size: 14px;">{{$gruppi->count()}}</span> gruppi hotel
</div>

<div style="margin-bottom: 20px;">
  <a href="{{ url("admin/gruppo-hotels/create") }}" class="btn btn-primary">Nuovo gruppo</a>
</div>

<table class="table table-hover table-bordered table-responsive datatable">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Hotel</th>
      <th></th>
      <th>Inserito il</th>
      <th>Ultima modifica</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  @if (isset($gruppi))
    @foreach($gruppi as $gruppo)
        <tr>
        <td>{{ $gruppo->nome }}</td>
        <td>{{ $gruppo->getHotels() }}</td>
        <th>@if($gruppo->note != '') <span  style="color: black;font-weight: bold;font-size: 16px;"> <i class="entypo-docs"></i></span>  @endif</th>
        <td>{{ $gruppo->created_at->formatLocalized("%x %X") }}</td>
        <td>{{ $gruppo->updated_at->formatLocalized("%x %X") }}</td>
        <td class="text-center">
            <a href="{{ url("admin/gruppo-hotels/".$gruppo->id."/edit") }}" class="btn btn-primary">Modifica</a>
        </td>
        <td class="text-center">
            {!! Form::open(['id' => 'record_delete_'.$gruppo->id, 'url' => 'admin/gruppo-hotels/'.$gruppo->id, 'method' => 'DELETE']) !!}
            <input type="button" name="elimina" value="Elimina" data-id="{{$gruppo->id}}" class="btn btn-danger del_gruppo">
            {!! Form::close() !!}
        </td>
        </tr>
    @endforeach
  @endif
  </tbody>
</table>
@endsection


@section('onbodyclose')
    <script>
      
      jQuery(document).ready(function(){

        jQuery('.del_gruppo').click(function(event){
          event.preventDefault();

          if(window.confirm('ATTENZIONE! Il gruppo verrà eliminato definitivamente. Continuare?'))
          {
            var id = jQuery(this).data('id');
            jQuery('#record_delete_'+id).submit();
          } 

        });
        
                
      });
      
      
    </script>  
@endsection