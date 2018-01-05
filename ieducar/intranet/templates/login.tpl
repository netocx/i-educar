<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Intranet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <link rel=stylesheet type='text/css' href='styles/login.css?5' />
    <link rel=stylesheet type='text/css' href='styles/font-awesome.css?5' />
    <script type='text/javascript' src='scripts/jquery/jquery-1.8.3.min.js?5'></script>
    <script type='text/javascript' src='scripts/jquery/jquery-1.8.3.min.js?5'></script>

  <script type="text/javascript">
    window.ambiente = '<!-- #&CORE_EXT_CONFIGURATION_ENV&# -->';

    var $j = jQuery.noConflict();

    function currentSO() {
      var so = undefined;

      if (navigator && navigator.platform) {
        var platform = navigator.platform.toLowerCase();

        if (platform.indexOf('win') > -1) {
          so = 'windows';
        } else if (platform.indexOf('linux') > -1) {
          so = 'linux';
        } else if (platform.indexOf('mac') > -1) {
          so = 'macOS';
        } else if (platform.indexOf('x11') > -1) {
          so = 'unix';
        } else {
          so = platform;
        }
      }

      return so;
    }

    function loginpage_onload() {
      var domainName = window.location.hostname;

      if (domainName.indexOf('treinamento') < 0 && domainName.indexOf('demonstracao') < 0) {
        $j('.only-for-clients').show();
      }

      $j('.fade-in').fadeIn('slow');

      $j('#login').focus();

      // used for support links
      if (currentSO() == 'windows') {
        $j('.visible-for-windows-so').show();
      } else {
        $j('.visible-for-non-windows-so').show();
      }
    }
  </script>

  <script async src="https://www.googletagmanager.com/gtag/js?id=***REMOVED***"></script>
  <script>
    if (window.ambiente == 'production') {
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('set', {'content_group1': window.location.hostname});

      gtag('config', '***REMOVED***');
    }
  </script>

  </head>
  <body onload="loginpage_onload();" class="hidden fade-in">
     <div id="flash-container">

      <!-- #&PENDENCIA_ADMINISTRATIVA&# -->

    </div>

    <div id="corpo">
      <div class="box" id="mensagens" style="">
        <!-- #&MENSAGENS&# -->
      </div>

      <div>
        <!-- #&BRASAO&# -->
      </div>

      <h2><!-- #&NOME_ENTIDADE&# --></h2>

      <!-- #&ERROLOGIN&# -->

      <div id="login-form" class="box shadow">
        <h1>Acesse sua conta</h1>
        <!-- #&CRIARCONTA&# -->
        <form action="" method="post">
          <label class="" for="login">Matrícula:</label>
          <input type="text" name="login" id="login">

          <label class="" for="senha">Senha:</label>
          <input type="password" name="senha" id="senha">

          <!-- #&RECAPTCHA&# -->

          <input type="submit" class="submit" src="imagens/nvp_bot_entra_webmail.jpg" value="Entrar">
          <p class="forget-password"><a class="light small" href="/module/Usuario/RedefinirSenha">Esqueceu sua senha?</a></p>
            </p>
        </form>

      </div> <!-- end login-form -->

    </div> <!-- end corpo -->

    <div id="rodape" class="texto-normal">
      <p>
        <!-- #&RODAPE_LOGIN&# -->
      </p>

      <div class="rodape-social">
        <!-- #&RODAPE_EXTERNO&# -->
        <div class="social-icons">
          <!-- #&LINKS_SOCIAL&# -->
        </div>
      </div>
    </div> <!-- end rodape -->

  </body>
</html>
