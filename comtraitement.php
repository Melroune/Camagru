<?php
session_start();
require_once 'class.user.php';
//require_once '../../class.pdo.php';
$user_home = new USER();

if  (!$user_home->is_logged_in())
{
    $user_home->redirect('index.php');
}
//echo "userSesion: ".$_SESSION['userSession'];
$userSesion = $_SESSION['userSession'];

if (isset($_POST['postcoms']) && isset($_POST['idimg']))
{
	$postcoms = $_POST['postcoms'];
	$idimg = $_POST['idimg'];
  // if ($idimg == 0) {
  //       $user_home->redirect('galerie.php');
  // }
	if (!preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $postcoms) && !preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $idimg))
	{
		// echo $postcoms.' ';
		// echo $idimg.' ';
		// echo "yolo";
		if(strlen($postcoms) > 300)
		{
			$errorLc = "le commentaire doit faire moin de  300 caractere";
			echo "
       		{
           		\"errorLc\":\"$errorLc\"
       		}";
		}
		else
		{

			$sqlInsertCom = $user_home->runQuery('INSERT INTO `tbl_com`(`comID`, `imgID`, `com`) VALUES (:comID, :idimg, :postcoms)');
			$sqlInsertCom->bindparam(":comID",`comID` );
			$sqlInsertCom->bindparam(":idimg",$idimg );
			$sqlInsertCom->bindparam(":postcoms",$postcoms );
			$sqlInsertCom->execute();
			$idLast = $user_home->lastID(); //je recupere l'id du dernier commentaire inserer
			echo "
        	{
           		\"id\":\"$idLast\",
           		\"postcoms\":\"$postcoms\",
           		\"mail\":\"ok\"
       		}";
       		$sqlrecupUserId = $user_home->runQuery('SELECT * FROM `tbl_galerie` WHERE `imgID` = '.$idimg.'');
       		$sqlrecupUserId->execute();
       		$rowUserId = $sqlrecupUserId->fetch();
       		$userID = $rowUserId['userID'];
       		$sqlRecupEmail = $user_home->runQuery('SELECT * FROM `tbl_users` WHERE `userID` = '.$userID.'');
       		$sqlRecupEmail->execute();
       		$rowEmail = $sqlRecupEmail->fetch();
       		$email = $rowEmail['userEmail'];

       		 $subject = "Nouveaux commentaire";
       		 $message_txt = "Bonjour , vous avec un nouveau comentaire
       		Camagru";
       		$message_html = "
          <html>
              <head>
              </head>
              <body>
                  Bonjour , vous avec un nouveau comentaire <br />
                  Camagru
              </body>
          </html>
          ";
       		$user_home->r_mail($email,$subject,$message_txt,$message_html);
		}
	}else
	{
		$errorCs = "le commentaire ne doit pas contenir de caractere speciaux";
		echo "
        {
           	\"errorCs\":\"$errorCs\"
       	}";
	}
}else
{
	echo "le commentaire ne peut etre vide";
}
//cree une regex pour verifier le format de mon message


?>
