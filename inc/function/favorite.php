<?php include("../settings.php");

if(!$logged){die();}

if(isset($_POST["idserial"])){

	if (!ctype_digit($_POST['idserial'])){ die(); }

$countfavu = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_favorite WHERE fav_serialid='".mysqli_escape_string($link,$_POST["idserial"])."' AND fav_userid='".$userinfo["user_id"]."'"));
echo $countfavu;
if($countfavu == 0){
	mysqli_query($link,"INSERT INTO tvsee_favorite(fav_serialid,fav_userid) VALUES('".mysqli_escape_string($link,$_POST["idserial"])."','".$userinfo["user_id"]."')");
}else{
	mysqli_query($link,"DELETE FROM tvsee_favorite WHERE fav_serialid='".mysqli_escape_string($link,$_POST["idserial"])."' AND fav_userid='".$userinfo["user_id"]."'");
}

}

?>