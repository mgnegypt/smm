<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$settings["site_name"]?> - Admin Login</title>
    <link href="/css/admin/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  </head>
  <body class="admin-login-galaxy">
    <div class="login-galaxy-bg" aria-hidden="true">
      <span class="galaxy-orb orb-1"></span>
      <span class="galaxy-orb orb-2"></span>
      <span class="galaxy-orb orb-3"></span>
      <span class="galaxy-grid"></span>
    </div>

    <main class="admin-login-shell container">
      <section class="admin-login-card">
        <div class="admin-login-brand">
          <div class="brand-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <h1>Admin Control</h1>
            <p>Secure galaxy access for <?=$settings["site_name"]?></p>
          </div>
        </div>

        <?php if( $success ): ?>
          <div class="alert alert-success"><?php echo $successText; ?></div>
        <?php endif; ?>

        <?php if( $error ): ?>
          <div class="alert alert-danger"><?php echo $errorText; ?></div>
        <?php endif; ?>

        <form id="yw0" action="" method="post" class="admin-login-form">
          <div class="form-group">
            <label for="AdminUsers_login"><i class="fa-regular fa-user"></i> Username</label>
            <input class="form-control" name="username" id="AdminUsers_login" type="text" maxlength="300" required />
          </div>

          <div class="form-group">
            <label for="AdminUsers_passwd"><i class="fa-solid fa-lock"></i> Password</label>
            <input class="form-control" name="password" id="AdminUsers_passwd" type="password" maxlength="300" required />
          </div>

          <?php if(  $_SESSION["recaptcha"]  ): ?>
            <div class="form-group recaptcha-wrap">
              <div class="g-recaptcha" data-sitekey="<?php echo $settings["recaptcha_key"] ?>"></div>
            </div>
          <?php endif; ?>

          <input type="hidden" name="remember" value="1">
          <button type="submit" name="login" class="btn btn-galaxy-login">
            <i class="fa-solid fa-right-to-bracket"></i>
            Sign in to dashboard
          </button>
        </form>
      </section>
    </main>

    <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
  </body>
</html>
