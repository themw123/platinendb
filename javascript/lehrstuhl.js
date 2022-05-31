
$(document).ready(function(){ 

  getLehrstuhl();

});


//Wenn auf lehrstuhlbutton geklickt wird
$('.lehrstuhlbutton').on( 'click', function () {
  $("#lehrstuhlbutton").toggleClass("far fa-caret-square-down far fa-caret-square-up");
  $('#fehleraddlehrstuhl').hide();

  if(!$("#collapse4").hasClass("show")) {
    $('.lehrstuhldiv').addClass('lehrstuhlaniAn');
    $('.lehrstuhldiv').removeClass('lehrstuhlaniAus');
  }
  else {
    $('.lehrstuhldiv').removeClass('lehrstuhlaniAn');
    $('.lehrstuhldiv').addClass('lehrstuhlaniAus');
  }
});


function getLehrstuhl() {
  //Liste aktualisieren
  var name;
  
  var aktion = "lehrstuhl";

  

  $.ajax({  
        url:"verarbeitungALG/getLehrstuhl.php",  
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




$('#add2').on( 'click', function () {
    
    //hinzufügen
    var aktion = "lehrstuhl";
    var addLehrstuhl = document.getElementById("addLehrstuhl").value; 
  
    var col = "4";
    
    if(addLehrstuhl.length > 0) {
      $("#add2").attr("disabled", true);
      //$("#addbearbeiter").text("Bitte warten...");
      $.ajax({  
          url:"verarbeitungALG/addLehrstuhl.php",  
          method:"post",
          data:{addLehrstuhl:addLehrstuhl, aktion:aktion},
          dataType: 'JSON',
          success: function(data){

                var zustand = data.data; 
                var error = data.error;
                var inputfeld = document.getElementById("addLehrstuhl");
                if(zustand == "erfolgreich") {
                  inputfeld.value = '';
                  $('.lehrstuhldiv').removeClass('lehrstuhlaniAn');
                  $('.lehrstuhldiv').addClass('lehrstuhlaniAus');
                  getLehrstuhl();
                  $('#collapse'+col).collapse("hide");
                  //$("#addbearbeiter").text("hinzufügen");
                }
                else{
                  document.getElementById("fehleraddlehrstuhl").innerHTML="Datenbankfehler: " + error;
                  $('#fehleraddlehrstuhl').show();
                }
        } 
      });
      setTimeout(function(){
        $("#add2").attr("disabled", false);
      }, 500);
    }
    else {
      if (addLehrstuhl == null || addLehrstuhl ==''){
        document.getElementById("fehleraddlehrstuhl").innerHTML="Bitte gibt ein Lehrstuhlkürzel ein";
        $('#fehleraddlehrstuhl').show();
      }
    }

});
  





$('#rem2').on( 'click', function () {
    
  var aktion = "lehrstuhl";
  var col = "4";
  


  var Objekt = document.getElementById(aktion);
  var Text = Objekt.options[Objekt.selectedIndex].text;
  //var index = Objekt.options[Objekt.selectedIndex].index;


  if (Text != "Option wählen") {
    $("#rem2").attr("disabled", true);
    $.ajax({  
      url:"verarbeitungALG/remLehrstuhl.php",  
      method:"post",
          data:{Text:Text, aktion:aktion},
          dataType: 'JSON',
          success: function(data){

              var zustand = data.data; 
              var error = data.error;
              if(zustand == "erfolgreich") {
                Objekt.remove(Objekt.selectedIndex);
                Objekt.selectedIndex = "0";
                $('.lehrstuhldiv').removeClass('lehrstuhlaniAn');
                $('.lehrstuhldiv').addClass('lehrstuhlaniAus');
                getLehrstuhl();
                $('#collapse'+col).collapse("hide");
              }
              else {
                if(error.indexOf("foreign") >= 0) {
                  document.getElementById("fehleraddlehrstuhl").innerHTML="Der Lehrstuhl ist bereits einem Auftraggeber zugewiesen.";
                }
                else {
                  document.getElementById("fehleraddlehrstuhl").innerHTML="Datenbankfehler: " + error;
                }
                
                $('#fehleraddlehrstuhl').show();

              }
      } 
    });
    setTimeout(function(){
      $("#rem2").attr("disabled", false);
  }, 500);
}
  

});







