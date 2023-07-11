@extends('templates.admin')

@section('title')
	Moderazione politiche di accettazione del bonus vacanze 2020
@endsection

@section("content")

<div class="row">
    <div class="col-lg-12">
        <h2>Proiezioni</h2>
    </div>
    <div class="col-sm-3">
        <div class="tile-stats tile-green" style="min-height:auto  !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_si}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_si}}</div> 
            <h3>Hotel che accettano bonus<br /><small style="color:#fff;">&nbsp;</small></h3>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="tile-stats tile-orange" style="min-height:auto !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_si_attesa}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_si_attesa}}</div> 
            <h3>Hotel che accettano bonus<br /><small style="color:#fff;">in attesa di moderazione</small></h3>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="tile-stats tile-red" style="min-height:auto  !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_no}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_no}}</div> 
            <h3>Hotel che <b>non accettano</b> bonus<br /><small style="color:#fff;">&nbsp;</small></h3>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="tile-stats tile-purple" style="min-height:auto !important;"> 
            <div class="num" data-start="0" data-end="{{$hotel_no_attesa}}" data-postfix="" data-duration="1500" data-delay="0">{{$hotel_no_attesa}}</div> 
            <h3>Hotel che <b>non accettano</b> bonus<br /><small style="color:#fff;">in attesa di moderazione</small></h3>
        </div>
    </div>

</div>
<br /><br />

    @if (count($hotels) == 0)
    
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <p>Nessuna <b>politca</b> da moderare</p>
        </div>
    </div>
    @else 
        
    
        <div class="row">
            <div class="col-lg-12">
        
              <h4>Elenco politiche da approvare</h4>

              @foreach($hotels as $hotel)
                <div class="offerta_da_approvare offerta_bb">

                <h3 style="padding-bottom:0;">{{$hotel["nome"]}} (#{{$hotel["id"]}})</h3>
                    <p>{{$hotel["indirizzo"]}}, {{$hotel["localita"]->alias}}</p>

                    <table class="table table-hover table-bordered table-responsive datatable dataTable">
                        <thead>
                            <tr>
                                <th>Politica di bonus scelta</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr id="item_{{$hotel->id}}">
                            <td>
                                @if($hotel->bonus_vacanze_2020 == "2")
                                    Accetto il bonus vacanze
                                @elseif($hotel->bonus_vacanze_2020 == "-2")
                                    Non accetto il bonus vacanze
                                @endif
                            </td>
                            <td style="width:150px" class="buttons">
                                <button onClick="enabled(this, {{$hotel->id}})" class="btn btn-warning"><i class="entypo-block"></i></button>
                                <button onClick="_delete({{$hotel->id}})" class="btn btn-danger"><i class="entypo-cancel"></i></button>
                            </td>
                        </tr>
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

                url: '<?php echo url("admin/politiche-bonus/moderazione") ?>',
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

                url: '<?php echo url("admin/politiche-bonus/moderazione") ?>',
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