
<div class="modal" id="pop_messages" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
<?php

/*
 * Messaggi all'utente
 */

foreach($session_response_messages as $session_response_message)
  {
  ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-<?=SessionResponseMessages::typeToClass($session_response_message["type"]) ?>">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <i class="fa fa-info-circle"></i> <?=e($session_response_message["text"] )?>
      </div>
    </div>
  </div>
  <?php                      
  }                  
?>                
<!-- /.row -->

</div>
<div class="modal-footer" style="text-align: center !important; margin-top:0; border-top:0; padding-top:0;">
  <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
</div>
</div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php

/*
 * Questi sono i messaggi automatici tornati dal Validator
 * https://laravel.com/docs/5.1/validation
 * la loro localizzazione è fatta in 
 * resources/lang/it/validation.php
 * dopo aver modificato il file ricordarsi di
 * php artisan cache:clear
 */

if(count($errors) > 0)
  {
  foreach($errors->all() as $error)
    {
    ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="fa fa-info-circle"></i> <?=e($error)?>
        </div>
      </div>
    </div>    
    <?php
    }
  }
?>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif