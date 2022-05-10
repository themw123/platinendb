//# sourceURL=formEditor.js

//Wenn Bei Nutzen oder Platine auf add Bearbeiter bzw Auftraggeber geklickt wird
$(document).ready(function(){ 


  
  if(aktionx.includes("Nutzen")) {
    aktion = "bearbeiter";
    ziel = "NU";
  }
  else {
    aktion = "auftraggeber";
    ziel = "Pl";
  }


  getAuftraggeberOrBearbeiter(true);


});


//Wenn auf Bearbeiterbutton geklickt wird
$('.bearbeiterbutton').on( 'click', function () {
  
  $("#bearbeiterbutton").toggleClass("far fa-caret-square-down far fa-caret-square-up");
  $('#fehleraddbenutzer').hide();
});


function getAuftraggeberOrBearbeiter(firstTime) {
  //Liste aktualisieren
  var name;
  

  $.ajax({  
        url:"verarbeitung"+ziel+"/"+aktion+"/get"+aktion+".php",  
        method:"post",
        dataType: 'JSON',
        data:{aktion:aktion},
        success: function(response){

          $("#"+aktion).empty();
          //$("#"+aktion).append('<option value="" disabled selected>Option wählen</option>');
          for(var i=0; i<response.length; i++){
            name = response[i][0];
            defaultt = response[i][1];
            //admin = response[i][2];

            if(firstTime) {
              if(defaultt == 1) {
                $("#"+aktion).append('<option value="' + name + '" selected=selected   >' + name + '</option>')    
              }
              else {
                $("#"+aktion).append('<option value="' + name + '">' + name + '</option>')    
              }
            }
            else {
              $("#"+aktion).append('<option value="' + name + '">' + name + '</option>')    
            }
        }
        $(".selectpicker").selectpicker("refresh");
    } 
    
  });
  
  }




$('#add1').on( 'click', function () {
    
    //hinzufügen
    var auftr = document.getElementById("addBenutzer").value; 
    var lehr = document.getElementById("lehrstuhl").value; 

  
    var aktion = "auftraggeber";
    var ziel = "Pl";
    var col = "3";

    var Objekt = document.getElementById(aktion);

    

    if(auftr.length > 0 && !(lehr == null || lehr =='')) {
      $("#add1").attr("disabled", true);
      //$("#addbearbeiter").text("Bitte warten...");
      $.ajax({  
          url:"verarbeitung"+ziel+"/"+aktion+"/add"+aktion+".php",  
          method:"post",
          data:{auftr:auftr, lehr:lehr, aktion:aktion},
          dataType: 'JSON',
          success: function(data){

                var zustand = data.data; 
                var error = data.error;
                var inputfeld = document.getElementById("addBenutzer");
                if(zustand == "erfolgreich") {
                  inputfeld.value = '';
                  Objekt.selectedIndex = "0";
                  getAuftraggeberOrBearbeiter();
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
        $("#add1").attr("disabled", false);
      }, 500);
    }
    else {
      if(auftr == null || auftr =='') {
        document.getElementById("fehleraddbenutzer").innerHTML="Bitte gib einen Auftraggebernamen ein";
        $('#fehleraddbenutzer').show();
      }
      else if (lehr == null || lehr ==''){
        document.getElementById("fehleraddbenutzer").innerHTML="Bitte wähle einen Lehrstuhl aus";
        $('#fehleraddbenutzer').show();
      }
    }
});
  





$('#rem1').on( 'click', function () {

  var aktion = "auftraggeber";
  var ziel = "Pl";
  var col = "3";
  


  var Objekt = document.getElementById(aktion);
  var Text = Objekt.options[Objekt.selectedIndex].text;
  //var index = Objekt.options[Objekt.selectedIndex].index;


  if (Text != "Option wählen") {
    $("#rem1").attr("disabled", true);
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
                getAuftraggeberOrBearbeiter();
                $('#collapse'+col).collapse("hide");
              }
              else {
                if(zustand == "nichtadmin") {
                  document.getElementById("fehleraddbenutzer").innerHTML="Ein Admin kann nicht gelöscht werden.";
                }
                else {
                  if(error.indexOf("foreign") >= 0) {
                    document.getElementById("fehleraddbenutzer").innerHTML="Der Auftraggeber ist bereits einer Platine zugewiesen.";
                  }
                  else {
                    document.getElementById("fehleraddbenutzer").innerHTML="Datenbankfehler: " + error;
                  }
                }
                $('#fehleraddbenutzer').show();

              }
      } 
    });
    setTimeout(function(){
      $("#rem1").attr("disabled", false);
  }, 500);
}
  

});







