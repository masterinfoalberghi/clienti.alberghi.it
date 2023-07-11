@extends('templates.admin')

@section('title')
  @if ($data["record"]->exists)
    Modifica utente ID={{ $data["record"]->id }}   
  @else
    Nuovo utente
  @endif
@endsection

@section('content')

@if ($data["record"]->exists)
  {!! Form::open(['id' => 'record_delete', 'url' => 'admin/utenti/delete', 'method' => 'POST']) !!}
    <input type="hidden" name="id" value="<?=$data["record"]->id ?>">
  {!! Form::close() !!}
@endif

{!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/utenti/store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

  {!! csrf_field() !!}

  <input type="hidden" name="id" value="<?=($data["record"]->exists ? $data["record"]->id : 0)?>">

  <div class="form-group">
    {!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      {!! Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
    </div>
  </div>

  <div class="form-group">
    {!! Form::label('username', 'Username', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      {!! Form::text('username', null, ['placeholder' => 'Username', 'class' => 'form-control']) !!}
      <p class="help-block">Deve essere univoco, normalmente ha lo stesso valore del campo email, ma per alcuni gruppo di hotel può avere un altro valore</p>
    </div>    
  </div>  

  <div class="form-group">
    {!! Form::label('role', 'Ruolo', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      {!! Form::select('role', ["" => ""] + Utility::getUiAvailableRoles(), null, ['class' => 'form-control']) !!}
    </div>
  </div>

  <div class="form-group">
    {!! Form::label('hotel_associato', 'Hotel associato', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
      <input placeholder="Hotel associato a questo account" class="form-control typeahead" value="<?=e(old("hotel_associato") ? old("hotel_associato") : $data["record"]->getHotelName())?>" name="hotel_associato" type="text" id="hotel_associato" autocomplete="off" data-local='<?=implode(",", Utility::getUsersHotels(true))?>'>
      <p class="help-block">Per gli utenti con ruolo <strong>hotel</strong></p>
    </div>
  </div>
  
  @if ($data["record"]->exists)

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" id="password_cambia" name="password_cambia" value="1"> Imposta una nuova password
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      {!! Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control', 'disabled' => true]) !!}
      </div>
    </div>  

    <div class="form-group">
      {!! Form::label('password_confirmation', 'Conferma password', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
        {!! Form::password('password_confirmation', ['placeholder' => 'Conferma password', 'class' => 'form-control', 'disabled' => true]) !!}
        <p class="help-block">La password deve essere almeno lunga 8 caratteri, deve contenere almeno una lettera ed almeno un numero</p>
      </div>
    </div>
  @else
    <div class="form-group">
      {!! Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
      </div>
    </div>  

    <div class="form-group">
      {!! Form::label('password_confirmation', 'Conferma password', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-10">
        {!! Form::password('password_confirmation', ['placeholder' => 'Conferma password', 'class' => 'form-control']) !!}
        <p class="help-block">La password deve essere almeno lunga 8 caratteri, deve contenere almeno una lettera ed almeno un numero</p>
      </div>
    </div>
  @endif  

  <div class="form-group">
    <div class="col-sm-12">
      @include('templates.admin_inc_record_buttons')
    </div>
  </div>

{!! Form::close() !!}

@endsection


@section('onbodyclose')
<script>

function onRoleChange(value){
  if(value === 'hotel')
    jQuery('#hotel_associato').attr('disabled', false);
  else{
    jQuery('#hotel_associato')
      .val('')
      .attr('disabled', true);
  }  
}

onRoleChange(jQuery('#role').val());
jQuery('#role').change(function(){
  onRoleChange(jQuery(this).val());
});

jQuery('#password_cambia').change(function(){
  jQuery('#password, #password_confirmation').prop('disabled', !this.checked)
});

</script>
@endsection