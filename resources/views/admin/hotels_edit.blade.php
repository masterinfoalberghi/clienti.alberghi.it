@extends('templates.admin')

@php $path404 = config("app.cdn_online") . "/images/404.jpg"; @endphp

{{-- {{dd($data)}} --}}

@section('title')
    @if ($data["record"]->exists)
        Modifica Hotel
    @else
        Nuovo Hotel 
    @endif
    ( id: {{$data["newid"] == 0 ? $data["record"]->id : $data["newid"]}} )
@endsection

@section('content')
		
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12">
				@include('templates.admin_inc_record_buttons_top', array('reset' => "si"))
                @if($data["record"]->revisions->count()) 
                <a type="button" class="btn btn-default" target="_blank" href="/admin/hotels/{{$data["record"]->id}}/revisions/0/">{{$data["record"]->revisions->count()}} {{$data["record"]->revisions->count() == 1 ? "Revisione" : "Revisioni"}} </a> 
                 &nbsp;&nbsp;&nbsp;
                @if (isset($data["record"]->revisions[0])) Ultima modifica: <b>{{\Carbon\Carbon::parse($data["record"]->revisions[0]->updated_at)->format("j F Y H:i:s")}}</b>@endif
                &nbsp;&nbsp;&nbsp;
                {{$data["record"]->editor == NULL ? "Aggiornato da CRM" : (!is_null($data["record"]->editors->nome) ? $data["record"]->editors->name :$data["record"]->editors->email) }}
                @endif
				<hr />	
			</div>
		</div>
	</div>
	
	@if ($data["record"]->exists)

		{!! Form::open(['id' => 'record_delete', 'url' => url('admin/hotels/delete'), 'method' => 'POST']) !!}
			<input type="hidden" name="id" value="{{$data["record"]->id }}">
		{!! Form::close() !!}

		{!! Form::open(['id' => 'image_delete', 'url' => url('admin/hotels/remove-image'), 'method' => 'POST']) !!}
			<input type="hidden" name="id" value="{{$data["record"]->id }}">
		{!! Form::close() !!}
        
	@endif

	{!! Form::model($data["record"], ['id' =>'save_form_hotel', 'role' => 'form', 'url' => url('admin/hotels/store'), 'method' => 'POST', 'class' => 'form-horizontal','files' => true]) !!}

        <input type="hidden" name="id" value="{{$data["record"]->id}}">
        <input type="hidden" name="newid" value="{{$data["newid"]}}">

        {{-- Attivo / Chiuso / Disattiva listini --}}
        <div class="form-group">
            <div class="col-sm-8">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    @if ($data["record"]->attivo == -1)
                        <span style="background-color: #F24A46; display:block; color: #fff; padding:3px 5px; margin:3px 0; text-align:center;">HOTEL DI PROVA</span>
                        <input type="hidden" name="attivo" value="{{$data['record']->attivo}}">
                    @else<div class="checkbox"><label>
                        {!! Form::checkbox('attivo', "1", null, ["id"=>"attivo"]) !!} Attivo
                    </div></label>@endif
                </div>

                <div class="col-sm-3">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('chiuso_temp', "1", null) !!} 
                            @if ($data["record"]->chiuso_temp)
                                <span style="display: inline-block; background-color: #F24A46; color: #fff; border-radius: 3px; padding: 5px; margin-top: -5px;">Temporaneamente chiuso</span>
                            @else
                                Temporaneamente chiuso
                            @endif
                        </label>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('hide_price_list', "1", null) !!} 
                            @if ($data["record"]->chiuso_temp)
                                <span style="display: inline-block; background-color: #F24A46; color: #fff; border-radius: 3px; padding: 5px; margin-top: -5px;">Temporaneamente chiuso</span>
                            @else
                                Disattiva listini
                            @endif
                        </label>
                    </div>
                </div>

                
            </div>
        </div>	 
        
        <hr>

        {{-- Dati iniziali --}}
        <div class="form-group">
            <div class="col-sm-8">

                <div class="form-group">
                    {!! Form::label('nome', 'Nome', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-7">
                        {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'form-control']) !!}
                    </div>
                     
                </div>

                <div class="form-group">
                    {!! Form::label('commerciale_id', 'Commerciale', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('commerciale_id', $data["commerciali"], null, ['class' => 'form-control', 'id' => 'commerciale_id']) !!}
                    </div>
                </div>
            
                <div class="form-group">
                    {!! Form::label('prezzo_min', 'Prezzo Minimo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('prezzo_min', null, ['placeholder' => 'Prezzo minimo', 'class' => 'form-control']) !!}
                        <p class="help-block">va sempre inserito in questo formato: x,xx con al massimo 3 cifre davanti alla virgola</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('prezzo_max', 'Prezzo Massimo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::text('prezzo_max', null, ['placeholder' => 'Prezzo massimo', 'class' => 'form-control']) !!}
                        <p class="help-block">va sempre inserito in questo formato: x,xx con al massimo 3 cifre davanti alla virgola</p>
                    </div>
                </div>

                <div class="form-group">
                  {!! Form::label('cir', 'CIR', ['class' => 'col-sm-2 control-label'])!!}
                  <div class="col-sm-10">
                    {!! Form::text('cir', null, ['placeholder' => 'CIR', 'class' => 'form-control'])!!}
                  </div>
                </div>
        
            </div>

            <div class="col-sm-3 pull-right">
                
                    @if ($data["record"]->exists)
                        
                        <div class="text-center">
                            @if ($data["record"]->attivo)
                                <img src="{{ $data["record"]->getListingImg('360x200', true) }}" style="width:100%; height:auto; ">
                            @else
                                <img src="{{$path404}}" style="width:100%; height:auto;">
                            @endif
                        </div>
                            
                    @else
                        
                        <div class="text-center">
                            Per poter inserire un'immagine prima devi creare il record
                        </div>
                    
                    @endif

                
            </div>
        </div>

        {{-- Struttura --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Struttura</div>
            </div>
        
            <div class="panel-body">

                <div class="form-group">
                    {!! Form::label('tipologia_id', 'Tipologia', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-2">
                        {!! Form::select('tipologia_id', $data["tipologie"], null, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div id="category_star">
                        <div class="col-sm-2">
                            {!! Form::select('categoria_id', $data["categorie"], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>	 

            </div>
        
        </div>	
        
        {{-- Informazioni Geografiche --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Informazioni Geografiche</div>
            </div>
            
            <div class="panel-body">
                
                <div class="form-group">
                    {!! Form::label('localita_id', 'Località', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('localita_id', $data["localita"], null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('cap', 'CAP modificato', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('cap', null, ['placeholder' => 'CAP', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('indirizzo', 'Indirizzo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('indirizzo', null, ['placeholder' => 'Es: Via Tripoli, 5', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('mappa_latitudine', 'Latitudine', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('mappa_latitudine', null, ['placeholder' => 'Latitudine', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('mappa_longitudine', 'Longitudine', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('mappa_longitudine', null, ['placeholder' => 'Longitudine', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('distanza_spiaggia', 'Distanza dalla spiaggia', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('distanza_spiaggia', null, ['placeholder' => 'Distanza dalla spiaggia', 'class' => 'form-control']) !!}
                    </div> 
                </div>

                <div class="form-group">
                    {!! Form::label('distanza_centro', 'Distanza dal centro (Km)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('distanza_centro', null, ['placeholder' => 'Distanza dal centro (Km)', 'class' => 'form-control']) !!}
                        <p class="help-block">lasciare il campo vuoto per ricalcolare la distanza automaticamente</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('distanza_staz', 'Distanza dalla stazione (Km)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('distanza_staz', null, ['placeholder' => 'Distanza dalla stazione (Km)', 'class' => 'form-control']) !!}
                        <p class="help-block">lasciare il campo vuoto per ricalcolare la distanza automaticamente</p>
                    </div> 
                </div>
                
                <div class="form-group">
                    {!! Form::label('distanza_fiera', 'Distanza dalla Fiera (Km)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('distanza_fiera', null, ['placeholder' => 'Distanza dalla Fiera (Km)', 'class' => 'form-control']) !!}
                        <p class="help-block">lasciare il campo vuoto per ricalcolare la distanza automaticamente</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('distanza_casello', 'Distanza dal casello più vicino (Km)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('distanza_casello', null, ['placeholder' => 'Distanza dal casello più vicino (Km)', 'class' => 'form-control']) !!}
                        <p class="help-block"><b style="color:#000">{{$data["record"]["distanza_casello_label"]}}</b> - lasciare il campo vuoto per ricalcolare la distanza automaticamente</p>
                    </div> 
                </div>

                @if ($data["record"]->exists)
                    
                    <div class="form-group">
                        
                        <label class="col-sm-2 control-label">Distanza dai <em>Points Of Interest</em> (Km)</label>

                        <?php
                        foreach($data["record"]->poi as $poi) {
                            ?><div class="col-sm-1">
                                {!! $poi->poi_lingua()->first()->nome !!}<br>
                                {!! $poi->pivot->distanza !!}
                            </div><?php
                        } ?>
                        
                    </div>

                    <div class="form-group">
                        
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>
                                {!! Form::checkbox('poi_ricalcola_distanza', "1", null) !!} Inserisci i NUOVI POI</em>
                                </label>
                            </div>		
                        </div>
                        
                    </div>
			
                @else
                    
                    {!! Form::hidden('poi_ricalcola_distanza', 1) !!}
                
                @endif

            </div>
        </div>

        {{-- Slug --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Slug</div>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    {!! Form::label('alternate', 'Slug in lingua', ['class' => 'col-sm-2 control-label']) !!}
                    @php $class=""; @endphp
                    @foreach(Langs::getAll() as $lang)
                        <div style="margin-bottom:5px; position:relative; " class="col-sm-10 {{$class}}">
                            {!! Form::text('slug_' . $lang , $data["record"]["slug_" . $lang], ['placeholder' => 'Link alternate', 'class' => 'form-control', "style" => "padding-left:40px;"]) !!}
                            <div class="langTag">{{$lang}}</div>
                        </div>
                        @php $class="col-sm-offset-2"; @endphp
                    @endforeach
                    
                </div>
            </div>
        </div>

        {{-- Recapiti --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Recapiti</div>
            </div>
            
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('telefono', 'Telefono', ['class' => 'col-sm-2 control-label']) !!}
                    {{-- <div class="col-sm-8">
                        {!! Form::text('telefono', null, ['placeholder' => 'Telefono', 'class' => 'form-control']) !!}
                    </div> --}}
                    @if(isset($data['record']['telefono']) && $data['record']['telefono'] != "")
                        <div id="form-telefono" class="col-sm-7">
                            @foreach ($data["record"]['telefono'] as $i => $telefono)
                                @if($data['record']['telefono'] != "")
                                    <div class="form-telefono" style="{{$i > 0 ? 'margin-top:15px' : ''}}">
                                        <input type="text" class="form-control" name="telefono[]" placeholder="Telefono" value="{{$telefono}}" style="width:50%; display:inline-block">
                                        @if($i > 0)
                                            <button type="button" class="btn btn-danger delete-telefono"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="col-sm-3 text-right">
                        <button type="button" id="add-telefono" class="btn btn-primary">Nuovo numero di telefono</button>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    {!! Form::label('cell', 'Cellulare', ['class' => 'col-sm-2 control-label']) !!}
                    @if(isset($data['record']['cell']))
                        <div id="form-cell" class="col-sm-7">
                            @foreach ($data["record"]['cell'] as $i => $cell)
                                <div class="form-cell" style="{{$i > 0 ? 'margin-top:15px' : ''}}">
                                    <input type="text" class="form-control" name="cell[]" placeholder="Cellulare" value="{{$cell}}" style="width:50%; display:inline-block">
                                    @if($i > 0)
                                       <button type="button" class="btn btn-danger delete-cell"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                      <div class="col-sm-3 text-right">
                        <button type="button" id="add-cell" class="btn btn-primary">Nuovo numero di cellulare</button>
                    </div>
                </div>

                {{-- <div class="form-group">
                    {!! Form::label('telefoni_mobile_call', 'Telefoni mobile call', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('telefoni_mobile_call', null, ['placeholder' => 'Telefoni mobile call', 'class' => 'form-control']) !!}
                        <p class="help-block"> Inserire il numero (che si vuole utilizzare nella app per fare la chiamata alla struttura) senza separatori e/o spazi; per inserire più numeri separarli con la virgola</p>
                    </div>
                </div>		 --}}

                {!!Form::hidden('telefoni_mobile_call', null)!!}

                {{-- DEPRECATO DA Lucio il 23 Nov 2020 
                <div class="form-group">
                    {!! Form::label('fax', 'Fax', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('fax', null, ['placeholder' => 'Fax', 'class' => 'form-control']) !!}
                    </div>
                </div> --}}
                {!! Form::hidden('fax', null) !!}

                <hr>
                <div class="form-group">
                    {!! Form::label('whatsapp', 'Whatsapp', ['class' => 'col-sm-2 control-label']) !!}
                    @if(isset($data['record']['whatsapp']))
                        <div id="form-whatsapp" class="col-sm-7">
                            @foreach ($data["record"]['whatsapp'] as $i => $whatsapp)
                                <div class="form-cell" style="{{$i > 0 ? 'margin-top:15px' : ''}}">
                                    <input type="text" class="form-control" name="whatsapp[]" placeholder="Whatsapp" value="{{$whatsapp}}" style="width:50%; display:inline-block">
                                    @if($i > 0)
                                     <button type="button" class="btn btn-danger delete-whatsapp"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                      <div class="col-sm-3 text-right">
                        <button type="button" id="add-whatsapp" class="btn btn-primary">Nuovo numero di Whatsapp</button>
                    </div>
                </div>
                

                <div id="wrapper" style="">
                    
                <div class="form-group" style="width:100%; text-align: right;">
                <div class="col-sm-5" style="float: right;">
                    {!! Form::label('note_wa', 'Note', ['class' => 'col-sm-2 control-label', 'style' => 'float:left;']) !!}

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="margin: 0px!important; padding: 0 !important;">
                    @foreach (Langs::getAll() as $lang)
                        <li role="notewa" <?php echo $lang === "it" ? 'class="active"' : null?>>
                        <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                            <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                        </a>
                        </li>
                    @endforeach
                    </ul>
                    <div class="tab-content">

                    @foreach (Langs::getAll() as $lang)
                        @php
                        $field = 'notewa_'.$lang;
                        @endphp
                        <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}">
                        {!! Form::text($field, isset($data["record"]) && $data["record"]->$field ? $data["record"]->$field : null, ['class' => 'form-control']) !!}
                        </div>
                    @endforeach
                    </div>

                </div>
                </div>

                </div>

                
                <hr>

                {{-- DEPRECATO DA Lucio il 23 Nov 2020 
                <div class="form-group">
                    {!! Form::label('skype', 'Skype', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('skype', null, ['placeholder' => 'Skype', 'class' => 'form-control']) !!}
                    </div>
                </div> --}}
                {!! Form::hidden('skype', null) !!}
                <div class="form-group">
                    {!! Form::label('email', 'Email principale', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                        <p style="margin:5px; ">Sono le email principali sottoscritte al momento della firma del contratto. A questi indirizzi arrivano le email <b>Dirette e Multiple</b>.<br />
                            <b>In caso di mancanza di email secondarie</b>, a queste email verranno spedite anche le email "spazzatura"</p>
                        </div>
                     
                    <div class="col-sm-4">
                        {{-- 3643 --}}
                        @if ((Auth::user()->id == 3661 || Auth::user()->id == 2765) && $data['record']->id >= 20000)
                            <button type="button" class="btn btn-success"  onClick="jQuery('#modal-password').modal('show');">Genera Password</button>
                            <button type="button" class="btn btn-info"  onClick="jQuery('#modal-newsletter').modal('show');">Invio lettera di presentazione</button>
                        @endif
                    </div>  
                    
                </div>

                <div class="form-group">
                    {!! Form::label('email_multipla', 'Email multipla', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('email_multipla', null, ['placeholder' => 'Email multiple', 'class' => 'form-control']) !!}
                        <p style="margin:5px; ">Se compilato spedisce le email multiple a questo indirizzo invece che al principale</p>
                    </div>
                </div>
                
                @if(Auth::user()->hasRole("admin"))
                    
                    <div class="form-group">
                        {!! Form::label('email_secondaria', 'Email secondarie', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('email_secondaria', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                            <p style="margin:5px; ">Se compilata devia tutti i contenuti "spazzatura" su questi indirizzi. Es. Email di intento di chiamata, newsletter, etc...</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('email_risponditori', 'Email 1clicksuite', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10" style="padding-top: 7px;">
                            {!! Form::text('email_risponditori', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                            <p style="margin:5px; ">Email da compilare per 1clcksuite</p>
                        </div>
                    </div>
                    
                @else
                    
                    {!! Form::hidden('email_secondaria', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                    {!! Form::hidden('email_risponditori', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                    <div class="form-group">
                        {!! Form::label('email_secondaria', 'Email secondarie', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! $data["record"]->email_secondaria !!}
                            <p style="margin:5px; ">Se compilata devia tutti i contenuti "spazzatura" su questi indirizzi. Es. Email di intento di chiamata, newsletter, etc...<br />
                                <b>Rivolgersi ad un amministratore per modificare questa email</b>
                            </p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('email_risponditori', 'Email 1clicksuite', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10" style="padding-top: 7px;">
                            {!! $data["record"]->email_risponditori !!}
                            <p style="margin:5px; ">Email da compilare per 1clcksuite<br />
                                <b>Rivolgersi ad un amministratore per modificare questa email</b></p>
                        </div>
                    </div>
                
                @endif

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input name="nascondi_url" type="checkbox" value="1" @if($data["record"]->nascondi_url) checked @endif> Nascondi l'URL del sito per disservizi momentanei.
                            </label>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    {!! Form::label('link', 'Link', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('link', null, ['placeholder' => 'Link', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('testo_link', 'Testo Link', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('testo_link', null, ['placeholder' => 'Testo Link', 'class' => 'form-control']) !!}
                    </div>
                </div>	
            </div>
        </div>

        {{-- MATRIMONI --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Organizzazione eventi</div>
            </div>
            
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Organizzaza:</label>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('organizzazione_comunioni', "1", null) !!} Comunioni
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('organizzazione_cresime', "1", null) !!} Cresime
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('organizzazione_matrimoni', "1", null) !!} Matrimoni
                        </label>
                        </div>
                    </div>
                    
                </div>

                <div class="form-group">
                    {!! Form::label('note_organizzazione_matrimoni', 'Note', ['class' => 'col-sm-2 control-label']) !!}	
                    <div class="col-sm-9">
                        {!! Form::text('note_organizzazione_matrimoni', null, ['placeholder' => 'altro', 'class' => 'form-control']) !!}
                    </div>
                </div>
                
            </div>
        </div>

        {{-- Date di apertura --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Date di apertura</div>
            </div>
            
            <div class="panel-body">

                <div class="form-group">
                    
                    {!! Form::label('aperto_dal', 'Aperto dal', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-4">
                        <input type="text" value="@if($data["record"]->exists){{Utility::getLocalDate($data["record"]->aperto_dal, '%d/%m/%Y')}}@endif" id="aperto_dal" name="aperto_dal">
                    </div>
                    
                    {!! Form::label('aperto_al', 'Aperto al', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-4">
                        <input type="text" value="@if($data["record"]->exists){{Utility::getLocalDate($data["record"]->aperto_al, '%d/%m/%Y')}}@endif" id="aperto_al" name="aperto_al">
                    </div>
                </div>

            </div>
        </div>
        
        {{-- Periodi di apertura --}}
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading"><div class="panel-title">Periodi di apertura</div></div>
            <div class="panel-body">

                <div class="form-group">
                    
                    <label class="col-sm-2 control-label">Apertura</label>
                
                    <div class="col-sm-1">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('annuale', "1", null, ["id"=>"annuale"]) !!} Annuale
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('aperto_eventi_e_fiere', "1", null, ["id"=>"aperto_eventi_e_fiere"]) !!} Aperto eventi e fiere
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('aperto_pasqua', "1", null, ["id"=>"aperto_pasqua"]) !!} Aperto Pasqua
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('aperto_25_aprile', "1", null, ["id"=>"aperto_25_aprile"]) !!} 25 Aprile
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('aperto_1_maggio', "1", null, ["id"=>"aperto_1_maggio"]) !!} 1° Maggio
                        </label>
                        </div>
                    </div>

                    <div class="col-sm-2 col-sm-offset-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('aperto_capodanno', "1", null, ["id"=>"aperto_capodanno"]) !!} Aperto capodanno
                            </label>
                        </div>
                    </div>	

                </div>

                <div class="form-group">
                    
                    <div class="col-sm-offset-2 col-sm-1" style="margin-top: 58px;">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('aperto_altro_check', "1", null) !!} Aperto altro
                            </label>
                        </div>
                    </div>	
                            
                    <div class="col-sm-9">
                            <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" >
                    @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                        <a class="tab_lang" data-id="altro_{{ $lang }}" href="#altro_{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                
                <div class="tab-content">

                                @foreach (Langs::getAll() as $lang)
                                    @php
                                        $field = 'aperto_altro_'.$lang;
                                    @endphp
                                    <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="altro_{{ $lang }}">
                                        {!! Form::text($field, old("$field"), ['placeholder' => 'altro', 'class' => 'form-control']) !!}
                                    </div>
                                @endforeach

                            </div>

                    </div>
                    
                </div>
                
            </div>
        </div>

        {{-- Camere e posti letto --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Camere e posti letto</div>
            </div>
            
            <div class="panel-body">
                
                <div class="form-group">
                    {!! Form::label('n_camere', 'n° camere', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('n_camere', null, ['placeholder' => 'n° camere', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('n_posti_letto', 'n° posti letto', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('n_posti_letto', null, ['placeholder' => 'n° posti letto', 'class' => 'form-control']) !!}
                    </div>
                </div> 

                <div class="form-group">
                    {!! Form::label('n_appartamenti', 'n° appartamenti', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('n_appartamenti', null, ['placeholder' => 'n° appartamenti', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('n_suite', 'n° suite', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('n_suite', null, ['placeholder' => 'n° suite', 'class' => 'form-control']) !!}
                    </div>
                </div>

            </div>
            
        </div>

        {{-- DATI PRESI DA NUOVA SEZIONE Servizi Checkin Checkput --}}
        <input type="hidden" name="reception_24h" value="0">
        <input type="hidden" name="reception1_da_ora" value="">
        <input type="hidden" name="reception1_da_minutes" value="">
        <input type="hidden" name="reception1_a_ora" value="">
        <input type="hidden" name="reception1_a_minutes" value="">
        <input type="hidden" name="reception2_da_ora" value="">
        <input type="hidden" name="reception2_da_minutes" value="">
        <input type="hidden" name="reception2_a_ora" value="">
        <input type="hidden" name="reception2_a_minutes" value="">
        
        {{-- Check In - Check Out --}}
        <input type="hidden" name="checkin_it" value="">
        <input type="hidden" name="checkin_en" value="">
        <input type="hidden" name="checkin_fr" value="">
        <input type="hidden" name="checkin_de" value="">
        <input type="hidden" name="checkout_it" value="">
        <input type="hidden" name="checkout_en" value="">
        <input type="hidden" name="checkout_fr" value="">
        <input type="hidden" name="checkout_de" value="">

        {{-- Orari dei pasti --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Orari dei pasti</div>
            </div>
            
            <div class="panel-body">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Colazione</label>
                    <label class="col-sm-1 control-label">dalle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('colazione_da', null, [
                        'class' => 'form-control timepicker', 
                        'data-default-time' => '00:00',
                        'data-template' => 'dropdown',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                    
                    <label class="col-sm-1 control-label">alle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('colazione_a', null, [
                        'class' => 'form-control timepicker', 
                        'data-template' => 'dropdown',
                        'data-default-time' => '00:00',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    
                    <label class="col-sm-2 control-label">Pranzo</label>
                    <label class="col-sm-1 control-label">dalle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('pranzo_da', null, [
                        'class' => 'form-control timepicker', 
                        'data-template' => 'dropdown',
                        'data-default-time' => '00:00',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                    
                    <label class="col-sm-1 control-label">alle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('pranzo_a', null, [
                        'class' => 'form-control timepicker', 
                        'data-template' => 'dropdown',
                        'data-default-time' => '00:00',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                    
                </div>

                <div class="form-group">
                    
                    <label class="col-sm-2 control-label">Cena</label>
                    <label class="col-sm-1 control-label">dalle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('cena_da', null, [
                        'class' => 'form-control timepicker', 
                        'data-template' => 'dropdown',
                        'data-default-time' => '00:00',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                    
                    <label class="col-sm-1 control-label">alle</label>
                    
                    <div class="col-sm-1">
                        {!! Form::text('cena_a', null, [
                        'class' => 'form-control timepicker', 
                        'data-template' => 'dropdown',
                        'data-default-time' => '00:00',
                        'data-show-seconds' => 'false',
                        'data-show-meridian' => 'false',
                        'data-minute-step' => '5'
                        ]) !!}
                    </div>
                    
                </div>		

            </div>
        </div>

        {{-- Trattamenti principali --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Trattamenti principali</div>
            </div>
            
            <div class="panel-body">
                
                @if (isset($data['trattamenti_evidenze']) && count($data['trattamenti_evidenze']))
                    <div class="row">
                        <div class="col-sm-12">
                            <label>
                            ATTENZIONE: <span class="vetrina_associata">i trattamenti evidenziati in rosso </span>sono quelli associati alle evidenze. Se li rimuovi le relative evidenze non saranno più visibili.
                            </label>
                        </div>
                    </div>
                @endif
                
                <div class="form-group">		
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_ai', "1", null) !!} 

                                @if (isset($data['trattamenti_evidenze']) && in_array('ai', $data['trattamenti_evidenze']))
                                <span class="vetrina_associata">All inclusive</span>
                                @else
                                All inclusive
                                @endif
                            
                            </label>
                        
                        </div>
                    </div>
                    <div class="col-sm-10">
                            {!! Form::textarea('note_ai_it', isset($data['record']) && $data['record']->note_ai_it != '' ? $data['record']->note_ai_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>
                
                <div class="form-group">
                
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_pc', "1", null) !!} 
                                
                                @if (isset($data['trattamenti_evidenze']) && in_array('pc', $data['trattamenti_evidenze']))
                                <span class="vetrina_associata">Pensione completa</span>
                                @else
                                Pensione completa
                                @endif
                                
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_pc_it', isset($data['record']) && $data['record']->note_pc_it != '' ? $data['record']->note_pc_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>


                    {{-- mp spiaggia --}}
                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_mp_spiaggia', "1", null) !!}
                                Mezza pensione + Spiaggia
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_mp_spiaggia_it', isset($data['record']) && $data['record']->note_mp_spiaggia_it != '' ? $data['record']->note_mp_spiaggia_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>
                {{-- \mp spiaggia --}}

                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_mp', "1", null) !!}
                                
                                @if (isset($data['trattamenti_evidenze']) && in_array('mp', $data['trattamenti_evidenze']))
                                <span class="vetrina_associata">Mezza pensione</span>
                                @else
                                Mezza pensione
                                @endif
                                
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_mp_it', isset($data['record']) && $data['record']->note_mp_it != '' ? $data['record']->note_mp_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>


                    {{-- bb spiaggia --}}
                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_bb_spiaggia', "1", null) !!}
                                Bed &amp; breakfast + Spiaggia
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_bb_spiaggia_it', isset($data['record']) && $data['record']->note_bb_spiaggia_it != '' ? $data['record']->note_bb_spiaggia_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>
                {{-- \bb spiaggia --}}

                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_bb', "1", null) !!}
                                
                                @if (isset($data['trattamenti_evidenze']) && in_array('bb', $data['trattamenti_evidenze']))
                                <span class="vetrina_associata">Bed &amp; breakfast</span>
                                @else
                                Bed &amp; breakfast
                                @endif

                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_bb_it', isset($data['record']) && $data['record']->note_bb_it != '' ? $data['record']->note_bb_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>


                
                {{-- sd spiaggia --}}
                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_sd_spiaggia', "1", null) !!}
                                Solo dormire + Spiaggia
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_sd_spiaggia_it', isset($data['record']) && $data['record']->note_sd_spiaggia_it != '' ? $data['record']->note_sd_spiaggia_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>
                {{-- \sd spiaggia --}}


                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox">
                            
                            <label>
                                {!! Form::checkbox('trattamento_sd', "1", null) !!} 
                                
                                @if (isset($data['trattamenti_evidenze']) && in_array('sd', $data['trattamenti_evidenze']))
                                <span class="vetrina_associata">Solo dormire</span>
                                @else
                                Solo dormire
                                @endif
                                
                            </label>
                            
                        </div>
                    </div>
                    <div class="col-sm-10">
                        {!! Form::textarea('note_sd_it', isset($data['record']) && $data['record']->note_sd_it != '' ? $data['record']->note_sd_it : null, ['class' => 'form-control','style' => 'height: 100px!important; min-height:100px!important; width: 600px;','readonly']) !!}
                    </div>
                </div>



            </div>
        </div>	

        {{-- Caparra alla prenotazione? --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Caparra</div>
            </div>
            
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Caparra alla prenotazione?</label>
                    
                    <div class="col-sm-1">
                        <div class="radio">
                            <label>
                                {!! Form::checkbox('caparra_si', "1",null, ["id"=>"caparra_si"]) !!} Si
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="radio">
                            <label>
                                {!! Form::checkbox('caparra_no', "1",null, ["id"=>"caparra_no"]) !!} No				
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-1">
                        <div class="checkbox" style="margin-top:38px;">
                            <label>
                                {!! Form::checkbox('caparra_altro_check', "1", null) !!} Altro
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">

                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_caparra" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                            @foreach (Langs::getAll() as $lang)
                                @php
                                    $field = 'caparra_altro_'.$lang;
                                @endphp
                                <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_caparra">
                                {!! Form::text($field, old("$field"), ['placeholder' => 'altro...', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- Pagamenti --}}
        <div class="panel panel-default pagamenti_accettati" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Pagamenti accettati</div>
            </div>
            
            <div class="panel-body">
                
                <div class="form-group">
                    
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('contanti', "1", null) !!} Contanti
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_contanti" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                        @foreach (Langs::getAll() as $lang)
                            @php
                                $field = 'note_contanti_'.$lang;
                            @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_contanti">
                            {!! Form::text($field, old("$field"), ['placeholder' => 'note contanti', 'class' => 'form-control']) !!}
                            </div>
                        @endforeach
                        </div>
                    </div>
                    
                </div>

                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('assegno', "1", null) !!} Assegno
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_assegno" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
            <div class="tab-content">
                        @foreach (Langs::getAll() as $lang)
                            @php
                                $field = 'note_assegno_'.$lang;
                            @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_assegno">
                                    {!! Form::text($field, old("$field"), ['placeholder' => 'note assegno', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:34px;">
                            <label>
                                {!! Form::checkbox('carta_credito', "1", null) !!} Carta di credito / Prepagata
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_carta" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                    @foreach (Langs::getAll() as $lang)
                        @php
                            $field = 'note_carta_credito_'.$lang;
                        @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_carta">
                                {!! Form::text($field, old("$field"), ['placeholder' => 'note carta di credito', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('bonifico', "1", null) !!} Bonifico
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_bonifico" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                    @foreach (Langs::getAll() as $lang)
                        @php
                            $field = 'note_bonifico_'.$lang;
                        @endphp
                                <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_bonifico">
                                {!! Form::text($field, old("$field"), ['placeholder' => 'note bonifico', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('paypal', "1", null) !!} Paypal
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_paypal" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                @foreach (Langs::getAll() as $lang)
                    @php
                        $field = 'note_paypal_'.$lang;
                    @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_paypal">
                                    {!! Form::text($field, old("$field"), ['placeholder' => 'note paypal', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('bancomat', "1", null) !!} Bancomat
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_bancomat" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                @foreach (Langs::getAll() as $lang)
                    @php
                        $field = 'note_bancomat_'.$lang;
                    @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_bancomat">
                                    {!! Form::text($field, old("$field"), ['placeholder' => 'note bancomat', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                {{-- Altro pagamento --}}
                <div class="form-group">
                    <div class="col-sm-2">
                        <div class="checkbox" style="margin-top:35px;">
                            <label>
                                {!! Form::checkbox('altro_pagamento', "1", null) !!} Altro
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_altro_pagamento" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="tab-content">
                @foreach (Langs::getAll() as $lang)
                    @php
                        $field = 'note_altro_pagamento_'.$lang;
                    @endphp
                            <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_altro_pagamento">
                                    {!! Form::text($field, old("$field"), ['placeholder' => 'note altro pagamento', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- END Altro pagamento --}}

            </div>
        </div>

        {{-- Lingue --}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Lingue parlate</div>
            </div>
            
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Lingue parlate</label>
                    
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('inglese', "1", null) !!} Inglese
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('francese', "1", null) !!} Francese
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('tedesco', "1", null) !!} Tedesco
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('spagnolo', "1", null) !!} Spagnolo
                            </label>
                        </div>
                    </div>
                
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('russo', "1", null) !!} Russo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('altra_lingua', 'Altro', ['class' => 'col-sm-2 control-label', 'style' => 'margin-top: 38px;']) !!}	
                    <div class="col-sm-9">
                        <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin-top:0;">
                @foreach (Langs::getAll() as $lang)
                    <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
                    <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}_lingua" aria-controls="profile" role="tab" data-toggle="tab">
                        <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
                    </a>
                    </li>
                @endforeach
                </ul>
            <div class="tab-content">
                @foreach (Langs::getAll() as $lang)
                    @php
                        $field = 'altra_lingua_'.$lang;
                    @endphp
                    <div role="tabpanel" class="tab-pane <?php echo $lang === "it" ? 'active' : null?>" id="{{ $lang }}_lingua">
                                {!! Form::text($field, old("$field"), ['placeholder' => 'altro', 'class' => 'form-control']) !!}
                                </div>
                            @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div> 

        {{-- Rating --}}
        <div class="panel panel-default" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">Rating</div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('enabled_rating_ia', "1", null) !!} Recensioni attive
                        </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    {!! Form::label('rating_ia', 'Punteggio recensioni', ['class' => 'col-sm-2 control-label']) !!}

                    <div class="col-sm-4">
                        {!! Form::text('rating_ia', null, ['placeholder' => 'Rating', 'class' => 'form-control']) !!}
                    </div> 

                    {!! Form::label('n_rating_ia', 'Numero recensioni', ['class' => 'col-sm-2 control-label']) !!}

                    <div class="col-sm-4">
                        {!! Form::text('n_rating_ia', null, ['placeholder' => 'Numero recensioni', 'class' => 'form-control']) !!}
                    </div> 

                </div>

                <div class="form-group">
                    
                    <h4 class="col-sm-offset-2" style="padding-left:15px; ">Fonti recensioni</h4><br />
                    
                    @php $t = 0;  @endphp

                    <div class="fonti">

                    @if (empty($data["record"]->source_rating_ia) || $data["record"]->source_rating_ia == "[]" || $data["record"]->source_rating_ia == "0") 
                    
                        @if ($data["record"]->source_rating_ia == "[]" || $data["record"]->source_rating_ia == "0") @php $data["record"]->source_rating_ia = "" @endphp @endif

                        <div class="fonte">
                            {!! Form::label('source_rating_' . $t, 'Fonte ' . ($t+1), ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('source_rating_ia[]', null, ['placeholder' => 'es: https://www.tripadvisor.it/Hotel_Review-g187807-d4609895-Reviews-Hotel_Sabrina_Rimini-Rimini_Province_of_Rimini_Emilia_Romagna.html', 'class' => 'form-control']) !!}
                            </div><div style="clear:both;"></div>
                        </div>

                    @else
                        
                        @foreach(json_decode($data["record"]->source_rating_ia) as $source_rating)

                            <div class="fonte">
                                {!! Form::label('source_rating_' . $t, 'Fonte ' . ($t+1), ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('source_rating_ia[]', $source_rating , ['placeholder' => 'es: https://www.tripadvisor.it/Hotel_Review-g187807-d4609895-Reviews-Hotel_Sabrina_Rimini-Rimini_Province_of_Rimini_Emilia_Romagna.html', 'class' => 'form-control']) !!}
                                </div><div style="clear:both;"></div>
                            </div>
                            @php $t++; @endphp
                            <hr />
                        @endforeach
                    
                    @endif

                </div> 
                <br />
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" id="addSourceRating" class="btn btn-success">Aggiungi una fonte</button>
                </div>

                </div>

            </div>
        </div>

        {{-- Altrov--}}
        <div class="panel panel-default" data-collapsed="0">
            
            <div class="panel-heading">
                <div class="panel-title">Altro</div>
            </div>

            <div class="panel-body">
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-2">
                        <div class="checkbox">
                        <label>
                            {!! Form::checkbox('green_booking', "1", null) !!} Partecipazione green booking
                        </label>
                        </div>
                    </div>
                    
                    {!! Form::hidden('chiedi_camere', 0) !!}
                    
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label class="eco_sostenibile">
                                {!! Form::checkbox('eco_sostenibile', "1", null) !!} Ecosostenibile
                            </label>
                        </div>
                    </div>
            
                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('certificazione_aci', "1", null) !!} Certificazione AiC
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('design_hotel', "1", null) !!} Design hotel
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('family_hotel', "1", null) !!} Family hotel
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                @include('templates.admin_inc_record_buttons',array('reset' => "si"))
            </div>
        </div>

    {!! Form::close() !!}

    {!! Form::open(['id' => 'generate_password', 'url' => url('admin/hotels/generate-psw', $data['record']->id), 'method' => 'POST']) !!}
    <div class="modal fade in" id="modal-password" data-backdrop="static"> 
        <div class="modal-dialog"> 
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                    <h4 class="modal-title">Genera password</h4> 
                </div> 
                <div class="modal-body">
                    Conferma l'email alla quale <b>inviare la notifica con l'account creato</b>:
                    {!! Form::text('email', $data["record"]->email, ['placeholder' => 'Email', 'class' => 'form-control']) !!}<br />
                    Conferma l'email con la quale <b>creare il nuovo account</b>:
                    {!! Form::text('email_account', $data["record"]->email, ['placeholder' => 'Email', 'class' => 'form-control']) !!}<br />
                    Conferma l'email principale delle struttura alla quale <b>ricevere i preventivi di Info Alberghi</b>:
                    {!! Form::text('email_preventivo', $data["record"]->email, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                    
                </div> 
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Invia password</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    {!! Form::open(['id' => 'generate-newsletter', 'url' => url('admin/hotels/newsletter', $data['record']->id), 'method' => 'POST']) !!}
    <div class="modal fade in" id="modal-newsletter" data-backdrop="static"> 
        <div class="modal-dialog"> 
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                    <h4 class="modal-title">Lettera di presentazione</h4> 
                </div> 
                <div class="modal-body">
                    Conferma l'email alla quale <b></b>inviare la lettera di presentazione</b>:
                    {!! Form::text('email_presentazione', $data["record"]->email, ['placeholder' => 'Email', 'class' => 'form-control']) !!}<br />                    
                </div> 
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Invia presentazione</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    

@endsection

@section('onheadclose')

	<style>
		.file-input-name { border:1px solid #ddd; padding:8px; margin: 8px 0 0 0; width:100% !important;  display: block;}
        #category_star { display:none; }
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

    <link  href="{{ Utility::assets('/vendor/oldbrowser/css/jquery-ui.min.css') }}" rel="stylesheet">
    <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>

    <script>
        jQuery(document).ready(function(){
            jQuery('#js-remove-image').click(function(event){
                event.preventDefault();
                jQuery('#image_delete').submit();
            });
        });

        jQuery(document).on("click", "#add-telefono", function() {
           jQuery('#form-telefono').append('<div class="form-telefono" style="margin-top:15px">\
                <input type="text" class="form-control" name="telefono[]" placeholder="Telefono" style="width:50%; display:inline-block" value="">\
                <button type="button" class="btn btn-danger delete-telefono"><i class="glyphicon glyphicon-remove"></i> Elimina</button>\
            </div>' )
        });

        jQuery(document).on("click", '.delete-telefono', function(){
            jQuery(this).closest('.form-telefono').remove();
        });

        jQuery(document).on("click", "#add-cell",function() {
           jQuery('#form-cell').append('<div class="form-cell" style="margin-top:15px">\
                <input type="text" class="form-control" name="cell[]" placeholder="Cellulare" style="width:50%; display:inline-block" value="">\
                <button type="button" class="btn btn-danger delete-cell"><i class="glyphicon glyphicon-remove"></i> Elimina</button>\
            </div>' )
        });

        jQuery(document).on("click", '.delete-cell', function(){
            jQuery(this).closest('.form-cell').remove();
        });

        jQuery(document).on("click", "#add-whatsapp",function() {
           jQuery('#form-whatsapp').append('<div class="form-whatsapp" style="margin-top:15px">\
                <input type="text" class="form-control" name="whatsapp[]" placeholder="Whatsapp" style="width:50%; display:inline-block" value="">\
                <button type="button" class="btn btn-danger delete-whatsapp"><i class="glyphicon glyphicon-remove"></i> Elimina</button>\
            </div>' )
        });

        jQuery(document).on("click", '.delete-whatsapp', function(){
            jQuery(this).closest('.form-whatsapp').remove();
        });
    </script>

@endsection

@section('onbodyclose')

    <script src="{{Utility::assets('/vendor/neon/js/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{Utility::assets('/vendor/neon/js/fileinput.js')}}"></script>

    <script type="text/javascript">

        function selectTypeStructere () {
            let v =  jQuery("#tipologia_id").val();
            if (v == 4) {

                jQuery("#category_star option").each(function (i) {
                    let o = jQuery(this).text().replace(/★/g, "✪");
                    jQuery(this).text(o);
                });

            } else {
                    jQuery("#category_star option").each(function (i) {
                    let o = jQuery(this).text().replace(/✪/g, "★");
                    jQuery(this).text(o);
                });
            }

            jQuery("#category_star").show();
        }

        jQuery(document).ready(function() {

            /** Gestione tipologia hotel */
            jQuery("#tipologia_id").change(function () {
                selectTypeStructere();
            });

            selectTypeStructere();
            
            // Gestione capparra
            
            jQuery("#caparra_si").click(function () {
                jQuery("#caparra_no").prop("checked", "");
            });
            
            jQuery("#caparra_no").click(function () {
                jQuery("#caparra_si").prop("checked", "");
            });

            jQuery("#addSourceRating").click(function () {

                var source = jQuery(".fonte").eq(0).clone().html();
                jQuery(".fonti").append('<hr /><div class="fonte">' + source + "</div>");

                jQuery(".fonte").each(function (i) {
                    jQuery(this).find("label").text("Fonte " + (i+1));
                    jQuery(this).find("input").attr("id", "source_rating_" + i);
                });

            });

            jQuery("#annuale").click(function() {

                var me = jQuery(this).prop('checked');
                jQuery("#aperto_eventi_e_fiere").prop("checked", me);
                jQuery("#aperto_pasqua").prop("checked", me);
                jQuery("#aperto_25_aprile").prop("checked", me);
                jQuery("#aperto_1_maggio").prop("checked", me);
                jQuery("#aperto_capodanno").prop("checked", me);
                jQuery("#aperto_dal").val('');
                jQuery("#aperto_al").val('');

            });

            jQuery("#attivo").click(function() {

                var attivo = jQuery(this).prop('checked');
                
                if(!attivo) {
                    jQuery("#commerciale_id").val(0);
                }


            });

            // DATEPICKER
            // Set all date pickers to have Italian text date.
            jQuery.datepicker.setDefaults(jQuery.datepicker.regional["it"]);

            jQuery('#aperto_dal').datepicker({
                defaultDate: "+0d",
                showOn: "both",
                buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif')}}",
                numberOfMonths: 1,
                autoSize: true,
                showAnim: "clip",
                dateFormat: "dd/mm/yy",
                //minDate: "+0D",
                onSelect: function(selectedDate) {
                    jQuery("#aperto_al" ).datepicker("option", "minDate", selectedDate);
                    jQuery("#aperto_al").datepicker("option", "defaultDate", selectedDate);
                }
            }); 

            jQuery('#aperto_al').datepicker({
                showOn: "both",
                buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif')}}",
                numberOfMonths: 1,
                autoSize: true,
                showAnim: "clip",
                dateFormat: "dd/mm/yy",
                onSelect: function( selectedDate ) {
                    jQuery( "#aperto_dal" ).datepicker("option", "maxDate", selectedDate);
                }
            });
            // FINE DATEPICKER

        });
    </script>

@endsection