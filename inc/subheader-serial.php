<?php if (!defined('PERM')) { die(); }

$countepserial = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_serialid='".$dataserial["serial_id"]."'"));

$favuser = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_favorite WHERE fav_serialid='".$dataserial["serial_id"]."' AND fav_userid='".$userinfo["user_id"]."'"));

?>
<div class="subheader subheaderserial" style="background: #1b1d25 url(<?php echo $dataserial["serial_bgimg"]; ?>) no-repeat center top;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">

  <div class="container text-center">

<div class="subheader-serial col-md-6 col-md-offset-3">
<h2><?php echo $dataserial["serial_name"]; ?></h2>
<div class="genre"><?php echo $dataserial["serial_genre"]; ?></div>
<?php echo $dataserial["serial_desc"]; ?>

	<div class="desc">
		<?php if($logged){ ?><a href="#favorite" class="btn btn-default btn-border favadd" id="favadd" <?php echo ($favuser==1 ? 'style="border-color:#e67e22"':'style="border-color:#2ecc71"'); ?> data-idserial="<?php echo $dataserial["serial_id"]; ?>" data-sfaved="<?php echo ($favuser==1 ? '1':'0'); ?>"><?php echo ($favuser==1 ? 'Odstrániť z obľúbených':'Pridať do obľúbených'); ?></a><?php } ?>
		<?php if($countepserial >= 1){ ?><a href="/epizoda/<?php echo $dataserial["serial_seourl"]; ?>/s01e01" class="btn btn-default btn-border">Začať sledovať</a><?php } ?>
	</div>

</div>

  </div>

</div>