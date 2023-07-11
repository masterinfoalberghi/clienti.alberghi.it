@extends('templates.admin')
@section('title') @if ($data["record"]->exists) Modifica Pagina @else Nuova Pagina @endif @endsection
@section('content')

	@if ($data["record"]->exists)
	
		<a target="blank" style="margin-bottom:20px; display:block; font-size:16px;" href="{{ config('app.url_frontend') .'/'. $data["record"]->uri }}" >Apri su sito <i class="entypo-popup"></i></a>
		<div style="clear:both"></div>
	  
		{!! Form::open(['id' => 'record_delete', 'url' => 'admin/listing/delete', 'method' => 'POST']) !!}
		<input type="hidden" name="id" value="{{ $data["record"]->id }}">
		{!! Form::close() !!}
		
	@endif
  
	{!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/listing/store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
	
		{!! Form::hidden('banner_vetrina_id', null, ['class' => 'form-control']) !!}
		{!! Form::hidden('evidenza_vetrina_id', null, ['class' => 'form-control']) !!}			
	
		<input type="hidden" name="menu_auto_annuale" value="0">
		<input type="hidden" name="id" value="{{($data["record"]->exists ? $data["record"]->id : 0)}}">
		<input type="hidden" name="listing_preferiti" value="0">
		    	
		<div class="form-group">
			<div class="col-sm-12 con_bottoni_in_alto">
				@include('templates.admin_inc_record_buttons')
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label>
						{!! Form::checkbox('attiva', "1", null, ["class" => "apple-switch"]) !!} <span style="display:inline-block; margin:8px 12px; ">Attiva pagina</span>
					</label>
				</div>
			</div>
        </div>
    
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2 ">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('vetrine_top_enabled', "1", null) !!} Attiva per vetrina TOP
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tipo_evidenza_crm', 'tipo Evidenza CRM', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                <label>
                    {!! Form::select('tipo_evidenza_crm', $data["tipi_evidenze_cms"], null, ['class' => 'form-control']) !!}
                </label>
            </div>
        </div>

		<div class="form-group">
			{!! Form::label('lang_id', 'Lingua', ['class' => 'col-sm-2 control-label']) !!}
			<div class="col-sm-4">
				{!! Form::select('lang_id', $data["langs"], null, ['class' => 'form-control']) !!}
			</div>
		</div>

		@if ($data["record"]->exists)
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<a href="{{ url("admin/listing/".$data["record"]->id."/clona") }}">Clona questa pagina</a>. Alla pagina clone verrà assegnato una URI temporaneo che dovrai personalizzare.
				</div>
			</div>
		@endif
    
		<div class="panel panel-default" data-collapsed="0">
			
			<div class="panel-heading">
				<div class="panel-title">Menu</div>
			</div>
			
			<div class="panel-body">
				<div class="form-group">
					{!! Form::label('menu_dal', 'Visibile nel menu dal', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						<input id="reportrange-start" class="form-control" type="text" name="menu_dal" value="{{ (isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_dal,'%d/%m/%y') != "" ) ? $data["record"]->menu_dal->format('d/m/Y') : ''}}" style="width:150px; display:inline-block"> al
						<input id="reportrange-end"   class="form-control" type="text" name="menu_al"  value="{{ (isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_al,'%d/%m/%y')  != "" ) ? $data["record"]->menu_al->format('d/m/Y')  : ''}}" style="width:150px; display:inline-block">
						@if(isset($data["record"]->menu_dal) && Utility::isValidMenu($data["record"]->menu_dal->format('Y-m-d'), $data["record"]->menu_al->format('Y-m-d'), null, 1))
							<span style="display:inline-block; margin-left: 30px; "><b style="color: green;">Menu attivo per la data di oggi</b></span>
						@endif
					</div>
					<div class="col-sm-10 col-sm-offset-2 ">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('menu_auto_annuale', "1", null, ["id"=>'menu_auto_annuale']) !!} Rinnovo annuale automatico
							</label><br >
							<label>
								{!! Form::checkbox('menu_evidenza', "1", null, ["id"=>'menu_evidenza']) !!} Metti in evidenza nel menu tematico
							</label>
							
						</div>
					</div>	
					
				</div>
			</div>
		</div>

		<div class="panel panel-default" data-collapsed="0">
			
			<div class="panel-heading">
				<div class="panel-title">Template</div>
			</div>
			
			<div class="panel-body">
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
					I campi <strong>Macro Località</strong> e <strong>Località</strong> servono per fare comparire una pagina nel <strong>Menù Località</strong>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('template', 'Template', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::select('template', ['localita' => 'localita', 'listing' => 'listing'], null, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('menu_macrolocalita_id', 'Macro Località', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::select('menu_macrolocalita_id', ["" => ""] + $data['macrolocalita'], null, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('menu_localita_id', 'Località', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::select('menu_localita_id', ["" => ""], null, ['class' => 'form-control', 'data-id' => $data["record"]->menu_localita_id]) !!}
					</div>
				</div>
		
			</div>
		</div>

		<div class="panel panel-default" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">Campi SEO</div>
			</div>

			<div class="panel-body">

				<div class="form-group">
					{!! Form::label('uri', 'URI', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::text('uri', null, ['placeholder' => 'URI', 'class' => 'form-control']) !!}
						<p class="help-block">
							Alcune URI particolari hanno al loro interno il placeholder <em>{CURRENT_YEAR}</em>. 
							<strong>Non è un errore!</strong> serve a gestire le pagine tipo <em>/estate-2016/cattolica.php</em> e i vari redirect 301 dagli anni precedente all'anno attuale.
						</p>
					</div>
				</div>
				
				 <div class="form-group">
	                {!! Form::label('alternate', 'Pagine in lingua', ['class' => 'col-sm-2 control-label']) !!}
	                @php $class=""; @endphp
                    @foreach($data["langs"] as $lang)
                        @if ($lang != $data["record"]->lang_id)
                            <div style="margin-bottom:5px; position:relative; " class="col-sm-10 {{$class}}">
                                {!! Form::text('alternate_' . $lang , $data["alternate_uri"][$lang], ['placeholder' => 'Link alternate', 'class' => 'form-control', "style" => "padding-left:40px;"]) !!}
                                <div class="langTag">{{$lang}}</div>
                            </div>
                            @php $class="col-sm-offset-2"; @endphp
                        @endif
                    @endforeach
	            </div>
			
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        Link associati alla pagina (bilaterale).
                    </div>
                </div>
			      	
				<div class="form-group">
					{!! Form::label('ancora', 'Ancora', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::text('ancora', null, ['placeholder' => 'Ancora', 'class' => 'form-control']) !!}
						<p class="help-block">compare nei link che puntano a questa pagina</p>
					</div>
				</div>
			
				<div class="form-group">
					
					{!! Form::label('seo_title', 'Title', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10 cont_caratteri">
						
						{!! Form::text('seo_title', null, ['placeholder' => 'Title', 'class' => 'form-control cont_caratteri_input']) !!}
						<a data-link="seo_title" href="#" class="tag">{HOTEL_COUNT}</a> 
						<a data-link="seo_title" href="#" class="tag">{PREZZO_MIN}</a>
						<a data-link="seo_title" href="#" class="tag">{PREZZO_MAX}</a>
						<a data-link="seo_title" href="#" class="tag">{OFFERTE_COUNT}</a>
						<a data-link="seo_title" href="#" class="tag">{LOCALITA}</a>
						<a data-link="seo_title" href="#" class="tag">{MACRO_LOCALITA}</a>
						<a data-link="seo_title" href="#" class="tag">{CURRENT_YEAR}</a>
						<a data-link="seo_title" href="#" class="tag">{CURRENT_MONTH}</a>
						
						<span class="cont_caratteri">0</span>   
					</div>
					
				</div>
				
				<div class="form-group">
				
					{!! Form::label('seo_description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
					
					<div class="col-sm-10 cont_caratteri">
						
						{!! Form::textarea('seo_description', null, ['placeholder' => 'Description', 'class' => 'form-control cont_caratteri_input']) !!}
						<a data-link="seo_description" href="#" class="tag">{HOTEL_COUNT}</a> 
						<a data-link="seo_description" href="#" class="tag">{PREZZO_MIN}</a>
						<a data-link="seo_description" href="#" class="tag">{PREZZO_MAX}</a>
						<a data-link="seo_description" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="seo_description" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="seo_description" href="#" class="tag">{MACRO_LOCALITA}</a>
						<a data-link="seo_description" href="#" class="tag">{CURRENT_YEAR}</a>
						<a data-link="seo_title" href="#" class="tag">{CURRENT_MONTH}</a>
						<span class="cont_caratteri">0</span>   
						
					</div>
					
				</div>
			
			</div>

		</div>
	
	    <div class="panel panel-default config-visibilita" @if ($data["spot_attivo"] == 0) data-collapsed="1" @else data-collapsed="0" @endif>
		
		    <div class="panel-heading">
                <div class="panel-title form-group" style="float:none; margin-bottom: 0;">
	      
	      		<div class="col-sm-2">Mostra in homepage</div>

	      		<div class="col-sm-10">
					<label>{!! Form::radio('spot_attivo', "0", $data["spot_attivo"] == 0 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo1']) !!} da nessuna parte</label>&nbsp;&nbsp;&nbsp;
					<label>{!! Form::radio('spot_attivo', "1", $data["spot_attivo"] == 1 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo2']) !!} nelle <b>Evidenze</b></label>&nbsp;&nbsp;&nbsp;
					<label>{!! Form::radio('spot_attivo', "2", $data["spot_attivo"] == 2 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo3']) !!} nelle <b>Offerte</b></label>&nbsp;&nbsp;&nbsp;
					<label>{!! Form::radio('spot_attivo', "3", $data["spot_attivo"] == 3 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo4']) !!} nei <b>Link secondari</b></label>&nbsp;&nbsp;&nbsp;
				</div>
	            </div>
	        </div>
		
            <div class="panel-body">
                
                <div class="form-group">
                    {!! Form::label('spot_ordine', 'Ordine', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-1">
                        {!! Form::text('spot_ordine', $data["spot_ordine"], ['placeholder' => '10', 'class' => 'form-control']) !!}
                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('spot_h1', 'Titolo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_h1', $data["spot_h1"], ['placeholder' => 'Titolo', 'class' => 'form-control']) !!}
                        <a data-link="spot_h1" href="#" class="tag">{HOTEL_COUNT}</a> 
                        <a data-link="spot_h1" href="#" class="tag">{PREZZO_MIN}</a>
                        <a data-link="spot_h1" href="#" class="tag">{PREZZO_MAX}</a>
                        <a data-link="spot_h1" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="spot_h1" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="spot_h1" href="#" class="tag">{MACRO_LOCALITA}</a>
                        <a data-link="spot_h1" href="#" class="tag">{CURRENT_YEAR}</a>
                        <a data-link="spot_h1" data-text="{{\Carbon\Carbon::now()->format('D j F Y')}}" href="#" class="tag2"><b>[DATE]</b></a> 
                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('spot_h2', 'Sottotitolo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_h2', $data["spot_h2"], ['placeholder' => 'es: 1-3 Giugno, 2 Luglio {CURRENT_YEAR}, Tutto quello ch vuoi ...', 'class' => 'form-control']) !!}
                        <a data-link="spot_h2" href="#" class="tag">{HOTEL_COUNT}</a> 
                        <a data-link="spot_h2" href="#" class="tag">{PREZZO_MIN}</a>
                        <a data-link="spot_h2" href="#" class="tag">{PREZZO_MAX}</a>
                        <a data-link="spot_h2" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="spot_h2" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="spot_h2" href="#" class="tag">{MACRO_LOCALITA}</a>
                        <a data-link="spot_h2" href="#" class="tag">{CURRENT_YEAR}</a>
                        <a data-link="spot_h2" data-text="{{\Carbon\Carbon::now()->format('D j F Y')}}" href="#" class="tag2"><b>[DATE]</b></a> 

                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('spot_colore', 'Colore tematico', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_colore', $data["spot_colore"], ['placeholder' => 'es: #ff0000', 'class' => 'form-control','style' => "display:inline-block; width:30%;"]) !!}
                    </div>
                </div>
                
                <div class="form-group">
                    
                    {!! Form::label('spot_immagine', 'Immagine', ['class' => 'col-sm-2 control-label']) !!}
                    {!! Form::hidden('spot_immagine', $data["spot_immagine"], ['class' => 'form-control dropzoneFileUpload', 'id' => 'spot_image']) !!}
                    
                    <div class="col-sm-10">
                        <div class="dropzone dropzone_singola" id="dropzoneFileUpload" data-path="immagini-spot" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block;
                                @if( $data["spot_immagine"] != "") background: #f5f5f5 url('{{ Utility::assetsLoaded('/romagna/spothome/600x290/'.$data['spot_immagine'])}}') top center no-repeat; @endif
                            ">
                        </div>
                        <div class="dz-default dz-message">
                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUpload" class="deleteImage">Elimina immagine</a><br>
                            <span>Note: Partire da una immagine di dimensioni 1200x580px </span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('spot_descrizione', 'Descrizione', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('spot_descrizione', $data["spot_descrizione"], ['placeholder' => 'Descrizione', 'class' => 'form-control ckeditor-small']) !!}
                        <a data-link="spot_h1" href="#" class="tag">{HOTEL_COUNT}</a> 
                    </div>
                </div>
                
                <div class="form-group">
                    {!! Form::label('menu_dal', 'Visibile dal', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <input id="reportrange_spot_visibilita-start" class="form-control" type="text" name="spot_visibile_dal" value="{{ (isset($data["spot_visibile_dal"]) && Utility::getLocalDate($data["spot_visibile_dal"],'%d/%m/%y') != "" ) ? $data["spot_visibile_dal"]->format('d/m/Y') : ''}}" style="width:150px; display:inline-block"> al
                        <input id="reportrange_spot_visibilita-end"   class="form-control" type="text" name="spot_visibile_al"  value="{{ (isset($data["spot_visibile_al"]) && Utility::getLocalDate($data["spot_visibile_al"],'%d/%m/%y')  != "" ) ? $data["spot_visibile_al"]->format('d/m/Y')  : ''}}" style="width:150px; display:inline-block">
                        @if(isset($data["spot_visibile_dal"]->menu_dal) &&  Utility::isValidMenu($data["spot_visibile_dal"]->format('Y-m-d'), $data["spot_visibile_al"]->format('Y-m-d'), null, $data["spot_visibile_ricorsivo"]))
                            <span style="display:inline-block; margin-left: 30px; "><b style="color: green;">Spot attivo per la data di oggi</b></span>
                        @endif
                    </div>
                    <div class="col-sm-10 col-sm-offset-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('spot_visibile_ricorsivo', "1", $data["spot_visibile_ricorsivo"], ["id"=>'spot_visibile_ricorsivo']) !!} Rinnovo annuale automatico
                            </label>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    
	    <div class="panel panel-default config-visibilita-footer" @if ($data["spot_attivo_footer"] == 0) data-collapsed="1" @else data-collapsed="0" @endif>
			
			<div class="panel-heading">
				<div class="panel-title form-group" style="float:none; margin-bottom: 0;">
		      
		      		<div class="col-sm-2">Mostra nel Footer</div>
		      		<div class="col-sm-10">
						<label>{!! Form::radio('spot_attivo_footer', "0", $data["spot_attivo_footer"] == 0 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer1']) !!} da nessuna parte</label>&nbsp;&nbsp;&nbsp;
						<label>{!! Form::radio('spot_attivo_footer', "1", $data["spot_attivo_footer"] == 1 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer2']) !!} {{trans("title.informazioni_footer")}}</label>&nbsp;&nbsp;&nbsp;
						<label>{!! Form::radio('spot_attivo_footer', "2", $data["spot_attivo_footer"] == 2 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer3']) !!} {{trans("title.link_utili_footer")}}</label>&nbsp;&nbsp;&nbsp;
						<label>{!! Form::radio('spot_attivo_footer', "3", $data["spot_attivo_footer"] == 3 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer4']) !!} {{trans("title.eventi_fiere_footer")}}</label>&nbsp;&nbsp;&nbsp;
					</div>
		      
		   		</div>
			</div>
			
			<div class="panel-body">
				
				<div class="form-group">
														
					{!! Form::label('spot_ordine_footer', 'Ordine', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-1">
						 {!! Form::text('spot_ordine_footer', $data["spot_ordine_footer"], ['placeholder' => '10', 'class' => 'form-control']) !!}
					</div>
					
				</div>
			
			</div>
			
	    </div>
    
        <div class="panel panel-default" data-collapsed="0">

            <div class="panel-heading"><div class="panel-title">Header</div></div>
            <div class="panel-body">
	  
                <div class="form-group">
                    {!! Form::label('h1', 'Titolo header (h1)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                                
                        {!! Form::text('h1', null, ['placeholder' => 'Titolo pagina', 'class' => 'form-control cont_caratteri_input']) !!}
                        <a data-link="h1" href="#" class="tag">{HOTEL_COUNT}</a> 
                        <a data-link="h1" href="#" class="tag">{PREZZO_MIN}</a>
                        <a data-link="h1" href="#" class="tag">{PREZZO_MAX}</a>
                        <a data-link="h1" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="h1" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="h1" href="#" class="tag">{MACRO_LOCALITA}</a>
                        <a data-link="h1" href="#" class="tag">{CURRENT_YEAR}</a>
                
                    </div>
                </div>
            
                <div class="form-group">
                        
                    {!! Form::label('immagine', 'Immagine', ['class' => 'col-sm-2 control-label']) !!}
                    {!! Form::hidden('immagine', null , ['class' => 'form-control dropzoneFileUploadEvidenza', 'id' => 'immagine']) !!}
                    
                    <div class="col-sm-10">
                        
                        <div class="dropzone dropzone_singola" id="dropzoneFileUploadEvidenza" data-path="immagini-evidenza" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block;
                            @if( $data["record"]->immagine != "")
                                background: transparent url('{{config("app.cdn_online") . '/images/romagna/pagine/600x290/'. $data['record']->immagine}}') center center no-repeat;
                            @endif
                            ">
                        </div>
                        
                        <div class="dz-default dz-message">
                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadEvidenza" class="deleteImage">Elimina immagine</a><br>
                        </div>
                        
                    </div>
                    
                </div>
        
                <div class="form-group">
                    {!! Form::label('descrizione_1', 'Testo header', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('descrizione_1', null, ['id'=>'descrizione_1','placeholder' => 'Testo header', 'class' => 'form-control ckeditor']) !!}
                    </div>
                </div>
            
            </div>

        </div>
	
        <div class="panel panel-default config-vetrina" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Vetrina associata alla pagina</div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('vetrina_id', 'Vetrina associata', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('vetrina_id', ["" => "Nessuna vetrina associata"] + ["-1" => "Pseudo Vetrina Riviera Romagnola"] + $data["vetrine"], null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default config-listing" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">
                    <label>
                        {!! Form::checkbox('listing_attivo', "1", null , ["id"=>"listing_attivo"]) !!} Questa pagina <strong>contiene un listing</strong>
                    </label>
                </div>
            </div>
        
            <div class="panel-body">

                <p class=" col-sm-offset-2">Imposta i filtri per l'estrazione delle strutture che compongono il listing di questa pagina.</p>

                <div class="form-group">
                    {!! Form::label('listing_macrolocalita_id', 'Macro Località', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('listing_macrolocalita_id', $data['macrolocalita'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('listing_localita_id', 'Località', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('listing_localita_id', ["" => ""], null, ['class' => 'form-control', 'data-id' => $data["record"]->listing_localita_id]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Categoria</label>
                    @foreach($data['categorie'] as $id => $name)
                        <div class="col-sm-1">
                            <div class="checkbox">
                                <label>
                                {!! Form::checkbox('listing_categorie_ids[]', $id, in_array($id, $data["record"]->splitCommaSeparatedString("listing_categorie")) ) !!}
                                {{ $name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tipologia</label>
                    @foreach($data['tipologie'] as $id => $name)
                        <div class="col-sm-1">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('listing_tipologie_ids[]', $id, in_array($id, $data["record"]->splitCommaSeparatedString("listing_tipologie")) ) !!}
                                    {{ $name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Trattamento</label>
                    @foreach(["ai" => "All Inclusive", "pc" => "Pensione Completa", "mp" => "Mezza Pensione", "bb" => "Bed and Breakfast", "sd" => "Solo Dormire", "obb" => "ONLY Bed and Breakfast"] as $id => $name)
                        <div class="col-sm-1">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('listing_trattamenti_ids[]', $id, in_array($id, $data["record"]->splitCommaSeparatedString("listing_trattamento")),  ['id' => 'listing_' . $id] ) !!}
                                    {{ $name }}
                                </label>
                            </div>
                        </div> 
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Strutture </label>
                    <div class="col-sm-1">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('listing_suite', "1", null) !!} con suite
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="checkbox">
                            <label>
                            {!! Form::checkbox('listing_design', "1", null) !!} Design
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('listing_family', "1", null) !!} Family
                            </label>
                        </div>
                    </div>

                </div>

                <p class="col-sm-offset-2 mt-5" style="margin-top:30px;">Per i filtri <strong>Categoria</strong>, <strong>Tipologia</strong>, <strong>Trattamento</strong> se non spunti nessuna voce, significa "tutto"</p>

                <div class="form-group">
                    {!! Form::label('listing_parolaChiave_id', 'Offerte che contengono parole', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::select('listing_parolaChiave_id', ["" => ""] + $data['parole_chiave'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('listing_gruppo_servizi_id', 'Servizi', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::select('listing_gruppo_servizi_id', ["" => ""] + $data['gruppo_servizi'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('listing_offerta', 'che contengono almeno 1 ', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::select('listing_offerta', ["" => ""] + ['offerta' => 'Offerta', 'lastminute' => 'Last minute'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('listing_offerta_prenota_prima', 'che contengono almeno 1 ', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::select('listing_offerta_prenota_prima', ["" => ""] + ['offerta_prenota_prima' => 'Offerta Prenota Prima'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('indirizzo_stradario', 'Indirizzo stradario', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::text('indirizzo_stradario', $data["record"]->indirizzo_stradario, ['placeholder' => 'Indirizzo stradario', 'class' => 'form-control', 'readonly' => 'true']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('listing_puntoForzaChiave_id', 'Punti di forza che contengono parole', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::select('listing_puntoForzaChiave_id', ["" => ""] + $data['puntiForza_chiave'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    
                    {!! Form::label('listing_poi_id', 'Punti di interesse (a x metri dal punto )', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-2">
                        {!! Form::text('listing_poi_dist', $data["listing_poi_dist"], ['placeholder' => 'distanza in metri', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-8">
                        {!! Form::select('listing_poi_id', ["" => ""] + $data['poi'], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-3">
                    <div class="checkbox">

                    <label>
                    {!! Form::checkbox('listing_bonus_vacanze_2020', "1", null) !!} Bonus vacanze 2020
                    </label><br />

                    <label>
                    {!! Form::checkbox('listing_whatsapp', "1", null) !!} WhatsApp
                    </label><br />

                    <label>
                    {!! Form::checkbox('listing_green_booking', "1", null) !!} Green Booking
                    </label><br />

                    <label>
                    {!! Form::checkbox('listing_eco_sostenibile', "1", null) !!} Ecosostenibile
                    </label><br />

                    {{Form::hidden('listing_coupon', 0)}}

                    <label>
                    {!! Form::checkbox('listing_bambini_gratis', "1", null) !!} Bambini gratis
                    </label><br />

                    <label>
                    {!! Form::checkbox('listing_annuali', "1", null) !!} Annuali
                    </label><br />

                    <label>
                    {!! Form::checkbox('listing_preferiti', "1", null) !!} Preferiti
                    </label>
                    </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="panel panel-default config-faq" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Faq</div>
            </div>
            <div class="panel-body">
                <p>Aggiungi il codice FAQ di schema Org</p>
                <div>
                    <div class="col-sm-6" style="background: #10111A; min-height:500px;">
                        <textarea id="editor" placeholder="Inserisci il testo schema " style="min-height:500px !important; " name="faq">{{$data["record"]["faq"]}}</textarea>
                    </div>
                    <div class="col-sm-6">
                        <div id="output"><iframe id="result" style="width:100%; min-height:500px;"></iframe></div>
                    </div>
                </div>
            </div>
        </div>
    
	    @php $indice = 1; @endphp
        @if ( $data["record"]->descrizione_2 != "") {	
            @php $contenuti = json_decode($data["record"]->descrizione_2);@endphp
            @foreach($contenuti as $contenuto):
                @switch($contenuto->tipocontenuto)
                    @case("text")
                        <div id="pannello-h2-{{$indice}}" class="pannello-h2-{{$indice}} panel panel-default"  data-collapsed="0">
                            <input type="hidden" value = "text" name="tipocontenuti[]" />

                            <div class="panel-heading">
                                <div class="panel-title">Contenuto testuale #{{$indice}}</div>
                            </div>
                            
                            <div class="panel-body">

                                <input type="hidden" value = "1col" name="layout[]"/>
                                
                                <div class="form-group">
                                    <label for="immagine_secondaria" class="col-sm-2 control-label">Immagine</label>
                                    <input type="hidden" name="immagine_secondaria[]" value="{{$contenuto->immagine}}" class="form-control dropzoneFileUploadSecondario_{{$indice}}" id="immagine_secondaria_{{$indice}}" />
                                    <div class="col-sm-10">
                                    <div class="dropzone dropzone_singola" id="dropzoneFileUploadSecondario_{{$indice}}" data-indice="{{$indice}}"  data-path="immagini-evidenza" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block; @if ($contenuto->immagine != "") {{ 'background: transparent url(' . config("app.cdn_online") .'/images/romagna/pagine/600x290/'. $contenuto->immagine . ') top center no-repeat;' }} @endif"></div>
                                        <div class="dz-default dz-message">
                                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadSecondario_{{ $indice }}" class="deleteImage">Elimina immagine</a><br>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="h2" class="col-sm-2 control-label">Titolo</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="h2[]" placeholder="Titolo" value="{{ $contenuto->h2 }}" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="h3" class="col-sm-2 control-label">Sottotitolo</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="h3[]" placeholder="Sottotitolo" value="{{ $contenuto->h3 }}" />
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label for="descrizione_2" class="col-sm-2 control-label">Testo</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control ckeditor ckeditor-add-{{ $indice }}" placeholder="Testo" name="descrizione_2[]">{{$contenuto->descrizione_2}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                            <button class="delContentButton btn btn-danger" data-index="{{ $indice }}" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @break;
                
                    @case("gallery")
    
                        <div id="pannello-h2-{{$indice}}" class="pannello-h2-{{$indice}} panel panel-default" data-collapsed="0">
                            
                            <input type="hidden" value="gallery" name="tipocontenuti[]"/>

                            <div class="panel-heading">
                                <div class="panel-title">Galleria fotografica #{{ $indice }}</div>
                            </div>
                            
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="immagine_secondaria" class="col-sm-2 control-label">Immagini</label>
                                    <div class="col-sm-10">
                                        <div class="dropzone dropzone_gallery" id="dropzoneFileUploadSecondarioGallery_{{ $indice }}" data-indice="{{ $indice }}"  data-path="pagine" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block; background: #eee url('/images/admin/photo.png') center center no-repeat"></div>
                                        <div class="dz-default dz-message">
                                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong><br>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div id="dropzoneFileUploadSecondarioGallery_{{ $indice }}_list" class="form-group sortable">
                                    @if(count($contenuto->galleria))
                                        @for( $i=0; $i<count($contenuto->galleria->immagini); $i++)
                                            <div class="row" style="margin-bottom: 5px;">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
                                                    <input type="hidden" name="immagine_gallery[{{$indice-1}}][]" value="{{$contenuto->galleria->immagini[$i]}}" />
                                                    <img src="{{Utility::assetsLoaded("images/romagna/pagine/300x200/" . $contenuto->galleria->immagini[$i] )}}" width="150" height="100" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control" name="testo_gallery[{{ $indice-1 }}][]">{{$contenuto->galleria->testo[$i] }}</textarea>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="#" class="btn btn-danger" onclick="delThisPhoto(this); return false;">Elimina</a>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                                    
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="delGallerytButton btn btn-danger" data-index="{{ $indice }}" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>
                                        
                            </div>
                            
                        </div> 
                        
                        @break;
                    
                    @case("map")

                        <div id="pannello-h2-{{$indice}}" class="pannello-h2-{{$indice}} panel panel-default" data-collapsed="0">
                            
                            <input type="hidden" value="map" name="tipocontenuti[]"/>
                            <div class="panel-heading">
                                <div class="panel-title">Mappa e punti di interese #{{$indice}}</div>
                            </div>
                            
                            <div class="panel-body">
                                                        
                                <div class="form-group" >
                                    <label for="layout" class="col-sm-2 control-label">Latitudine / Longitudine</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="map_lat_lon[]" value="{{$contenuto->map_lat_lon}}" placeholder="es: 44.0535197,12.5395819"/>
                                        <br /><label>
                                            <input name="map_source[]" type="checkbox" value="1" @if($contenuto->map_source) checked @endif > includi il listing attivo
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="delMapButton btn btn-danger" data-index="{{ $indice }}" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>
                                        
                            </div>
                            
                        </div>
                    
                        @break;
                @endswitch
                @php $indice++; @endphp
            @endforeach
        @endif
	
        <div class="aggiungiContenuto" style="text-align: center; ">
            <div  style="padding:50px; ">
                <button class="addContentButton btn btn-info" data-index='{{ $indice }}' type="button" onclick="addContentSecondary(this);">Aggiungi blocco BODY COPY</button>
                @php /*
                <button class="addGalleryButton btn btn-info" data-index='{{ $indice }}' type="button" onclick="addGalleryButton(this);">Aggiungi una gallery fotografica</button>
                <button class="addMapButton btn btn-info" data-index='{{ $indice }}' type="button" onclick="addMapButton(this);">Aggiungi una mappa</button>
                */ @endphp
            </div>
        </div>
	
        <div class="form-group">
            <div class=" col-sm-12">
                @include('templates.admin_inc_record_buttons')
            </div>
        </div>

    {!! Form::close() !!}

@endsection

@section('onheadclose')

  	<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/daterangepicker/daterangepicker.min.css', true)}}" />
  	<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/palettecolor/palette-color-picker.min.css', true)}}" />
  	<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.css')}}" />
      
	<script type="text/javascript" src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
	<script type="text/javascript" src="{{Utility::assets('/vendor/daterangepicker/daterangepicker.min.js', true)}}"></script>
	<script type="text/javascript" src="{{Utility::assets('/vendor/palettecolor/palette-color-picker.min.js', true)}}"></script>
	<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.js')}}"></script>

	<!-- Loading css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/material-ocean.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/show-hint.css">

    <!-- Loading js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/xml/xml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/css/css.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/htmlmixed/htmlmixed.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/edit/matchbrackets.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/show-hint.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/javascript-hint.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/html-hint.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/xml-hint.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/addon/hint/css-hint.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/keymap/sublime.js"></script>
    <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/tinymce/tinymce.min.js')}}"></script>
	
	<script  type="text/javascript">

		function chainLocalita(el_father, el_child, is_listing) {

			var loc = @php echo $data["localita_chained"] @endphp;
			var m_id = el_father.val()
			
			if(m_id){
				
				var localita_id = el_child.data('id');
				el_child.empty();
			
				/**
				 * Voglio che le options siano ordinate per alfabeticamente
				 */
				 
				var locarray = [];
				jQuery.each(loc[m_id], function(localita_id, localita_nome){
				 	locarray.push({'id': localita_id, 'nome': localita_nome});
				});	
			
				locarray.sort(function(a, b){
					return a.nome.toLowerCase().localeCompare(b.nome.toLowerCase());
				});
						
				/**
				 * Se la macrolocalita contiene una sola localita,
				 * la select delle localita obbligherà ad una scelta (non ci sarà la voce "vuota")
				 * se invece la macrolicalita ha più localita,
				 * la select delle localita renderà possibile non indicare nulla (ci sarà la voce "vuota")
				 */
			 
				if(is_listing === true){
	
					if(locarray.length > 1)
						locarray.unshift({'id': '', 'nome': ''});
					  
				} else
					locarray.unshift({'id': '', 'nome': ''});
			
				jQuery.each(locarray, function(i, el){
					var opt = jQuery('<option></option>')
					  .attr('value', el.id)
					  .attr('selected', localita_id == el.id)
					  .text(el.nome);
					el_child.append(opt);
				});
				
			}
		}
		
		function addContentSecondary() {
			
		}
	
		jQuery(document).ready(function(){
		
			function conteggio ( obj) {
			
				var result = obj.val();
				return result.length; 
			
			}
		
			jQuery(".cont_caratteri_input").each(function () {
		
				jQuery(this).parent().find("span.cont_caratteri").text( conteggio(jQuery(this)) );
				
			}).keyup(function (e) {
				
				jQuery(this).parent().find("span.cont_caratteri").text(  conteggio(jQuery(this)) );
				
			});
		
			jQuery(".tag").click( function (e){
			
				var me = jQuery('#' + jQuery(this).data("link"));
				var text = 	me.val();
				var valore = jQuery(this).text();
				var newtext = text.substr(0, me[0].selectionStart) + valore + text.substr(me[0].selectionEnd, text.length);
				
				me.val(newtext);
				
				jQuery(this).parent().find("span.cont_caratteri").text( conteggio( me ) );
				
				e.preventDefault();
		
			});
			
			jQuery(".tag2").click( function (e){
			
				var me = jQuery('#' + jQuery(this).data("link"));
				var text = 	me.val();
				var valore = jQuery(this).data("text")
				var newtext = text.substr(0, me[0].selectionStart) + valore + text.substr(me[0].selectionEnd, text.length);
				
				me.val(newtext);		
				jQuery(this).parent().find("span.cont_caratteri").text( conteggio( me ) );
				
				e.preventDefault();
		
			});
						
		
			var el_listing_father = jQuery('#listing_macrolocalita_id');
			var el_listing_child = jQuery('#listing_localita_id');
			
			var el_menu_father = jQuery('#menu_macrolocalita_id');
			var el_menu_child = jQuery('#menu_localita_id');
		
			chainLocalita(el_listing_father, el_listing_child, true);
		
			el_listing_father.change(function(){
				chainLocalita(el_listing_father, el_listing_child, true);
			});
		
			chainLocalita(el_menu_father, el_menu_child, false);
		
			el_menu_father.change(function(){
				chainLocalita(el_menu_father, el_menu_child, false);
			});
		
		});
	
	</script>
	
    <style>

        #output iframe{  border:none; }

        .CodeMirror {
                position: absolute; 
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                height: 100%;
                width: 100%;
        }
		
		.tag { font-size:10px; padding:3px 6px; background: #f0f0f0; display: inline-block; border:1px solid #ddd; margin-top: 5px; margin-right:2px;}
		.cont_caratteri { position:relative; }
		span.cont_caratteri { position:absolute; top:0; right:15px; padding:3px; background: #666; color:#fff; font-size: 10px;  }
		
		.dropzone { min-height: 290px; width:600px; padding:0;  }		
		.dropzone img { width:100%; height:auto; }
		.dropzone .dz-default.dz-message { background: none; }
		
		.aggiungiContenuto { width:100%;  margin-bottom: 30px;  }
		
		.image-layout { border:2px solid #fff; opacity: 0.5}
		.image-layout:hover {opacity: 1}
		.image-layout.selected { border:2px solid #788B9C; opacity: 1;}
		
		.palette-color-picker-button { margin:0 0 0 8px; vertical-align: middle; }
		
		.langTag {
		
			position:absolute; top:1px; left:16px;
			width:30px; 
			height:29px; 
			background: #f5f5f5;
			line-height: 29px; 
			text-align: center; 
			-webkit-border-radius: 2px 0 0 2px;
			-moz-border-radius: 2px 0 0 2px;
			border-radius: 2px 0 0 2px;
			border-right:1px solid #ddd; 
			
		}
		
	</style>

@endsection

@section('onbodyclose')

    <script type="text/javascript">

        function checkTrattamenti () {
            let d = false;
            if (jQuery("#listing_obb").is(":checked")) { d = true; }
            jQuery("#listing_ai").prop('disabled', d);
            jQuery("#listing_pc").prop('disabled', d);
            jQuery("#listing_mp").prop('disabled', d);
            jQuery("#listing_bb").prop('disabled', d);
            jQuery("#listing_sd").prop('disabled', d);
        }

        jQuery(document).ready(function(){
            jQuery("#listing_obb").click(function () {
                checkTrattamenti();
            })
        })
        checkTrattamenti();


        var dropZoneArray = [];
            
        /**
        * Avvia il tinymce
        * lo script ckeditor è stato sostituito perchè non era possibile configurre l'upload in maniera facile
        * I File vengono salvati con IL NOME ORIGINALE nella cartella /images/statiche/desktop/***file***.com
        */
    
        jQuery(document).ready(function(){
            
            var validation = false;
            var myTimeoutId = null;

            var config = {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                keyMap:"sublime",
                tabSize:4,
            };

            var editor = CodeMirror.fromTextArea(document.getElementById('editor'),config);
            editor.setOption("theme", "material-ocean");

            function loadHtml(html) {
                const document_pattern = /( )*?document\./i;
                let finalHtml = html.replace(document_pattern, "document.getElementById('result').contentWindow.document.");
                jQuery('#result').contents().find('html').html(finalHtml);
            }

            loadHtml(jQuery('#editor').val());

            editor.on('change',function(cMirror){
                if (myTimeoutId!==null) {
                    clearTimeout(myTimeoutId);
                }
                myTimeoutId = setTimeout(function() {
                    try{
                        loadHtml(cMirror.getValue());
                    }catch(err){
                        console.log('err:'+err);
                    }
                }, 1000);
            });

            jQuery("#listing_attivo").change(function () {
                
                var io = jQuery(this);
                                
                if (io.is(":checked"))
                    jQuery(".config-listing").attr("data-collapsed" , 0);
                else
                    jQuery(".config-listing").attr("data-collapsed" , 1);
                
            }).trigger("change");

            jQuery(".spot_attivo").change(function () {
                
                var io = jQuery(this);
            

                if (io.val() != 0)
                    jQuery(".config-visibilita").attr("data-collapsed" , 0);
                else
                    jQuery(".config-visibilita").attr("data-collapsed" , 1);
                
            });
            
            jQuery(".spot_attivo_footer").change(function () {
                
                var io = jQuery(this);
            
                if (io.val() != 0)
                    jQuery(".config-visibilita-footer").attr("data-collapsed" , 0);
                else
                    jQuery(".config-visibilita-footer").attr("data-collapsed" , 1);
                
            });
            
            /**
            * Color palette
            */
            
            jQuery('#spot_colore').paletteColorPicker({

                colors: ["#ffffff", "#222222", "#8CC152", "#26A69A", "#1FB0E1", "#1A5C87", "#F24A46" , "#EC407A", "#F1B000", "#FF9326", "#8D6E63" ],
                insert: 'after', // default -> 'before'
                clear_btn: 'last', // null -> without clear button, default -> 'first'
            });
            
            /**
            * Date picker
            */
            
            moment.locale('it');
            
            @if( isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_dal,'%d/%m/%y') != "" )
                
                var start = moment("{{$data["record"]->menu_dal->format('Y-m-d')}}");
                var end = moment("{{$data["record"]->menu_al->format('Y-m-d')}}");

            @else
                
                var start = moment();
                var end = moment().add(1, 'year');
                jQuery("#menu_auto_annuale").prop("checked", "checked" );

            @endif

            function cambiaData () {

                var start 	= moment(jQuery('#reportrange-start').val() , "DD-MM-YYYY");
                var end 	= moment(jQuery('#reportrange-end').val() 	, "DD-MM-YYYY");
            
                if(end.isBefore(start)) {

                    end = start.add(1,"d");
                    jQuery('#reportrange-end')
                        .val( end.format("DD/MM/YYYY") )
                        .data('daterangepicker')
                            .setStartDate(start);

                    jQuery('#reportrange-end')
                        .data('daterangepicker')
                        .setMinDate(start);


                } 
            }

            jQuery('#reportrange-start').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: start,
                opens: "right",
                locale: {
                format: 'DD/MM/YYYY'
                }
            });

            jQuery('#reportrange-end').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: end,
                opens: "right",
                locale: {
                format: 'DD/MM/YYYY'
                }
            });
            
            jQuery('#reportrange-start, #reportrange-end').change( cambiaData );
            cambiaData();
            
            @if( isset($data["spot_visibile_dal"]) && Utility::getLocalDate($data["spot_visibile_dal"],'%d/%m/%y') != "" )
                
                var start_spot_visibilita = moment("{{$data["spot_visibile_dal"]->format('Y-m-d')}}");
                var end_spot_visibilita = moment("{{$data["spot_visibile_al"]->format('Y-m-d')}}");

            @else

                var start_spot_visibilita = moment();
                var end_spot_visibilita = moment().add(1, 'year');

            @endif
            
            function cambiaData2() {

                var start 	= moment(jQuery('#reportrange_spot_visibilita-start').val() , "DD-MM-YYYY");
                var end 	= moment(jQuery('#reportrange_spot_visibilita-end').val() 	, "DD-MM-YYYY");
            
                if(end.isBefore(start)) {

                    end = start.add(1,"d");
                    jQuery('#reportrange_spot_visibilita-end')
                        .val( end.format("DD/MM/YYYY") )
                        .data('daterangepicker')
                            .setStartDate(start);

                    jQuery('#reportrange_spot_visibilita-end')
                        .data('daterangepicker')
                        .setMinDate(start);

                } 
            
            }
            
            jQuery('#reportrange_spot_visibilita-start').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: start_spot_visibilita,
                opens: "right",
                locale: {
                format: 'DD/MM/YYYY'
                }
            });

            jQuery('#reportrange_spot_visibilita-end').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: end_spot_visibilita,
                opens: "right",
                locale: {
                format: 'DD/MM/YYYY'
                }
            });
        
            jQuery('#reportrange_spot_visibilita-start, #reportrange_spot_visibilita-end').change( cambiaData2 );
            cambiaData2();
            

                        
        });

        /**
        * Dropzone
        */
        
        var baseUrl = "{{ url('/') }}";
        var token = "{{ Session::token() }}";
        
        Dropzone.autoDiscover = false;
        
        function initDropZone (tipo ) {
                            
            jQuery(".dropzone_" + tipo).not(".init").each(function (i) {
                
                var dz = jQuery(this);
                var id = dz.attr("id");
                var path = dz.data("path");
                var service = dz.data("service");
                var indice = dz.data("indice");
                
                dz.addClass("init");
                

                var options = {
                    
                    maxFilesize: 5, // MB
                    createImageThumbnails: false,
                    url: baseUrl+"/admin/"+path+"/" + service,
                    params: { _token: token }	
                    
                }
                
                if (tipo == "gallery") {
                    
                    options.uploadMultiple = true;
                    previewTemplate: '<img data-dz-thumbnail id="src'+id+i+'" />';
                    
                } else {
                    
                    previewTemplate: '<img data-dz-thumbnail id="src'+id+i+'" style="display:none"/>';
                    
                }
                
                dropZoneArray[i] = new Dropzone("div#" + id, options);
                
                if (tipo == "singola") {
                    
                    dropZoneArray[i].on("success", function(file, response) {
                        
                        jQuery("div#" + id).css("background", '#eee url("'+response[1]+'") center center no-repeat');
                        jQuery("." + id).val(response[0]);
                        
                    });
                    
                    dropZoneArray[i].on("error", function(file, error) {
                        
                        alert(error);
                        jQuery("." + id).val("");
                        
                    });
                
                } else {
                    
                    dropZoneArray[i].on("success", function(file, response) {
                        
                        jQuery("div#" + id).css("background", 'none');
                        
                        var html = '<div class="row" style="margin-bottom: 5px;">' +
                                        '<div class="col-sm-2"></div>' +
                                        '<div class="col-sm-2">' +
                                            '<input type="hidden" name="immagine_gallery['+(indice-1)+'][]" value="'+response[0]+'">' +
                                            '<img src="{{Utility::assetsLoaded("images/romagna/pagine/300x200/")}}/' + response[0] +'" width="150" height="100">' +
                                        '</div>' +
                                        '<div class="col-sm-6">' +
                                            '<textarea class="form-control" name="testo_gallery['+(indice-1)+'][]"></textarea>' +
                                        '</div>' +
                                        '<div class="col-sm-2">' +
                                            '<a href="#" class="btn btn-danger" onclick="delThisPhoto(this); return false;">Elimina</a>' +
                                        '</div>' +
                                    '</div>';
                                        
                        jQuery("div#" + id + "_list").append(html);
                        
                    });
                    
                    dropZoneArray[i].on("error", function(file, error) {
                        
                        alert(error);
                        
                    });
                    
                }
                
            });
            
            jQuery(".deleteImage").unbind("click");
            jQuery(".deleteImage").bind("click", function (e) {
                
                e.preventDefault();
                
                var id = jQuery(this).data("id");
                {{-- jQuery("div#" + id).css("background", 'transparent url("//static.info-alberghi.com/images/admin/photo.png") center center no-repeat'); --}}
                jQuery("." + id).val("");
                
            });
            
            
        }
        
        initDropZone("singola");
        initDropZone("gallery");
        
        /**
        * Layout
        */
        
        function initLayout() {
            
            jQuery(".image-layout").unbind("click");
            jQuery(".image-layout").bind("click" , function () {
                
                var me = jQuery(this);
                me.parent().find(".image-layout").removeClass("selected");
                me.addClass("selected");
                me.parent().find("input").val(me.data("layout"));
                
            });
            
        }
        
        initLayout();
        
        /**
        * Ordinamenti
        */
        
        function initOrder() {
            
            jQuery(".sortable").not("init").sortable({ 
                    
                placeholder: "ui-state-highlight",
                forcePlaceholderSize: true, 
                opacity: 0.6, 
                update: function() {
                    
                    /*var token = "{{ Session::token() }}";
                    var order = jQuery(this).sortable("serialize") + '&_token='+token;
                    var baseUrl = "{{ url('/') }}";*/
                    
                    /*jQuery.post(
                        baseUrl+"/admin/immagini-gallery/order_ajax",
                        order,
                        function(theResponse){}
                    );*/
                    
                }
            
            });
            
            jQuery(".sortable").addClass("init");
            
        }
        
        initOrder();
            
        /**
        * Tiny
        */
            
        function initTiny ( _selector, _height , _toolbar = null ) {
            
            if (!_toolbar)
                _toolbar = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link anchor image | code";
                
            tinymce.init({
            
                selector: _selector,
                height: _height,
                
                menubar:false,
                forced_root_block : false,
                extended_valid_elements : 'span[itemscope|itemtype|itemprop|content],div[itemscope|itemtype|itemprop],strong[itemprop],p[itemprop]',
                
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste imagetools jbimages code filemanager"
                ],
                
                image_advtab: true,
                
                toolbar: _toolbar,
                relative_urls: false
                
            });
            
        }
        
        initTiny(".ckeditor", 300);
        initTiny(".ckeditor-small", 200, "bold italic | code");
        
        /**
        * Cancella un contenuto
        */
        
        function delContentSecondary(object) {
            
            var indice = jQuery(object).attr("data-index");
            jQuery("#pannello-h2-"+indice).remove();
            
        }
        
        /**
        * Cancella una foto 
        */
        
        function delThisPhoto(object) {
            
            jQuery(object).parent().parent().remove();
            return false;
                    
        }
        
        /**
        * Add gallery
        */
        
        function addGalleryButton(object) {
            
            var tipocontenuti = "gallery";
            var indice = jQuery(object).data("index");
            
            var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="0">' +
                            '<input type="hidden" value="gallery" name="tipocontenuti[]"/>' +
                            '<div class="panel-heading">' +
                                '<div class="panel-title">Galleria fotografica #'+indice+'</div>' +
                            '</div>' +
                            
                            '<div class="panel-body">' +
                                
                                '<div class="form-group">' +
                                    '<label for="immagine_secondaria" class="col-sm-2 control-label">Immagini</label>' +
                                    '<div class="col-sm-10">' +
                                        '<div class="dropzone dropzone_gallery" id="dropzoneFileUploadSecondarioGallery_'+indice+'" data-indice="'+indice+'" data-path="pagine" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block; background: transparent url(\'/images/admin/photo.png\') center center no-repeat"></div>' +
                                        '<div class="dz-default dz-message">' +
                                            '<strong>Clicca o trascina un file sul riquadro per caricarlo</strong><br>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<div id="dropzoneFileUploadSecondarioGallery_'+indice+'_list" class="form-group sortable">' +
                                '</div>' +
                        
                            
                                '<div class="form-group">'+
                                    '<div class="col-sm-10 col-sm-offset-2">' +
                                        '<button class="delGallerytButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                                    '</div>' +
                                '</div>' +
                            
                            '</div>' +
                            
                        '</div>';
                        
            jQuery(".aggiungiContenuto").before(html);
            initDropZone("gallery");
            indice++;
            jQuery(".aggiungiContenuto .btn").data("index", indice);
            initOrder();
            
        }
        
        /**
        * Aggiungo una mappa
        */
        
        function addMapButton(object) {
            
            var tipocontenuti = "map";
            var indice = jQuery(object).data("index");
            
            var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="">' +
                        '<input type="hidden" value = "'+tipocontenuti+'" name="tipocontenuti[]"/>' +
                        '<div class="panel-heading">' +
                                '<div class="panel-title">Mappa e punti di interese #'+indice+'</div>' + 
                        '</div>' +
                        
                        '<div class="panel-body">' +
                                                        
                                '<div class="form-group" >' +
                                    '<label for="layout" class="col-sm-2 control-label">Latitudine / Longitudine</label>' +
                                    '<div class="col-sm-10">' +
                                        '<input class="form-control" name="map_lat_lon[]" value="" placeholder="es: 44.0535197,12.5395819"/>' +
                                        '<br /><label>' +
                                            '<input name="map_source[]" type="checkbox" value="1"> includi il listing attivo' +
                                        '</label>' +
                                    '</div>' + 
                                '</div>' +
                        
                        '</div>' +
                        
                        
                            '<div class="form-group">'+
                                '<div class="col-sm-10 col-sm-offset-2">' +
                                    '<button class="delGallerytButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                                '</div>' +
                            '</div>' +
                        
                        '</div>';
                        
            indice++;
            jQuery(".aggiungiContenuto .btn").data("index", indice);
            jQuery(".aggiungiContenuto").before(html);
                    
        }
        
        /**
        * Add contenuto
        */
        
        function addContentSecondary(object) {
                    
            var tipocontenuti = "text";
            var indice = jQuery(object).data("index");
            var titolo = "";
            var sottotitolo = "";
            var testo = "";
            var immagine = "";
            var layout = "normal";
                            
            var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="">' +
                        '<input type="hidden" value = "'+tipocontenuti+'" name="tipocontenuti[]"/>' +
                        '<div class="panel-heading">' +
                                '<div class="panel-title">Contenuti secondario BODY COPY #'+indice+'</div>' + 
                        '</div>' +
                        
                        '<div class="panel-body">' +
                                
                                '<div class="form-group">' +
                                    '<label for="layout" class="col-sm-2 control-label">Immagine</label>'+
                                    '<div class="col-sm-10">' + 
                                        '<img class="image-layout " src="/images/admin/1col.jpg" data-layout="1col" />' +
                                        '<img class="image-layout selected" src="/images/admin/normal.jpg" data-layout="normal" />' +
                                        '<img class="image-layout" src="/images/admin/left.jpg" data-layout="left" />' +
                                        '<img class="image-layout" src="/images/admin/right.jpg" data-layout="right" />' +
                                        '<img class="image-layout" src="/images/admin/over.jpg" data-layout="over" />' +
                                        '<input type="hidden" value = "'+layout+'" name="layout[]"/>' +
                                    '</div>' + 
                                '</div>' + 
                                
                                '<div class="form-group">' +
                                    '<label for="immagine_secondaria" class="col-sm-2 control-label">Immagine</label>'+
                                    '<input type="hidden" name="immagine_secondaria[]" value="'+immagine+'" class=form-control dropzoneFileUploadSecondario_'+indice+' id="immagine_secondaria_'+indice+'" />' + 
                                    '<div class="col-sm-10">' + 
                                        '<div class="dropzone dropzone_singola" id="dropzoneFileUploadSecondario_'+indice+'" data-path="immagini-evidenza" data-service="uploadImage" data-indice="'+indice+'" style="border:2px dashed #ddd; display: inline-block; "></div>' +
                                        '<div class="dz-default dz-message">' +
                                            '<strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadSecondario_'+indice+'" class="deleteImage">Elimina immagine</a><br>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="form-group">' +
                                '<label for="h2" class="col-sm-2 control-label">Titolo contenuto</label>'+
                                '<div class="col-sm-10">' +
                                    '<input class="form-control" name="h2[]" placeholder="Titolo" value="'+titolo+'" />' +
                                '</div>' +
                                '</div>'+
                                '<div class="form-group">' +
                                '<label for="h3" class="col-sm-2 control-label">Sottotitolo</label>'+
                                '<div class="col-sm-10">' +
                                    '<input class="form-control" name="h3[]" placeholder="Sottotitolo" value="'+ sottotitolo+'" />' +
                                '</div>' +
                                '</div>'+
                                '<div class="form-group">'+
                                '<label for="descrizione_2" class="col-sm-2 control-label">Testo contenuto</label>' + 
                                '<div class="col-sm-10">' +
                                        '<textarea class="form-control ckeditor-add-'+indice+'" placeholder="Testo contenuto" name="descrizione_2[]">'+testo+'</textarea>' +
                                '</div>' +
                            '</div>' +
                            
                                '<div class="form-group">'+
                                    '<div class="col-sm-10 col-sm-offset-2">' +
                                        '<button class="delContentButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                                    '</div>' +
                                '</div>' +
                                
                            '</div>' +
                        
                    '</div>';
                    
            jQuery(".aggiungiContenuto").before(html);
            
            initTiny(".ckeditor-add-"+indice, 300);
            initDropZone("singola");
            initLayout();
            initOrder();
            indice++;
            jQuery(".aggiungiContenuto .btn").data("index", indice);

        }

    </script>

@endsection