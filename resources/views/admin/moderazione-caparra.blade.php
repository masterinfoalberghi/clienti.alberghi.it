@extends('templates.admin')

@section('title')
	Moderazione politiche di modifica/cancellazione prenotazione
@endsection

@section("content")

<div class="row">
    <div class="col-lg-12">
        <h2>Proiezioni</h2>
    </div>
    <div class="col-sm-3">
        <div class="tile-stats tile-red" style="min-height:auto  !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_attivi}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_attivi}}</div> 
            <h3>Hotel con politiche inserite</h3>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="tile-stats tile-red" style="min-height:auto !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_attesa}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_attesa}}</div> 
            <h3>Hotel in attesa di moderazione</h3>
        </div>
    </div>

    <div class="col-sm-3">
        
    </div>

    <div class="col-sm-3">
        
    </div>
</div>
<br /><br />
    @if (count($hotels) == 0)
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <p>Nessuna <b>politca/periodo</b> da moderare</p>
        </div>
    </div>
    @else 
        
    
        <div class="row">
            <div class="col-lg-12">
        
              <h4>Elenco politiche da approvare</h4>

              @foreach($hotels as $hotel)
                <div class="offerta_da_approvare offerta_bb">

                <h3 style="padding-bottom:0;">{{$hotel["nome"]}} (#{{$hotel["id"]}})</h3>
                    <p>{{$hotel["indirizzo"]}}, {{$hotel["localita"]}} ({{$hotel["provincia"]}})</p>

                    <table class="table table-hover table-bordered table-responsive datatable dataTable">
                        <thead>
                            <tr>
                                
                                <th>Da</th>
                                <th>A</th>
                                <th>Politica di cancellazione scelta</th>
                                <th>Op</th>
                                <th>Giorni</th>
                                <th>&euro; / %</th>
                                <th>Mesi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($hotel["data"] as $caparre)
                        <tr id="item_{{$caparre->id}}">
                          @php

                                if ($caparre->option == 1)
                                    $option_text = __("labels.option_1");
                                else if ($caparre->option == 2)
                                    $option_text = __("labels.option_2");
                                else if ($caparre->option == 3)
                                    $option_text = __("labels.option_3");
                                else if ($caparre->option == 4)
                                    $option_text = str_replace(["$1","$2"], [$caparre->day_before, $caparre->perc] , __("labels.option_4"));
                                else if ($caparre->option == 5)
                                    $option_text = __("labels.option_5");
                                else if ($caparre->option == 6)
                                    $option_text =  str_replace(["$1","$2"], [$caparre->day_before, $caparre->month_after] , __("labels.option_6"));
                                else if ($caparre->option == 7)
                                    $option_text = str_replace(["$1"], [$caparre->day_before] , __("labels.option_7"));
                                else if ($caparre->option == 8)
                                    $option_text = str_replace(["$1","$2"], [$caparre->day_before, $caparre->perc] , __("labels.option_8"));
                                else if ($caparre->option == 9)
                                    $option_text = str_replace(["$1","$2"], [$caparre->day_before, $caparre->month_after] , __("labels.option_9"));
                                else if ($caparre->option == 10)
                                    $option_text = str_replace(["$1"], [$caparre->perc] , __("labels.option_10"));
                                
                            @endphp  
                           
                            <td style="width:80px">{{\Carbon\Carbon::parse($caparre->from)->format("d/m/Y")}}</td>
                            <td style="width:80px">{{\Carbon\Carbon::parse($caparre->to)->format("d/m/Y")}}</td>
                            <td style="width:350px">{{$option_text}}</td>
                            <td style="width:10px">{{$caparre->option}}</td>
                            <td style="width:50px">{{$caparre->day_before}}</td>
                            <td style="width:50px">{{$caparre->perc}}</td>
                            <td style="width:50px">{{$caparre->month_after}}</td>
                            <td style="width:150px" class="buttons">
                                <button onClick="enabled(this, {{$caparre->id}})" class="btn btn-warning"><i class="entypo-block"></i></button>
                                {{-- <a  class="btn btn-info"><i class="entypo-pencil"></i></button> --}}
                                <button onClick="_delete({{$caparre->id}})" class="btn btn-danger"><i class="entypo-cancel"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @endforeach
        
        
            </div>
          </div>

       

    @endif
@endsection

@section('onheadclose')

    <script type="text/javascript">
        function enabled(obj, id)
        {

            var _token = jQuery('input[name=_token]').val();
            var obj = jQuery(obj).find("i");

            if (obj.hasClass("entypo-block")) {
                var value = "enabled";
            } else{
                var value = "disabled";
            }

            jQuery.ajax({

                url: '<?php echo url("admin/politiche-cancellazione/moderazione") ?>',
                type: "post",
                async: false,
                data : {
                    'id': id,
                    '_token': _token,
                    'action': 'status',
                    'value': value
                },

                success: function(data) {
                    
                    if (value == "enabled") {
                        obj.removeClass("entypo-block").addClass("entypo-check");
                        obj.parent().removeClass("btn-warning").addClass("btn-success");
                    } else {
                        obj.removeClass("entypo-check").addClass("entypo-block");
                        obj.parent().removeClass("btn-success").addClass("btn-warning");
                    }
                }

            });

        }

        function _delete(id)
        {
            
            var _token = jQuery('input[name=_token]').val();
            var obj = jQuery(obj).find("i");

            jQuery.ajax({

                url: '<?php echo url("admin/politiche-cancellazione/moderazione") ?>',
                type: "post",
                async: false,
                data : {
                    'id': id,
                    '_token': _token,
                    'action': 'delete',
                    'value': ""
                },

                success: function(data) {

                    console.log("#item_" + id);
                    jQuery("#item_" + id).addClass("danger");
                    jQuery("#item_" + id + " .buttons").empty();

                }

            });
        }

    </script>

@endsection