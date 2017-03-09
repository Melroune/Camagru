<?php
session_start();
require_once '../../class.user.php';
//require_once '../../class.pdo.php';
$user_home = new USER();

if  (!$user_home->is_logged_in())
{
    $user_home->redirect('../../index.php');
}
//echo "userSesion: ".$_SESSION['userSession'];
$userSesion = $_SESSION['userSession'];
//echo $userSesion;

//$db = new App\Database('camagru');


if (isset($_POST['idPost']))
{

//    echo $_POST['idPost'];
    $idPost = $_POST['idPost'];
//echo $idPost." image a sup";

        $delDb = $user_home->runQuery('DELETE FROM `tbl_galerie` WHERE `imgID` = "' . $idPost . '"');
        $delDb->execute();
    echo "
        {   
            \"response\":\"ok\"
        }";


}
?>