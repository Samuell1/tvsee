<?php
    ob_start();
    session_start();
    define("PERM",TRUE);

    $onlylogged = 0;
    $weburl = "http://tvsee.eu/";
    $link = mysqli_connect("wm23.wedos.net", "w28413_tvsee", "XXX","d28413_tvsee")or die("Nastala chyba pri pripojení");
        mysqli_query($link,"set names utf8");

    $num1 = rand(1,9);
    $num2 = rand(1,9);

function redirect($link){
  header("Location: ".$link);
}
function odkazactive($name){
    $web = $_SERVER["PHP_SELF"];
  if(strstr($web, $name)){ return true; }else{ return false; }
}
function bezd($text){
    $return = Str_Replace(
              Array("ĺ","ô","á","č","ď","é","ě","í","ľ","ň","ó","ř","š","ť","ú","ů","ý","ž","Á","Č","Ď","É","Ě","Í","Ľ","Ň","Ó","Ř","Š","Ť","Ú","Ů","Ý","Ž") ,
              Array("l","o","a","c","d","e","e","i","l","n","o","r","s","t","u","u","y","z","A","C","D","E","E","I","L","N","O","R","S","T","U","U","Y","Z") ,
                        $text);
    $return = Str_Replace(Array(" ?"," !"), "", $return);
    $return = Str_Replace(Array("(",")",".","!",",","\"","'",":","#","]","[","-?","?","&",";","”","“","€","$","+","quot","","´","%"), "", $return);
    $return = Str_Replace(Array(" - "," ", "_", "/","--"), "-", $return);
    $return = StrToLower($return);
        return $return;
}
function serialname($id){
  global $link;
  $pp = mysqli_query($link,"SELECT * FROM tvsee_serialy WHERE serial_id='".$id."'");
  $id = mysqli_fetch_array($pp);
    return $id["serial_name"];
}

function pagination($rows1,$limit,$pocet_stran,$strana,$golink="",$orderlink=""){

    if($rows1 > $limit){

            $aktualnastrana = (isset($_GET['strana']) ? $_GET['strana']:"1");

                if($pocet_stran < 20){

                // strany pod 10

        echo '<div class="clearfix"></div>';
    echo '<div class="text-center">';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != 1 ? $aktualnastrana-1 : $aktualnastrana).$orderlink.'">&laquo;</a></li></ul>';
        echo ' <ul class="pagination">';
for ($i=1; $i<=$pocet_stran; $i++)
{
  if ($i<>$strana) {
        echo '<li><a href="'.$golink.'?strana='.$i.$orderlink.'">'.$i.'</a></li>';
  } else {
        echo '<li class="active"><a href="'.$golink.'?strana='.$i.$orderlink.'">'.$i.'</a></li>';
  }
}
        echo '</ul> ';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != $pocet_stran ? $aktualnastrana+1 : $aktualnastrana).$orderlink.'">&raquo;</a></li></ul>';
    echo '</div>';


        }else{

        //strany nad 10

        $strana1p = $aktualnastrana - 1;
        $strana2p = $aktualnastrana - 2;

        $strana1m = $aktualnastrana + 1;
        $strana2m = $aktualnastrana + 2;

        echo '<div class="clearfix"></div>';
        echo '<div class="text-center">';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != 1 ? $aktualnastrana-1 : $aktualnastrana).$orderlink.'">&laquo;</a></li></ul>';

         echo ' <ul class="pagination">';
        if($aktualnastrana >= 4){

          echo "<li><a href='".$golink."?strana=".$strana2p.$orderlink."' title='".$strana2p."'>".$strana2p."</a></li>";
          echo "<li><a href='".$golink."?strana=".$strana1p.$orderlink."' title='".$strana1p."'>".$strana1p."</a></li>";

        }

        echo '<li class="active"><a href="'.$golink.'?strana='.$aktualnastrana.$orderlink.'">'.$aktualnastrana.'</a></li>';

        if($aktualnastrana <= ($pocet_stran - 3)){

          echo "<li><a href='".$golink."?strana=".$strana1m.$orderlink."' title='".$strana1m."'>".$strana1m."</a></li>";
          echo "<li><a href='".$golink."?strana=".$strana2m.$orderlink."' title='".$strana2m."'>".$strana2m."</a></li>";

        }
                echo '</ul> ';


        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != $pocet_stran ? $aktualnastrana+1 : $aktualnastrana).$orderlink.'">&raquo;</a></li></ul>';
        echo '</div>';

                }
        }
}

if(isset($_GET["logout"])){

  setcookie("login", '',(time()-3600), "/", "", "0");
  $logged = 0;
  redirect("/");
}

if(isset($_COOKIE["login"]) == 0){

  setcookie("login", '',(time()-3600), "/", "", "0");
  $logged = 0;

}else{

  $login = explode(".",$_COOKIE['login']);
  $userquery = mysqli_query($link,"SELECT * FROM tvsee_users WHERE user_id='".mysqli_real_escape_string($link,$login[0])."'");
  $userinfo = mysqli_fetch_array($userquery);

  if($userinfo["user_pass"] == $login["1"] && $userinfo["user_id"] == $login["0"] ){
    $logged = 1;
  }else{
    $logged = 0;
    redirect("?logout");
  }

}


if(isset($_POST["email"]) && isset($_POST["userpass"])){

    $pass = hash('sha512',$_POST["userpass"]);
    $email = htmlspecialchars($_POST["email"]);

    $loginprogress = mysqli_query($link,"SELECT * FROM tvsee_users WHERE user_email='".$email."' AND user_pass='".$pass."'");
    $userinfo = mysqli_fetch_array($loginprogress);

    if(mysqli_num_rows($loginprogress) == 1){

      setcookie("login",$userinfo["user_id"].".".$pass,(time()+(3600*24*12*24)), "/", "", "0");
      redirect("/");
      $error = 0;

    }else{

      $error = 1;

    }


}

if($logged){
  mysqli_query($link,"UPDATE tvsee_users SET user_lastlog='".time()."' WHERE user_id='".$userinfo["user_id"]."'");
}
