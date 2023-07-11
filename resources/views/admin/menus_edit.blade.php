@extends('templates.admin')

@section('title')
  Menu Tematico
    @if(gettype(json_decode($data['macrolocalita']->nome)) == "object")
        {{json_decode($data['macrolocalita']->nome)->it}}
    @else
        {{$data['macrolocalita']->nome}}
    @endif

@endsection

@section('content')
  <?php
  // devo passare anche la macro perché le pagine trasversali non hanno macro e micro, quindi troverei 0
  $macrolocalita_id = $data['macrolocalita_id'];
  ?>

  <!-- Nav tabs -->

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    @foreach (Langs::getAll() as $lang)
      <li role="presentation" <?= $lang === 'it' ? 'class="active"' : null ?>>
        <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile"
          role="tab" data-toggle="tab">
          <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
        </a>
      </li>
    @endforeach
  </ul>


  <!-- Tab panes -->
  <form method="post" action="{{ url('admin/menus/add') }}" class="form-horizontal">

    {!! csrf_field() !!}

    <input type="hidden" name="macrolocalita_id" value="{{ $data['macrolocalita']->id }}">

    <div class="tab-content">
      @foreach (Langs::getAll() as $lang)
        <div role="tabpanel" class="tab-pane <?= $lang === 'it' ? 'active' : null ?>" id="{{ $lang }}">

            <h2>
                {{ Langs::getName($lang) }}
                @if (!empty($data['pagine'][$lang]))
                    <a target="blank" href="{{ url($data['pagine'][$lang]) }}" class="pull-right"><small>Apri su sito <i
                    class="entypo-popup"></i></small></a>
                @endif
            </h2>

            <div class="row">

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">Link presenti nel blocco Servizi</div>
                </div>
                <div class="panel-body">
                    @if (empty($data['servizi'][$lang]))
                    <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                    @else
                    <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                    <div class="dd ordinamento">
                        <ul class="list-unstyled" class="dd-list">
                        @foreach ($data['servizi'][$lang] as $row)
                            <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                            <div class="dd-handle dd3-handle">Drag</div>
                            <div class="dd3-content">
                                @if ($row->template == 'listing')
                                <a href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @else
                                <a href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @endif
                                <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}" class="pull-right"><i class="entypo-cancel"></i></a>
                            </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                </div>
                </div>

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">Link presenti nel blocco Offerte</div>
                </div>
                <div class="panel-body">
                    @if (empty($data['offerte'][$lang]))
                    <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                    @else
                    <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                    <div class="dd ordinamento">
                        <ul class="list-unstyled" class="dd-list">
                        @foreach ($data['offerte'][$lang] as $row)
                            <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                            <div class="dd-handle dd3-handle">Drag</div>
                            <div class="dd3-content">

                                @if ($row->template == 'listing')
                                <a class="dd3-content-items"
                                    href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @else
                                <a class="dd3-content-items"
                                    href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @endif

                                @if ($row->menu_dal != '2000-01-01' && $row->menu_dal != '0000-00-00' && $row->menu_al != '2000-01-01' && $row->menu_al != '0000-00-00')
                                @if ($valid = Utility::isValidMenu($row->menu_dal, $row->menu_al, 'ancora', $row->menu_auto_annuale))
                                    <?php $color = 'green'; ?>
                                @else
                                    <?php $color = 'red'; ?>
                                @endif
                                @if ($row->menu_auto_annuale)
                                    @php
                                    $mask = '%d/%m';
                                    @endphp
                                @else
                                    @php
                                    $mask = '%d/%m/%y';
                                    @endphp
                                @endif

                                <small class="dd3-content-items" style="color: {{ $color }}">
                                    {{ Utility::getLocalDate(Carbon\Carbon::parse($row->menu_dal), $mask) }} -
                                    {{ Utility::getLocalDate(Carbon\Carbon::parse($row->menu_al), $mask) }}
                                </small>
                                @endif
                                <a class="dd3-content-items"
                                href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                                class="pull-right"><i class="entypo-cancel"></i></a>
                            </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                </div>

                </div>

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">Link presenti nel blocco Trattamenti</div>
                </div>
                <div class="panel-body">
                    @if (empty($data['trattamenti'][$lang]))
                    <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                    @else
                    <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                    <div class="dd ordinamento">
                        <ul class="list-unstyled" class="dd-list">
                        @foreach ($data['trattamenti'][$lang] as $row)
                            <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                            <div class="dd-handle dd3-handle">Drag</div>
                            <div class="dd3-content">

                                @if ($row->template == 'listing')
                                <a class="dd3-content-items"
                                    href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @else
                                <a class="dd3-content-items"
                                    href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                @endif

                                <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                                class="pull-right"><i class="entypo-cancel"></i></a>
                            </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                <div class="panel-title">Link presenti nel blocco Parchi</div>
                </div>
                <div class="panel-body">
                @if (empty($data['parchi'][$lang]))
                <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                @else
                <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                <div class="dd ordinamento">
                <ul class="list-unstyled" class="dd-list">
                @foreach ($data['parchi'][$lang] as $row)
                    <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content">

                        @if ($row->template == 'listing')
                        <a class="dd3-content-items"
                            href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @else
                        <a class="dd3-content-items"
                            href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @endif

                        <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                        class="pull-right"><i class="entypo-cancel"></i></a>
                    </div>
                    </li>
                @endforeach
                </ul>
                </div>
                @endif
                </div>
                </div>

                </div>

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                <div class="panel-title">Link presenti nel blocco Famiglia</div>
                </div>
                <div class="panel-body">
                @if (empty($data['famiglia'][$lang]))
                <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                @else
                <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                <div class="dd ordinamento">
                <ul class="list-unstyled" class="dd-list">
                @foreach ($data['famiglia'][$lang] as $row)
                    <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content">
                        @if ($row->template == 'listing')
                        <a class="dd3-content-items"
                            href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @else
                        <a class="dd3-content-items"
                            href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @endif
                        <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                        class="pull-right"><i class="entypo-cancel"></i></a>
                    </div>
                    </li>
                @endforeach
                </ul>
                </div>
                @endif
                </div>
                </div>
                </div>

                <div class="col-sm-4">

                <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                <div class="panel-title">Link presenti nel blocco Visibilità</div>
                </div>
                <div class="panel-body">
                @if (empty($data['visibilita'][$lang]))
                <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                @else
                <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                <div class="dd ordinamento">
                <ul class="list-unstyled" class="dd-list">
                @foreach ($data['visibilita'][$lang] as $row)
                    <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content">

                        @if ($row->template == 'listing')
                        <a class="dd3-content-items"
                            href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @else
                        <a class="dd3-content-items"
                            href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                        @endif

                        <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                        class="pull-right"><i class="entypo-cancel"></i></a>
                    </div>
                    </li>
                @endforeach
                </ul>
                </div>
                @endif
                </div>
                </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">Link presenti nel blocco Punti di interesse</div>
                        </div>
                        <div class="panel-body">
                            @if (empty($data['poi'][$lang]))
                                <div class="alert alert-info">Non è ancora stato inserito nessun link in questo blocco</div>
                            @else
                                <p>Ordina i link tramite drag'n'drop. Le modifiche all'ordinamento sono salvate automaticamente</p>
                                <div class="dd ordinamento">
                                    <ul class="list-unstyled" class="dd-list">
                                        @foreach ($data['poi'][$lang] as $row)
                                            <li class="dd-item dd3-item" data-id="{{ $row->id }}">
                                                <div class="dd-handle dd3-handle">Drag</div>
                                                <div class="dd3-content">
                                                    @if ($row->template == 'listing')
                                                    <a class="dd3-content-items"
                                                    href="{{ url('admin/listing/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                                    @else
                                                    <a class="dd3-content-items"
                                                    href="{{ url('admin/pages/' . $row->cms_pagine_id . '/edit') }}">{{ $row->uri }}</a>
                                                    @endif
                                                    <a href="{{ url('admin/menus/' . $row->id . '/' . $macrolocalita_id . '/delete') }}"
                                                    class="pull-right"><i class="entypo-cancel"></i></a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <h2>Aggiungi nuovi links</h2><br />
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">Aggiungi nuovi listing</div>
                        </div>
                        <div class="panel-body">
                            @if (empty($data['cms_pagine']['listing'][$lang]))
                                <div class="alert alert-warning">Questa macro località non risulta associata a nessuna pagina con template
                                listing</div>
                            @else
                                <p>I link che non saranno inseriti nei blocchi Servizi e Offerte, compariranno nell'elenco "Altri link"</p><br />
                                @foreach ($data['cms_pagine']['listing'][$lang] as $id => $uri)
                            
                                    <div class="form-group">
                                        <label class="col-sm-4 ">
                                        <a href="{{ url("admin/listing/$id/edit") }}">{{ $uri }}</a>
                                        </label>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 0, true) !!} Nessuno
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'servizi') !!} Servizi
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'offerte') !!} Offerte
                                            </label>
                                        </div>
                                        </div>

                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'trattamenti') !!} Trattamenti
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'parchi') !!} Parchi
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'visibilita') !!} Visibilità
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'famiglia') !!} Famiglia
                                            </label>
                                        </div>
                                        </div>

                                        <div class="col-sm-1">
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::radio("cms_pagina_{$lang}_{$id}", 'poi') !!} Poi
                                            </label>
                                        </div>
                                        </div>
                                    </div>
                            
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-default" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">Aggiungi nuove pagine statiche</div>
                        </div>
                        <div class="panel-body">
                            @if (empty($data['cms_pagine']['statica'][$lang]))
                                <div class="alert alert-warning">Questa macro località non risulta associata a nessuna pagina con template
                                statica</div>
                            @else
                                @foreach ($data['cms_pagine']['statica'][$lang] as $id => $uri)
                                <div class="form-group">
                                    <label class="col-sm-3 ">
                                    <a href="{{ url("admin/pages/$id/edit") }}">{{ $uri }}</a>
                                    </label>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 0, true) !!} Nessuno
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'servizi') !!} Servizi
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'offerte') !!} Offerte
                                        </label>
                                    </div>
                                    </div>

                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'trattamenti') !!} Trattamenti
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'parchi') !!} Parchi
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'visibilita') !!} Visibilità
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'famiglia') !!} Famiglia
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-sm-1">
                                    <div class="checkbox">
                                        <label>
                                        {!! Form::radio("cms_pagina_{$lang}_{$id}", 'poi') !!} Poi
                                        </label>
                                    </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
      @endforeach
      <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Salva</button>
    </div>
  </form>

@endsection

@section('onheadclose')
  <style>
    /**
         * Nestable Draggable Handles
         */

    .dd3-content {
      display: block;
      height: 30px;
      margin: 5px 0;
      padding: 5px 10px 5px 40px;
      color: #333;
      text-decoration: none;
      font-weight: bold;
      border: 1px solid #ccc;
      background: #fafafa;
      background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
      background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
      background: linear-gradient(top, #fafafa 0%, #eee 100%);
      -webkit-border-radius: 3px;
      border-radius: 3px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
    }

    .dd3-content:hover {
      color: #2ea8e5;
      background: #fff;
    }

    .dd-dragel>.dd3-item>.dd3-content {
      margin: 0;
    }

    .dd3-item>button {
      margin-left: 30px;
    }

    .dd3-handle {
      position: absolute;
      margin: 0;
      left: 0;
      top: 0;
      cursor: pointer;
      width: 30px;
      text-indent: 100%;
      white-space: nowrap;
      overflow: hidden;
      border: 1px solid #aaa;
      background: #ddd;
      background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
      background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
      background: linear-gradient(top, #ddd 0%, #bbb 100%);
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }

    .dd3-handle:before {
      content: '≡';
      display: block;
      position: absolute;
      left: 0;
      top: 3px;
      width: 100%;
      text-align: center;
      text-indent: 0;
      color: #fff;
      font-size: 20px;
      font-weight: normal;
    }

    .dd3-handle:hover {
      background: #ddd;
    }

  </style>

  <script>
    jQuery(document).ready(function() {

      jQuery('.ordinamento').nestable({
          maxDepth: 0
        })
        .on('change', function(e) {
          var ids = [];

          var li = jQuery(e.target).find('li');

          li.each(function() {
            ids.push(jQuery(this).data('id'));
          });

          jQuery.ajax({
            url: '<?= url('admin/menus/saveOrder') ?>',
            type: "post",
            data: {
              'ids': ids.join(','),
              '_token': jQuery('input[name=_token]').val()
            }
          });
        });
    });
  </script>
@endsection

@section('onbodyclose')
  <script type="text/javascript" src="{{ Utility::assets('/vendor/neon/js/jquery.nestable.js') }}"></script>
@endsection
