<?php
session_start();
require_once 'class.user.php';
$user = new USER();

if($user->is_logged_in()!="")
{
    $user->redirect('home.php');
}

if(isset($_POST['btn-submit']))
{
    $email = $_POST['txtemail'];

    $stmt = $user->runQuery("SELECT userID FROM tbl_users WHERE userEmail=:email LIMIT 1");
    $stmt->execute(array(":email"=>$email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() == 1)
    {
        $id = base64_encode($row['userID']);
        $code = md5(uniqid(rand()));

        $stmt = $user->runQuery("UPDATE tbl_users SET tokenCode=:token WHERE userEmail=:email");
        $stmt->execute(array(":token"=>$code,"email"=>$email));

        $message_txt= "
            bonjour $email
            <br/> <br/>
            vous avez fait une demande de reset de mot de passe,
            si ce n'est pas vous qui avez fait cette demande ignorer cette email
            <br/> <br/>
            cliquer sur lien qui suis pour reinitialiser votre mot de passe
            <br/> <br/>
            <a href='http://localhost:8080/camagru/resetpass.php?id=$id&code=$code'>click ici pour reset ton password</a>
            merci :)
            ";
        $message_html= "
            bonjour $email
            <br/> <br/>
            vous avez fait une demande de reset de mot de passe,
            si ce n'est pas vous qui avez fait cette demande ignorer cette email
            <br/> <br/>
            cliquer sur lien qui suis pour reinitialiser votre mot de passe
            <br/> <br/>
            <a href='http://localhost:8080/camagru/resetpass.php?id=$id&code=$code'>click ici pour reset ton password</a>
            merci :)
            ";

        $subject = "Password Reset";

        $user->r_mail($email,$subject,$message_txt,$message_html);
        $msg = "
              <div class=' success-success'>
                  un email a etait envoyer a $email.
                  cliquer sur le lien présent dans celui-ci, pour generer un nouveau Password.
              </div>
              ";
      }
      else
      {
          $msg = "
                  <div class='error-error'>
                      <strong>désolé !</strong> l'email na pas etait trouver.
                  </div>
                ";
      }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mot de passe oublié</title>
          <!-- <link rel="stylesheet" href="css/style.css" /> -->
        <link rel="stylesheet" media="screen" href="css/screen.css" />
    </head>
    <body>
      <?php include('header.php');?>
        <div id="login">
        <div class="leftLogin"></div>
            <div class="f-container">
              <form class="form-signin" method="post">
                  <h2 class="form-signin-entete">Mot de passe oublié</h2>
                  <?php
                      if(isset($msg))
                      {
                          echo $msg;
                      }
                      else
                      {
                  ?>
                          <div class='info-info'>
                              Veuillez enter votre adresse email. Vous recevrez un lien pour changer de mot de passe.

                          </div>
                  <?php
                      }
                  ?>
                  <br />
                  <hr />
                  <input type="email" class="f-input" placeholder="Email address" name="txtemail" required />
                  <br />
                  <hr />
                  <button type="submit" class="btn" name="btn-submit" >Envoyer</button>
              </form>
            </div>
            <div class="rightLogin"></div>
        </div>
       <?php include('footer.php');?>
    </body>
</html>
