<?php
session_start();
require_once 'class.user.php';
//require_once 'class.pdo.php';
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
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $row['userEmail']; ?></title>
    </head>
    <body>
    <?php include('header.php');?>
    <div id="globalVideo">
        <div id="contentVideo">
            <video id="video" hidden></video>
            <img src="" id="uploadPicturePvw" style="display: none;">
            <canvas id="canvasOne"></canvas>

            <canvas id="canvas"></canvas>

        </div>
        <!-- <div id="rightScrenMq"> -->
            <div id="rightScren">

                <?php
                $sqlgal = 'SELECT * FROM `tbl_galerie`  WHERE userID = "'.$_SESSION['userSession'].'" ORDER BY imgID DESC';
                foreach ($user_home->query($sqlgal) as $gal):?>
                    <div class="previewContain">
                    <span class="delPic" data-id="<?= $gal->imgID; ?>" onclick="this.parentNode.remove();" >X</span>
                    <img data-id="<?= $gal->imgID; ?>" class="miniPic" src="assets/image/<?= $gal->imageName; ?>" />
                    </div>
                <?php endforeach; ?>
            </div>
        <!-- </div> -->
    </div>
    <?php

    ?>
        <div id="botVideo" >

            <div id="vignAll">
                <?php foreach ($user_home->query('SELECT * FROM tbl_vignette') as $vign): ?>
                    <img id="<?= $vign->vignID ?>" class="vignette" onclick="undisableBtn()" src="<?= $vign->vignetteAdress; ?>" />

                <?php endforeach; ?>

            </div>

            <div id="buPhoto">
                <button type="button" class="btn" onclick="disableBtn()" id="clear">reset</button>
                <button type="button" class="btn" id="startbutton" disabled="true">Prendre une photo</button>
<!--                <imput type="file" class="btn" id="uploadPicture" >Uploader une photo</imput>-->
                <span id="ca_text"></span>
                    <button type="submit" class="btn" id="uploadPicture">Uploader une photo</button>
                    <input type="file" style="display: none;" accept="image/*"/>
            </div>
            <!-- <img src="http://placekitten.com/g/320/261" id="photo" alt="photo"> -->

        </div>
<!--    <img id="photo" src="img/02.jpg" height="300" width="400"/>-->
    <script type="text/javascript" src="js/cam.js"></script>
    <!-- Activer desactiver image -->
    <script type="text/javascript">
    //desactive le bouton photo
    function disableBtn() {
        document.getElementById("startbutton").disabled = true;
    }
    //active le bouton photo
    function undisableBtn() {
        document.getElementById("startbutton").disabled = false;
    }

    </script>
     <?php include('footer.php');?>
    </body>
</html>
