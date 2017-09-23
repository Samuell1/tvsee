<?php 
include("../inc/settings.php");
if($logged && $userinfo["user_perm"] == 1){redirect("/");}
include("../inc/header.php");

if(isset($_GET["del"])){
  mysqli_query($link,"DELETE FROM tvsee_epizody WHERE ep_id='".(int)$_GET["del"]."'");

  $serdata =  mysqli_fetch_array(mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_id='".(int)$_GET["del"]."'"));
  unlink("..".$serdata["ep_img"]);
  redirect("epizody");
}


?>

<div class="container containeradmin">
<div class="row">

<div class="col-md-3">
<?php include("managerpanel.php"); ?>
</div>

<div class="col-md-9">

  <div class="panel">
    <div class="panel-title">
      <span>Zoznam epizód</span>
    </div>

<ul class="list-group" style="border:1px solid #ddd;border-top:0;">
<?php
$tahaj = mysqli_query($link,"SELECT * FROM tvsee_serialy");
while($datas = mysqli_fetch_array($tahaj)) {

$tahaj2 = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$datas["serial_id"]."'");
$checkseasons = mysqli_fetch_array(mysqli_query($link,"SELECT MAX(ep_season) AS countseasons,ep_serialid FROM tvsee_epizody WHERE ep_serialid='".$datas["serial_id"]."'"));

echo '
    <a class="list-group-item" data-toggle="collapse" href="#serial'.$datas["serial_id"].'">'.$datas["serial_name"].'
          <span class="pull-right">
            <span class="btn btn-default btn-xs">Série: '.($checkseasons["countseasons"] >= 1 ? $checkseasons["countseasons"]:"00").'</span> 
            <span class="btn btn-default btn-xs">Epizódy: '.mysqli_num_rows($tahaj2).'</span>
          </span>
        </a>

    <div id="serial'.$datas["serial_id"].'" class="panel-collapse collapse">

';

  for ($i=1; $i <= $checkseasons["countseasons"]; $i++) { 

echo '
<a class="list-group-item disabled" data-toggle="collapse" href="#serial'.$datas["serial_id"].'seria'.$i.'">>> Séria '.$i.'</a>

<div id="serial'.$datas["serial_id"].'seria'.$i.'" class="panel-collapse collapse">
<table class="table table-hover table-striped" style="margin-bottom:0;">

';

$tahaj3 = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$datas["serial_id"]."' AND ep_season='".(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i)."' ORDER BY ep_epizodeseason");
while($data = mysqli_fetch_array($tahaj3)) {
echo '
<tr>
<td width="1%"><span class="btn btn-default btn-xs">'.$data["ep_epizodeseason"].'</span></td>
<td><a href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'" target="_blank">'.serialname($data["ep_serialid"]).' S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'</a></td>
<td>'.date("j. n. Y",$data["ep_date"]).'</td>
<td class="text-right">
<a href="?edit='.$data["ep_id"].'#edit" class="btn btn-info btn-sm">Upraviť</a>
<a href="?del='.$data["ep_id"].'" onclick="return confirm(\'Odstrániť epizódu?\');" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
</td>

</tr>
';
}

echo '
</table>
</div>
';

}

echo '
    </div>
';

}


?>

</ul>

  </div>


<? if(isset($_GET["edit"])){ ?>

  <div class="panel" id="edit">
    <div class="panel-title">
      <span>Upraviť epizódu</span>
    </div>
<?php

$tahaj = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_id='".(int)$_GET["edit"]."'");

$data = mysqli_fetch_array($tahaj);

  if(isset($_POST["episodesave"])){

    if(isset($_POST["reupimg"])){

        $img = @file_get_contents($weburl."inc/resize.php?i=".$_POST["epizodaimg"]."&w=400&h=200");
        if($img){

          $urlimg = "/file/episode/".bezd(serialname($_POST["epizodaserialid"]))."-s".$_POST["epizodaseria"]."e".$_POST["epizodacislo"].".jpg";
          file_put_contents("..".$urlimg, $img);
        }
    }else{
      $urlimg = $_POST["epizodaimg"];
    }


          mysqli_query($link,"UPDATE tvsee_epizody SET ep_season='".$_POST["epizodaseria"]."',ep_epizodeseason='".$_POST["epizodacislo"]."',ep_duration='".$_POST["epizodaduration"]."',ep_desc='".strip_tags($_POST["epizodadesc"])."',ep_serialid='".$_POST["epizodaserialid"]."',ep_img='".$urlimg."',ep_source1='".strip_tags($_POST["zdrojurl1"])."',ep_source2='".strip_tags($_POST["zdrojurl2"])."',ep_source3='".strip_tags($_POST["zdrojurl3"])."',ep_source4='".strip_tags($_POST["zdrojurl4"])."',ep_sourcedesc1='".strip_tags($_POST["zdrojdesc1"])."',ep_sourcedesc2='".strip_tags($_POST["zdrojdesc2"])."',ep_sourcedesc3='".strip_tags($_POST["zdrojdesc3"])."',ep_sourcedesc4='".strip_tags($_POST["zdrojdesc4"])."' WHERE ep_id='".(int)$_GET["edit"]."'");
          redirect("?edit=".(int)$_GET["edit"]."&saved");
    
  }

  if(isset($_GET["saved"])){
  echo '<div class="alert alert-success">Epizóda bola uložená!</div>';
  }

echo '
<form class="form-horizontal" method="post" action="">

  <div class="form-group">
    <label class="col-sm-3 control-label">Číslovanie:</label>
<div class="row">
  <div class="col-xs-2">
    
    <select class="form-control" name="epizodaseria">
    <option value="'.$data["ep_season"].'" selected>'.$data["ep_season"].'</option>
';

for ($i=1; $i <= 30; $i++) { 
echo '<option value="'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'">S'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'</option>';
}

echo '
    </select>
  </div>
  <div class="col-xs-3">
    <select class="form-control" name="epizodacislo">
    <option value="'.$data["ep_epizodeseason"].'" selected>'.$data["ep_epizodeseason"].'</option>
';

for ($i=1; $i <= 30; $i++) { 
echo '<option value="'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'">E'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'</option>';
}

echo '
    </select>
  </div>
</div>
  </div>
  <div class="form-group">
    <label for="epizodaserialid" class="col-sm-3 control-label">Seriál:</label>
    <div class="col-sm-9">
    <select class="form-control" name="epizodaserialid">
    <option value="'.$data["ep_serialid"].'">'.serialname($data["ep_serialid"]).'</option>

';

$tahaj1 = mysqli_query($link,"SELECT * FROM tvsee_serialy ORDER BY serial_name");

while($data1 = mysqli_fetch_array($tahaj1)) {
echo '<option value="'.$data1["serial_id"].'">'.$data1["serial_name"].'</option>';
}

echo '
    </select>
    </div>
  </div>
  <div class="form-group">
    <label for="epizodaimg" class="col-sm-3 control-label">Obrázok odkaz:</label>
    <div class="col-sm-9">
    <input type="text" class="form-control" id="epizodaimg" name="epizodaimg" value="'.$data["ep_img"].'" required>      
      <p class="help-block">(400 px × 200 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label for="reupimg" class="col-sm-3 control-label">Reupload obrázku</label>
    <div class="col-sm-9">
      <div class="btn btn-default"><input type="checkbox" name="reupimg"></div>
      <p class="help-block pull-right">Pri zaškrtnutí sa nahrá nový obrázok (odkaz na nový obrázku zadajte v "Obrázok odkaz").</p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 1:</label>

<div class="row">
  <div class="col-xs-6">
    <input type="text" class="form-control" id="zdrojurl1" name="zdrojurl1" value="'.$data["ep_source1"].'" required>
    <p class="help-block">http://...embed-eo0zln1xvabg-<b>820</b>x<b>380</b>.html</p>
  </div>
  <div class="col-xs-2">
    <input type="text" class="form-control" id="zdrojdesc1" name="zdrojdesc1" value="'.$data["ep_sourcedesc1"].'" required>
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 2:</label>
<div class="row">
  <div class="col-xs-6">
    <input type="text" class="form-control" id="zdrojurl1" name="zdrojurl2" value="'.$data["ep_source2"].'">
  </div>
  <div class="col-xs-2">
    <input type="text" class="form-control" id="zdrojdesc1" name="zdrojdesc2" value="'.$data["ep_sourcedesc2"].'">
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 3:</label>
<div class="row">
  <div class="col-xs-6">
    <input type="text" class="form-control" id="zdrojurl1" name="zdrojurl3" value="'.$data["ep_source3"].'">
  </div>
  <div class="col-xs-2">
    <input type="text" class="form-control" id="zdrojdesc1" name="zdrojdesc3" value="'.$data["ep_sourcedesc3"].'">
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 4:</label>
<div class="row">
  <div class="col-xs-6">
    <input type="text" class="form-control" id="zdrojurl1" name="zdrojurl4" value="'.$data["ep_source4"].'">
  </div>
  <div class="col-xs-2">
    <input type="text" class="form-control" id="zdrojdesc1" name="zdrojdesc4" value="'.$data["ep_sourcedesc4"].'">
  </div>
</div>
  </div>
  <div class="form-group">
    <label for="epizodaduration" class="col-sm-3 control-label">Dĺžka epizódy:</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="epizodaduration" name="epizodaduration" value="'.$data["ep_duration"].'" required>
    </div>
  </div>

  <div class="form-group">
    <label for="epizodadesc" class="col-sm-3 control-label">Popis epizody:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="epizodadesc" name="epizodadesc" rows="4">'.$data["ep_desc"].'</textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <button type="submit" name="episodesave" class="btn btn-default">Uložiť epizódu</button>
    </div>
  </div>
</form>
';

?>
  </div>

<? }else{ ?>

  <div class="panel">
    <div class="panel-title">
      <span>Pridať epizódu</span>
    </div>
<?php

	if(isset($_POST["episodeadd"])){

    $over = mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$_POST["epizodaserialid"]."' AND ep_season='".$_POST["epizodaseria"]."' AND ep_epizodeseason='".$_POST["epizodacislo"]."'");


    if(mysqli_num_rows($over) == 0){
        $img = @file_get_contents($weburl."inc/resize.php?i=".$_POST["epizodaimg"]."&w=400&h=200");
        if($img){

          $urlimg = "/file/episode/".bezd(serialname($_POST["epizodaserialid"]))."-s".$_POST["epizodaseria"]."e".$_POST["epizodacislo"].".jpg";
          file_put_contents("..".$urlimg, $img);

          mysqli_query($link,"INSERT INTO tvsee_epizody(ep_season,ep_epizodeseason,ep_duration,ep_img,ep_desc,ep_date,ep_userid,ep_serialid,ep_source1,ep_source2,ep_source3,ep_source4,ep_sourcedesc1,ep_sourcedesc2,ep_sourcedesc3,ep_sourcedesc4) 
                  VALUES('".$_POST["epizodaseria"]."','".$_POST["epizodacislo"]."','".$_POST["epizodaduration"]."','".$urlimg."','".strip_tags($_POST["epizodadesc"])."','".time()."','".$userinfo["user_id"]."','".$_POST["epizodaserialid"]."','".$_POST["zdrojurl1"]."','".$_POST["zdrojurl2"]."','".$_POST["zdrojurl3"]."','".$_POST["zdrojurl4"]."','".$_POST["zdrojdesc1"]."','".$_POST["zdrojdesc2"]."','".$_POST["zdrojdesc3"]."','".$_POST["zdrojdesc4"]."')");
          redirect("epizody");

        }else{
        echo '<div class="alert alert-danger">Chyba: Problém pri duplikovaní obrázku...</div>';
        }
		}else{
    echo '<div class="alert alert-danger">Chyba: Epizóda pre '.serialname($_POST["epizodaserialid"]).' s číslom s'.$_POST["epizodaseria"].'e'.$_POST["epizodacislo"].' už existuje!</div>';
    }

	}

echo '
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">

  <div class="form-group">
    <label class="col-sm-3 control-label">Číslovanie:</label>
<div class="row">
  <div class="col-xs-2">
    <select class="form-control" name="epizodaseria">
';

for ($i=1; $i <= 30; $i++) { 
echo '<option value="'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'">S'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'</option>';
}

echo '
    </select>
  </div>
  <div class="col-xs-3">
    <select class="form-control" name="epizodacislo">
';

for ($i=1; $i <= 30; $i++) { 
echo '<option value="'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'">E'.(in_array($i, array(1,2,3,4,5,6,7,8,9)) ? "0".$i : $i).'</option>';
}

echo '
    </select>
  </div>
</div>
  </div>
  <div class="form-group">
    <label for="epizodaserialid" class="col-sm-3 control-label">Seriál:</label>
    <div class="col-sm-9">
    <select class="form-control" name="epizodaserialid">
';

$tahaj = mysqli_query($link,"SELECT * FROM tvsee_serialy ORDER BY serial_name");

while($data = mysqli_fetch_array($tahaj)) {
echo '<option value="'.$data["serial_id"].'">'.$data["serial_name"].'</option>';
}

echo '
    </select>
    </div>
  </div>
  <div class="form-group">
    <label for="epizodaimg" class="col-sm-3 control-label">Obrázok odkaz:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="epizodaimg" name="epizodaimg" placeholder="http://..." value="'.(isset($_POST["epizodaimg"]) ? $_POST["epizodaimg"]:"").'" required>
      <p class="help-block">(400 px × 200 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 1:</label>
<div class="row">
  <div class="col-xs-6">
  	<input type="text" class="form-control" id="zdrojurl1" name="zdrojurl1" placeholder="odkaz" value="'.(isset($_POST["zdrojurl1"]) ? $_POST["zdrojurl1"]:"http://streamin.to/embed--820x380.html").'" required>
    <p class="help-block">http://...embed-123123-<b>820</b>x<b>380</b>.html</p>
  </div>
  <div class="col-xs-2">
  	<input type="text" class="form-control" id="zdrojdesc1" name="zdrojdesc1" value="'.(isset($_POST["zdrojdesc1"]) ? $_POST["zdrojdesc1"]:"").'" placeholder="CZ dab, CZ tit,..." required>
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 2:</label>
<div class="row">
  <div class="col-xs-6">
  	<input type="text" class="form-control" id="zdrojurl2" name="zdrojurl2" value="'.(isset($_POST["zdrojurl2"]) ? $_POST["zdrojurl2"]:"").'" placeholder="odkaz">
  </div>
  <div class="col-xs-2">
  	<input type="text" class="form-control" id="zdrojdesc2" name="zdrojdesc2" value="'.(isset($_POST["zdrojdesc2"]) ? $_POST["zdrojdesc2"]:"").'" placeholder="CZ dab, CZ tit,...">
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 3:</label>
<div class="row">
  <div class="col-xs-6">
  	<input type="text" class="form-control" id="zdrojurl3" name="zdrojurl3" value="'.(isset($_POST["zdrojurl3"]) ? $_POST["zdrojurl3"]:"").'" placeholder="odkaz">
  </div>
  <div class="col-xs-2">
  	<input type="text" class="form-control" id="zdrojdesc3" name="zdrojdesc3" value="'.(isset($_POST["zdrojdesc3"]) ? $_POST["zdrojdesc3"]:"").'" placeholder="CZ dab, CZ tit,...">
  </div>
</div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Zdroj 4:</label>
<div class="row">
  <div class="col-xs-6">
  	<input type="text" class="form-control" id="zdrojurl4" name="zdrojurl4" value="'.(isset($_POST["zdrojurl4"]) ? $_POST["zdrojurl4"]:"").'" placeholder="odkaz">
  </div>
  <div class="col-xs-2">
  	<input type="text" class="form-control" id="zdrojdesc4" name="zdrojdesc4" value="'.(isset($_POST["zdrojurl4"]) ? $_POST["zdrojurl4"]:"").'" placeholder="CZ dab, CZ tit,...">
  </div>
</div>
  </div>
  <div class="form-group">
    <label for="epizodaduration" class="col-sm-3 control-label">Dĺžka epizódy:</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="epizodaduration" name="epizodaduration" value="'.(isset($_POST["epizodaduration"]) ? $_POST["epizodaduration"]:"").'" placeholder="HODINY:MINUTY:SEKUNDY" required>
    </div>
  </div>

  <div class="form-group">
    <label for="epizodadesc" class="col-sm-3 control-label">Popis epizody:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="epizodadesc" name="epizodadesc" rows="4"></textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <button type="submit" name="episodeadd" class="btn btn-default">Pridať epizódu</button>
    </div>
  </div>
</form>
';

?>
  </div>

<? } ?>


</div>

</div>
</div>

<?php include("../inc/footer.php"); ?>