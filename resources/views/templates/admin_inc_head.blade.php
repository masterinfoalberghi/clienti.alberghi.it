
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="InfoAlberghi">    

    <!-- Bootstrap Core CSS -->
	
    <link media="all" type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic'"/>

	@if ($__env->yieldContent("jquery-ui-css"))
 		@yield("jquery-ui-css")
 	@else
     <link type="text/css" rel="stylesheet"  href="{{Utility::assets('/vendor/neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css' , false, true)}}"/>
	@endif

    <link media="all" type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/font-icons/entypo/css/entypo.css', false, true)}}"/>
    
    <link media="all" type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/bootstrap.css', false, true)}}"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/neon-core.css', false, true)}}"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/neon-theme.css', false, true)}}"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/custom.css', false, true)}}"/>
    

    {{--  ATTENZIONE: questo css non va incluso nelle pagine POI perché va in conflitto con il multiselect jquery utilizzato !! --}}
    @if (Request::segment(2) != 'poi')
        <link type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/neon-forms.css')}}"/>
    @else

        {{-- ATTENZIONE SE SONO in POI devo cmq avere il css per la textbox per la ricerca hotel nella sidebar di SX !!!  --}}
        <style type="text/css" media="screen">
            .twitter-typeahead {
                width: 100%;
            }
            .twitter-typeahead .tt-hint {
                padding: 6px 12px;
                color: #dee0e4;
                line-height: 1.42857143;
                margin: 0;
                padding-top: 5.5px;
                width: 100%;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .twitter-typeahead {
                width: 100%;
            }
            .tt-dropdown-menu {
                min-width: 160px;
                margin-top: 2px;
                padding: 5px 0;
                background-color: #fff;
                border: 1px solid #ebebeb;
                -webkit-background-clip: padding-box;
                -moz-background-clip: padding;
                background-clip: padding-box;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 3px;
                width: 100%;
            }
            .tt-suggestion.tt-is-under-cursor {
                color: #303641;
                background: #f3f3f3;
            }
            .tt-suggestion {
                display: block;
                padding: 4px 12px;
            }
            .bootstrap-tagsinput .tag [data-role="remove"] {
                margin-left: 8px;
                cursor: pointer;
            }
            bootstrap-tagsinput .tag {
                display: inline-block;
                margin-right: 2px;
                color: white;
                font-size: 10px;
            }
        </style>
    @endif
    
	
	<style type="text/css" media="screen">
	
		#chart_div { 
			border: 1px solid #ebebeb;
			margin-bottom: 17px;
			border-radius: 3px;
		}
		
	</style>
	
    <link type="text/css" rel="stylesheet" href="{{Utility::assets('/vendor/neon/css/custom.css')}}" />
	<script src="{{Utility::assets('/vendor/neon/js/jquery-1.11.0.min.js')}}"></script>
    <script>$.noConflict();</script>    

    <!--[if lt IE 9]>
        <script src="{{Utility::assets('/vendor/neon/js/ie8-responsive-file-warning.js')}}"></script>
    <![endif]-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="{{Utility::assets('/vendor/neon/comp/html5shiv-3.7.0.js')}}"></script>
       <script src="{{Utility::assets('/vendor/neon/comp/respond-1.4.2.min.js')}}"></script>
    <![endif]-->