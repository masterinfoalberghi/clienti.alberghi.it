@extends('templates.admin')

@section('title')
Listini Personalizzati
@endsection

@section('content')

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessun <em>Listino Personalizzato</em>, <a href="{{ url("admin/listini-custom/create") }}">creane una ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Trascina i listini impostandoli nell'ordine che preferisci. L'ordinamento sarà salvato automaticamente.</h4>
      <br>
      {!! csrf_field() !!}

      <div class="dd ordinamento">
        <ul class="list-unstyled" class="dd-list">
        @foreach($data["records"] as $row)
          <li class="dd-item dd3-item" data-id="{{ $row->id }}">
            <div class="dd-handle dd3-handle">Drag</div>
            <div class="container dd3-content" @if (!$row->attivo) style="color: #ac1818" @endif>
              <div class="item titolo">
                {{ $row->listiniCustomLingua->findByLang("it")->titolo ? $row->listiniCustomLingua->findByLang("it")->titolo : '-' }}
              </div>
              <div class="item">
                {{ $row->attivo ? 'Attivo' : 'Non attivo' }}
              </div>
              <div class="item">
                <a href="{{ url("admin/listini-custom/".$row->id."/edit") }}" class="btn btn-primary">Modifica</a>
              </div>
            </div>
          </li>
        @endforeach
        </ul>
      </div>

    </div>
  </div>
@endif

@endsection

@section('onheadclose')
<style>
/**
 * Nestable Draggable Handles
 */


.container {
  display: flex;
  justify-content: space-between;
}

.item {
  width: 300px;
  margin-top: 5px;
}

.item.titolo {
  width: 500px;
}

.item:last-child {
  position: absolute;
    right: 5px;
    width: auto;
    margin-top: 0px;
}
 
.dd3-content { position: relative; display: block; height: 40px; margin: 5px 0; padding: 3px 10px 5px 4px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 5px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd3-content:hover { color: #2ea8e5; background: #fff; }

li.dd-item.dd3-item {
  margin:15px 0;
}

.dd-dragel > .dd3-item > .dd3-content { margin: 0; }

.dd3-item > button { margin-left: 30px; }

.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; height: 40px; text-indent: -400px; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    z-index: 1;
}
.dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
.dd3-handle:hover { background: #ddd; }
</style>

<script>
  jQuery(document).ready(function(){

    jQuery('.ordinamento').nestable({
      maxDepth: 0
    })
    .on('change', function(e){
      var ids = [];

      var li = jQuery(e.target).find('li');

      li.each(function(){
        ids.push(jQuery(this).data('id'));
      });

      jQuery.ajax({
        url: '<?=url("admin/listini-custom/saveOrder") ?>',
        type: "post",
        data: {
          'ids': ids.join(','),
          '_token': jQuery('input[name=_token]').val()
        }
      });
    });
  });
</script>
@endsection

@section('onbodyclose')
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery.nestable.js')}}"></script>
@endsection