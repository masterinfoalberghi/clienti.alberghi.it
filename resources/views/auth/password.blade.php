{{--

Pagina per attivare la procedura di password recovery

@link https://laravel.com/docs/5.1/authentication
@author Luca Battarra

--}}

<!DOCTYPE html>
<html lang="it">
  <head>

      <title>Recupero password / InfoAlberghi Panel</title>

      @include('templates.admin_inc_head')

  </head>
  <body class="page-body login-page">
    <script type="text/javascript">
      var baseurl = '';
    </script>
    <div class="login-container">
      <div class="login-header login-caret">

        <div class="login-content">

          <a href="{{ url('/') }}" style="font-size:20px;">
              <span style="color: #FFF">INFO</span><span style="color: #3FA7E6">ALBERGHI</span>
          </a>

          <p class="description">Inserisci lo username del tuo account per avviare la procedura di recupero password.</p>

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
            <h3>Si è verificato un errore</h3>
            <p>
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
            </p>
          </div>
          @endif

          @if (Session::has('status'))
          <div class="form-forgotpassword-success" style="display: block; height: auto;">
            <p>
            {{ Session::get('status') }}
            </p>
          </div>
          @endif

          <form class="form-horizontal" role="form" method="POST" action="{{ url('admin/password/email') }}" id="form_forgot_password">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-forgotpassword-success">
              <i class="entypo-check"></i>
              <h3>Reset email has been sent.</h3>
              <p>Please check your email, reset password link will expire in 24 hours.</p>
            </div>
            <div class="form-steps">
              <div class="step current" id="step-1">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="entypo-mail"></i> </div>
                    <input autofocus="on" type="text" class="form-control" name="username" id="email" placeholder="Username" autocomplete="off" value="{{ old('username') }}" />
                  </div>
                </div>
                <div class="form-group"> <button type="submit" class="btn btn-info btn-block btn-login">
                  Recupera la password
                  <i class="entypo-right-open-mini"></i> </button>
                </div>
              </div>
            </div>
          </form>

          <div class="login-bottom-links">

            <a href="{{ url("admin/auth/login/") }}" class="link"> <i class="entypo-lock"></i>
            Ritorna alla pagina di Login
            </a> <br />

             Powered by <a target="_blank" href="/">Info Alberghi</a>

          </div>

        </div>
      </div>
    </div>
  </body>
</html>