<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();
//======Si l'user et deja connecter on le renvois directement sur la page d'acceuil======
if($reg_user->is_logged_in()!="")
{
    $reg_user->redirect('home.php');
}
//=======================================================================================

//======on verifie que l'inscription et ok et on envois le mail de verification==========
if(isset($_POST['btn-signup']))
{
    $uname = trim($_POST['txtuname']);
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtpass']);
    $code = md5(uniqid(rand()));

    $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
    $stmt->execute(array(":email_id"=>$email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $uname) && !preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $upass))
  {
    if($stmt->rowCount() > 0)//si il ya un doublon
    {
        $msg = "
            <div class='error-error'>
              <strong> désolé ! </strong> l'email utiliser est deja utiliser, veiller essayer avec une nouvelle adresse.
            </div>
            ";
    }
    else
    {
      if(strlen($upass) < 8){
          $msg = "mdp trop cour";
      }
      else if (strlen($upass) > 20) {
          $msg = "mdp trop long";
      }
      else if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/i', $upass)) {
          $msg = "le mdp doit contenir des caracter alpha numerique !.1";
      }
      else if($reg_user->register($uname,$email,$upass,$code))
      {
          //$id = '12';

          $id =$reg_user->lastID();
          $key = base64_encode($id);
          $id = $key;
          #$lien = '<a href="http://camagru.local.42.fr:8080/verify.php?id=$id&code=$code">';

          $message_txt = "Bonjour pour valider votre inscription cliquer sur ce lien:
          <a href='http://localhost:8080/camagru/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>";
          $message_html = "
          <html>
              <head>
              </head>
              <body>
                  Bonjour pour valider votre inscription<b>cliquer sur ce lien:
                  <br /><br />
                        <a href='http://localhost:8080/camagru/verify.php?id=$id&code=$code'>
                        Click HERE to Activate :)</a>
                  <br /><br />
              </body>
          </html>
          ";
          $subject = "Confirm Registration";
          $reg_user->r_mail($email,$subject,$message_txt,$message_html);
          $msg = "
                <div class=' success-success'>
                   <strong>Félicitations! !</strong> un email a etait envoyer a $email.
                   Valider la création de votre compte en cliquant sur le lien présent dans celui-ci.
                 </div>
                 ";
        }
        else
        {
            $msg = "un problème est survenu lors de l'inscription, veuillez réessayer.";
        }

    }
  }
  else
  {
      $msg = "vous dever utiliser des caracter alpha numerique.";
  }
}
//=======================================================================================
?>
<!--=====page inscription================================================================-->
<!DOCTYPE html>
<html>
    <head>
        <title>Inscription | Pirikura</title>
          <!-- <link rel="stylesheet" href="css/style.css" /> -->
        <!-- <link rel="stylesheet" media="screen" href="css/screen.css" /> -->
    </head>
    <body>
              <?php include('header.php');?>
        <div id="login">

        <div class="leftLogin"></div>
            <div class="f-container">
                <?php if(isset($msg)) echo $msg; ?>
                <form class="form-signin" method="post">
                    <h2 class="form-signin-entete">Inscription</h2>
                    <hr /><br/>
                    <input type="text" placeholder="Username" name="txtuname" required /><br /><br />
                    <input type="email" placeholder="Email address" name="txtemail" required /><br /><br />
                    <input type="password" placeholder="Password" name="txtpass" required /><br /><br />
                    <hr />
                    <br />
                    <button type="submit" class="btn" name="btn-signup"> Envoyer</button>
                    <a href="index.php" class="btn" style="float:right;" class="btn btn-large">Se connecter</a>
                    <br />
                </form>
            </div> <!-- fin de /f-container -->
        <div class="rightLogin"></div>
        </div>
         <?php include('footer.php');?>
    </body>
</html>

<!--=====================================================================================-->
