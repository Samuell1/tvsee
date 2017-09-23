<?php if (!defined('PERM')) { die(); }


$bgimg = mysqli_fetch_array(mysqli_query($link,"SELECT serial_bgimg FROM tvsee_serialy ORDER BY RAND() LIMIT 1;"));


?>
<div class="subheader" style="background: #1b1d25 url(<? echo $bgimg["serial_bgimg"]; ?>) no-repeat center top;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">

<?php if(isset($_GET["register"]) && $logged == 0){ ?>
  <div class="container registercontainer subheader-serial">

<div class="text-center register">
<h2>Vytvoriť nový účet</h2>
Zaregistrujte sa a odomknú sa vám všetky funkcie. Komentovať epizódy, pridávať seriály k obľúbeným.
</div>

<?php
if(isset($_POST["register"])){

  $namereg = htmlspecialchars($_POST["name"]);
  $emailreg = htmlspecialchars($_POST["email"]);
  $passreg = htmlspecialchars($_POST["pass"]);
  $passreg2 = htmlspecialchars($_POST["passrepeat"]);

  if(isset($_POST["antispamreg"]) && $_POST["antispamreg"] == $_SESSION["control2"]){

  if(!preg_match('/[^A-Za-z0-9.#\\-$]/', $namereg)){

  $checkemail = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_users WHERE user_email='".mysqli_real_escape_string($link,$emailreg)."'"));
  $checkname = mysqli_num_rows(mysqli_query($link,"SELECT * FROM tvsee_users WHERE user_name='".mysqli_real_escape_string($link,$namereg)."'"));

  if($checkemail == 0 AND $checkname == 0){

  if (filter_var($emailreg, FILTER_VALIDATE_EMAIL)) {

  if($passreg == $passreg2){

    if(strlen($passreg) >= 6){

    if(strlen($namereg) >= 4){

      mysqli_query($link,"INSERT INTO tvsee_users (user_name, user_email, user_pass, user_datereg, user_ip)
      	 VALUES('".mysqli_real_escape_string($link,$namereg)."','".mysqli_real_escape_string($link,$emailreg)."','".hash('sha512',$passreg)."', '".time()."', '".$_SERVER["REMOTE_ADDR"]."') ");

    echo '<div class="text-center text-success">Úspešne ste sa zaregistrovali. Teraz sa môžete prihlásiť.</div>';

    }else{
    echo '<div class="text-center text-warning">Chyba: Dĺžka mena je kratšia ako 4 znaky.</div>';
    }

    }else{
    echo '<div class="text-center text-warning">Chyba: Dĺžka hesla je kratšia ako 6 znakov.</div>';
    }

  }else{
  echo '<div class="text-center text-warning">Chyba: Heslá sa nezhodujú.</div>';
  }

  }else{
  echo '<div class="text-center text-warning">Chyba: Zadajte správny email.</div>';
  }

  }else{
  echo '<div class="text-center text-warning">Chyba: Uživateľské meno alebo email už existuje.</div>';
  }

  }else{
  echo '<div class="text-center text-warning">Chyba: Uživateľské meno obsahuje nevyžiadané znaky.</div>';
  }
  
  }else{
  echo '<div class="text-center text-warning">Chyba: Zle vyplnený antispam.</div>';
  }

}

    $_SESSION["control2"] = $num1 + $num2;

?>

<form class="form-horizontal" method="post" action="?register">
  <div class="form-group">
    <label for="name" class="col-sm-offset-2 col-sm-2 control-label">Uživateľské meno:</label>
    <div class="col-sm-6">
    <input type="text" class="form-control" id="name" name="name" value="" required>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-offset-2 col-sm-2 control-label">Emailová adresa</label>
    <div class="col-sm-6">
    <input type="email" class="form-control" id="email" name="email" value="<? echo (isset($emailreg) ? $emailreg:"@"); ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="pass" class="col-sm-offset-2 col-sm-2 control-label">Heslo:</label>
    <div class="col-sm-6">
      <input class="form-control" name="pass" id="pass" type="password" required>
      <p class="help-block">Heslo musí obsahovať viacej ako 6 znakov.</p>
    </div>
  </div>
  <div class="form-group">
    <label for="passrepeat" class="col-sm-offset-2 col-sm-2 control-label">Heslo znova:</label>
    <div class="col-sm-6">
      <input class="form-control" name="passrepeat" id="passrepeat" type="password" required>
    </div>
  </div>
    <div class="form-group">
    <label for="antispamreg" class="col-sm-offset-2 col-sm-2 control-label">Antispam:</label>
    <div class="col-sm-4">
    <div class="input-group">
      <span class="input-group-addon"><? echo $num1; ?> + <? echo $num2; ?> =</span>
      <input name="antispamreg" class="form-control" placeholder="vypočítajte príklad" type="text">
    </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-6">
      <button type="submit" name="register" class="btn btn-default">Registrovať sa</button>
    </div>
  </div>
</form>

  </div>
<? }else{ ?>
  <div class="container text-center" style="padding-top: 250px;">

<div style="padding-bottom:30px;">
<h2>Online seriály zadarmo</h2>
Portál tvsee.eu ponúka možnosť sledovať seriály online zadarmo.

</div>
<div class="clearfix"></div>

<div class="row">
  <div class="col-md-4 col-sm-4 col-xs-4">
<i class="fa fa-play-circle fa-4x"></i>
<h3>Online</h3>
Seriály nie je potrebné sťahovať, môžete ich sledovať priamo
vo vašom internetovom prehliadači.
  </div>
  <div class="col-md-4 col-sm-4 col-xs-4">
<i class="fa fa-tasks fa-4x"></i>
<h3>Kvalita</h3>
V našom sortimente máme tie najlepšie svetové seriály
v štandartnej až vysokej kvalite.
  </div>
  <div class="col-md-4 col-sm-4 col-xs-4">
<i class="fa fa-dollar fa-4x"></i>
<h3>Zadarmo</h3>
Všetky online seriály, ktoré máme sú samozrejme zadarmo
a bez limitu, môžete pozerať ako dlho chcete.
  </div>
</div>

  </div>
<? } ?>

</div>