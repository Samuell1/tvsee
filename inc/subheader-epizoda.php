<?php if (!defined('PERM')) { die(); }  ?>

<div class="subheader subheaderepizoda" style="background: #1b1d25 url(<?php echo $dataserial["serial_bgimg"]; ?>) no-repeat center top;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">

  <div class="container text-center">

<div class="subheader-serial col-md-6 col-md-offset-3">
<h2><?php echo $dataserial["serial_name"]; ?> S<?php echo $dataepizoda["ep_season"]; ?>E<?php echo $dataepizoda["ep_epizodeseason"]; ?></h2>
<div class="desc"><?php echo $dataepizoda["ep_desc"]; ?></div>
</div>

<div id="cinemabg"></div>
<div role="tabpanel">

  <div class="tab-content">
<?php
for ($i=1; $i <= 4; $i++) {

	if($dataepizoda["ep_source$i"] != ""){
echo '
    <div role="tabpanel" class="tab-pane '.($i == 1 ? 'active in':'').'" id="source'.$i.'">
    	<div style="background:rgba(0,0,0,.8);padding:10px;display:inline-block;z-index:100;position:relative;">
    	<IFRAME style="z-index:150;" SRC="'.$dataepizoda["ep_source$i"].'" allowfullscreen="true" width="820" height="380" FRAMEBORDER=0 SCROLLING=NO></IFRAME>
    	</div>
    </div>
';
	}

}
?>
  </div>

  <ul class="nav nav-tabs subheadersource">

<?php
for ($i=1; $i <= 4; $i++) {

	if($dataepizoda["ep_source$i"] != ""){
echo '
    	<li '.($i == 1 ? 'class="active"':'').'><a href="#source'.$i.'" data-toggle="tab">Zdroj '.$i.' ('.$dataepizoda["ep_sourcedesc$i"].')</a></li>
';
	}

}
?>
      <li class="pull-right"><a id="cinemamod" href="#">Kino mód</a></li>
  </ul>

</div>

  </div>

</div>