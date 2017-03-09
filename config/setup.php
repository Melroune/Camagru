<?php
    // require_once('Database.php');
    session_start();
    	function createDB()
    	{
    		try{
                 $DB_DSN = "mysql:host=localhost;charset:UTF8";
                 $DB_USER = "root";
                 $DB_PASSWORD = "1123581321e";
    			$connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			return($connect);
    		} catch (PDOException $e) {
    			echo "Connection Failed, like your life :" . $e->getMessage();
    		}
    	}
    $connecto = createDB();
    // $connect = $connection->dbConnection();
    // var_dump($connect);
    if(!$connecto)
    {
        die(" Failed to connect ");
    }
        $bdd = $connecto->query(
        "CREATE DATABASE IF NOT EXISTS `camagru` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci; USE `camagru`;"
        );
        echo " ta 1 ";
        if(!$bdd)
        {
            exit('DATABASE camagru FAILED TO CREATE'."<br />");
        }else{
            echo "DATABASE camagru SUCESSFULLY CREATED"."<br />";
        }
function connectDB()
        {
            try{
                $DB_DSN = "mysql:host=localhost;dbname=camagru";
                $DB_USER = "root";
                $DB_PASSWORD = "1123581321e";
                $connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return($connect);
            } catch (PDOException $e) {
                echo "Connection Failed, like your life :" . $e->getMessage();
            }
        }
        $connect = connectDB();
        // $connect = $connection->dbConnection();
        // var_dump($connect);
        if(!$connect)
        {
            die(" Failed to connect ");
        }
    echo " ta 1 ";
            $tableCom = $connect->query(
                "CREATE TABLE `tbl_com` (\n"
                    ."`comID` int(11) NOT NULL,\n"
                    ."`imgID` int(11) NOT NULL,\n"
                    ."`com` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL) \n"
                    ."ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                if(!$tableCom){
                    exit('TABLE commentaire FAILED TO CREATE'."\n");
                }else {
                    echo "TABLE commentaire SUCESSFULLY CREATED"."\n";
                }

                    $tableGalerie = $connect->query(
                        "CREATE TABLE `tbl_galerie` (
                            `imgID` int(11) NOT NULL,
                            `userID` int(11) NOT NULL,
                            `imageName` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                            `vote` int(11) NOT NULL DEFAULT '0',
                            `IDvotes` text NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                    if(!$tableGalerie){
                        exit('TABLE Galerie FAILED TO CREATE'."<br />");
                    }else {
                        echo "TABLE Galerie SUCESSFULLY CREATED"."<br />";
                    }

                        $tableUsers = $connect->query(
                            "CREATE TABLE `tbl_users` (
                                `userID` int(11) NOT NULL,
                                `userName` varchar(100) NOT NULL,
                                `userEmail` varchar(100) NOT NULL,
                                `userPass` varchar(100) NOT NULL,
                                `userStatus` enum('Y','N') NOT NULL DEFAULT 'N',
                                `tokenCode` varchar(100) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                        if(!$tableUsers){
                            exit('TABLE Users FAILED TO CREATE'."<br />");
                        }else {
                            echo "TABLE Users SUCESSFULLY CREATED"."<br />";
                        }
                            $tableVignette = $connect->query(
                                "CREATE TABLE `tbl_vignette` (
                                    `vignID` int(11) NOT NULL,
                                    `vignetteAdress` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                            if(!$tableVignette){
                                exit('TABLE Vignette FAILED TO CREATE'."<br />");
                            }else {
                                echo "TABLE Vignette SUCESSFULLY CREATED"."<br />";
                            }
                            $addmask = $connect->query(
                            "INSERT INTO `tbl_vignette` (`vignID`, `vignetteAdress`) VALUES
                            (1, 'img/image.png'),
                            (2, 'img/triforce.png'),
                            (3, 'img/Illuminati.png');"
                            );
                            if(!$addmask){
				                exit('FAILED TO ADD MASKS'."<br />");
			                }else{
				                echo "MASKS SUCESSFULLY ADDED"."<br />";
                            }

                                $tableVote = $connect->query(
                                    "CREATE TABLE `tbl_vote` (
                                        `voteID` int(11) NOT NULL,
                                        `imgID` int(11) NOT NULL,
                                        `userID` int(11) NOT NULL
                                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
                                    if(!$tableVote){
                                        exit('TABLE votes FAILED TO CREATE'."<br />");
                                    }else {
                                        echo "TABLE votes SUCESSFULLY CREATED"."<br />";
                                    }

                        $test = $connect->query(
                    "  ALTER TABLE `tbl_com` ADD PRIMARY KEY (`comID`);"
                    );
                    $test1 = $connect->query(
            "ALTER TABLE `tbl_galerie`
              ADD PRIMARY KEY (`imgID`);"
                );
                $test2 = $connect->query("ALTER TABLE `tbl_users`
                  ADD PRIMARY KEY (`userID`),
                  ADD UNIQUE KEY `userEmail` (`userEmail`);
                ");
                $test3 = $connect->query(
    "ALTER TABLE `tbl_vignette`
      ADD PRIMARY KEY (`vignID`);"
            );
                $test4 = $connect->query(
                "ALTER TABLE `tbl_vote`ADD PRIMARY KEY (`voteID`);
                ");
                    $test5 = $connect->query(
                    " ALTER TABLE `tbl_users`
                      MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
                    ");
                    $test6 = $connect->query(
                    "ALTER TABLE `tbl_vignette`
                      MODIFY `vignID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;"
                      );
                      $test7 = $connect->query(
                      "ALTER TABLE `tbl_com`
                        MODIFY `comID` int(11) NOT NULL AUTO_INCREMENT;"
                        );
                        $test8 = $connect->query(
                        "ALTER TABLE `tbl_galerie`
                          MODIFY `imgID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;"
                          );
                          $test9 = $connect->query(
                          "ALTER TABLE `tbl_vote`
                            MODIFY `voteID` int(11) NOT NULL AUTO_INCREMENT;"
                             );

?>
