<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if (isset($_POST['username']) && filter_var($_POST['username'],FILTER_VALIDATE_EMAIL) 
	&& isset($_POST['password']) && !empty($_POST['password'])) {
	$datamember = db_row("SELECT * FROM `sa_member` WHERE `mem_email`='".cek($_POST['username'])."'");
	if (isset($datamember['mem_email'])) {
		if (validate_password($_POST['password'],$datamember['mem_password'])) {
      $id = $datamember['mem_id'];
      $hash = sha1(rand(0,500).microtime().SECRET);
      $signature = sha1(SECRET . $hash . $id);
      $cookie = base64_encode($signature . "-" . $hash . "-" . $id);
      setcookie('authentication', $cookie,time()+36000,'/');
      db_query("UPDATE `sa_member` SET `mem_lastlogin`='".date('Y-m-d H:i:s')."' WHERE `mem_id`=".$id);
      if (isset($_GET['redirect'])) {
      	if (substr($_GET['redirect'],0,1) == '/') {
      		$gored = substr($_GET['redirect'],1);
      	} else {
      		$gored = $_GET['redirect'];
      	}
        header('Location:'.$weburl.$gored);
      } else {
      	header('Location:'.$weburl.'dashboard');
      }
      echo 'Login berhasil';
    } else {
        $error = 'Email atau Password anda salah.';
    }
	} else {
		$error = 'Email anda salah.';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl.$favicon;?>" />
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>

    <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
    <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
    <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
      *, *::before, *::after { box-sizing: border-box; }

      body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 1rem;
      }

      .login-container {
        width: 100%;
        max-width: 420px;
      }

      .login-card {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem 2rem 2rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
        border: none;
      }

      .login-logo {
        display: block;
        margin: 0 auto 0.5rem;
        max-height: 64px;
        width: auto;
        object-fit: contain;
      }

      .login-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
        text-align: center;
      }

      .login-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.75rem;
      }

      .form-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
      }

      .form-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f9fafb;
      }

      .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        background: #fff;
        outline: none;
      }

      .form-control::placeholder {
        color: #9ca3af;
      }

      .input-group {
        position: relative;
      }

      .input-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.9rem;
        z-index: 4;
        pointer-events: none;
      }

      .input-group .form-control {
        padding-left: 2.5rem;
      }

      .toggle-password {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
        z-index: 4;
        background: none;
        border: none;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        transition: color 0.2s;
      }

      .toggle-password:hover {
        color: #6366f1;
      }

      .btn-login {
        width: 100%;
        padding: 0.7rem;
        font-size: 0.9375rem;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
        letter-spacing: 0.01em;
      }

      .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(99,102,241,0.35);
        opacity: 0.95;
      }

      .btn-login:active {
        transform: translateY(0);
      }

      .btn-login:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
      }

      .login-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f3f4f6;
      }

      .login-footer a {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #6366f1;
        text-decoration: none;
        transition: color 0.2s;
      }

      .login-footer a:hover {
        color: #4f46e5;
        text-decoration: underline;
      }

      .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.8125rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .alert-error i {
        color: #ef4444;
        font-size: 1rem;
        flex-shrink: 0;
      }

      .spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 0.5rem;
        vertical-align: middle;
      }

      @keyframes spin {
        to { transform: rotate(360deg); }
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
      }

      .login-card {
        animation: fadeIn 0.4s ease-out;
      }

      @media (max-width: 480px) {
        .login-card {
          padding: 2rem 1.5rem 1.5rem;
          border-radius: 12px;
        }
      }
    </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <img src="<?php echo $weburl.$logoweb;?>" alt="Logo" class="login-logo" />
      <h1 class="login-title">Selamat Datang</h1>
      <p class="login-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

      <?php if (isset($error) && !empty($error)) { ?>
        <div class="alert-error">
          <i class="fas fa-circle-exclamation"></i>
          <span><?= $error; ?></span>
        </div>
      <?php } ?>

      <form action="" method="post" id="loginForm">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <div class="input-group">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" class="form-control" name="username" placeholder="nama@email.com" required autocomplete="email" />
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan password" required autocomplete="current-password" />
            <button type="button" class="toggle-password" id="togglePassword" onclick="togglePassword()" aria-label="Toggle password">
              <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login" id="btnLogin">
          LOGIN
        </button>
      </form>

      <div class="login-footer">
        <a href="register"><i class="fas fa-user-plus"></i> Register</a>
        <a href="reset"><i class="fas fa-key"></i> Lupa Password?</a>
      </div>
    </div>
  </div>

  <script src="<?= $weburl;?>bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      var input = document.getElementById('password');
      var icon = document.getElementById('eyeIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
      }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
      var btn = document.getElementById('btnLogin');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Mohon tunggu...';
    });
  </script>
</body>
</html>