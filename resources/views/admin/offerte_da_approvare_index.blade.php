@extends('templates.admin')

@section('title')
Offerte da approvare
@endsection

@section('content')

  <div class="row">
    <div class="col-lg-12">

      <h4>
      Elenco offerte da approvare&nbsp;&nbsp;&nbsp;
      <i class="entypo-list"></i> <span class="badge badge-info" id="totale"></span> </a>
      </h4>
      <?php $count = 0; ?>
      @foreach($offerte as $offerta)
      <?php
           
	        $offerta_lingua =  $offerta->offerte_lingua->first();
          $pagine_link = array();
          $array_title_pagine = array();
        ?>

        @if (!is_null($offerta_lingua))
          @foreach($paroleChiave as $parola)
                @if (!empty(strtolower($parola["chiave"])) && ( strpos(strtolower($offerta_lingua["titolo"]), strtolower($parola["chiave"])) || strpos(strtolower($offerta_lingua["testo"]), strtolower($parola["chiave"])) ))

              <?php
                $pagine_link = "";
              ?>
              @if($pagine_link)
                @foreach($pagine_link as $pl)
                  <?php array_push($array_title_pagine, array($pl["id"], $pl["h1"], strtolower($parola["chiave"]), $pl["uri"], $pl["lang_id"]));	?>
                @endforeach
              @endif
            @endif
          @endforeach


          <?php $count++; ?>

			<div class="offerta_da_approvare {{$offerta->tipologia}}" id="off_{{$offerta->id}}">
				@if ($offerta->tipologia=="offerta")
				<b class="label label-success">{{$offerta->tipologia}}</b>
				@else
				<b class="label label-success" style="background-color:#f24a46">{{$offerta->tipologia}}</b>
				@endif

				<h3>{{$offerta_lingua->titolo}} valida dal {{$offerta->valido_dal->format('d/m/Y')}} al {{$offerta->valido_al->format('d/m/Y')}}</h3>
				<p>
				<ul>
				<li>Prezzo a persona @if ($offerta->prezzo_a_partire_da) a partire da @endif <strong>€ {{$offerta->prezzo_a_persona}}</strong></li>
				<li>per {{$offerta->per_persone}} persone</li>
				<li>per {{$offerta->per_giorni}} notti</li>
				<li>in {{$offerta->formula}}</li>
				</ul>
				</p>
				<p>
				{!!$offerta_lingua->testo!!}
				</p>
				<br />
				@if($array_title_pagine)
					<h4>In quale pagine vedo questa offerta?</h4>
					<div class="pagine_destinazione">
						<table width="100%">
							<tr>
								<th width="20%">Chiave</th>
								<th>Lingua</th>
								<th>Parola</th>
								<th>Pagina</th>
							</tr>
						@foreach($array_title_pagine as $p)
							<tr>
								<td>{{$p[3]}}</td>
								<td><img src="{{Utility::asset('/images/admin/' . $p[4] . '.png')}}" style="width:15px; height:auto;"></td>
								<td>{{$p[2]}}</td>
								<td>({{$p[0]}}) {{$p[1]}}<a href="{{Utility::getUrlWithLang("it" , "/" . $p[3], true)}}"><i class="entypo-popup"></i></a></td>
							</tr>
						@endforeach
						</table>
					</div>
				@endif

				<br />

				<div class="label_approvazione">
					<button class="btn btn-default" type="button">
						{{$offerta->cliente->nome}} <b>({{$offerta->cliente->id}})</b> - {{$offerta->tipologia}} - {{$offerta->getUltimaModifica()}}
					</button>
				</div>


				<div class="bottoni_approvazione_" style="float:right;">
					<a data-id-offerta="{{$offerta->id}}" class="approva btn btn-success">Approva</a>
					<a data-id="{{$offerta->cliente->id}}" data-id-offerta="{{$offerta->id}}" data-tipologia="{{$offerta->tipologia}}" class="modifica btn btn-danger">Modifica</a>
				</div><div style="clear:both;"></div>


			</div>

        @endif

      @endforeach

      @if ($count > 0)
        <hr />
      @endif


      @foreach($offertePP as $offerta)
      	<?php $offerta_lingua =  $offerta->offerte_lingua->first(); ?>
         @if (!is_null($offerta_lingua))

          <?php $count++; ?>

          <div class="offerta_da_approvare offerta_pp" id="off_pp_{{$offerta->id}}">
		  	<b class="label label-danger">offerta prenota prima</b>
            <h3>{{$offerta_lingua->titolo}} valida dal {{$offerta->valido_dal->format('d/m/Y')}} al {{$offerta->valido_al->format('d/m/Y')}}</h3>
            <p>
              <ul>
                <li>Sconto <strong>{{$offerta->perc_sconto}}%</strong></li>
                <li>prenotando entro il {{$offerta->prenota_entro->format('d/m/Y')}}</li>
                <li>per {{$offerta->per_persone}} persone</li>
                <li>per {{$offerta->per_giorni}} giorni</li>
              </ul>
            </p>
            <p>
              {!!$offerta_lingua->testo!!}
            </p>

            <div class="bottoni_approvazione_"  style="float:right;">
              <a data-id-offerta="{{$offerta->id}}" class="approva_pp btn btn-success">Approva</a>
              <a data-id="{{$offerta->cliente->id}}" data-id-offerta="{{$offerta->id}}" class="modifica_pp btn btn-danger">Modifica</a>
            </div><div style="clear:both;"></div>

            <div class="label_approvazione">
              <button class="btn btn-default" type="button">
                {{$offerta->cliente->nome}} <b>({{$offerta->cliente->id}})</b> - Prenota prima - {{$offerta->getUltimaModifica()}}
              </button>
            </div>

          </div>

        @endif

      @endforeach

      @if ($count > 0)
        <hr />
      @endif


      @foreach ($offerteBB as $offerta)
          <?php $offerta_lingua =  $offerta->offerte_lingua->first(); ?>

           @if (!is_null($offerta_lingua))
            <?php $count++; ?>

            <div class="offerta_da_approvare offerta_bb" id="off_bb_{{$offerta->id}}">
    		      
              <b class="label label-info">offerta bambini gratis</b>
              <h3>Bambini gratis dal {{$offerta->valido_dal->format('d/m/Y')}} al {{$offerta->valido_al->format('d/m/Y')}}</h3>
              <p>
                <ul>
                  <li>Bambini {{ $offerta->_fino_a_anni() }} {{ $offerta->anni_compiuti }}</li>
                </ul>
              </p>
              <p>
                {!!$offerta_lingua->note!!}
              </p>

              <div class="bottoni_approvazione_"  style="float:right;">
                <a data-id-offerta="{{$offerta->id}}" class="approva_bb btn btn-success">Approva</a>
                <a data-id="{{$offerta->cliente->id}}" data-id-offerta="{{$offerta->id}}" class="modifica_bb btn btn-danger">Modifica</a>
              </div><div style="clear:both;"></div>

              <div class="label_approvazione">
                <button class="btn btn-default" type="button">
                  {{$offerta->cliente->nome}} <b>({{$offerta->cliente->id}})</b> - Bambini gratis - {{$offerta->getUltimaModifica()}}
                </button>
              </div>

            </div>
          @endif

      @endforeach



    </div>
  </div>

  @if($count == 0)
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-info">
          <i class="fa fa-info-circle"></i> Nessuna offerta da approvare.
        </div>
      </div>
    </div>
  @endif

@endsection



@section('onheadclose')
	<style>
		table th, table td { border-bottom: 1px solid #ddd; padding:5px; vertical-align: top  }
		table, th, td { border-collapse: collapse; }
		table tr:nth-child(even) { background: #f5f5f5; }
		.label { text-transform: uppercase; }
		.label-success { background: #639a48;}
		.label-danger { background: #ff9326;}
		.label-info { background: #1fb0e1;}
	</style>

  <script type="text/javascript">

  jQuery(function() {

      var $totale = {{$count}};

      jQuery("#totale").html($totale);

      /* modifica e approva offerta */
      jQuery(".modifica").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");
          var tipologia = jQuery(this).data("tipologia");
          var uri = null;

          if (tipologia=='offerta')  {
            uri = "offerte";
          }
          else {
            uri = "last";
          }

          jQuery.ajax({
                  url: '<?php echo url("admin/seleziona-hotel-da-id-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id': jQuery(this).data("id"),
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                    // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                    // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                    // return(val) viene eseguito prima che val prenda il nuovo valore

                    // location.href = '/admin/'+ uri +'/' + id_offerta + '/edit';

                    window.open(
                        '/admin/'+ uri +'/' + id_offerta + '/edit',
                        '_blank'
                      );
                }
            });

      });

      jQuery(".approva").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");

          jQuery.ajax({
                  url: '<?php echo url("admin/approva-offerta-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id-offerta': id_offerta,
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                      jQuery("#off_"+id_offerta).fadeOut();
                  }
            });

      });

      /* modifica e approva offerta prenota prima */


      jQuery(".modifica_pp").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");

          jQuery.ajax({
                  url: '<?php echo url("admin/seleziona-hotel-da-id-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id': jQuery(this).data("id"),
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                    // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                    // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                    // return(val) viene eseguito prima che val prenda il nuovo valore

                    // location.href = '/admin/prenota-prima/' + id_offerta + '/edit';

                    window.open(
                        '/admin/prenota-prima/' + id_offerta + '/edit',
                        '_blank'
                      );
                }
            });

      });


       jQuery(".approva_pp").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");

          jQuery.ajax({
                  url: '<?php echo url("admin/approva-offerta_pp-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id-offerta': id_offerta,
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                      jQuery("#off_pp_"+id_offerta).fadeOut();
                  }
            });

      });





      /* modifica e approva offerta bambini gratis */


      jQuery(".modifica_bb").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");

          jQuery.ajax({
                  url: '<?php echo url("admin/seleziona-hotel-da-id-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id': jQuery(this).data("id"),
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                    // se la chiamata ajax ha successo val prende il valore resituito dalla chiamata
                    // la chiamata DEVE ESSERE "async: false" perché altrimenti il return di editable
                    // return(val) viene eseguito prima che val prenda il nuovo valore

                    // location.href = '/admin/bambini-gratis/' + id_offerta + '/edit';

                    window.open(
                        '/admin/bambini-gratis/' + id_offerta + '/edit',
                        '_blank'
                      );
                }
            });

      });


       jQuery(".approva_bb").click(function() {

          var id_offerta = jQuery(this).data("id-offerta");

          jQuery.ajax({
                  url: '<?php echo url("admin/approva-offerta_bb-ajax") ?>/'+id_offerta,
                  type: "post",
                  async: false,
                  data : {
                          'id-offerta': id_offerta,
                          '_token': jQuery('input[name=_token]').val()
                          },
                  success: function(id_offerta) {
                      jQuery("#off_bb_"+id_offerta).fadeOut();
                  }
            });

      });





    });

  </script>

@endsection
