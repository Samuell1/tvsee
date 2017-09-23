<?php include("inc/settings.php");

  if (!isset($_GET['name'])){ redirect("/"); }
  if (!ctype_digit($_GET['seria'])){ redirect("/"); }
  if (!ctype_digit($_GET['ep'])){ redirect("/"); }
  
  $d_serial = mysqli_query($link,"SELECT * FROM tvsee_serialy WHERE serial_seourl='".$_GET["name"]."'");
  $dataserial = mysqli_fetch_array($d_serial);

  $d_ep = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."' AND ep_season='".$_GET["seria"]."' AND ep_epizodeseason='".$_GET["ep"]."'");
  $dataepizoda = mysqli_fetch_array($d_ep);

  if (mysqli_num_rows($d_ep) != 1){ redirect("/"); }
  if (mysqli_num_rows($d_serial) != 1){ redirect("/"); }

  mysqli_query($link,"UPDATE tvsee_epizody SET ep_views=ep_views+1 WHERE ep_id='".$dataepizoda["ep_id"]."'");


$title = $dataserial["serial_name"]." - s".$dataepizoda["ep_season"]."e".$dataepizoda["ep_epizodeseason"];
include("inc/header.php");
include("inc/subheader-epizoda.php");

?>

<div class="container containerpanels">

<div class="panel">

    <div class="panel-title">
      <span>Ďalšie epizódy</span>
    </div>

<div class="row">

<?php
$serial_ep = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."'");


$serial_ep1 = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."' AND ep_season='".$dataepizoda["ep_season"]."' AND ep_epizodeseason>'".$dataepizoda["ep_epizodeseason"]."' ORDER BY ep_epizodeseason LIMIT 0,6");

if(mysqli_num_rows($serial_ep1) >= 1){
while($data = mysqli_fetch_array($serial_ep1)) {
echo '
<div class="video col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <a class="link" href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'">
        <div class="thumb">
            <img alt="img twd" class="img-responsive" src="'.$data["ep_img"].'"/>
            <div class="duration">'.$data["ep_duration"].'</div>
            <div class="videohover"></div>
        </div>
        <div class="title">

             '.serialname($data["ep_serialid"]).' S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'<br>
            <div class="desc">
              <span><i class="fa fa-calendar"></i> '.date("j. n. Y",$data["ep_date"]).'</span>
              <span><i class="fa fa-eye"></i> '.$data["ep_views"].'x</span>
            </div>

        </div>
    </a>
</div>
';

}
}else{

$checkseasons = mysqli_fetch_array(mysqli_query($link,"SELECT MAX(ep_season) AS countseasons,ep_serialid FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."'"));

echo '
<div class="text-center">Neboli nájdené žiadne ďalšie epizódy v sérií <strong>'.$dataepizoda["ep_season"].'</strong>.<br><br>
';
if($checkseasons["countseasons"] > $dataepizoda["ep_season"]){
echo '<a href="/epizoda/'.$dataserial["serial_seourl"].'/s0'.($dataepizoda["ep_season"]+1).'e01" class="btn btn-default text-block">Prejsť na ďalšiu sériu.</a>';
}
echo '
</div>
';
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