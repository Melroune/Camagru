<?php 
session_start();
require_once '../../class.user.php';	
//require_once '../../class.pdo.php';
$user_home = new USER();

if  (!$user_home->is_logged_in())
{
    $user_home->redirect('index.php');
}
//echo "userSesion: ".$_SESSION['userSession'];
$userSession = $_SESSION['userSession'];

// function updateVotes($imageID, $voteList)
// {

// }
if(isset($_POST['imgId']) && isset($_POST['userId']))
{
	$imgId = $_POST['imgId'];
	$userId = $_POST['userId'];
	$errorVote = "vous avez deja voter";
	$votePlus = "vote +1 ";
	$sqlVoteList = 'SELECT * FROM `tbl_galerie` WHERE `imgID`= '.$imgId.'';
	$stmtVoteList = $user_home->runQuery($sqlVoteList);
	$stmtVoteList->execute();
	$rowVote = $stmtVoteList->fetch();
	$voteList = $rowVote['IDvotes'];
	$voteNb = $rowVote['vote'];
	$votePlusOk = 0; //je defini vote plus 
	if(isset($voteList)) //vous aver deja voter pour cette image 
	{
		
		$tabVote = explode(",", $voteList);
		$i = 0;
		$iMax = count($tabVote);
		foreach ($tabVote as $key => $value) 
		{
			if($value == $userId)
			{
				$votePlusOk = 1;
				echo "
    			{
    		       	\"errorVote\":\"$errorVote\",
    		       	\"voteNb\":\"$voteNb\",
        		   	\"IDimg\":\"$imgId\",
        		   	\"voteList\":\"$voteList\"
   				}";
   						
   				break;
			}
		}
	}
	if($votePlusOk == 0)
	{ 
		
		if($voteList != NULL){
		$voteListUp = $voteList.",".$userId;
		}
		else
		{
			$voteListUp = $userId;
   		}
   		$voteNb = $voteNb += 1;
   		$sqlVoteList = 'UPDATE `tbl_galerie` SET `vote` = :voteNb , `IDvotes`= :voteListUp WHERE `imgID` = '.$imgId.'';
		$stmtVoteList = $user_home->runQuery($sqlVoteList);
		$stmtVoteList->bindparam(":voteListUp", $voteListUp);
		$stmtVoteList->bindparam(":voteNb", $voteNb);
		$stmtVoteList->execute();
		echo "
   		{
   			\"voteList\":\"$voteList\",
       	   	\"votePlus\":\"$voteNb\",
       	  	\"voteNb\":\"$voteNb\",
    	   	\"IDimg\":\"$imgId\"
   		}";
	}

}
	// je commence par recuperer la liste contenu dans iDvotes dans la table galerie. 
	// je stock une copie de la liste dans une nouvel variable -- 
	// ensuite je l'explode(delimiter, string) avec le carractere "," et je parcour le tableau obtenu pour verifier si il y a presence de l'id de l'utilisateur 
	// si l'id est deja present j'ecrit vous aver deja voter.
	// si l'id ni est pas j'ajoute a la suite de la liste obtenu une "," si la liste n'est pas vide et j'upload la liste  
	// je doit explode(",", $listIDimg);
?>