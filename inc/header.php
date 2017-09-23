<?PHP if (!defined('PERM')) { die(); }

if(!$logged){
  if($onlylogged==1){redirect("/modudrzby.php");}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><? echo (isset($title) ? $title." - tvsee.eu":"Online seriály - tvsee.eu"); ?></title>
    <meta name="author" content="Copyright - TVSEE.eu"/>
    <meta name="description" content="Online seriály na jednom mieste! Sleduj zadarmo a neobmedzene."/>
    <meta name="keywords" content="online, serialy, tvsee, tv, free, zadarmo, serie, cz, sk, titulky"/>
    <meta name="robots" content="index, follow">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/default.css">
    <meta content="11698" name="wwwexasharecom">
  </head>
  <body>

<?php if($logged){ ?>

<div id="favoritedlist" style="background:#14161C;padding:20px 20px 0px 20px;display:none;">

<?php


$getserials = mysqli_query($link,"SELECT * FROM tvsee_favorite WHERE fav_userid='".$userinfo["user_id"]."'");
$favserialcount = mysqli_num_rows($getserials);

if($favserialcount >= 1){

$i = 1;
while($row = mysqli_fetch_assoc($getserials)){
    $favserials .= "(SELECT * FROM tvsee_epizody WHERE ep_serialid='".$row["fav_serialid"]."' ORDER BY ep_date DESC LIMIT 0,1) ".($favserialcount == $i ? "":"UNION ALL ");
    $i++;
}

$favserialis = mysqli_query($link,$favserials." ORDER BY ep_date DESC LIMIT 0,12");

if(mysqli_num_rows($favserialis) >= 1){

echo '<div class="row">';
while($data = mysqli_fetch_array($favserialis)) {
echo '
<div class="video col-lg-1 col-md-3 col-sm-2 col-xs-4">
    <a class="link" href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'">
        <div class="thumb">
            <img alt="'.$data["ep_season"].'-'.$data["ep_epizodeseason"].'" class="img-responsive" src="'.$data["ep_img"].'"/>
            <div class="duration">'.$data["ep_duration"].'</div>
            '.($data["ep_date"]>strtotime('-1 day') ? '<div class="new">Nové</div>':'').'
            <div class="videohover"></div>
        </div>
        <div class="title">
             <span class="titleserial">'.serialname($data["ep_serialid"]).'</span> Epizóda S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'<br>
        </div>
    </a>
</div>
';

}
echo '</div>';
}else{
echo '<div class="text-center">Žiadne obľúbené seriály.</div>';
}

}else{
echo '<div class="text-center">Žiadne obľúbené seriály.</div>';
}

?>
</div>
<?php } ?>

<nav class="navbar navbar-inverse" role="navigation">
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><strong>TV</strong>SEE.EU<sup>beta</sup></a>
    </div>


    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/">Hlavná stránka</a></li>
        <li><a href="/serialy">Všetky seriály</a></li>
        
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php if($logged){ ?>
        <li class="tooltipactive" data-toggle="tooltip" data-placement="bottom" data-title="Posledné epizódy z mojich obľúbených seriálov."><a href="#" id="showfavorited"><i class="fa fa-heart"></i></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><? echo $userinfo["user_name"]; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/manager">Administrácia</a></li>
            <li class="divider"></li>
            <li><a href="?logout">Odhlásiť</a></li>
          </ul>
        </li>
        <?php }else{ ?>
        <li><a href="/?register">Vytvoriť účet</a></li>
        <li>
                <a href="#login" class="dropdown-toggle" data-toggle="dropdown">Prihlásiť sa <span class="caret"></span></a>
                <div class="dropdown-menu dropdown-login">
                  <form action="" method="post">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            Prihlásenie
                        </div>
                        <div class="col-sm-12">
                            <input type="email" name="email" placeholder="Email" onclick="return false;" class="form-control input-sm"/>
                        </div>
                        <br/>
                        <div class="col-sm-12">
                            <input type="password" name="userpass" placeholder="Heslo" class="form-control input-sm" name="password"/>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success btn-sm">Prihlásiť</button>
                        </div>
                    </div>
                  </form>

        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>