<!DOCTYPE html>
<html lang="it">

<head>

    @include('templates.admin_inc_head')

    <title>
        @yield('title','') ~ Info Alberghi
    </title>

    @yield('onheadclose', '')
    <link href="{{Utility::assets('/vendor/fontello/fontello.min.css', true)}}" rel="stylesheet" type="text/css" />

    @php
        $release = \App\User::getAcceptance();
    @endphp
    
    <style>
         input.apple-switch {
            position: relative;
            appearance: none; outline: none;
            width: 40px; height: 20px;
            background-color: #ffffff; 
            border: 1px solid #D9DADC;
            border-radius: 40px; 
            box-shadow: inset -20px 0 0 0 #ffffff;
            transition-duration: 200ms;
            outline: none; 
        }

        input.apple-switch:after {
            content: "";
            position: absolute;
            top: 1px; left: 1px;
            width: 16px; height: 16px;
            border-radius: 50%; 
            box-shadow: 2px 4px 6px rgba(0,0,0,0.2);
        }

        input.apple-switch:checked {
            border-color: #4ED164;
            box-shadow: inset 20px 0 0 0 #4ED164;
        }
        input.apple-switch:checked:after {
            left: 20px;
            box-shadow: -2px 4px 3px rgba(0,0,0,0.05);
        }
        
    </style>

</head>


<body class="page-body" data-url="http://neon.dev">

	<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

		@include('widget.admin.sidebar')

		<div class="main-content">
			
			<div class="row" style="border-bottom:1px solid #999; margin-bottom:50px">
				<div class="col-md-6">
					<h2 style="margin:0">
						@yield('icon')
						@yield('title')
					</h2>
				</div>
				@include('widget.admin.top')		
			</div>
			
			@php

				$session_response_messages = SessionResponseMessages::retrieve();
				count($session_response_messages) ? $popup_trigger = 1 : $popup_trigger = 0;
		
			@endphp
			
			@include('templates.admin_inc_messages')
			
			<div class="row">
                
                @php 
                    if (!isset($appends) && !isset($appends["type_page"]))
                        $middlepage ="pages";
                    else
                        $middlepage = $appends["type_page"];
                @endphp
                
				<div class="col-md-4 massive-button" style="display:none; margin:17px 0 8.5px;">		
					{!! Form::open(['id' => 'massive_edit', 'url' => 'admin/' . $middlepage . '/massive', 'method' => 'POST']) !!}
						<button type="submit" class="btn btn-danger">Modifica tutto</button>
						<input type="hidden" value="" name="modify_all_ids" id="modify_all_ids" />
						<input type="hidden" value="{{ Request::getQueryString() }}" name="querystring_ricerca" />
					{!! Form::close() !!}
				</div>
				
			</div>
			
			@yield('content')

			<div class="row">
				<div class="col-md-8"></div>
				<div class="col-md-4 massive-button" style="display:none; text-align:right; margin:17px 0 8.5px;">		
					<button type="button" onclick='document.getElementById("massive_edit").submit()' class="btn btn-danger">Modifica tutto</button>
				</div>
			</div>
		
		</div>
	</div> {{-- / .page-container --}}

	{!! Form::open(['id' => 'delete-cache', 'url' => 'admin/hotel-cache/' . Auth::user()->getUiEditingHotelId(), 'method' => 'POST']) !!}
	{!! Form::close() !!}

    @if (Auth::user()->role == "hotel" && (Auth::user()->hotel_id == 470 || Auth::user()->hotel_id >= 20000) && is_null($release[1]))
        <div class="modal fade" id="acceptance" data-backdrop="static" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content">
                    <div class="modal-header"> 
                        <h4 class="modal-title">Termini e condizioni</h4> 
                    </div>
                <div class="ody">
                    {!!$release[0]->text!!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onClick="acceptRelease()" data-dismiss="modal">Accetto</button>
                </div>
            </div>
        </div>
    @endif

	@include('widget.admin.popupcache')
    @include('templates.admin_inc_onbodyclose')

		@yield('onbodyclose', '')
		
		@if($popup_trigger)
		<script>

			jQuery(document).ready(function() {
				jQuery("#pop_messages").modal({
						backdrop:'true',
						keyboard:'false'
						});
			});

            jQuery('#acceptance').modal('show', {backdrop: 'static'});

		</script>
		@endif

        @if (Auth::user()->role == "hotel" && (Auth::user()->hotel_id == 470 || Auth::user()->hotel_id >= 20000) && is_null($release[1]))
            <script>
                jQuery(document).ready(function() {
                    jQuery('#acceptance').modal('show', {backdrop: 'static'});
                });

                function acceptRelease() {

                    data_options = {
                        "_token": '{{ csrf_token() }}',
                        "release_user_id": '{{Auth::user()->id}}',
                        "release_text_id": '{{$release[0]->id}}',
                    }

                    jQuery.ajax({
					    url: '/admin/acceptance-release',
					    type: "post",
					    data: data_options
    				});

                }

            </script>
        @endif
	

</body>
</html>