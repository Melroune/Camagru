<?php
session_start();
require_once 'class.user.php';
// require_once 'class.pdo.php';

$user_home = new USER();

if(!$user_home->is_logged_in())
{
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//initialisation des object pdo
//$db = new App\Database('camagru');
$userSession = $_SESSION['userSession'];

function testPostInt($var)
{
    if (!is_numeric($var) && !is_int($var))
    {
        return false;
    }

    return true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $row['userEmail']; ?></title>
    </head>
    <body>
        <?php include('header.php');?>
        <?php
        if(isset($_GET['com']))//verifie si la variable et vide
        {
        	if(!testPostInt($_GET['com']))// si c4est un int
        	{
            echo('<a href="galerie.php">retourner a la galerie</a>');
            exit();
        	}
        	if($_GET['com'] <= 0)
        	{
            echo('<a href="galerie.php">retourner a la galerie</a>');
            exit();
        	}
        	$com = $_GET['com'];
        	//si la variable get et un valeure qui ce trouve dans la bdd
        	$sqlRealImg = 'SELECT `imgID` FROM `tbl_galerie` WHERE `imgID`= '.$com.'';
        	$stmtRealImg = $user_home->runQuery($sqlRealImg);
        	$stmtRealImg->execute();

        	if(!$stmtRealImg->fetch())//si la valeur ne si trouve pas
        	{
            echo('<a href="galerie.php">retourner a la galerie</a>');
            exit();
        	}
        	else//sinon on ferme la premiere execution et on la relance pour recuperer l'id valide
        	{
        		$stmtRealImg->closeCursor();
        		$stmtRealImg->execute();
        		$rowRealImg = $stmtRealImg->fetch();
        		// print_r($rowRealImg);
        		$imgID = $rowRealImg['imgID'];
        		// echo $imgID;
	        }
  }
        ?>
        <div class="globalCom">
        	<div class="comImage">
        		<?php
        		//Recuperation de l'image a commenter
        		$sqlRecupImg = 'SELECT * FROM `tbl_galerie` WHERE `imgID` = '.$imgID.'';
        		$stmtRecupImg = $user_home->runQuery($sqlRecupImg);
        		$stmtRecupImg->execute();

        		$rowRecupImg = $stmtRecupImg->fetch();
        		?>
        		<img class="imgCom" src="assets/image/<?= $rowRecupImg['imageName']; ?>">
        		<!-- fin de recuperation de l'image -->

        	</div>
        	<div class="com">
        	   <div class="affcom">
        		  <div id="newCom">
        	       <?php
        	           	$sqlRecupCom = 'SELECT * FROM `tbl_com` WHERE `imgID` = '.$imgID.'';
        		        foreach ($user_home->query($sqlRecupCom) as $comRecup):
                    ?>
        			    <div class="blocCom"><?= $comRecup->com; ?></div>
        	       <?php endforeach; ?>
        		  </div>
        	   </div>
        	   <div class="postcom">
				    <form method="post" action="">
					   <p>
						  <label for="postcoms">Poster un commentaire: </label><br />
						  <input type="hidden" name="idimg" id="idimg" value="<?= $imgID; ?>" ><br>
						  <textarea name="postcom" id="postcoms" onmouseout="undisableBtn()" value=""></textarea><br />
						  <button type="submit" class="btn" id="btnpostcoms" onmousemove="undisableBtn()" disabled="true">Commenter</button>
					   </p>
				    </form>
        	   </div>
        	</div>
        </div>
        		<?php

        		# pourvoir ajouter un comentaire a la bdd
        		# il faut que j'insert le comentaire dans la basse de donner
        		# il me faut :
        		# un formulaire pour soumaitre qui sera traiter par une page comtraitement.php
        		# dans la page comtraitement.php
        		# je verifierais que le texte soumi ne contient pas de caractere special
        		# puis je l'insererait dans la bdd
        		# et envois un mail a la personne qui detien limage
        		#
        		# ensuite j'affiche le com a la suite de limage

        		?>
    <script type="text/javascript" src="js/com.js"></script>
	<script type="text/javascript">
    //desactive le bouton photo
    function trimfield(str)
	{
	    return str.replace(/^\s+|\s+$/g,'');
	}

    function undisableBtn() {
        if(document.getElementById('postcoms').value != '')
        {
        	if(trimfield(document.getElementById('postcoms').value))
        	{
        		document.getElementById("btnpostcoms").disabled = false;
  			}
  		}else
  		{
  				document.getElementById("btnpostcoms").disabled = true;
    	}
    }

    </script>
        <?php include('footer.php');?>
    </body>
</html>
<!--  	// $sqlImg = 'SELECT `imgID` FROM tbl_galerie ORDER BY `imgID` DESC LIMIT 1';
        	// $stmtImg = $user_home->runQuery($sqlImg);
        	// $stmtImg->execute();
        	// $row = $stmtImg->fetch();

        	// // print_r($row);
        	// $idMax = $row['imgID']; -->
