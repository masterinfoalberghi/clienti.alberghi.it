 @php $locale = "it"; @endphp


<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Pagina in manutenzione</title>
    <meta name="description" content="503 Pagina in manutenzione">

  <style>
      
      @include('desktop.css.above')
      @include('desktop.css.homepage')
      @include('desktop.css.503')
      
    </style>


    @include('header', ["locale"=> $locale]) 

  </head>
  
<body class="class-page-home">
    
    @include('menu.header_menu', array("home"=>1, "locale"=> $locale)) 
    
    <main id="main-content">

      <div class="container">
        <div class="row">
          <div class="warning503 margin-top margin-bottom">
            <header>
              <img src="{{Utility::asset('/images/sad.png')}}" style="display: inline-block; " />
              <h1>Pagina in manutenzione</h1>
            </header>
            <p>Questa pagina è in manutenzione.</p>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      
    </main>
    
    @include('menu.sx_newsletter')
    @include('composer.footer') 
  
  <link   href="{{Utility::assets('/vendor/fontello/animation.min.css', true)}}" rel="stylesheet" type="text/css" /> 
  <link   href="{{Utility::assets('/vendor/fontello/fontello.min.css', true)}}" rel="stylesheet" type="text/css" />  
    <script  src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    
    <script  src="{{Utility::asset('/desktop/js/generale.min.js')}}"></script>
    <script  src="{{Utility::asset('/desktop/js/form.min.js')}}"></script>
    <script  src="{{Utility::asset('/desktop/js/newsletter.min.js')}}"></script>
    
    <script type="text/javascript">
            
        var $csrf_token = '<?php echo csrf_token(); ?>'; 
        var console = console?console:{log:function(){}}; 
        
        @include("lang.newsletter")
                   
    </script>
   
    @include('footer')
    
</body>
</html>
