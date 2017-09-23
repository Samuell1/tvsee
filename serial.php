<?php include("inc/settings.php");

  if (!ctype_digit($_GET['id'])){ redirect("/"); }

  $d_serial = mysqli_query($link,"SELECT * FROM tvsee_serialy WHERE serial_id='".(int)$_GET["id"]."'");
  $dataserial = mysqli_fetch_array($d_serial);

  if(mysqli_num_rows($d_serial) == 0){redirect("/");}
  if(isset($_GET["name"]) && $_GET["name"] != bezd($dataserial["serial_name"])){
  redirect("serial/".$dataserial["serial_id"]."/".bezd($dataserial["serial_name"]));
  }

  mysqli_query($link,"UPDATE tvsee_serialy SET serial_views=serial_views+1 WHERE serial_id='".$dataserial["serial_id"]."'");

$title = $dataserial["serial_name"];
include("inc/header.php");
include("inc/subheader-serial.php");

?>

<div class="container containerpanels">

<div class="panel">
    <div class="panel-title">
      <span>Najnovšie epizódy</span>
    </div>

<div class="row">

<?php
$serial_ep = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."' ORDER BY ep_season DESC,ep_epizodeseason DESC LIMIT 0,8");

if(mysqli_num_rows($serial_ep) >= 1){
while($data = mysqli_fetch_array($serial_ep)) {
echo '
<div class="video col-lg-3 col-md-3 col-sm-4 col-xs-6">
    <a class="link" href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'">
        <div class="thumb">
            <img alt="img twd" class="img-responsive" src="'.$data["ep_img"].'"/>
            <div class="duration">'.$data["ep_duration"].'</div>
            <div class="videohover"></div>
        </div>
        <div class="title">

             <span class="titleserial">'.serialname($data["ep_serialid"]).'</span> Epizóda S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'<br>
            <div class="desc">
              <span><i class="fa fa-calendar"></i> '.date("j. n. Y",$data["ep_serialid"]).'</span>
              <span><i class="fa fa-eye"></i> '.$data["ep_views"].'x</span>
            </div>

        </div>
    </a>
</div>
';

}
}else{
echo '<div class="text-center">Pre tento seriál neboli nájdené žiadne epizódy.</div>';
}

?>
</div>

</div>


  <div class="panel">
    <div class="panel-title">
      <span>Série pre seriál <?php echo $dataserial["serial_name"]; ?></span>
    </div>

<?php if(mysqli_num_rows($serial_ep) >= 1){ ?>

<ul class="nav nav-pills nav-stacked col-md-2">
<?php

  $checkseasons = mysqli_fetch_array(mysqli_query($link,"SELECT MAX(ep_season) AS countseasons,ep_serialid FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."'"));

  for ($i=1; $i <= $checkseasons["countseasons"]; $i++) { 
    echo '<li '.($i == 1 ? 'class="active"':'').'><a href="#seria'.$i.'" data-toggle="pill">'.$i.'. séria</a></li>';
  }

?>
</ul>
<div class="tab-content col-md-10">
<?php

  for ($i=1; $i <= $checkseasons["countseasons"]; $i++) { 

echo '
        <div class="tab-pane fade '.($i == 1 ? 'active in':'').'" id="seria'.$i.'">
             <h4>Epizódy '.$i.'. série</h4><br>
        <div class="row">
';
$serial_ep = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."' AND ep_season='".(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i)."' ORDER BY ep_epizodeseason");
while($data = mysqli_fetch_array($serial_ep)) {
echo '
<div class="video col-lg-3 col-md-3 col-sm-4 col-xs-6">
    <a class="link" href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'">
        <div class="thumb">
            <img alt="img twd" class="img-responsive" src="'.$data["ep_img"].'"/>
            <div class="duration">'.$data["ep_duration"].'</div>
            <div class="videohover"></div>
        </div>
        <div class="title">
             '.serialname($data["ep_serialid"]).' S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'<br>
        </div>
    </a>
</div>
';

}
echo '
        </div>
        </div>
';

  }

?>

</div>
<div class="clearfix"></div>

<?php 
}else{
echo '<div class="text-center">Pre tento seriál neboli nájdené žiadne epizódy.</div>';
}
?>

  </div>

</div>

<?php include("inc/footer.php"); ?>