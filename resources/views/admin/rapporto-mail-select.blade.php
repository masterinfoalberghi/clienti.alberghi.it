@extends('templates.admin')

@section('title')
Mail hotel
@endsection

@section('content')


  <form method="post" action="{{ url("admin/stats/rapporto-mail") }}" style="padding:20px 0 0 0; width: 90%">
         
          {!! csrf_field() !!}

    <div class="row" style="margin-bottom: 20px;">
      <div class="col-md-4 col-sx-12">
          
          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Seleziona un hotel</div>
                <div class="panel-options">
                </div>
              </div>
              <div class="panel-body" style="padding: 5px 15px 7px 15px;">
              <div class="input-group" class="pull-left" style="width: 100%; background: #fff; cursor: pointer; padding: 10px;">
                  <input value="" class="form-control typeahead ricerca-mail" id="ricerca-mail" name="mail_hotel" type="text" autocomplete="on" data-local="{{ implode(',', Utility::getHotelsRapportoMail()) }}">       
              </div>
              </div>
            </div>

        </div>
          
        <div class="col-md-4 col-sx-12">
      

          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Seleziona un periodo</div>
                <div class="panel-options">
                </div>
              </div>
              <div class="panel-body" style="padding: 22px 15px;">
              <label for="dal">Dal</label>
              <input type="text" id="dal" name="dal">
              &nbsp;&nbsp;&nbsp;
              <label for="al">al</label>
              <input type="text" id="al" name="al">
            </div>
          </div>

        </div>

        <div class="col-md-4 col-sx-12">
      

          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Seleziona la tipologia</div>
                <div class="panel-options">
                </div>
              </div>
              <div class="panel-body">
              <div id="" class="pull-left" style="width: 100%; background: #fff; cursor: pointer; padding: 10px 10px 0 10px;">
                {!! Form::select('tipologia_mail',['s' => 'Scheda', 'm' => 'Multipla'], null, ["id" => "tipologia_mail", "class"=>"form-control"]) !!}     
              </div>
              </div>
          </div>

        </div>

      </div>
      
      <div class="row" style="margin-top: 20px;">
         <div class="form-group">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary" style="position: relative; top: 0px;">Calcola</button>
            </div>
         </div>
      </div>

      </form>  

@endsection


@section('onheadclose')
    
    <link href="{{ Utility::assets('/vendor/oldbrowser/css/jquery-ui.min.css') }}" rel="stylesheet">
    <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ Utility::assets('/vendor/oldbrowser/js/jquery.ui.datepicker-it.js') }}" type="text/javascript"></script>
  
@endsection

@section('onbodyclose')
    <script type="text/javascript">
        jQuery(function() {

              
              // DATEPICKER
              
              // Set all date pickers to have Italian text date.
              jQuery.datepicker.setDefaults(jQuery.datepicker.regional["it"]);
              

              jQuery('#dal').datepicker({
                  defaultDate: "+0d",
                  showOn: "both",
                  buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif', true)}}",
                  numberOfMonths: 3,
                  autoSize: true,
                  showAnim: "clip",
                  dateFormat: "yy-mm-dd",
                  onSelect: function(selectedDate) {
                      //jQuery("#al" ).datepicker("option", "minDate", selectedDate);
                      //jQuery("#al").datepicker("option", "defaultDate", selectedDate);
                     
                     var date = jQuery(this).datepicker('getDate');
                     if(date)
                       {
                         date.setDate(date.getDate() + 1);
                         jQuery("#al").datepicker('setDate', date);
                       }
                      
                  }
              }); 


              jQuery('#al').datepicker({
                  showOn: "both",
                  buttonImage: "{{Utility::assetsImage('/icons/icoCalendar.gif', true)}}",
                  numberOfMonths: 3,
                  autoSize: true,
                  showAnim: "clip",
                  dateFormat: "yy-mm-dd",
                  onSelect: function( selectedDate ) {
                         //jQuery( "#dal" ).datepicker("option", "maxDate", selectedDate);
                         
                         var date = jQuery(this).datepicker('getDate');
                         if(date)
                           {
                             date.setDate(date.getDate() - 1);
                             jQuery("#dal").datepicker("option", "maxDate", date);
                           }
                  }
              });

              
              // FINE DATEPICKER

           
        });
    </script>
@endsection