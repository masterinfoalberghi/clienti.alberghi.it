@extends('templates.admin')

@section('jquery-ui-js')

    <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery-ui/js/jquery-ui-1.8.0.js')}}"></script>
	
@endsection

@section('jquery_ui_css')
    <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css')}}" />
@endsection

@section('title')
	I 9 Punti di Forza
@endsection

@section('content')
    <div class="row">
    <div class="col-lg-12">

        <p>Inserisci in ordine di importanza 9 caratteristiche o servizi della tua struttura che vuoi mettere in evidenza.<br>
        Questi punti sono molto importanti perchè molto visibili agli utenti. </p>
        
        <div class="alert alert-warning"> 
            <strong>Attenzione! </strong><br />
            Metti in evidenza 3 punti di forza nel listing e migliora la tua comunicazione,<br />
            Adesso hai più spazio per i punti di forza: la nuova lunghezza massima è 28 caratteri<br /><br />
            <b>Le modifiche saranno visibili entro le 24 h successive)</b> 
        </div>

        <div>
            <a class="btn btn-danger" href="#" id="delete_all" title="Cancella tutti i punti di forza">Cancella tutti i punti di forza</a>
        </div>

        <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @foreach (Langs::getAll() as $lang)
            <li role="presentation" <?=( $lang === "it" ? 'class="active"' : null)?>>
                <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                </a>
            </li>
            @endforeach
        </ul>
        

        <!-- Tab panes -->
        <form method="post">

            {!! csrf_field() !!}

            <div class="tab-content">        
            @foreach (Langs::getAll() as $lang)
                <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">            

                
                @for ($i = 1; $i <= $data["MAX_ALLOWED"]; $i++)
                
                    <label for="pf{{ $lang }}{{ $i }}">Punto di Forza #{{ $i }}</label><br />

                    <ul class="col-sm-7" tabindex="-1" id="pf{{ $lang }}{{ $i }}" >
                    <li>{{ @$data["punti_forza"][$lang."_".$i] }}</li>
                    </ul>
            
                    @if ($lang == "it")
                        @php $valore = ""; @endphp

                        @if (isset($data["evidenza" . $i]))
                            @php $valore = $data["evidenza" . $i];  @endphp
                        @endif
                        <div class="col-sm-3">
                            <label>{!! Form::checkbox('evidenza' . $i, "1", $valore,["class" => "pdf-check", "onclick" => "contaPDF()"]) !!} Metti in evidenza</label>
                        </div>
                        
                    @endif

                    <div style="clear:both;"></div>
                    
                @endfor                          

                </div>
            @endforeach  
            <button type="submit" class="btn btn-primary" value="salva" name="salva"><i class="glyphicon glyphicon-ok"></i> Salva</button>
            <button type="submit" class="btn btn-blue pull-right"  value="traduci" name="salvatraduci"><i class="glyphicon glyphicon-transfer"></i> Salva e forza la traduzione</button>
            </div>
        </form>

        </div>
    </div>
    </div>
@endsection

@section('onbodyclose')
	
	<link   rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

    <link   href="{{Utility::assets('/vendor/tag/jquery.tagit.min.css', true)}}" rel="stylesheet" type="text/css">
	<link   href="{{Utility::assets('/vendor/tag/tagit.ui-zendesk.min.css', true)}}" rel="stylesheet" type="text/css">
	<script  src="{{Utility::assets('/vendor/tag/tag-it.min.js', true)}}" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">

		function contaPDF () {
			if (jQuery(".pdf-check:checked").length >= 3) {
				jQuery(".pdf-check").not(':checked').prop("disabled", "disabled");
			} else {
				jQuery(".pdf-check").prop("disabled", "");
			}
	    }

	    jQuery(document).ready(function($) {
    	    var allow = [];
		    allow["it"] = [{!! $allowPointLang["it"] !!}];
		    allow["en"] = [{!! $allowPointLang["en"] !!}];
		    allow["fr"] = [{!! $allowPointLang["fr"] !!}];
		    allow["de"] = [{!! $allowPointLang["de"] !!}];
		    @php foreach (Langs::getAll() as $lang) {
			    for ($i = 1; $i <= $data["MAX_ALLOWED"]; $i++) { @endphp
			        jQuery("#pf{{$lang}}{{$i}}").tagit({availableTags: allow["{{$lang}}"], allowSpaces:true, tagLimit: 1, limitInput: 30, fieldName: "tag_pf{{ $lang }}{{ $i }}"});			        
		        @php } 
			} @endphp
		    jQuery("#delete_all").click(function(e){
                jQuery('ul.tagit').each(function(){
                    jQuery(this).find('li.tagit-choice').remove();
                })
			});
	    });

	</script>
	
@endsection