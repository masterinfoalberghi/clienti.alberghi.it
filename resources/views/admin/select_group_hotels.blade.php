@extends('templates.admin')

@section('title')
{{$gruppo->nome}} - {{$gruppo->hotels->count()}} hotels
@endsection

@section('content')
  
  <?php 
  $row_hotels = $gruppo->hotels->chunk(3);
  ?>

  @foreach ($row_hotels as $n_row => $hotels_in_row)
    
    <div class="row">
    @foreach ($hotels_in_row as $h)
      {{-- E' UN LINK --}}
      @if (!is_null($h->user) && Auth::user()->id != optional($h->user)->id) 

        <form id="{{$h->id}}" method="post" action="{{ url("admin/simpleLogin") }}">
            {!! csrf_field() !!}
            <input type="hidden" name="username" value="{{$h->user()->first()->username}}">
        </form>
        <a href="#" class="simpleLogin" data-id="{{$h->id}}">
          <div class="col-lg-4 col-md-12 col-sm-12 hotel_gruppo container">
            <div class="panel panel-gradient" data-collapsed="0">
            <div class="panel-body">

                <h2>{{{$h->nome}}}<span class="rating">{{{$h->stelle->nome}}}</span></h2>
                
                <div class="wrapper_localita">
                  <div style="margin-bottom:10px;"><span class="localita">{{{ $h->localita->nome }}}</span></div>
                </div>
                
                <div class="clear"></div>

                <div class="fotoStruttura">
                  <img src="/{{$h->getListingImg('220x148', true)}}" class="alignleft image" width="220" height="148" alt="{{{$h->nome}}}">
                  <div class="middle">
                      <div class="text-entra">ENTRA</div>
                    </div>
                </div>
                <div class="icon"><i class="glyphicon glyphicon-lock"></i></div>

            </div>{{-- /.panel-body  --}}
            </div>
          </div>
        </a>
      
      @else

        <div class="col-lg-4 col-md-12 col-sm-12 hotel_gruppo container">
          <div class="panel panel-gradient" data-collapsed="0" style="background-color: whitesmoke; border-color:#21a9e1;">
          <div class="panel-body">

              <h2>{{{$h->nome}}}<span class="rating">{{{$h->stelle->nome}}}</span></h2>
              
              <div class="wrapper_localita">
                <div style="margin-bottom:10px;"><span class="localita">{{{ $h->localita->nome }}}</span></div>
              </div>
              
              <div class="clear"></div>

              <div class="fotoStruttura">
                <img src="/{{$h->getListingImg('220x148', true)}}" class="alignleft image" width="220" height="148" alt="{{{$h->nome}}}">
                <div class="middle">
                    <div class="text">HOTEL CONNESSO</div>
                  </div>
              </div>
              <div class="icon ok"><i class="glyphicon glyphicon-ok"></i></div>

          </div>{{-- /.panel-body  --}}
          </div>
        </div>

      @endif

    @endforeach  
    </div>  

  @endforeach

  <hr>
  <hr>

  @include('admin.avvisi_inc', ["statisticheMese" => $statisticheMese])
  


  @include('admin.newsletterLink_inc')

@endsection

@section('onbodyclose')
  <script type="text/javascript">
  
    jQuery(document).ready(function($ = jQuery) {

      $(".simpleLogin").click(function(e){
          e.preventDefault();
          id = $(this).data("id");
          document.getElementById(id).submit();
      });
       
    });

  </script>
@stop

