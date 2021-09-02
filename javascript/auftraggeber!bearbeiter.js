

//# sourceURL=formEditor.js
//Wenn Bei Nutzen oder Platine auf add Bearbeiter bzw Auftraggeber geklickt wird
$(document).ready(function(){ 

    var aktion2= "";
    if(aktionx.includes("Nutzen")) {
      var aktion = "bearbeiter";
      var ziel = "NU";
    }
    else {
      var aktion = "auftraggeber";
      var ziel = "Pl";
    }
    
    $.ajax({  
          url:"verarbeitung"+ziel+"/"+aktion+"/get"+aktion+".php",  
          method:"post",
          dataType: 'JSON',
          data:{aktion:aktion},
          success: function(response){
            
              $("#"+aktion).empty();
              $("#"+aktion).append('<option value="" disabled selected>Option wählen</option>');
              

              for(var i=0; i<response.length; i++){
                  name = response[i];
                  $("#"+aktion).append('<option value="' + name + '">' + name + '</option>')
              }

              if(aktionx.includes("hinzufügen")) {
                $('select option:contains("est")').prop('selected',true);
              } 
      } 
    });
});


//Wenn auf Bearbeiterbutton geklickt wird
$('.bearbeiterbutton').on( 'click', function () {
  $("#bearbeiterbutton").toggleClass("far fa-caret-square-down far fa-caret-square-up");
  $('#fehleraddbenutzer').hide();
});



function refresh1() {
  //Liste aktualisieren
  var aktionx = $(".modal-title").get(2).innerText;
  var name;
  if(aktionx.includes("Nutzen")) {
    aktion = "bearbeiter";
    var ziel = "NU";
  }
  else {
    aktion = "auftraggeber";
    var ziel = "Pl";
  }

  $.ajax({  
        url:"verarbeitung"+ziel+"/"+aktion+"/get"+aktion+".php",  
        method:"post",
        dataType: 'JSON',
        data:{aktion:aktion},
        success: function(response){

          $("#"+aktion).empty();
          $("#"+aktion).append('<option value="" disabled selected>Option wählen</option>');
          for(var i=0; i<response.length; i++){
              name = response[i];
              $("#"+aktion).append('<option value="' + name + '">' + name + '</option>')
          }
    } 
  });
  }




$('#add').on( 'click', function () {
    
    //hinzufügen
    var aktionx = $(".modal-title").get(2).innerText;
    var bearOderAuftr = document.getElementById("addBenutzer").value; 

    if(aktionx.includes("Nutzen")) {
      var aktion = "bearbeiter";
      var ziel = "NU";
      var col = "2";
    }
    else {
      var aktion = "auftraggeber";
      var ziel = "Pl";
      var col = "3";
    }

    if(bearOderAuftr.length > 0) {
      $("#add").attr("disabled", true);
      //$("#addbearbeiter").text("Bitte warten...");
      $.ajax({  
          url:"verarbeitung"+ziel+"/"+aktion+"/add"+aktion+".php",  
          method:"post",
          data:{bearOderAuftr:bearOderAuftr, aktion:aktion},
          dataType: 'JSON',
          success: function(data){

                var zustand = data.data; 
                var error = data.error;
                var inputfeld = document.getElementById("addBenutzer");
                if(zustand == "erfolgreich") {
                  inputfeld.value = '';
                  refresh1();
                  $('#collapse'+col).collapse("hide");
                  //$("#addbearbeiter").text("hinzufügen");
                }
                else{
                  document.getElementById("fehleraddbenutzer").innerHTML="Datenbankfehler: " + error;
                  $('#fehleraddbenutzer').show();
                }
        } 
      });
      setTimeout(function(){
        $("#add").attr("disabled", false);
    }, 500);
    }
});
  





$('#rem').on( 'click', function () {
    
  var aktionx = $(".modal-title").get(2).innerText;

  if(aktionx.includes("Nutzen")) {
    var aktion = "bearbeiter";
    var ziel = "NU";
    var col = "2";
  }
  else {
    var aktion = "auftraggeber";
    var ziel = "Pl";
    var col = "3";
  }


  var Objekt = document.getElementById(aktion);
  var Text = Objekt.options[Objekt.selectedIndex].text;
  var index = Objekt.options[Objekt.selectedIndex].index;


  if (index != 0) {
  $("#rem").attr("disabled", true);
  $.ajax({  
        url:"verarbeitung"+ziel+"/"+aktion+"/rem"+aktion+".php",  
        method:"post",
        data:{Text:Text, aktion:aktion},
        dataType: 'JSON',
        success: function(data){

            var zustand = data.data; 
            var error = data.error;
            if(zustand == "erfolgreich") {
              Objekt.remove(Objekt.selectedIndex);
              Objekt.selectedIndex = "0";
              refresh1();
              $('#collapse'+col).collapse("hide");
            }
            else {
              if(error.indexOf("foreign") >= 0) {
                document.getElementById("fehleraddbenutzer").innerHTML="Der Auftraggeber ist bereits einer Platine zugewiesen.";
              }
              else {
                document.getElementById("fehleraddbenutzer").innerHTML="Datenbankfehler: " + error;
              }
              $('#fehleraddbenutzer').show();
            }
    } 
  });
  setTimeout(function(){
    $("#rem").attr("disabled", false);
}, 500);
}
  

});







