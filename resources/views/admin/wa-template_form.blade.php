

@if ($wa_template->exists)
  <form role="form" action="{{ route('wa-template.update', $wa_template->id) }}" method="POST">
  {{ method_field('PUT') }}
@else
  <form role="form" action="{{ route('wa-template.store') }}" method="POST" enctype="multipart/form-data">
@endif
  {!! csrf_field() !!}

  <div class="form-group row">
    <label class="col-md-2 text-change" for="titolo">Titolo:</label>
    <div class="col-md-10">
      <input type="text" name="titolo" id="titolo" value="{{ old('titolo') != '' ?  old('titolo') : $wa_template->titolo}}"  class="form-control" placeholder="Titolo" required>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-md-2 text-change" for="testo">testo:</label>
    <div class="col-md-10">
        <textarea name="testo" class="form-control" id="testo" rows="10" required>{{ old('testo') != '' ?  old('testo') : $wa_template->testo}}</textarea>
    </div>
  </div>

  <div class="box-footer">
    <button type="submit" class="btn btn-primary avoid-glyphicon">
      @if ($wa_template->exists)
        Modifica
      @else
        Crea
      @endif
    </button>
    <a href="{{ route('whatsapp-non-rubirca-lista') }}" title="Annulla" class="btn btn-gold pull-right">Annulla</a>
  </div>
</form>