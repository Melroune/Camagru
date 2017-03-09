<?php

require_once 'class.user.php';
$user = new USER();

//======== redirection index si les id et le code sont vide ======
if(empty($_GET['id']) && empty($_GET['code']))
{
    $user->redirect('index.php');
}
//================================================================
// //======== modification du statu =================================
//
if(isset($_GET['id']) && isset($_GET['code']))
{
    $id = base64_decode($_GET['id']);
    $code = $_GET['code'];

    $statusY = "Y";
    $statusN = "N";
    //on conpare l'id envoier par le mail a celui dans la bdd et si
    //c'est ok on modifie le status en Y
    $stmt = $user->runQuery("SELECT userID,userStatus FROM tbl_users WHERE userID=:uID AND tokenCode=:code LIMIT 1");
    $stmt->execute(array(":uID"=>$id, ":code"=>$code));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0)
    {
        if($row['userStatus'] == $statusN)
        {
            $stmt = $user->runQuery("UPDATE tbl_users SET userStatus=:status WHERE userID=:uID");
            $stmt->bindparam(":status",$statusY);
            $stmt->bindparam(":uID",$id);
            $stmt->execute();

            $msg = "
                <div class=' success-success'>
                    <strong> Felicitation ! </strong> Votre compte et maintenant activer :
                    <a href='index.php'>Connecter vous ici </a>
                </div>
            ";
        }
        else
        {
            $msg = "
                <div class='error-error'>
                    <strong> désolé ! </strong> Votre compte est déjà activé.
                </div>
            ";
        }
    }
    else
    {
        $msg = "
           <div class='error-error'>
              <strong> désolé ! </strong> Votre compte est déjà activé.
           </div>
        ";
    }
}
// //================================================================
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Confirm Registration</title>
      <!-- <link rel="stylesheet" href="css/style.css" /> -->
      <link rel="stylesheet" media="screen" href="css/screen.css" />
    </head>
    <body>
      <?php include('header.php');?>
      <div id="login">
        <div class="leftLogin"></div>
        <div class="f-container">
            <?php if(isset($msg)) { echo $msg; } ?>
        </div>
            <div class="rightLogin"></div>
    </div>

 <?php include('footer.php');?>
  </body>
</html>
