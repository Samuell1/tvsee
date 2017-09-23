<?php include("inc/settings.php"); include("inc/header.php"); ?>

<?php include("inc/subheader-index.php"); ?>


<div class="container containerpanels">

<div class="panel">
    <div class="panel-title">
      <span>Najnovšie epizódy</span>
    </div>

<div class="row">

<?php
$tahaj = mysqli_query($link,"SELECT * FROM tvsee_epizody ORDER BY ep_date DESC LIMIT 0,12");

if(mysqli_num_rows($tahaj) >= 1){
while($data = mysqli_fetch_array($tahaj)) {
echo '
<div class="video col-lg-3 col-md-3 col-sm-4 col-xs-6">
    <a class="link" href="/epizoda/'.bezd(serialname($data["ep_serialid"])).'/s'.$data["ep_season"].'e'.$data["ep_epizodeseason"].'">
        <div class="thumb">
            <img alt="'.$data["ep_season"].'-'.$data["ep_epizodeseason"].'" class="img-responsive" src="'.$data["ep_img"].'"/>
            <div class="duration">'.$data["ep_duration"].'</div>
            '.($data["ep_date"]>strtotime('-1 day') ? '<div class="new">Nové</div>':'').'
            <div class="videohover"></div>
        </div>
        <div class="title">

             <span class="titleserial">'.serialname($data["ep_serialid"]).'</span> Epizóda S'.$data["ep_season"].'E'.$data["ep_epizodeseason"].'<br>
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
echo '<div class="text-center">Pre tento seriál neboli nájdené žiadne epizódy.</div>';
}

?>

</div>

</div>

<div class="row">

<div class="col-md-6">
  <div class="panel">
    <div class="panel-title">
      <span>Najnovšie seriály</span>
      <a href="/serialy" class="btn btn-default btn-sm pull-right hidden-xs">Všetky seriály</a>
    </div>

<div class="row">

<?php
$tahaj = mysqli_query($link,"SELECT * FROM tvsee_serialy ORDER BY serial_date DESC LIMIT 0,6");

while($data = mysqli_fetch_array($tahaj)) {

echo '
<div class="video col-md-4 col-sm-4 col-xs-6">
    <a class="link" href="/serial/'.$data["serial_id"].'/'.bezd($data["serial_name"]).'">
        <div class="thumb">
            <img alt="'.$data["serial_name"].'" class="img-responsive" src="'.$data["serial_img"].'"/>
        </div>
        <div class="title text-center">
             '.$data["serial_name"].'
        </div>
    </a>
</div>
';

}

?>

</div>

  </div>
</div>

<div class="col-md-6">
  <div class="panel">
    <div class="panel-title">
      <span>Komunita na Facebooku</span>
    </div>
    <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FOnline-seri%25C3%25A1ly-TVSEEeu%2F1553504594895498%3Fsk%3Dinfo%26tab%3Dpage_info&amp;width&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:200px;width:100%;" allowTransparency="true"></iframe>

  </div>

  <div class="panel">
    <div class="panel-title">
      <span>Blog</span>
      <a href="/blog" class="btn btn-default btn-sm pull-right hidden-xs">Prejsť na blog</a>
    </div>

      <div class="panel panel-blog">
                <div class="panel-heading">
                    <h3>Pridaný nový seriál WalkingDead</h3>
                    <h5><span>Samuell</span> - <span>27. 11. 2014</span> </h5>
                </div>
                <div class="panel-body">
                    <p>Do people born in 2000 get to choose if they are Generation Y or Generation Z? How do you decide what generation y you decide what generation you wantou want to belong to?</p>
                </div>
      </div>
      <div class="panel panel-blog">
                <div class="panel-heading">
                    <h3>Uprávy v designu stránky</h3>
                    <h5><span>Samuell</span> - <span>27. 11. 2014</span> </h5>
                </div>
                <div class="panel-body">
                    <p>Do people born in 2000 get to choose if they are Generation Y or Generation Z? How do yoo?</p>
                </div>
      </div>

  </div>
</div>

</div>

</div>

<?php include("inc/footer.php"); ?>