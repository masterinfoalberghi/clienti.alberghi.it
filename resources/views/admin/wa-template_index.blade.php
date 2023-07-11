
@if ($wa_templates->count())
  @foreach ($wa_templates as $template)
      {!! Form::open(['id' => 'record_delete_'.$template->id, 'url' => 'admin/wa-template/'.$template->id, 'method' => 'DELETE']) !!}
      {!! Form::close() !!}
  @endforeach
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Titolo</th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($wa_templates as $template)
        <tr>
          <td scope="row">{{$template->id}}</td>
          <td> <a href="{{ route('wa-template.edit',$template->id) }}" title="Modifica">{{$template->titolo}}</a></td>
          <td> <a class="mod_template" href="{{ route('wa-template.edit',$template->id) }}" title="Modifica"><i class="entypo-pencil"></i> Modifica</a></td>
          <td><a href="#" data-id="{{$template->id}}" class="del_template"><i class="entypo-trash"></i>Elimina</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p>Nessun template</p>
@endif
<div>
   <a class="btn btn-primary" href="{{ route('wa-template.create') }}" role="button">Nuovo Template</a>
</div>

  <script>
    
    jQuery(document).ready(function(){

      jQuery('.del_template').click(function(event){
        event.preventDefault();

        if(window.confirm('ATTENZIONE! il template verrà eliminato definitivamente. Continuare?'))
        {
          var id = jQuery(this).data('id');
          jQuery('#record_delete_'+id).submit();
        } 

      });
      
              
    });
    
    
  </script>

