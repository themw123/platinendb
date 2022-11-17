<?php

if ($currentpage == "index") {

  $hostname = gethostname();
  if ($hostname != "platinendb") {
    $style = "style=\"margin-bottom:-24px;\"";
  } else {
    $style = "";
  }

?>



  <nav class="navbar navbar-expand-md" <?php echo $style ?>>
    <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main-navigation">
      <ul class="navbar-nav">


      <?php
    }



    if ($currentpage == "registrierindex") {
      $hostname = gethostname();
      if ($hostname != "platinendb") {
        $style = "style=\"margin-bottom:-24px;\"";
      } else {
        $style = "";
      }
      ?>

        <nav class="navbar navbar-expand-md" <?php echo $style ?>>
          <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
          <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
            </ul>
          </div>
        </nav>

      <?php
    }

    if ($currentpage == "password_reset") {
      $hostname = gethostname();
      if ($hostname != "platinendb") {
        $style = "style=\"margin-bottom:-24px;\"";
      } else {
        $style = "";
      }
      ?>
        <nav class="navbar navbar-expand-md" <?php echo $style ?>>
          <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
          <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">

            <?php
          }

          if ($currentpage == "platinenindex") {

            $hostname = gethostname();
            if ($hostname != "platinendb") {
              $style = "style=\"margin-top:-24px;\"";
            } else {
              $style = "";
            }
            ?>

              <nav class="navbar navbar-expand-md" <?php echo $style ?>>
                <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
                <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main-navigation">
                  <?php
                  if (isUserAdmin($login_connection) == true) {
                  ?>
                    <ul class="navbar-nav mr-auto navbar1">
                      <li class="nav-item">
                        <a id="platinen" class="nav-link active rounded" href="#">Platinenaufträge</a>
                      </li>

                      <li class="nav-item">
                        <a id="nutzen" class="nav-link rounded" href="nutzenindex.php">Nutzen</a>
                      </li>

                      <li class="nav-item">
                        <a id="auswertung" class="nav-link rounded" href="auswertungindex.php">Auswertung</a>
                      </li>

                    </ul>
                  <?php
                  }
                  ?>

                  <ul class="navbar-nav navbar2">
                    <li class="nav-item">
                      <a id="benutzer" class="nav-link rounded" data-toggle="modal" data-target=#exampleModal href="#">Benutzer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link rounded" href="index.php?logout">Logout</a>
                    </li>
                  </ul>

                </div>
              </nav>
            <?php
          }

          if ($currentpage == "nutzenindex") {
            $hostname = gethostname();
            if ($hostname != "platinendb") {
              $style = "style=\"margin-top:-24px;\"";
            } else {
              $style = "";
            }
            ?>

              <nav class="navbar navbar-expand-md" <?php echo $style ?>>
                <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
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

                    <li class="nav-item">
                      <a id="auswertung" class="nav-link rounded" href="auswertungindex.php">Auswertung</a>
                    </li>

                  </ul>


                  <ul class="navbar-nav navbar2">
                    <li class="nav-item">
                      <a id="benutzer" class="nav-link rounded" data-toggle="modal" data-target=#exampleModal href="#">Benutzer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link rounded" href="index.php?logout">Logout</a>
                    </li>
                  </ul>

                </div>
              </nav>
            <?php
          }


          if ($currentpage == "auswertungindex") {
            $hostname = gethostname();
            if ($hostname != "platinendb") {
              $style = "style=\"margin-top:-24px;\"";
            } else {
              $style = "";
            }
            ?>

              <nav class="navbar navbar-expand-md" <?php echo $style ?>>
                <a href="https://etit.ruhr-uni-bochum.de/est/" title=""><img src="bilder/est.png" alt="" width="50" height="35"></a>
                <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main-navigation">


                  <ul class="navbar-nav mr-auto navbar1">
                    <li class="nav-item">
                      <a id="platinen" class="nav-link rounded" href="platinenindex.php">Platinenaufträge</a>
                    </li>
                    <li class="nav-item">
                      <a id="nutzen" class="nav-link rounded" href="nutzenindex.php">Nutzen</a>
                    </li>

                    <li class="nav-item">
                      <a id="auswertung" class="nav-link active rounded" href="#">Auswertung</a>
                    </li>

                  </ul>


                  <ul class="navbar-nav navbar2">
                    <li class="nav-item">
                      <a id="benutzer" class="nav-link rounded" data-toggle="modal" data-target=#exampleModal href="#">Benutzer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link rounded" href="index.php?logout">Logout</a>
                    </li>
                  </ul>

                </div>
              </nav>
            <?php

          }

            ?>