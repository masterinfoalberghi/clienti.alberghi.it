@extends('templates.admin')

@section('title')
    Modifica Massiva Pagine listing
@endsection

@section('content')

    <div class="form-horizontal form-group">

        {!! Form::open(['id' => 'massive_edit', 'url' => 'admin/listing/massive/edit', 'method' => 'POST']) !!}
        {!! Form::hidden('ids_to_change', $ids, [ 'id' => 'ids_to_change']) !!}
        {!! Form::hidden('querystring_ricerca', $querystring_ricerca, [ 'id' => 'querystring_ricerca']) !!}

        <div class="panel panel-default" data-collapsed="0">

            <div class="panel-heading"><div class="panel-title">Campi SEO</div></div>
            <div class="panel-body">

                <div class="form-group">
                    {!! Form::label('titolo_seo_massivo', 'Title', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('titolo_seo_massivo', null, ['placeholder' => 'Title', 'class' => 'form-control cont_caratteri_input', 'id' => 'titolo_seo_massivo']) !!}
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{HOTEL_COUNT}</a>
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{PREZZO_MIN}</a>
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{PREZZO_MAX}</a>
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="titolo_seo_massivo" href="#" class="tag">{CURRENT_YEAR}</a>
                        <span class="cont_caratteri">0</span>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('descrizione_seo_massivo', 'Description', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::textarea('descrizione_seo_massivo', null, ['placeholder' => 'Description', 'class' => 'form-control cont_caratteri_input', 'id' => 'descrizione_seo_massivo' ]) !!}
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{HOTEL_COUNT}</a>
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{PREZZO_MIN}</a>
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{PREZZO_MAX}</a>
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{OFFERTE_COUNT}</a>
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{LOCALITA}</a>
                        <a data-link="descrizione_seo_massivo" href="#" class="tag">{CURRENT_YEAR}</a>
                        <span class="cont_caratteri">0</span>
                    </div>
                </div>

            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="js-salva-top"><i class="glyphicon glyphicon-ok"></i> Salva</button>
                <a href="/admin/listing?{{$querystring_ricerca}}" class="btn btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i> Annulla</a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@endsection

@section('onheadclose')

    <style>

        .tag { font-size:10px; padding:3px 6px; background: #f0f0f0; display: inline-block; border:1px solid #ddd; margin-top: 5px; margin-right:2px;}
        .cont_caratteri { position:relative; }
        span.cont_caratteri { position:absolute; top:0; right:15px; padding:3px; background: #666; color:#fff; font-size: 10px; }

    </style>

    <script>

        var console=console?console:{log:function(){}}
        jQuery(function($) {

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

        });


    </script>

@endsection

@section('onbodyclose')
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/ckeditor/adapters/jquery.js')}}"></script>
<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/jquery-fieldselection.js')}}"></script>
@endsection