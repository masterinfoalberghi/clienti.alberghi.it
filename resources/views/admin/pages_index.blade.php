@extends('templates.admin')

@section('title')
CMS Pagine
@endsection

@section('content')


<div class="legenda">
<span class="legenda-item"><i class="glyphicon glyphicon-ok" style="color:#69C441">&nbsp;</i> La pagina <b>è pubblicata</b></span>
<span class="legenda-item"><i class="glyphicon glyphicon-remove" style="color:#d90000">&nbsp;</i> La pagina <b>non è pubblicata</b></span>

</div>

<table id="records" class="table table-hover table-bordered table-responsive datatable dataTable" >
  <form>
  <thead>
    <tr>
        <th></th>
     <?php
     echo Utility::viewThOrderBy('ID', 'id');
     echo Utility::viewThOrderBy('Lingua', 'lang_id');
     echo Utility::viewThOrderBy('Attiva', 'attiva');
     echo Utility::viewThOrderBy('URI', 'uri');
     echo Utility::viewThOrderBy('data menu', 'menu_dal');
     echo Utility::viewThOrderBy('Ultima modifica', 'updated_at');
     ?>
      <th></th>
    </tr>
    <tr>
        <th><input type="checkbox" value="all" name="modify_page_all" id="modify_page_all" /></th>
      <th width="70">
        {!! Form::text('id', $old['id'], ['class' => 'form-control']) !!}
      </th>
       <th width="70">
        {!! Form::select('lang_id',  ["" => "", 'it' => 'it', 'en' => 'en', 'fr' => 'fr', 'de' => 'de'], isset($old['lang_id']) ? $old['lang_id'] : 'it', ['class' => 'form-control']) !!}
      </th>
      <th width="70">
        {!! Form::select('attiva',  ["" => "", 1 => "si", 0 => "no"], $old['attiva'], ['class' => 'form-control']) !!}
      </th >
     
      <th>
        {!! Form::text('uri', $old['uri'], ['class' => 'form-control']) !!}
      </th>
      <th width="140">
          &nbsp;
      </th>
      <th width="140">
        {!! Form::text('updated_at', $old['updated_at'], ['class' => 'form-control']) !!}
      </th>
      <th class="text-center">
          
        <button type="submit" class="btn btn-blue">Cerca</button>
          
      </th>
    </tr>
  </thead>
  </form>
  <tbody>
  
 
  
  @foreach($data["records"] as $cms_pagina)

      @if ($cms_pagina->menu_auto_annuale == 1 || Utility::isValidMenuAsCarbon($cms_pagina->menu_dal, $cms_pagina->menu_al, $cms_pagina->menu_auto_annuale) && $cms_pagina->attiva)
        <?php $color = "green"; ?>
      @else
        <?php $color = "red"; ?>
      @endif

   <?php 
    
      $style = "";
      $firstDateTimeObj = new DateTime();
      $firstDate = $firstDateTimeObj->format('Y-m-d h:i');
      $secondDate = $cms_pagina->updated_at->format('Y-m-d h:i');
      if ($firstDate == $secondDate) {
          $style = "style='background: #f8f5e5'";
      }
      
   ?>
  
    <tr <?php echo $style ?> id="col_{{ $cms_pagina->id }}">
        
        <td><input type="checkbox" value="{{ $cms_pagina->id }}" name="modify_page_{{ $cms_pagina->id }}" class="modify_page" id="modify_page_{{ $cms_pagina->id }}" /></td>
        <td width="66">{{ $cms_pagina->id }}</td>
        <td width="66">{{ $cms_pagina->lang_id }}</td>
        <td colspan="2" >
        
        <div class="modifica-inline-footer">	
            
            @if($cms_pagina->attiva)
                <i class="glyphicon glyphicon-ok" style="color:#69C441">&nbsp;</i>
            @else
                <i class="glyphicon glyphicon-remove" style="color:#d90000">&nbsp;</i>
            @endif
    
            @if($cms_pagina->template == "localita")
                <i class="glyphicon glyphicon-globe">&nbsp;</i>
            @elseif ($cms_pagina->template == "listing")
                <i class="glyphicon glyphicon-align-justify">&nbsp;</i>
            @else($cms_pagina->template = ="statica")
                <i class="">&nbsp;</i>
            @endif
            
            <a style="color:#b20000; " target="blank" href="{{ url($cms_pagina->uri) }}">{{ $cms_pagina->uri }} <i class="entypo-popup"></i></a>
            
        </div>
        
        <div class="modifica-inline" data-id="{{ $cms_pagina->id }}">
            
            <b style="margin-top:10px; color:#222; font-weight:normal ">{{ $cms_pagina->seo_title }}</b>
            <p>{{ $cms_pagina->seo_description }}</p>
            
        </div>
        
        <div class="modifica-inline-form form-horizontal form-group">
        
        {!! Form::open(["id" => "form-line-" .  $cms_pagina->id ,'url' => 'admin/pages/massive/edit', 'method' => 'POST']) !!}
        {!! Form::hidden('ids_to_change', $cms_pagina->id, [ 'id' => 'ids_to_change']) !!}
        
        <div class="form-group">

            {!! Form::label('URI', 'URI', ['class' => 'col-sm-2 control-label']) !!}

            <div class="col-sm-10" style="padding:7px 15px; ">
                <a style="color:#b20000; margin-bottom:5px; display:block; "  target="blank" href="{{ url($cms_pagina->uri) }}">{{ $cms_pagina->uri }} <i class="entypo-popup"></i></a>
            </div>
        </div>
        
        <div class="form-group">
        
            {!! Form::label('titolo_seo_massivo', 'Title', ['class' => 'col-sm-2 control-label']) !!}
            
            <div class="col-sm-10 cont_caratteri">
            
                {!! Form::text('titolo_seo_massivo', $cms_pagina->seo_title , ['placeholder' => 'Title', 'class' => 'form-control cont_caratteri_input', 'id' => 'titolo_seo_massivo_' . $cms_pagina->id]) !!}
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{HOTEL_COUNT}</a> 
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{PREZZO_MIN}</a>
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{PREZZO_MAX}</a>
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{OFFERTE_COUNT}</a>
<!-- 				<a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{MACRO_LOCALITA}</a> -->
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{LOCALITA}</a>
                <a data-link="titolo_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{CURRENT_YEAR}</a>
                <span class="cont_caratteri">0</span>
            
            </div>
        
        </div>
        
        
        <div class="form-group">
        
            {!! Form::label('descrizione_seo_massivo', 'Description', ['class' => 'col-sm-2 control-label']) !!}
            
            <div class="col-sm-10 cont_caratteri">
            
                {!! Form::textarea('descrizione_seo_massivo', $cms_pagina->seo_description , ['placeholder' => 'Description', 'class' => 'form-control cont_caratteri_input', 'id' => 'descrizione_seo_massivo_' . $cms_pagina->id ]) !!}
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{HOTEL_COUNT}</a> 
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{PREZZO_MIN}</a>
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{PREZZO_MAX}</a>
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{OFFERTE_COUNT}</a>
<!-- 				<a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{MACRO_LOCALITA}</a> -->
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{LOCALITA}</a>
                <a data-link="descrizione_seo_massivo_{{$cms_pagina->id}}" href="#" class="tag">{CURRENT_YEAR}</a>
                <span class="cont_caratteri">0</span>
        
            </div>
        
        </div>
        
        {!! Form::close() !!}
        
        </div>
        
        </td>
        
        <td width="140" style="color: {{$color}}; text-align: center;">
            @if (Utility::getLocalDate($cms_pagina->menu_dal,'%d/%m/%y') != "")
                @if ($cms_pagina->menu_auto_annuale)
                    {{ $cms_pagina->menu_dal->format("d/m")}} - {{ $cms_pagina->menu_al->format("d/m")}}<br />
                    <b>(Rin. automatico)</b>
                @else
                    {{ $cms_pagina->menu_dal->format("d/m/Y")}}
                    <br> al <br>
                    {{ $cms_pagina->menu_al->format("d/m/Y")}}
                @endif
            @endif
        </td>
        
        <td width="140">{{ $cms_pagina->updated_at->format("d/m/Y H:i") }}</td>
        <td class="text-center">
        <a href="{{ url("admin/pages/".$cms_pagina->id."/edit") }}" class="btn btn-primary modify-button">Modifica</a>
        
        
        <button class="btn btn-primary save-button" data-id="{{ $cms_pagina->id }}">Salva</a>
        
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $data["records"]->appends($appends)->links() }}
@endsection

@section('onheadclose')
<style>
    
.legenda { padding:10px; }
.legenda-item { padding-right: 35px;}

.save-button { display:none; }
.modifica-inline { display: block; cursor: pointer;}
.modifica-inline b { margin-bottom: 5px; display: block;  }
.modifica-inline p {  margin-bottom: 15px;  display: block; }
.modifica-inline-form { display: none; }

.tag { font-size:10px; padding:3px 6px; background: #f0f0f0; display: inline-block; border:1px solid #ddd; margin-top: 5px; margin-right:2px;}
.cont_caratteri { position:relative; }
span.cont_caratteri { position:absolute; top:0; right:15px; padding:3px; background: #666; color:#fff; font-size: 10px;  }


table > thead > tr > th{
  cursor: pointer;
}

table > thead > tr > th.nm-order-desc, table > thead > tr > th.nm-order-asc{
  color: #303641;
  font-weight: bold;
}

.nm-order-desc::before, .nm-order-asc::before{
  display: block;
  float: right;
  color: #303641;
  font-family: 'Entypo';
  content: '\e873';
}

.nm-order-asc::before{
  content: '\e876';
}

.intable td { border: 2px solid #f2f2f4 !important; }
</style>
@endsection

@section('onbodyclose')

    <script>

        jQuery(function($) {

            jQuery(".modifica-inline").click(function (e) {
                
                e.preventDefault();
                var id = $(this).data("id");
                $("#col_" + id + " .modifica-inline").hide();
                $("#col_" + id + " .modifica-inline-footer").hide();
                $("#col_" + id + " .modifica-inline-form").show();
                $("#col_" + id + " .modify-button").hide();
                $("#col_" + id + " .save-button").show();
                
                
            });
            
            jQuery(".save-button").click(function () {
                
                var id = jQuery(this).data("id");
                var titolo = jQuery("#titolo_seo_massivo_" + id).val();
                var descrizione = jQuery("#descrizione_seo_massivo_" + id).val();
                var token = jQuery("input[name='_token']").val();
                var ajax = 1;
                
                jQuery.post("/admin/pages/massive/edit", { ajax: ajax, _token: token, ids_to_change: id, titolo_seo_massivo: titolo, descrizione_seo_massivo: descrizione })
                .done(function(data) {
                    
                    $("#col_" + id + " .modifica-inline").show();
                    $("#col_" + id + " .modifica-inline-footer").show();
                    $("#col_" + id + " .modifica-inline-form").hide();
                    $("#col_" + id + " .modify-button").show();
                    $("#col_" + id + " .save-button").hide();
                    $("#col_" + id + " .modifica-inline b").text(titolo);
                    $("#col_" + id + " .modifica-inline p").text(descrizione);
                    
                });
                
            });
            
            function conteggio ( obj) {
                                
                var result = obj.val();
                return result.length;
                
            }
            
            $(".cont_caratteri_input").each(function () {
                
                $(this).parent().find("span.cont_caratteri").text( conteggio($(this)) );
                
            })
            .keyup(function (e) {
                
                $(this).parent().find("span.cont_caratteri").text(  conteggio($(this)) );
                
            })
            
            $(".tag").click( function (e){
                
                var me = 	$('#' + $(this).data("link"));
                var text = 	me.val();
                var valore = $(this).text();
                var newtext = text.substr(0, me[0].selectionStart) + valore + text.substr(me[0].selectionEnd, text.length);
                me.val(newtext);
                $(this).parent().find("span.cont_caratteri").text( conteggio( me ) );
                e.preventDefault();
                
            })

            
            function checkIfMassive() {

                var _return = false;
                var ids = "";
                $(".modify_page").each(function () {

                    if ($(this).is(":checked")) {
                        _return = true;
                        ids += $(this).val() + ",";
                    }

                });

                $("#modify_all_ids").val(ids.slice(0, -1));

                return _return;

            };



            $("#modify_page_all").click(function (e) {

//				e.preventDefualt();
                if ($(this).is(":checked")) {
                    $(".modify_page").prop("checked", "checked");
                } else {
                    $(".modify_page").prop("checked", "");
                }
                
                if (checkIfMassive()) {
                    $(".massive-button").show();
                } else {
                    $(".massive-button").hide();
                }

            });

            $(".modify_page").click( function () {

                if (checkIfMassive()) {
                    $(".massive-button").show();
                } else {
                    $(".massive-button").hide();
                }

            });

            if (checkIfMassive()) {
                    $(".massive-button").show();
                } else {
                    $(".massive-button").hide();
                }

        });


    </script>

@endsection
