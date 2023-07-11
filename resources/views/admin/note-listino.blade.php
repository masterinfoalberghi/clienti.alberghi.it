@extends('templates.admin')

@section('title')
Note Listino
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">

    @if ($data['record'])

    {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/note-listino', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

    {!! csrf_field() !!}

    @if (isset($data['attivo']))
    <div class="form-group">
      <div class="col-sm-10">
        {!! Form::checkbox( 'attivo', 1, $data['attivo'] ) !!} {!! Form::label('attivo',  'Attivo', ['class' => 'col-sm-2 control-label'] ) !!}
      </div>
    </div>
    @endif

    {!! Form::hidden( 'id', $data['id'] ) !!}

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
    <div class="tab-content">
      @foreach (Langs::getAll() as $lang)
        <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">
          <div class="form-group padding-lg">
            {!! Form::hidden( 'testo_id[' .$lang . ']', $data['record'][$lang]->id) !!}
            {!! Form::textarea( 'testo[' . $lang . ']', $data['record'][$lang]->testo, ["id" => "testo_$lang", 'data-lang' => $lang, "class" => "form-control", "placeholder" => "Note listino"] ) !!}
          </div>
        </div>
      @endforeach

      @if ($data['record'])
      <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
      @endif
    </div>

    {!! Form::close() !!}

    @endif

  </div>
</div>
@endsection



@section('onbodyclose')

  {!! HTML::script('neon/js/ckeditor/ckeditor.js'); !!}
  {!! HTML::script('neon/js/ckeditor/adapters/jquery.js'); !!}

  <script type="text/javascript">

    jQuery('domready', function($) {
      var baseRules = {
        tags: {
          strong: {},
          b:      {}
        }
      };

      var tt = [];

      $("textarea[name*='testo[']").each(function(el_index, el){
        var lang = jQuery(el).attr('data-lang');
        var id = jQuery(el).attr('id');
        tt[lang] = new CKEDITOR.replace(el.id, {
          language: 'it',
          removeButtons: 'Source,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Italic,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Indent,Outdent,Blockquote,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,Font,FontSize,TextColor,BGColor,Maximize,About'

        });
      });
    });

  </script>
@endsection