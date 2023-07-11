@extends('templates.admin')

@section('title')
DIDASCALIE FOTOGALLERY
@endsection

@section('content')

@if(count($data["records"]) === 0)
	
	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i> Non ci sono <em>immagini</em>.
			</div>
		</div>
	</div>
	  
@else
	
	{!! Form::open() !!}
		<div class="row">
			<div class="col-lg-12">
				
			<div class="alert alert-warning">
                <span style="font-size: 13px">
                    <b>Per aggiungere, modificare o eliminare foto </b> della gallery inviare una mail a <a href="mailto:supporto@info-alberghi.com">supporto@info-alberghi.com</a> oppure contattare il numero 0541 29187 (dal lunedì al venerdì - orario: 9-13 / 14-18).
                </span>
            </div>

			<div class="alert alert-info">
				
					<strong>Descrivi le tue foto</strong> con delle didascalie utili agli utenti. I testi devono essere <strong>informativi e non contenere spam</strong>.<br>
                    Ogni didascalia è <strong>limitata a {{$data["limit"]}} caratteri</strong> e viene <strong>pubblicata immediatamente</strong>.<br>
                    <strong>Lo staff di Info Alberghi si riserva la possibilità di fare modifiche</strong> in base alle esigenze e nel caso non vengano rispettati gli standard del portale. Ogni revisione significativa ti sarà notificata via mail.
					<br><br>
					<strong>Esempi:</strong>
                        <div class="row" style="margin:0 0 0 -25px; ">
                            <div class="col-sm-3">
                                <blockquote>La piscina: 80mq., altezza 1,10 m.</blockquote>
                            </div>
                            <div class="col-sm-3">
                                <blockquote>Camera Smeraldo, 25 mq con vista mare</blockquote>
                            </div>
                        </div>
				</span>
			</div>

			<ul class="nav nav-tabs" role="tablist" style="position:relative;">
				
				@foreach (Langs::getAll() as $lang)
					
					<li role="presentation" @php echo  ( $lang === $data["locale"] ? 'class="active"' : null) @endphp >
						@if (isset($commerciale) && $commerciale == 1)
							<a href="{{url('admin/commerciale-immagini-gallery-titoli/'.Utility::getLocaleUrl($lang))}}" aria-controls="profile" role="tab" data-toggle="tab">
						@else
							<a href="{{url('admin/immagini-gallery-titoli/'.Utility::getLocaleUrl($lang))}}" aria-controls="profile" role="tab" data-toggle="tab">
						@endif
							<img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
						</a>
					</li>
					
				@endforeach  
			</ul>
	
			<a href="?salva" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</a><br />	

			<div id="contentLeft" style="margin-top:30px">
				<ul id="sortable">

					@php 
					
						$count = 0; 
						foreach($data["records"] as $immagine):
							
							$count += 1;
							
							if ($immagine->immaginiGallery_lingua->isEmpty()):
								$rimasti = $data["limit"];
							else:
								$rimasti = $data["limit"] - strlen($immagine->immaginiGallery_lingua->first()->caption);
							endif;
						
						@endphp

						<li class="ui-state-default ui-sortable-helper" id="recordsArray_{{$immagine->id}}" style="position: relative;">
							
							<div class="immagine_gallery_list">
								Foto <span class="badge badge-success chat-notifications-badge">#{{$count}}</span>
							</div>
							
							<div class="immagine_agg_img" class="big">
								<a href="{{$immagine->getImg('800x538')}}" target="_blank">
									<img src="{{$immagine->getImg('220x220')}}" class="img2" alt="">
								</a>          
							</div>
							{{-- <div style="clear:both;"></div> --}}
							
							<div style="float:right;">
								<span style="padding:5px; color: #666; background-color: #fff;">
								<input readonly type="text" name="countdown_titolo_{{$immagine->id}}" size="3" value="{{$rimasti}}" class="caratteri_rimasti">caratteri rimasti</span>
							</div>
							
							<div class="caption cliente">

								<input type="text" name="foto_{{$immagine->id}}" placeholder="inserisci descrizione foto" onfocus="this.placeholder=''" onblur="this.placeholder='inserisci descrizione foto'" id="{{$immagine->id}}" class="click" style="width:100%; height:30px; color:#222;" maxlength="{{$data["limit"]}}" value="@if (!$immagine->immaginiGallery_lingua->isEmpty()){{$immagine->immaginiGallery_lingua->first()->caption}} @endif" onKeyDown="limitText(this.form.foto_{{$immagine->id}},this.form.countdown_titolo_{{$immagine->id}},{{$data["limit"]}})" onKeyUp="limitText(this.form.foto_{{$immagine->id}},this.form.countdown_titolo_{{$immagine->id}},{{$data["limit"]}})">

							</div>
							@if (isset($data['gruppi'][$immagine->gruppo_id]))
							
								<div class="listing-cliente">
									{{$data['gruppi'][$immagine->gruppo_id]}}
								</div>

							@endif
							
						</li>

					@endforeach
					
				</ul> {{-- sortable --}}
			</div> {{-- contentLeft --}}

	<div style="clear:both"></div>
	<a href="?salva" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</a>

			</div>
		</div>
	{!! Form::close() !!}
@endif

@endsection

@section('onheadclose')
  
  <script src="{{Utility::assets('/vendor/oldbrowser/js/jquery.jeditable.min.js')}}"></script>
  
<script type="text/javascript">
  function limitText(limitField, limitCount, limitNum) {
    if (limitField.value.length > limitNum) {
      limitField.value = limitField.value.substring(0, limitNum);
    } else {
      limitCount.value = limitNum - limitField.value.length;
    }
  }
</script>

  <script type="text/javascript">
    jQuery(function() {

        jQuery(".nav-tabs a").click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          document.location.href = jQuery
          (this).attr("href");
        })
        
        jQuery(".click").blur(function() {
            
            var val = jQuery("#"+this.id).val();

            var locale = '{{$data["locale"]}}';

            jQuery.ajax({
                     url: '@php echo url("admin/immagini-gallery/crea_caption_ajax") @endphp ',
                     type: "post",
                     async: false,
                     data : { 
                            'id': this.id, 
                            'value': val,
                            'locale':locale,
                            '_token': jQuery('input[name=_token]').val()
                            },
                    success: function(data) {
                      // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                      // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                      // return(val) viene eseguito prima che val prenda il nuovo valore
                      //location.reload();
                    }
                });

          });
      });
  </script>
@stop