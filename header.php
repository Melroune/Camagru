<header id="header">
    <div class="logo"><a href="index.php"><img src="img/logocama.png"></a></div>
    <div class="headerMid"></div>
<link rel="stylesheet" media="screen" href="css/Style1.css" />
<link rel="stylesheet" media="screen" href="css/screen.css" />
    <script type="text/javascript" src="js/ajax.js"></script>
    <nav class="navBar">
        <ul id="navElem">
<?php
    $test = $_SESSION["test"];
    if($test != "ok"){
        echo '<li class="navElement"><a tabindex="-1" href="galerie.php"> Galerie</a></li>';
        echo '<li class="navElement"><a href="index.php">Connection</a></li>';
        echo '<li class="navElement"><a href="signup.php">S\'inscrire</a></li>';
    }else{
        echo '<li class="navElement"><a tabindex="-1" href="galerie.php"> Galerie</a></li>';
        echo '<li class="navElement"><a tabindex="-1" href="logout.php"> Logout</a></li>';
    }
    ?>
        </ul>
    </nav>
    <div class="headerRight"><!-- yolo --></div>
</header>
