
<div class="form-group">
    
   
    {!! Form::label('tipologia', 'Tipologia', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
     
      <select id="tipologia" name="tipologia" class="form-control">
      	@if (old('tipologia') != '')
	        <option value="offerta" @if (old('tipologia') == 'offerta') selected="selected"@endif>offerta</option>
	        <option value="lastminute" @if (old('tipologia') == 'lastminute') selected="selected"@endif>lastminute</option>
	      @else
	      	<option value="offerta" @if ($offerta->tipologia == 'offerta') selected="selected"@endif>offerta</option>
	        <option value="lastminute" @if ($offerta->tipologia == 'lastminute') selected="selected"@endif>lastminute</option>
      	@endif
      </select>

    </div>

</div>