<?php
session_start();
require_once 'class.user.php';

$user_login = new USER();

$_SESSION["test"] = "salut";
if($user_login->is_logged_in()!="")
{
    $_SESSION["test"] = "ok";
    $user_login->redirect('home.php');
}
if(isset($_POST['btn-login']))
{
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtupass']);
    if($user_login->login($email,$upass))
    {
        $_SESSION["test"] = "ok";
        $user_login->redirect('home.php');
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
      <title>Connection | Pirikura</title>
          <!-- <link rel="stylesheet" href="css/style.css" /> -->
        <!-- <link rel="stylesheet" media="screen" href="css/screen.css" /> -->
  </head>
  <body>
  <?php include('header.php');?>
        <div id="login">

        <div class="leftLogin"></div>
            <div class="f-container">
        <?php
            if(isset($_GET['inactive']))
            {
        ?>
                <div class="error-error">
                    <strong>Désolé !</strong> votre compte n'est pas activer, pour
                    l'activer rendez-vous sur votre adresse e-mail.
                </div>
        <?php
            }
        ?>
          <form class="form-signin" method="post">
            <?php
                if(isset($_GET['error']))
                {
            ?>
                    <div class='error-error'>
                        <strong>Identifiant ou mot de passe incorrect</strong>
                    </div>
            <?php
                }
            ?>
                <h2 class="form-signin-entete">Connection</h2><hr /><br/>
                <input type="email" placeholder="Email address" name="txtemail" required /><br/><br/>
                <input type="password" placeholder="Password" name="txtupass" required /><br/><br/>
                <hr /><br/>
                <button type="submit" class="btn" name="btn-login"> Envoyer </button>
                <a href="signup.php" class="btn" style="float:right;">S'inscrire</a><br/><br/>
                <hr /><br/>
                <a class="btn" href="fpass.php">Mots de pass oublier ? </a>
            </form>
            </div>
        <div class="rightLogin"></div>

   </div>
<?php include('footer.php');?>
  </body>
</html>
