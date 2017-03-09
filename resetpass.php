<?php

require_once 'class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code']))
{
 $user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
    $id = base64_decode($_GET['id']);
    $code = $_GET['code'];

    $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userID=:uid AND tokenCode=:token");
    $stmt->execute(array(":uid"=>$id,":token"=>$code));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() == 1)
    {
        if(isset($_POST['btn-reset-pass']))
        {
            $pass = $_POST['pass'];
            $cpass = $_POST['confirm-pass'];

            if($cpass!==$pass)
            {
                $msg = "
                       <div class='error-error'>
                          <strong>Désolé !</strong> Le mot de passe ne correspond pas.
                       </div>
                       ";
            }
            else
            {
                $password = password_hash($cpass, PASSWORD_DEFAULT);
                $stmt = $user->runQuery("UPDATE tbl_users SET userPass=:upass WHERE userID=:uid");
                $stmt->execute(array(":upass"=>$password,":uid"=>$rows['userID']));

                $msg = "<div class='success-success'>
                            Le mots de pass est changer
                          Password Changed.
                        </div>
                       ";
                header("refresh:5;index.php");
            }
        }
     }
     else
     {
          exit;
     }
}
?>

<!DOCTYPE html>
<html>
  <head>
      <title>Réinitialiser son mot de passe</title>
            <!-- <link rel="stylesheet" href="css/style.css" /> -->
      <!-- <link rel="stylesheet" media="screen" href="css/screen.css" /> -->
  </head>
  <body>
    <?php include('header.php');?>
      <div id="login">
        <div class="leftLogin"></div>
          <div class="f-container">
              <div class='success-success'>
                  <strong>Bonjour !</strong>  <?php echo $rows['userName'] ?>
              </div>
              <form class="form-signin" method="post">
                  <h2 class="form-signin-entete">Réinitialiser son mot de passe</h2>
                  <?php
                      if(isset($msg))
                      {
                          echo $msg;
                      }
                  ?>
                  <input type="password" class="input-block-level" placeholder="New Password" name="pass" required /><br /><br />
                  <input type="password" class="input-block-level" placeholder="Confirm New Password" name="confirm-pass" required /><br /><br />
                  <hr />
                <br />
                  <button type="submit" name="btn-reset-pass">Envoyer</button>
            </form>
          </div>
          <div class="rightLogin"></div>
      </div>
      <?php include('footer.php');?>
  </body>
</html>
