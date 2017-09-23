<?php 
include("../inc/settings.php");
if($logged && $userinfo["user_perm"] == 1){redirect("/");}
include("../inc/header.php");


if(isset($_GET["del"])){
  mysqli_query($link,"DELETE FROM tvsee_serialy WHERE serial_id='".(int)$_GET["del"]."'");
  redirect("serialy");
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
      <span>Zoznam seriálov</span>
    </div>

<table class="table table-hover table-striped">

<?php
$tahaj = mysqli_query($link,"SELECT * FROM tvsee_serialy ORDER BY serial_name");

while($data = mysqli_fetch_array($tahaj)) {
echo '
<tr>
<td width="1%"><span class="btn btn-default btn-xs">'.$data["serial_id"].'</span></td>
<td><a href="/serial/'.$data["serial_id"].'/'.bezd($data["serial_name"]).'" target="_blank">'.$data["serial_name"].'</a></td>
<td>'.date("j. n. Y",$data["serial_date"]).'</td>
<td class="text-right">
<a href="?edit='.$data["serial_id"].'" class="btn btn-warning btn-sm">Upraviť</a>
<a href="?del='.$data["serial_id"].'" onclick="return confirm(\'Odstrániť seriál?\');" class="btn btn-warning btn-sm"><i class="fa fa-times"></i></a>
</td>

</tr>
';
}
?>

</table>

  </div>

<? if(isset($_GET["edit"])){ ?>

  <div class="panel">
    <div class="panel-title">
      <span>Upraviť seriál</span>
    </div>
<?php

$tahaj = mysqli_query($link,"SELECT * FROM tvsee_serialy WHERE serial_id='".(int)$_GET["edit"]."'");

$data = mysqli_fetch_array($tahaj);

  if(isset($_POST["sersave"])){

          mysqli_query($link,"UPDATE tvsee_serialy SET serial_seourl='".bezd(strip_tags($_POST["serialname"]))."',serial_name='".strip_tags($_POST["serialname"])."',serial_genre='".strip_tags($_POST["serialgenre"])."',serial_desc='".strip_tags($_POST["serialdesc"])."' WHERE serial_id='".(int)$_GET["edit"]."'");
          redirect("serialy");
    
  }

echo '
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label for="serialname" class="col-sm-3 control-label">Názov seriálu:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="serialname" name="serialname" value="'.$data["serial_name"].'" required>
    </div>
  </div>
  <div class="form-group">
    <label for="serialgenre" class="col-sm-3 control-label">Žáner:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="serialgenre" name="serialgenre" value="'.$data["serial_genre"].'" required>
    </div>
  </div>
  <div class="form-group">
    <label for="serialbgimg" class="col-sm-3 control-label">Obrázok pozadia:</label>
    <div class="col-sm-9">
      <input type="text" id="serialbgimg" name="serialbgimg" value="'.$data["serial_bgimg"].'" required>
      <p class="help-block">(1 344 px × 707 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label for="serialimg" class="col-sm-3 control-label">Obrázok:</label>
    <div class="col-sm-9">
      <input type="text" id="serialimg" name="serialimg" value="'.$data["serial_img"].'" required>
      <p class="help-block">(400 px × 200 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label for="serialdesc" class="col-sm-3 control-label">Popis seriálu:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="serialdesc" name="serialdesc" rows="6" required>'.$data["serial_desc"].'</textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <button type="submit" name="sersave" class="btn btn-default">Upraviť seriál</button>
    </div>
  </div>
</form>
';

?>
  </div>

<? }else{ ?>

  <div class="panel">
    <div class="panel-title">
      <span>Pridať seriál</span>
    </div>
<?php

	if(isset($_POST["seradd"])){


        $img1 = @file_get_contents($weburl."inc/resize.php?i=".$_FILES["serialbgimg"]["tmp_name"]."&w=1344&h=707");
        $img2 = @file_get_contents($weburl."inc/resize.php?i=".$_FILES["serialimg"]["tmp_name"]."&w=400&h=200");
        if($img1 AND $img2){

          $urlbgimg = "/file/bgserial/".bezd($_POST["serialname"]).".jpg";
          $urlimg = "/file/serial/".bezd($_POST["serialname"]).".jpg";
          file_put_contents("..".$urlbgimg, $img1);
          file_put_contents("..".$urlimg, $img2);

          mysqli_query($link,"INSERT INTO tvsee_serialy(serial_seourl,serial_name,serial_bgimg,serial_img,serial_genre,serial_desc,serial_date) 
                  VALUES('".bezd($_POST["serialname"])."','".strip_tags($_POST["serialname"])."','".$urlbgimg."','".$urlimg."','".strip_tags($_POST["serialgenre"])."','".strip_tags($_POST["serialdesc"])."','".time()."')");
          redirect("serialy");

        }
		
	}

echo '
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label for="serialname" class="col-sm-3 control-label">Názov seriálu:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="serialname" name="serialname" required>
    </div>
  </div>
  <div class="form-group">
    <label for="serialgenre" class="col-sm-3 control-label">Žáner:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="serialgenre" name="serialgenre" value="akcne, komedia, horor" required>
    </div>
  </div>
  <div class="form-group">
    <label for="serialbgimg" class="col-sm-3 control-label">Obrázok pozadia:</label>
    <div class="col-sm-9">
      <input type="file" id="serialbgimg" name="serialbgimg" required>
      <p class="help-block">(1 344 px × 707 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label for="serialimg" class="col-sm-3 control-label">Obrázok:</label>
    <div class="col-sm-9">
      <input type="file" id="serialimg" name="serialimg" required>
      <p class="help-block">(400 px × 200 px)</p>
    </div>
  </div>
  <div class="form-group">
    <label for="serialdesc" class="col-sm-3 control-label">Popis seriálu:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="serialdesc" name="serialdesc" rows="6" required></textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <button type="submit" name="seradd" class="btn btn-default">Pridať seriál</button>
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