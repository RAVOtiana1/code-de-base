<?php
session_start();
include("./connection.php");
if (isset($_POST['username'])) {
  $sql = "SELECT * FROM users where username = '" . $_POST['username'] . "' and password = password('" . $_POST['pass'] . "')";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
    $_SESSION["user"] = $_POST['username'];
    header("Location: ./inter2.php");
    exit;
  } else {
    $msg = "Login ou mot de passe incorrect";
  }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.122.0">
  <title>Signin</title>




  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">



  <style>
  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(135deg,rgb(41, 17, 1), #D2B48C); /* fond marron doré */
    font-family: Arial, sans-serif;
  }

  .login-container {
    display: flex;
    height: 100%;
  }

  .login-left {
    flex: 1;
    background: url('images/lo MMRS.png') center center no-repeat;
    background-size: contain;
  }

  .login-right {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }

  .login-form {
    background-color: rgba(255, 255, 255, 0.95); /* blanc semi-transparent */
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    max-width: 280px;
    width: 100%;
    margin-top: 100px;
  }
</style>

<div class="login-container">
  <div class="login-left"></div>
  <div class="login-right">
    <form method="POST" action="login.php" class="login-form">
      <h1 class="h4 mb-3 fw-bold text-center">Veuillez vous connecter</h1>
      <p class="text-danger text-center"><?php echo $msg ?? ''; ?></p>

      <div class="form-floating mb-3">
        <input required type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" value="<?php echo $_POST['username'] ?? ''; ?>">
        <label for="floatingInput">Nom d'utilisateur</label>
      </div>

      <div class="form-floating mb-3">
        <input required type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pass">
        <label for="floatingPassword">Mot de passe</label>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault" name="forget">
        <label class="form-check-label" for="flexCheckDefault">
          Se souvenir de moi
        </label>
      </div>

      <button class="btn btn-primary w-100 mb-2" type="submit" name="ok">Connexion</button>
      <button type="button" class="btn btn-outline-secondary w-100">S'inscrire</button>
    </form>
  </div>
</div>
