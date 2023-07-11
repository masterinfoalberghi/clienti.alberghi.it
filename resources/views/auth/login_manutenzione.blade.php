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
          <span style="color: #FFF">INFO</span><span style="color: #3FA7E6">ALBERGHI</span>
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
        
      <h1 style="color:#fff;">Amministrazione in manutenzione</h1>
      <p style="font-size:16px;">Ci scusiamo per il disagio, torneremo online il più presto possibile</p>

      <div class="login-bottom-links">

        Powered by <a target="_blank" href="/">Info Alberghi</a>

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


</body>
</html>