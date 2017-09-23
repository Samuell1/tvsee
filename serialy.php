<?php include("inc/settings.php"); $title = "Všetky seriály"; include("inc/header.php"); ?>

<div class="panel removebradius">
  <div class="container">
    <div class="row">

    <div class="col-md-9">
      <a href="?zoradit=nove" class="btn btn-default btn-sm">Najnovšie</a>
      <a href="?zoradit=sledovane" class="btn btn-default btn-sm">Najsledovanejšie</a>
    </div>

    <div class="col-md-3">
    <div class="right-inner-addon">
        <i class="fa fa-search"></i>
        <input type="search" class="form-control input-sm" placeholder="vyhľadávanie seriálov..."/>
    </div>
    </div>

    </div>
  </div>
</div>

<div class="container">

<div class="row">

<?php

if (isset($_GET['strana'])){
 $strana = (int)$_GET['strana'];

  if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}

if($_GET["zoradit"] == "sledovane"){
  $sort = "ORDER BY serial_views";
}else{
  $sort = "ORDER BY serial_date DESC";
}

$serialy_countall = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_serialy"));

  $limit = "14";
  $celkovy_pocet = $serialy_countall;
  $pocet_stran = ceil($celkovy_pocet/$limit);
  $pociatok = ($strana*$limit)-$limit;

    if($strana >$pocet_stran){redirect("/");} 

$serialy_vypis = mysqli_query($link,"SELECT * FROM tvsee_serialy $sort LIMIT $pociatok,$limit ");

while($data = mysqli_fetch_array($serialy_vypis)) {

echo '
<div class="col-md-3 col-sm-4">
    <a href="/serial/'.$data["serial_id"].'/'.bezd($data["serial_name"]).'">
      <div class="serialbox text-center">
            <img alt="'.$data["serial_name"].'" class="img-responsive" src="'.$data["serial_img"].'"/>
        <div class="title">
          '.$data["serial_name"].'
        </div>
        <div class="line hidden-xs"></div>
        <div class="desc hidden-xs">
          '.mb_substr($data["serial_desc"], 0, 90,"UTF-8").'...
        </div>
      </div>
    </a>
</div>
';

}

?>

</div>

<?php

if(isset($_GET["zoradit"]) && $_GET["zoradit"] == "sledovane"){$orderlink = "&zoradit=".$_GET["zoradit"];}else{ $orderlink = "";}

pagination($serialy_countall,$limit,$pocet_stran,$strana,"",$orderlink);

?>

</div>

<?php include("inc/footer.php"); ?>