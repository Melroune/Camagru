<?php
session_start();
require_once 'class.user.php';
// require_once 'class.pdo.php';

$user_home = new USER();

// if(!$user_home->is_logged_in())
// {
//     $user_home->redirect('index.php');
// }

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//initialisation des object pdo
//$db = new App\Database('camagru');
$userSession = $_SESSION['userSession'];
function testPostInt($var, $var2)
{
    // echo $var.' '.$var2.' ';
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
        <div id="globalGalerie">
            <?php
            $limitStart = 0;
            $limitEnd = 6;

                $sqlgalLength = 'SELECT * FROM `tbl_galerie`';
                $stmtGal = $user_home->runQuery($sqlgalLength);
                $stmtGal->execute();
                $nbEnter = $stmtGal->rowcount();

            if ($nbEnter != 0)
                {
                   $nbPage = ceil($nbEnter/$limitEnd);
            if(isset($_GET['page']))
            {
                // echo $_GET['page'].'page ';
                if (!testPostInt($_GET['page'], $nbPage))
                {
                    $page = 1;
                }
                $page = $_GET['page'];
            }
            else
            {
                $page = 1;
            }
            if($page == 0 || $page >= 20000 || $page > $nbPage || $page < 0)
            {
                $page = 1;
            }
            $limitStart = ($page - 1) * $limitEnd;
            $sqlgal = 'SELECT * FROM `tbl_galerie` ORDER BY imgID DESC LIMIT '.$limitStart.', '.$limitEnd.'';
            foreach ($user_home->query($sqlgal) as $gal):

            ?>
                <div class="imgContent" >
                    <!-- <?= $gal->imgID?> -->

                    <img class="imgGal" data-id="<?= $gal->imgID; ?>" src="assets/image/<?= $gal->imageName; ?>">
                    <div class="comLike" data-id="<?= $gal->imgID; ?>">
<?php
                      if($user_home->is_logged_in())
                      {
?>
                       <a class="comLink"  href="commentaire.php?com=<?= $gal->imgID; ?>">commentaire</a>
                       <div class="like" data-id="<?= $gal->imgID; ?>" data-userId="<?= $userSession ?>">Like (<?= $gal->vote; ?>)</div>
                         <!-- j'ecoute le lien like si un clique et effectuer je recupere en ajax la valeur data-id  que je lance sur la page de traitement-->
<?php
                      }
?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <div class="pagination">
            <?php
                // echo $nbEnter.'entrees. nbpages: '.$nbPage.' ';
                //j'affiche les lien des pages
                for($i = 1; $i <= $nbPage; $i++)
                {
                    echo '<a href="galerie.php?page='.$i.'"> '.$i.' </a> ';
                    echo '&nbsp;';
                }

                }else{
                        echo "aucune photo enregistrer";
                }
            ?>
        </div><!--pagination  -->
            <script type="text/javascript" src="js/like.js"></script>
        <?php include('footer.php');?>
    </body>
</html>

<?php

//commentaire
    //faire un lien pour ajouter un commentaire
    // le lien redirigera vers la page commentaire.php
    # <a href="commentaire.php?com="."
//like
?>
