<!DOCTYPE html>
<html lang="it">

<head>

    <title>Login / InfoAlberghi Panel</title>

    @include('templates.admin_inc_head')

</head>

<body class="page-body login-page">


<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
var baseurl = '';
</script>

<div class="login-container">

  <div class="login-header login-caret">

    <div class="login-content">

      <a href="{{ url('/') }}" style="font-size:20px;"> 
          <span style="color: #FFF">INFO</span><span style="color: #3FA7E6">ALBERGHI.com</span>
      </a>

      <p class="description">Effettua il login per accedere all'area riservata.</p>

      <!-- progress bar indicator -->
      <div class="login-progressbar-indicator">
        <h3>43%</h3>
        <span>logging in...</span>
      </div>
    </div>

  </div>

  <div class="login-progressbar">
    <div></div>
  </div>

  <div class="login-form">

    <div class="login-content">



      @if (count($errors) > 0)
      <div class="form-login-error" style="display: block; height: auto;">
        <h3>Login non valido</h3>
        <p>
        @foreach ($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </p>
      </div>
      @endif

      <form method="post" role="form" id="form_login" action="{{ url('admin/auth/login') }}">

        {!! csrf_field() !!}

        <div class="form-group">

          <div class="input-group">
            <div class="input-group-addon">
              <i class="entypo-user"></i>
            </div>

            <input type="text" class="form-control" name="username" id="username" placeholder="Username" autofocus value="{{ old("username") }}" />
          </div>

        </div>

        <div class="form-group">

          <div class="input-group">
            <div class="input-group-addon">
              <i class="entypo-key"></i>
            </div>

            <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
 
            <div class="input-group-addon">
              <i id="toggle-eye" class="glyphicon glyphicon-eye-open" style="
              font-size: 18px;
          "></i>
            </div>
          </div>

        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-login">
            <i class="entypo-login"></i>
            Login In
          </button>
        </div>
      </form>


      <div class="login-bottom-links">

        <a href="{{ url("admin/password/email") }}" class="link">Password dimenticata?</a> <br />

        Powered by <a target="_blank" href="/">Info Alberghi</a>@if ( Utility::isValidIP() ) with <img src="//static.info-alberghi.com/images/admin/laravel.svg" alt="Laravel Framework" title="Laravel Framework" width="35" height="35"><span style="color: #e74430; font-family: "Miriam Libre",sans-serif;">Laravel </span>({{app()::VERSION}}) @endif

      </div>

    </div>

  </div>

</div>

    <!-- jQuery -->
    {!! HTML::script('neon/js/gsap/main-gsap.js'); !!}

    <!-- Bootstrap Core JavaScript -->
    {!! HTML::script('neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js'); !!}

    {!! HTML::script('neon/js/bootstrap.js'); !!}
    {!! HTML::script('neon/js/joinable.js'); !!}
    {!! HTML::script('neon/js/resizeable.js'); !!}
    {!! HTML::script('neon/js/neon-api.js'); !!}
    {!! HTML::script('neon/js/jquery.validate.min.js'); !!}

    {!! HTML::script('neon/js/neon-custom.js'); !!}
    {!! HTML::script('neon/js/neon-demo.js'); !!}


    <script>
      jQuery("#toggle-eye").click(function(){
          jQuery(this).toggleClass("glyphicon-eye-open glyphicon-eye-close");
      
          var input = jQuery("#password");
          if (input.attr("type") === "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
      });

    </script>


</body>
</html>
