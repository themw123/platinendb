<?php

if ($currentpage == "index") {

    $hostname = $host= gethostname();
    if($hostname == "DESKTOP-4HFA8OJ") {
      $style="style=\"margin-bottom:-24px;\"";
    }
    else {
      $style = "";
    }

    echo'
    <nav class="navbar navbar-expand-md" '.$style.'">
    <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png"alt="" width="50" height="35"></a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main-navigation">
    <ul class="navbar-nav">
    ';
}


if ($currentpage == "registrierindex") {
    $hostname = $host= gethostname();
    if($hostname == "DESKTOP-4HFA8OJ") {
      $style="style=\"margin-bottom:-24px;\"";
    }
    else {
      $style = "";
    }

    echo'
    <nav class="navbar navbar-expand-md" '.$style.'">
    <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png"alt="" width="50" height="35"></a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main-navigation">
    <ul class="navbar-nav">
    </ul>
    </div>
    </nav>
    ';
}

if ($currentpage == "password_reset") {
    $hostname = $host= gethostname();
    if($hostname == "DESKTOP-4HFA8OJ") {
      $style="style=\"margin-bottom:-24px;\"";
    }
    else {
      $style = "";
    }

    echo'
    <nav class="navbar navbar-expand-md" '.$style.'">
    <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png"alt="" width="50" height="35"></a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main-navigation">
    <ul class="navbar-nav">
    ';
}

if ($currentpage == "platinenindex") {
    
    $hostname = $host= gethostname();
    if($hostname == "DESKTOP-4HFA8OJ") {
      $style="style=\"margin-top:-24px;\"";
    }
    else {
      $style = "";
    }
  
    echo'
    <nav class="navbar navbar-expand-md" '.$style.'">
    <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png"alt="" width="50" height="35"></a>
      <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="main-navigation">

      ';

      if (isUserEst($login_connection) == true) {
      echo'
      <ul class="navbar-nav mr-auto navbar1">
      <li class="nav-item">
      <a id="platinen" class="nav-link active rounded" href="#">Platinenaufträge</a>
      </li>
  
      <li class="nav-item">
      <a id="nutzen" class="nav-link rounded" href="nutzenindex.php">Nutzen</a>
      </li>
      </ul>
      ';
      }

      
      echo'

      <ul class="navbar-nav navbar2">
      <li class="nav-item">
      <a  id="benutzer" class="nav-link rounded" data-toggle="modal" data-target=#exampleModal  href="#">Benutzer</a>
      </li>
      <li class="nav-item">
      <a class="nav-link rounded" href="index.php?logout">Logout</a>
      </li>
      </ul>

      </div>
    </nav>
    ';
}

if ($currentpage == "nutzenindex") {
      $hostname = $host= gethostname();
      if($hostname == "DESKTOP-4HFA8OJ") {
        $style="style=\"margin-top:-24px;\"";
      }
      else {
        $style = "";
      }

      echo'
      <nav class="navbar navbar-expand-md" '.$style.'">
       <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png"alt="" width="50" height="35"></a>
       <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
  

        <ul class="navbar-nav mr-auto navbar1">
        <li class="nav-item">
        <a id="platinen" class="nav-link rounded" href="platinenindex.php">Platinenaufträge</a>
        </li>
        <li class="nav-item ">
        <a id="nutzen" class="nav-link active rounded" href="#">Nutzen</a>
        </li>
        </ul>
  

        <ul class="navbar-nav navbar2">
        <li class="nav-item">
        <a id="benutzer" class="nav-link rounded" data-toggle="modal" data-target=#exampleModal  href="#">Benutzer</a>
        </li>
        <li class="nav-item">
        <a class="nav-link rounded" href="index.php?logout">Logout</a>
        </li>
        </ul>
  
        </div>
      </nav>
      ';
}
?>
