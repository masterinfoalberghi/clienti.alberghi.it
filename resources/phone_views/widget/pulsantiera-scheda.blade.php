<div class="tofix" >
   	
    <div class="row pulsantiera" >
        
        @if (!isset($disabled))
    
                <div class="col-xs-4 pulsante cyan ">
                 
                 @if ($cliente->attivo == -1)
                      <a href="{{Utility::getUrlWithLang($locale,'/hotel.demo?id=demo&price-list')}}"  name="services_anchor" id="services_anchor" class="white-fe-pulsante">
                 @else
                     <a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id.'&price-list')}}"  name="services_anchor" id="services_anchor" class="white-fe-pulsante">
                 @endif
                       <img src="{{Utility::asset("images/ReportCard.png")}}" />
                       {{ strtoupper(trans('hotel.listino_prezzi')) }}
                  </a>
                  
                </div>

                <div class="col-xs-4 pulsante orange">
                
                    <div id="pulsante_scrivi_scheda" class="padding white-fe-pulsante email">
                        <img src="{{Utility::asset("images/mail.png")}}">{{trans("hotel.scrivi")}}
                        @if ($cliente->attivo == -1)
                            {!! Form::open([ 'id'=>'emailmobileforms', 'url' => Utility::getUrlWithLang($locale,"/hotel.demo?id=demo&contact")]) !!} 
                        @else
                            {!! Form::open([ 'id'=>'emailmobileforms', 'url' => Utility::getUrlWithLang($locale,'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
                        @endif
                        {!! Form::hidden('locale',$locale) !!}
                        {!! Form::hidden('ids_send_mail', $cliente->id)!!}
                        {!! Form::hidden('no_execute_prefill_cookie', true)!!}
                        {!! Form::hidden('referer', $referer)!!}      
                        {!!Form::close()!!}
                    </div>
            
                </div>

                    <div class="col-xs-4 pulsante green">
                    <a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda link_call white-fe-pulsante" href="tel:+39{{explode(',',$cliente->telefoni_mobile_call)[0]}}">
                        <img style="padding:2px 0;" src="{{ Utility::asset('/mobile/img/telefono-call.svg') }}">
                        {{trans("hotel.chiama")}}
                    </a>
                </div>
        @endif
        
    </div>
    
</div>