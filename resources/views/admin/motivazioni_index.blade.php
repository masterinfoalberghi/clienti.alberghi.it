@extends('templates.admin')

@section('title')
Motivazioni archiviazione promozioni
@endsection



@section('content')
 @if (isset($motivazione))
  <form action="{{ route('motivazioni.update', $motivazione->id) }}" method="POST">
   @method('PUT')
 @else
  <form action="{{ route('motivazioni.store') }}" method="POST">
 @endif    
    @csrf
    <div class="form-row">
      <div class="col-xl-4 col-sm-8">
        <input type="text" class="form-control " name="motivazione" value="@if (isset($motivazione)){{$motivazione->motivazione}} @endif" placeholder="Inserisci una nuova motivazone" id="motivazione" required>
      </div>
      <div class="col-xl-4 col-sm-4">
        <button type="submit" class="btn btn-primary btn-sm">Invia</button>
        <a href="{{ route('motivazioni.index') }}" class="btn btn-info btn-sm">Annulla</a>
      </div>
    </div>
  </form>  
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Motivazione</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($motivazioni as $motivazione)
        <tr>
          <td>{{$motivazione->motivazione}}</td>
          <td><a class="btn btn-primary btn-sm" href="{{ route('motivazioni.edit', $motivazione->id) }}">Modifica</a></td>
          <td class="text-center">
            <form action="{{ route('motivazioni.destroy', $motivazione->id) }}" id="record_delete_{{$motivazione->id}}" method="POST">
              @method('DELETE')
              @csrf
              <input type="button" name="elimina" value="Elimina" data-id="{{$motivazione->id}}" class="btn btn-danger del_motivazione">
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection


@section('onbodyclose')
    <script>
      
      jQuery(document).ready(function(){

        jQuery('.del_motivazione').click(function(event){
          event.preventDefault();

          if(window.confirm("ATTENZIONE! l'elemento verrà eliminato definitivamente. Continuare?"))
          {
            var id = jQuery(this).data('id');
            jQuery('#record_delete_'+id).submit();
          } 

        });
        
                
      });
      
      
    </script>  
@endsection