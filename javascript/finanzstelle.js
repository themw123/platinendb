
$(document).ready(function () {
  getFinanz();
});

$(".finanzbutton").on("click", function () {
  $("#finanzbutton").toggleClass(
    "far fa-caret-square-down far fa-caret-square-up"
  );
  $("#fehleraddfinanz").hide();

  if (!$("#collapse5").hasClass("show")) {
    $(".finanzdiv").addClass("finanzAn");
    $(".finanzdiv").removeClass("finanzAus");
  } else {
    $(".finanzdiv").removeClass("finanzAn");
    $(".finanzdiv").addClass("finanzAus");
  }
});

function getFinanz() {
  //Liste aktualisieren
  var namee;

  var aktion = "finanzGet";

  $.ajax({
    url: "verarbeitungPl/finanzstelle/getFinanz.php",
    method: "post",
    dataType: "JSON",
    data: { aktion: aktion },
    success: function (response) {
      var aktion = "finanz";

      $("#" + aktion).empty();

      //wird geholt aus Modal -> #finanz -> zweiter klassenname
      $auftraggeberDefault = $("#" + aktion).attr("class");
      $auftraggeberDefault = $auftraggeberDefault.replace("form-control ", "");

      for (var i = 0; i < response.length; i++) {
        selected = "";

        id = response[i][0];
        namee = response[i][1];

        if (namee == $auftraggeberDefault) {
          selected = "selected";
        }

        $("#" + aktion).append(
          "<option value='" + id + "' " + selected + ">" + namee + "</option>"
        );
      }

      //$("#auftraggeber").selectpicker("refresh");
      //selectpicker hat einen bug, refresh führt zu doppelten einträgen
      //deshalb muss refresh wie folgt selber ausgelöst werden
      $("#finanz").selectpicker("destroy");
      $("#finanz").selectpicker({
        size: 10,
      });
    },
  });
}

$("#add3").on("click", function () {
  //hinzufügen
  var aktion = "finanz";
  var addFinanz1 = document.getElementById("addFinanz1").value;
  var addFinanz2 = document.getElementById("addFinanz2").value;

  var col = "5";

  if (addFinanz1.length <= 0 || addFinanz2.length <= 0) {
    document.getElementById("fehleraddfinanz").innerHTML =
      "Bitte gibt den Namen und die Nummer der Finanzstelle ein";
    $("#fehleraddfinanz").show();
    return;
  }

  if (addFinanz2.length < 10) {
    document.getElementById("fehleraddfinanz").innerHTML =
      "Die Nummer muss 10 Stellig sein.";
    $("#fehleraddfinanz").show();
    return;
  }

  if (addFinanz2.match(/^[0-9]+$/) == null) {
    document.getElementById("fehleraddfinanz").innerHTML =
      "Die Nummer darf nur Zahlen enthalten.";
    $("#fehleraddfinanz").show();
    return;
  }

  $("#add3").attr("disabled", true);
  //$("#addbearbeiter").text("Bitte warten...");
  $.ajax({
    url: "verarbeitungPl/finanzstelle/addFinanz.php",
    method: "post",
    data: { addFinanz1: addFinanz1, addFinanz2: addFinanz2, aktion: aktion },
    dataType: "JSON",
    success: function (data) {
      var zustand = data.data;
      var error = data.error;
      var inputfeld1 = document.getElementById("addFinanz1");
      var inputfeld2 = document.getElementById("addFinanz2");

      if (zustand == "erfolgreich") {
        inputfeld1.value = "";
        inputfeld2.value = "";
        $(".finanzdiv").removeClass("finanzAn");
        $(".finanzdiv").addClass("finanzAus");
        getFinanz();
        $("#collapse" + col).collapse("hide");
        //$("#addbearbeiter").text("hinzufügen");
      } else {
        document.getElementById("fehleraddfinanz").innerHTML =
          "Datenbankfehler: " + error;
        $("#fehleraddfinanz").show();
      }
    },
  });
  setTimeout(function () {
    $("#add3").attr("disabled", false);
  }, 500);
});

$("#rem3").on("click", function () {
  var aktion = "finanz";
  var col = "5";

  var Objekt = document.getElementById(aktion);
  var Text = Objekt.options[Objekt.selectedIndex].value;
  //var index = Objekt.options[Objekt.selectedIndex].index;

  if (Text != "Option wählen") {
    $("#rem3").attr("disabled", true);
    $.ajax({
      url: "verarbeitungPl/finanzstelle/remFinanz.php",
      method: "post",
      data: { Text: Text, aktion: aktion },
      dataType: "JSON",
      success: function (data) {
        var zustand = data.data;
        var error = data.error;
        if (zustand == "erfolgreich") {
          Objekt.remove(Objekt.selectedIndex);
          Objekt.selectedIndex = "0";
          $(".finanzdiv").removeClass("finanzAn");
          $(".finanzdiv").addClass("finanzAus");
          getFinanz();
          $("#collapse" + col).collapse("hide");
        } else {
          if (error.indexOf("foreign") >= 0) {
            document.getElementById("fehleraddfinanz").innerHTML =
              "Die Finanzstelle ist bereits einer Platine zugewiesen.";
          } else {
            document.getElementById("fehleraddfinanz").innerHTML =
              "Datenbankfehler: " + error;
          }

          $("#fehleraddfinanz").show();
        }
      },
    });
    setTimeout(function () {
      $("#rem3").attr("disabled", false);
    }, 500);
  } else {
    document.getElementById("fehleraddfinanz").innerHTML =
      "Bitte wähle eine Finanzstelle aus";
    $("#fehleraddfinanz").show();
  }
});
