
//Wenn Bei Nutzen oder Platine auf add Bearbeiter bzw Auftraggeber geklickt wird


$(document).ready(function(){ 

  if(typeof aktionx !== 'undefined' && aktionx.includes("Nutzen")) {
    aktion = "bearbeiter";
    ziel = "NU";
  }
  else {
    aktion = "auftraggeber";
    ziel = "Pl";
  }

  

  getAuftraggeberOrBearbeiter();



});


//Wenn auf Bearbeiterbutton geklickt wird
$('.bearbeiterbutton').on( 'click', function () {
  
  $("#bearbeiterbutton").toggleClass("far fa-caret-square-down far fa-caret-square-up");
  $('#fehleraddbenutzer').hide();

  /*
  $("#auftraggeberdiv").hide().fadeIn(1000).css( box-shadow: 0px 0px 22px -6px rgb(0, 0, 0, 1);
  );
  box-shadow: 0px 0px 22px -6px rgb(0, 0, 0, 1);
  */  		

  if(!$("#collapse3").hasClass("show")) {
    $('.auftraggeberdiv').addClass('auftraganiAn');
    $('.auftraggeberdiv').removeClass('auftraganiAus');
  }
  else {
    $('.auftraggeberdiv').removeClass('auftraganiAn');
    $('.auftraggeberdiv').addClass('auftraganiAus');
    
    $('#collapse4').collapse('hide');
    $('.lehrstuhldiv').removeClass('lehrstuhlaniAn');
    $('.lehrstuhldiv').addClass('lehrstuhlaniAus');
  }
});





function getAuftraggeberOrBearbeiter() {
  //Liste aktualisieren


  $.ajax({  
        url:"verarbeitung"+ziel+"/"+aktion+"/get"+aktion+".php",  
        method:"post",
        dataType: 'JSON',
        data:{aktion:aktion},
        success: function(response){


          $("#"+aktion).empty();
          
          if(aktion == "auftraggeber") {
            getAuftraggeber(response);
          }
          else {
            getBearbeiter(response);
          }
        
    } 
    
  });
  
  }


function getAuftraggeber(response) {
          var $optgroup1 =  $('<optgroup id="auftragopt" label="Auftraggeber">');
          var $optgroup2 = $('<optgroup id="adminoptc" label="Admin">');


          for(var i=0; i<response.length; i++){
            namee = response[i][0];
            admin = response[i][1];
            selected = "";
            //wird geholt aus Modal -> #auftraggeber -> zweiter klassenname 
            $auftraggeberDefault = $('#'+aktion).attr('class').split(' ')[1];


            if(namee == $auftraggeberDefault) {
              selected = "selected";
            }

            if(admin != 1) {
               var op1 = "<option value='" + namee + "' "+selected+">" + namee + "</option>";
               $optgroup1.append(op1);
            }
            else {
              var op2 = "<option value='" + namee + "' "+selected+">" + namee + "</option>";
              $optgroup2.append(op2);
            }

        }


        if(typeof $auftraggeberDefault === 'undefined') {
          var $optgroupdef = $('<optgroup id="def" label="Default">');
          var opdef = "<option value=''>alle</option>";
          $optgroupdef.append(opdef);
          $("#"+aktion).append($optgroupdef);
        }
        
        $("#"+aktion).append($optgroup1,$optgroup2);
        
        //$("#auftraggeber").selectpicker("refresh");
        //selectpicker hat einen bug, refresh führt zu doppelten einträgen
        //deshalb muss refresh wie folgt selber ausgelöst werden
        $("#auftraggeber").selectpicker("destroy");
        $('#auftraggeber').selectpicker({
          size: 10
        });

        //if auftraggeberDefault is undefinded then set default to none
        if(typeof $auftraggeberDefault === 'undefined') {
          $('#auftraggeber').selectpicker('val', 'none');
          $("#con").css("visibility", "visible");
        }

}


function getBearbeiter(response) {
        for(var i=0; i<response.length; i++){
          namee = response[i];
          selected = "";
          //wird geholt aus Modal -> #auftraggeber -> zweiter klassenname 
          $auftraggeberDefault = $('#'+aktion).attr('class').split(' ')[1];

          if(namee == $auftraggeberDefault) {
            selected = "selected";
          }


            
 
          $("#"+aktion).append("<option value='" + namee + "' "+selected+">" + namee + "</option>");
          

        
    
      }

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
                  $('.auftraggeberdiv').removeClass('auftraganiAn');
                  $('.auftraggeberdiv').addClass('auftraganiAus');
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
                $('.auftraggeberdiv').removeClass('auftraganiAn');
                $('.auftraggeberdiv').addClass('auftraganiAus');
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







