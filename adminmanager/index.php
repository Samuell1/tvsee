<?php 
include("../inc/settings.php");
if($logged && $userinfo["user_perm"] == 1){redirect("/");}
include("../inc/header.php");

$countviewsep = mysqli_fetch_array(mysqli_query($link,"SELECT SUM(ep_views) AS totalreads FROM tvsee_epizody"));
$countviewsser = mysqli_fetch_array(mysqli_query($link,"SELECT SUM(serial_views) AS totalreads FROM tvsee_serialy"));
?>
<div class="container containeradmin">
<div class="row">

<div class="col-md-3">
<?php include("managerpanel.php"); ?>
</div>

<div class="col-md-8">

<div class="row">

  <div class="col-md-6">
  <div class="panel">
    <div class="panel-title">
      <span>Aktivita dnešný deň</span>
    </div>
   	<ul class="list-group">
    	<li class="list-group-item">Prihlásený užívatelia <span class="badge"><? echo mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_users WHERE user_lastlog > ".strtotime(date('d-m-Y')))); ?></span></li>
    	<li class="list-group-item">Komentáre <span class="badge">0</span></li>
    	<li class="list-group-item">Moje pridané epizódy <span class="badge"><? echo mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_epizody WHERE ep_userid='".$userinfo["user_id"]."'")); ?></span></li>
  	</ul>
  </div>
  </div>

  <div class="col-md-6">
  <div class="panel">
    <div class="panel-title">
      <span>Štatistiky</span>
    </div>
    <ul class="list-group">
      <li class="list-group-item">Užívatelia <span class="badge"><? echo mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_users")); ?></span></li>
      <li class="list-group-item">Seriály <span class="badge"><? echo mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_serialy")); ?></span></li>
      <li class="list-group-item">Komentáre <span class="badge">0</span></li>
      <li class="list-group-item">Epizódy <span class="badge"><? echo mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_epizody")); ?></span></li>
      <li class="list-group-item">Pozretí epizód <span class="badge"><? echo $countviewsep["totalreads"]; ?></span></li>
      <li class="list-group-item">Pozretí seriálov <span class="badge"><? echo $countviewsser["totalreads"]; ?></span></li>
    </ul>
  </div>
  </div>

</div>

</div>

</div>
</div>

<?php include("../inc/footer.php"); ?>