@extends('templates.admin')

@section('title')
Gallery
@endsection

@section('content')

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>immagine</em>, <a href="{{ url("admin/immagini-gallery/create") }}">creane una ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Gallery</h4>
      {{-- form cancellazione gallery --}}
      <div id="delete_gallery">
        {!! Form::open(['id' => 'delete-all', 'url' => 'admin/immagini-gallery/delete-all', 'method' => 'POST']) !!}
        {!! Form::close() !!}
        <button type="button" onclick="jQuery('#modal-confirm-delete-all').modal('show');" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> ELIMINA TUTTA LA GALLERY</button>
      </div>
      

      {{-- form creazione immagine di listing --}}
      <div id="img_listing">
        {!! Form::open(['id' => 'create_img_listing', 'url' => 'admin/immagini-gallery/create_img_listing', 'method' => 'POST']) !!} {!! Form::close() !!}
        <?php  /* <button type="button" onclick="jQuery('#modal-confirm-create_img_listing').modal('show');" class="btn btn-blue btn-icon">CREA IMMAGINE PER LISTING<i class="entypo-picture"></i> </button> */ ?>
        
      </div>

      <div id="contentLeft">
        <ul id="sortable">

        @foreach($data["records"] as $immagine)
          
          <li class="ui-state-default" id="recordsArray_{{$immagine->id}}">
            
            <div id="immagine_agg_img">
              <a href="{{$immagine->getImg('113x99') }}" target="_blank">
                <img src="{{$immagine->getImg('113x99') }}" class="img2" alt="" style="width:113px; height:auto; border:1px solid #fff; ">
              </a>
            </div>
            <div id="immagine_gallery_list">
              {{$immagine->foto}}
            </div>
            
            {{-- form cancellazione singola immagine --}}
            <div id="immagine_agg_del">
              {!! Form::open(['id' => 'item_delete_'.$immagine->id, 'url' => 'admin/immagini-gallery/'.$immagine->id, 'method' => 'DELETE']) !!}
              {!! Form::close() !!}
              <button type="button" onclick="jQuery('#modal-confirm-delete_{{$immagine->id}}').modal('show');" class="btn btn-danger pull-right"> <i class="entypo-trash"></i></button>
            </div>

            {{-- 
            TO DO .....
            <div class="checkbox">
              <label>
                {!! Form::checkbox('item_ids[]',$immagine->id, null) !!} Eliminazione multipla
              </label>
            </div> 
            --}}
             
            {{-- popup modale avviso elimninazione singola immagine --}}
            <div class="modal fade" id="modal-confirm-delete_{{$immagine->id}}" aria-hidden="true" style="display: none;">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Eliminazione record</h4>
                  </div>
                  <div class="modal-body">
                    Confermi di voler eliminare in maniera definitiva ed irreversibile l'immagine?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="jQuery('#item_delete_{{$immagine->id}}').submit();">Conferma</button>
                  </div>
                </div>
              </div>
            </div>
            
          </li>


        @endforeach
      </ul> {{-- sortable --}}
    </div> {{-- contentLeft --}}
    </div>
  </div>

  
  {{-- popup modale avviso elimninazione intera gallery --}}
  <div class="modal fade" id="modal-confirm-delete-all" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Eliminazione gallery</h4>
        </div>
        <div class="modal-body">
          Confermi di voler eliminare in maniera definitiva ed irreversibile l'intera gallery?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
          <button type="button" class="btn btn-primary" onclick="jQuery('#delete-all').submit();">Conferma</button>
        </div>
      </div>
    </div>
  </div>

  {{-- popup modale avviso creazione immagine listing da prima immagine della gallery --}}
  <div class="modal fade" id="modal-confirm-create_img_listing" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Immagine listing</h4>
        </div>
        <div class="modal-body">
          Confermi di voler creare (eventualmente sovrascrivere) l'immagine del listing a partire dalla PRIMA IMMAGINE di questa gallery?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
          <button type="button" class="btn btn-primary" onclick="jQuery('#create_img_listing').submit();">Conferma</button>
        </div>
      </div>
    </div>
  </div>

@endif

@endsection


@section('onbodyclose')
  
  <script type="text/javascript">
    jQuery(document).ready(function(){ 

      // update: sortupdate This event is triggered when the user stopped sorting and the DOM position has changed.
      
      // serialize: String Serializes the sortable's item ids into a form/ajax submittable string. Calling this method produces a hash that can be appended to any url to easily submit a new item order back to the server. 
      // It works by default by looking at the id of each item in the format "setname_number", and it spits out a hash like "setname[]=number&setname[]=number". 
      // For example, a 3 element list with id attributes "foo_1", "foo_5", "foo_2" will serialize to "foo[]=1&foo[]=5&foo[]=2". 


      jQuery(function() {
        jQuery("#contentLeft ul").sortable({ placeholder: "ui-state-highlight",forcePlaceholderSize: true, opacity: 0.6, cursor: 'crosshair', update: function() {
          var token = "{{ Session::token() }}";
          var order = jQuery(this).sortable("serialize") + '&_token='+token;
          var baseUrl = "{{ url('/') }}"; 
          jQuery.post(
              baseUrl+"/admin/immagini-gallery/order_ajax", 
              order, 
              function(theResponse){}
              );                                
        }                 
        });
      });

    }); 
  </script>
@stop