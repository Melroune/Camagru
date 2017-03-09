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


if (isset($_POST['imgData']))
{
//   echo $_POST['selectVignnet'];
    $w = 800;
    $h = 600;
    //on génere un nom aleatoire de fichier
    $char = 'abcdefghijklmnopqrstuvwxyz';
    $char .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char .= '1234567890';
    $coderand = '';
    for ($i=0;$i < 10;$i++)
        $coderand .= substr($char,rand()%(strlen($char)),1);
    $fileName = $coderand.uniqid().'.png';
    $path = "../../assets/image/".$fileName.".png";

    // on receptione la basse 64 envoyer par ajax et on la decode
    $data = str_replace('data:image/png;base64,', '', $_POST['imgData']);
    $data = str_replace(' ', '+', $data);
    $imageData = base64_decode($data);

    //on recup l'url de limage et on la formate pour pouvoir la traiter
    $overPicUrl = $_POST['selectVignnet'];
    $overPic = imagecreatefrompng("../../".$overPicUrl);

//    echo imagesx($overPic)." ";
//    echo imagesy($overPic);

    //on traitre l'image
    $img = imagecreatefromstring($imageData);
    if($img !== false){
        $overPic2 = imagecreatetruecolor(imagesx($overPic), imagesy($overPic));
        imagecolortransparent($overPic2, imagecolorallocatealpha($overPic2, 0, 0, 0, 127));
        imagealphablending($overPic2, false);
        imagesavealpha($overPic2, true);
        imagecopyresampled($overPic2, $overPic, 0, 0, 0, 0,imagesx($overPic), imagesy($overPic), imagesx($overPic), imagesy($overPic));
//        echo "  ".imagesx($overPic2)." ";
//        echo imagesy($overPic2);
        // pour la transparence
        imagesavealpha($overPic, true);
        imagesavealpha($img, true);

        // modifications ici
        imagecopyresampled($img, $overPic2, 250, 120, 0, 0, imagesx($overPic), imagesy($overPic), imagesx($overPic), imagesy($overPic));
//        imagecopy($img, $overPic2, 0, 0, 0, 0, $w, $h);

        $imgSave = imagepng($img, $path);
        imagedestroy($img);
        imagedestroy($overPic);
        imagedestroy($overPic2);
        $vote = 0;
        $pathUrl = $fileName.".png";
        $IDvotes = "";
        $insDb = $user_home->runQuery('INSERT INTO `tbl_galerie`(`imgID`, `userID`, `imageName`, `vote`, `IDvotes`) VALUES (:imgID, :userID, :imageName, :vote, :IDvotes)');
        $insDb->bindparam(":imgID",`imgID` );
        $insDb->bindparam(":userID",$userSesion);
        $insDb->bindparam(":imageName",$pathUrl);
        $insDb->bindparam(":vote", $vote);
        $insDb->bindparam(":IDvotes", $IDvotes);
        $insDb->execute();
        $idSelec = $user_home->lastID(); //je recupere l'id
        // j'envois les photo 
        echo "
        {
            \"id\":\"$idSelec\",
            \"path\":\"assets/image/".$pathUrl."\"
        }";

    }
    else{
        echo 'Erreur';
    }
}
?>

