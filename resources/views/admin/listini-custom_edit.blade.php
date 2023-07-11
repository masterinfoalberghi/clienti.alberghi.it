@extends('templates.admin')

@section('title')
  @if ($data["record"]->exists)
    Modifica Listino Personalizzato
  @else
    Nuovo Listino Personalizzato
  @endif
@endsection

@section('content')

	<div class="row">
		<div class="col-lg-12">

			@if ($data["record"]->exists)
				{!! Form::open(['id' => 'record_delete', 'url' => 'admin/listini-custom/delete', 'method' => 'POST']) !!}
					<input type="hidden" name="id" value="<?=$data["record"]->id ?>">
				{!! Form::close() !!}
			@endif

			{!! Form::model($data["model"], ['role' => 'form', 'url' => 'admin/listini-custom/store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'listiniCustomForm']) !!}
				
				{!! csrf_field() !!}
				<input type="hidden" name="id" value="<?=$data["record"]->id ?>" >

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('attivo', "1", null) !!} Visualizza questo listino sul sito
							</label>
						</div>
					</div>

					{{--
					
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									{!! Form::checkbox('intestazione', "1", null) !!} Sul sito visualizza la prima riga della tabella del listino come intestazione
								</label>
							</div>
						</div>
					
					--}}
					
					<div class="col-lg-10 col-lg-offset-2">
						@include("widget.admin.tabnav")
					</div>

					<div class="tab-content ">
				
						@foreach (Langs::getAll() as $lang)

							@if (isset($data['l']) && !is_null($data['l']))      
								<div role="tabpanel" class="tab-pane <?=( $lang === $data['l'] ? 'active' : null)?>" id="{{ $lang }}">
							@else
								<div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">
							@endif

								<div class="form-group">
									{!! Form::label("titolo_$lang", 'Titolo', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text("titolo_$lang", null, ['placeholder' => 'Titolo', 'class' => 'form-control']) !!}
									</div>
								</div>

								{{-- <div class="form-group">
									{!! Form::label("sottotitolo_$lang", 'Sottotitolo', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text("sottotitolo_$lang", null, ['placeholder' => 'Sottotitolo', 'class' => 'form-control']) !!}
									</div>
								</div>

								<div class="form-group">
									{!! Form::label("descrizione_$lang", 'Descrizione', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::textarea("descrizione_$lang", null, ['placeholder' => 'Descrizione', 'class' => 'form-control descrizione']) !!}
									</div>
								</div> --}}

								<div class="form-group">
									{!! Form::label("tabella_$lang", 'Listino', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::textarea("tabella_$lang", null, ['placeholder' => 'Listino', 'class' => 'form-control listino-custom', 'style' => ' height: 600px;']) !!}
									</div>
								</div>

							</div>
						@endforeach
						
					</div>
				

				<div class="form-group">
					<div class="col-sm-12">
						@include('templates.admin_inc_record_buttons')
					</div>
				</div>

			{!! Form::close() !!}

		</div>
	</div>

@endsection

@section('onheadclose')

  <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/tinymce/tinymce.min.js')}}"></script>
  
  <script type="text/javascript">
  
  
  /**
   * Il cursore è posizionato dentro ad un certo tag?
   * Mi serve perchè il plugin table di tinymce altera la tabella sulla base della posizione del cursore
   * @param  object    editor           Istanza attiva dell'editor tinymce (in genere è tornata da tinymce.activeEditor)
   * @param  string    inside_which_tag Il nome del tag dove verificare se il cursore è posizionato (es: "thead")
   * @return boolean
   */
  function isCursorInside(editor, inside_which_tag){

    cursor_position = editor.dom.getParent(editor.selection.getStart(), inside_which_tag);

    if(cursor_position)
      return true;

    return false;
  }

  function removeInvalidContent(editor){

    /*
    {{--
      Inizialmente avevo provato con le regexp,
      ma si riconferma la regola che è meglio non usare le regexp per fare il parsing del html
      allora ho pensato, il js ha già il suo parser html è il DOM!
      Per questo mi creo un fragment (che è una specie di dom temporaneo esterno al dom vero)
    --}}
    */

    var content = editor.getContent();

    var frag = document.createDocumentFragment();

    /*
     * Creo un fragment e ci metto un mio contenitore,
     * per poterlo cercare dopo con un css selector
     */
    var div = document.createElement("div");
    div.id = 'my_fragment_container';
    div.innerHTML = content;

    frag.appendChild(div);

    /*
     * Cerco la tabella, se non c'è imposto il contenuto come vuoto
     * se c'è ma non è da sola, forzo il contenuto a soltanto la tabella stessa
     */
    var table = frag.querySelector('#my_fragment_container table:first-of-type');

    /*
    {{--
      # Il problema del carattere € che sposta il cursore
      TinyMCE modifica il contenuto quando ci sono caratteri non ascii
      lo fa anche con gli spazi (credo temporaneamente a scopo di layout)
      li sostituisce con &nbsp;
      Tutto questo fa si che il controllo se il contenuto è cambiato
      ha tanti falsi positivi, quando il cotenuto viene riscritto il cursore viene riposizionato all'inizio
      Un'altra cosa che fa tinymce è che i <br> li cambia in <br />
      Quindi normalizzo i contenuti, prima di tutto nella configurazione di tinymce ho messo

      entity_encoding : "raw" vedi https://www.tinymce.com/docs/configure/content-filtering/#encodingtypes
      element_format : 'html' per impedire il cambio da <br> a <br /> che tinymce fa in automatico

      poi prima del controllo porto i due contenuti che verifico ad essere tutti e due con le entità convertite in caratteri veri e propri
      https://stackoverflow.com/questions/5796718/html-entity-decode
    --}}
    */    

    var normalize = jQuery('<textarea />');
    var whole_html = normalize.html(content).text();
    var table_only_html = normalize.html(table.outerHTML).text();
    var traduzioni;
    if(table === null)
      editor.setContent('');
    else if(whole_html !== table_only_html)
      editor.setContent(table_only_html);
  }

  tinymce.init({
      plugins: ["code"],
      selector: "textarea.descrizione",
      valid_elements: "strong,br,p",
      content_css: '{{ url("https://static.info-alberghi.com/css-js/romagna/vendor/neon/css/listini-custom.css") }}',
      menubar: false,
      statusbar: false,
      object_resizing : false,
      toolbar: "bold,code",


  });


  tinymce.init({
      selector: "textarea.listino-custom",

      @if ($data['advanced'])
        plugins: ["table", "code"],
      @else
        plugins: ["table"],
      @endif      
      valid_elements: "table,tr,th,td,tbody,thead,br,th[colspan],th[width],td[width]",
      content_css: '{{ url("https://static.info-alberghi.com/css-js/romagna/vendor/neon/css/listini-custom.css") }}',
      @if ($data['advanced'])
        menubar: true,
        statusbar: false,
      @else
        menubar: false,
        statusbar: false,
      @endif
      entity_encoding : "raw",
      element_format : 'html',
      object_resizing : false,
      toolbar: "1 2 3 4 | 5",
      table_toolbar_enabled: false,
      setup: function(editor) {

          /*
           * Con tinymce 4 non c'è un evento unico per capire se in qualche modo il content è cambiato
           * quindi devo ascoltare eventi diversi...
           * ad esempio l'evento change è chiamato solo quando l'editor perde il focus
           */
          editor.on('change', function(e) {
            removeInvalidContent(editor);
          });

          editor.on('paste', function(e) {
            removeInvalidContent(editor);
          });

          editor.on('keyup', function(e) {
            removeInvalidContent(editor);
          });

          editor.addButton('1', {
            text: 'Aggiungi una riga',
            icon: false,
            onClick: function() {

                editor.execCommand('mceTableInsertRowAfter', false, "Aggiungi una riga");
            }
          });

          editor.addButton('2', {
            text: 'Togli una riga',
            icon: false,
            onClick: function() {
              var ok = true;

              if(editor.dom.select('tbody tr').length <= 1){
                ok = false;
                editor.windowManager.alert("Hai raggiunto raggiunto il numero minimo di righe possibili.");
              }

              if(ok){
                try{
                  editor.execCommand('mceTableDeleteRow', false, "Togli una riga");
                }
                catch(e){
                }
              }
            }
          });

          editor.addButton('3', {
            text: 'Aggiungi una colonna',
            icon: false,
            onClick: function() {

              if(editor.dom.select('tbody tr:first-child td').length >= 6)
                editor.windowManager.alert("Hai raggiunto raggiunto il numero massimo di colonne possibili.");
              else
                editor.execCommand('mceTableInsertColAfter', false, "Aggiungi una colonna");
            }
          });

          editor.addButton('4', {
            text: 'Togli una colonna',
            icon: false,
            onClick: function() {
              if(editor.dom.select('tbody tr:first-child td').length <= 2)
                editor.windowManager.alert("Hai raggiunto raggiunto il numero minimo di colonne possibili.");
              else
                editor.execCommand('mceTableDeleteCol', false, "Togli una colonna");
            }
          });


          editor.addButton('5', {
            text: 'Clona questo listino su tutte le lingue',
            icon: false,
            onClick: function() {
              if(tinyMCE.activeEditor.id != 'tabella_it') 
              {
                alert('Questa operazione è possibile solo dalla lingia italiana !\n Se sei già sulla lingua italiana clicca sul listino e riprova');
                return false;
              }
              var content = editor.getContent();
              console.log('content = '+content);
              var data = {
                      content: content,
                      '_token': jQuery('input[name=_token]').val(),
                          };
              jQuery.ajax({

                        url: '<?=url("admin/listini-custom/translate_data_ajax") ?>',
                        type: 'POST',
                        data: data,
                        async:false,
                        success: function(data) {
                            traduzioni = jQuery.parseJSON(data);
                        }
                    });

              for(var i = 0; i < tinyMCE.editors.length; i++){

                if(tinyMCE.editors[i].id.indexOf('tabella') > -1 && editor.id != tinyMCE.editors[i].id) {

                  if(tinyMCE.editors[i].id.indexOf('tabella_en') > -1) {
                      tinyMCE.editors[i].setContent(traduzioni.en);
                  } 
                  else if(tinyMCE.editors[i].id.indexOf('tabella_fr') > -1) {
                      tinyMCE.editors[i].setContent(traduzioni.fr);
                  } 
                  else if(tinyMCE.editors[i].id.indexOf('tabella_de') > -1) {
                      tinyMCE.editors[i].setContent(traduzioni.de);
                  } else {
                    tinyMCE.editors[i].setContent(content);
                  }

                }
                  
              }

              editor.windowManager.alert("Il contenuto della versione in questa lingua è stato applicato a tutte le altre lingue. Ricorda di cliccare su 'Salva' per applicare le modifiche!");

            }
          });

          editor.on('init', function(e) {
            console.log('The Editor has initialized.');
          });

          
      }

  });
  </script>
@endsection
@section('onbodyclose')

<script>
  jQuery("form#listiniCustomForm").submit(function(){
    window.alert("Ricordati di clonare il listino per tutte le lingue cliccando l'apposito bottone sotto il titolo");
    return;
  });
</script>

@endsection
