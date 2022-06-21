//# sourceURL=formEditor.js


$(function(){
    $('#edit').submit(function(event){
      event.preventDefault();  

      //Lagen aktivieren
      $("#lagen", this).prop("disabled", false);

      //int/ext aktivieren
      $("#int", this).prop("disabled", false);

      var show = false;
      var meldung;

      //Wenn Nutzen hinzufügen und Testdaten unchecked
      if(aktionx == ("Nutzen hinzufügen") && !$('#checkbox-2').is(":checked")) {
        show = true;
        meldung = "Ohne Testdaten fortfahren?"
      }
      //Wenn Nutzen bearbeiten 
      else if(aktionx == ("Nutzen bearbeiten")) {
        /*
        //nur wenn von neu auf fertigung gestellt wurde und int/ext = int
        if(getselected == "neu" && getselected != $("#status :selected").val() && $('#int').val() == "int") {
          if ($('#uploadfeld').get(0).files.length === 0) {
            show = true;
            meldung = "Ohne Kupferflächen fortfahren?";
          }
        }
        */
      }




      if(show == true) {


        bootbox.confirm({

          show:show,
          size: "small",
          message: meldung,
      

          buttons: {
              cancel: {
                  label: 'nein',
                  className: 'btn btn-primary button15'
              },
              confirm: {
                  label: 'ja',
                  className: 'btn btn-primary button16'
              }
          },
      
      
          callback: function(result){
            //wenn abfrage bestätigt dann durchführen
            if(result){
              dostuff();
            }
          }
        });

      }
      //wenn show == false ist also keine abfrage erfolgt dann durchführen
      else {
        dostuff();
      }




  });
});



function dostuff() {

      $("#button8").attr("disabled", true);
      $("#button8").text("Bitte warten...");
     
      var aktion = $(".modal-title").get(2).innerText;
      var aktionText = "";
      var aktionText2 = "";
      var ziel= "";
      var upload = $('#uploadfeld').val();
    

      if(aktion.includes("Nutzen")) { 
        ziel = "NU";
        aktionText2 = "Der Nutzen";
        aktionText3 = "des Nutzen";
      }
      else if (aktion.includes("Platine")) {
        ziel = "Pl";
        aktionText2 = "Die Platine";
        aktionText3 = "der Platine";
      }

      if(aktion.includes("bearbeiten")) {
        aktion = "bearbeiten";
        aktionText = "bearbeitet";
      }
      else if(aktion.includes("hinzufügen")) {
        aktion = "einfuegen";
        aktionText = "hinzugefügt";
        var filterknopf = document.getElementById("button3");
        var leiste = document.getElementById("containerleiste");
        var button1knopf = document.getElementById("button1");
        var tabellecontainer = document.getElementById("tabellex");
        var buttondefault = document.getElementById("buttondefault");
        var buttonLegend = document.getElementById("buttonLegend");

      }



      if(upload != "" && upload != null) {
        var form = $('form')[0];
        var data = new FormData(form);
        data.append('aktion', aktion);
        data.append('file', $('input[type=file]')[0].files[0]);

        contentType = false;
        processData = false;
      }
      else {
        data = $('#edit').serialize() + "&aktion=" + aktion; 
        //default werte für contentType und processData
        contentType = "application/x-www-form-urlencoded; charset=UTF-8";
        processData = true;
      }

      //gucken ob layer daten gelöscht werden sollen
      if(ziel == "NU" && aktion == "bearbeiten") {
        if(getselected != "neu") {
          var aktuellerStatus = $('#status').val();
          if(aktuellerStatus == "neu") {
            data = data + "&layerLoeschen=true";
          }
        }
      }
    




      $.ajax({  
        url:"verarbeitung"+ziel+"/"+aktion+".php",  
        method:"POST",  
        data: data,
        contentType: contentType,
        processData: processData,
        success:function(data){
            
              $('#dataModal2').modal('hide');
              $('#tabelle1').DataTable().liveAjax.reload(); 

              
              var zustand = data.data;
              

              if (zustand == 'erfolgreich') {
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-success alertm">'+aktionText2+' wurde erfolgreich '+aktionText+'.</div>');
              }
              else if (zustand == 'nichtest') {  
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Die Platine befindet sich bereits auf einem Nutzen kann nur von einem Admin verändert werden.</div>');
              }
              else if(zustand == 'nichtveraenderbar'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Folgende Eigenschaften '+aktionText3+' dürfen nicht verändert werden, da bereits Abhängigkeiten bestehen: Material, Endkupfer, Stärke und Lagen.</div>');
              }
              else if(zustand == 'keineplatineaufnutzen'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Nutzen kann nicht in die Fertigung, da sich mindestens eine Platine drauf befinden muss.</div>');
              }
              else if(zustand == 'fehlerLagen'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Die Anzahl der Lagen stimmt nicht überein.</div>');
              }
              else if(zustand == 'dberror'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls. Fehler: ' + data.error +'</div>');
              }
              else if(zustand == 'fehlerhaft'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
              }
                  
              window.setTimeout(function() {
                $(".alertm").fadeTo(500, 0);
                $(this).remove();
                /*
                var table = $('#tabelle1').DataTable();
                table.fixedHeader.headerOffset(0);
                */
              }, 5000);



              setTimeout(function(){
              table = $(tabelle1).dataTable();
              table.fnDraw(false);
              }, 6000);


              if(aktion == "einfuegen") {
                    $(".leer2").remove();

                    table = $(tabelle1).dataTable();
                    if ( ! table.api().data().any() ) {
                    setTimeout(function() {
                      
                    table.api().liveAjax.resume(); 
                        
                    table.api().liveAjax.reload();   

                    filterknopf.style.visibility = "visible"

                    leiste.style.visibility = "visible"

                    button1knopf.style.visibility = "visible"

                    tabellecontainer.style.visibility = "visible" 

                    if(buttondefault !== null && buttondefault !== undefined) {
                      buttondefault.style.visibility = "visible"
                    }

                    buttonLegend.style.visibility = "visible"
                
                    }, 1000);
                    }
              }

           }   

      });
}












$('[data-toggle="popover"]').popover();



var aktionx = $(".modal-title").get(2).innerText;





function addUpload(fileName) {
  $('#upload-info').text(fileName);
  $('#upload-info').show();
  $('#inputbild').show();
  $('#upload-info').animate({opacity: 1,fontSize: '17px'},500);
  $('#inputbild').animate({opacity: 1,fontSize: '16px'},500);
  $('#delfile').show(); 
  $('#lagen').prop( "disabled", true );
  $('#fehleraddlagen').hide();
  //infoicon
  if($('#lagenid').hasClass('iconaus')) {
    $('#lagenid').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Lagen können erst wieder bearbeitet werden, wenn keine Lagen.txt-Datei ausgewählt ist.'></i>");
    $('#lagenid').removeClass('iconaus');
  }
  
}


function remUploadData(mel) {
  $('#uploadfeld').val('');
  $('#lagen').prop( "disabled", false);
  if(mel != null) {
    $('#fehleraddlagen').text(mel);
    $('#fehleraddlagen').show();
  }
  $('#upload-info').animate({opacity: 0,fontSize: '0px'},500, function() {$('#upload-info').hide();} );
  $('#inputbild').animate({opacity: 0,fontSize: '0px'},500, function() {$('#inputbild').hide();} );
  $('#delfile').hide(); 
  
  if(aktionx == "Nutzen bearbeiten") {
    $('#lagenid').text("Lagen: ");
    $('#lagenid').addClass('iconaus');
  }
}

function truncate(fileName, n, type){
  return (fileName.length > n) ? fileName.substr(0, n-1) + '(...).' + type : fileName;
};


function intorextOff(){
  remUploadData();
  $("#uploadfeld").prop('required',false); 
  $('#collapse3').collapse('hide');

  $('#lagen').prop( "disabled", false);
  $('#lagenid').text("Lagen: ");
  $('#lagenid').addClass('iconaus');
  $('#statuslabel').text("Status: ");
};

function intorextOn(){
  $('#collapse3').collapse('show');
  $("#uploadfeld").prop('required',true); 
  $('#statuslabel').text("Status: ");
  $('#statuslabel').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Kupferflächen(.txt) müssen angegeben werden wenn Status = Fertigung und int/ext = int'></i>");
  $('[data-toggle="popover"]').popover();
};

function statusAnimation1(getselected, selectedStatus, int) {
  $('#fehleraddlagen').hide();

  if(!$("#collapse3").hasClass("show") && getselected == "neu" && int == "int") {
    $('.statusdiv').addClass('statusAn');
    $('.statusdiv').removeClass('statusAus');
  }
  else if ($("#collapse3").hasClass("show") && selectedStatus != "Fertigung" && selectedStatus != "abgeschlossen" && int == "int"){
    $('.statusdiv').removeClass('statusAn');
    $('.statusdiv').addClass('statusAus');
  }
}

function statusAnimation2(on, getselected) {
  if(on && getselected != "neu") {
    $('.statusdiv').addClass('statusAn');
    $('.statusdiv').removeClass('statusAus');
  }
  else if(!on && getselected != "neu"){
    $('.statusdiv').removeClass('statusAn');
    $('.statusdiv').addClass('statusAus');
  }
}


$("#button8").html("fertig &nbsp <i class='fas fa-check greener'></i>");
$("#button8").attr("disabled", false);

//Wenn auf delfile geklickt wird
$("#delfile").click(function(ev){
  remUploadData();
})


if(aktion == "modaleinfuegen" && aktionx.includes("Platine")) {

  if(adminn == "nein") {
    $("#uploadfeld").prop('required',true);
  }

  //$('#button8').css('margin-top','112px');

          //upload überpruefen
          $('#uploadfeld').change(function () {
            var input = event.target;
            var name =  input.files[0].name;
            var type = "error";
            if(name.includes(".rar") || name.includes(".zip")) {
              if(name.includes(".rar")) {
                type = "rar";
              }
              else if(name.includes("zip")) {
                type = "zip";
              }
              let fileName = $('#uploadfeld').val().split('\\').pop();
              fileName = truncate(fileName, 18, type);

              addUpload(fileName);
            }
           else {
            remUploadData("Es wurde keine rar oder zip Datei ausgewählt.");
           }
        });  

}

if(aktion == "modalbearbeiten" && aktionx.includes("Platine")) {

  $('#checkbox-3').change(function() {
    if(this.checked) {
      $('#bearbeiter').attr("required", true);
      //$('.fertigungcheck').css('margin-bottom', '0rem');
    }
    else {
      $('#bearbeiter').attr("required", false);
      //$('.fertigungcheck').css('margin-bottom', '1rem');
    }

  });

}








if(aktion == "modalbearbeiten" && aktionx.includes("Nutzen")) {
        //upload überpruefen
        $('#uploadfeld').change(function () {
            var input = event.target;
            var type = input.files[0].type;

            if(type == "text/plain") {
                var reader = new FileReader();
                reader.onload = function(){
                  var text = reader.result;
                  var richtigerInhalt = text.includes(":Top");
                  var richtigerInhalt2 = text.includes(":Bottom");
        
                  if(!richtigerInhalt || !richtigerInhalt2) {
                    remUploadData("Der Inhalt entspricht nicht den Erwartungen.");
                  }
                  else {         
                    //AnzahlLagen aus aktuellem Nutzen
                    anzahlLagen1 = $("#lagen").val();
                    $('#fehleraddlagen').hide();
                    var text = reader.result;
                    var anfang = text.indexOf(":Top")-8;
                    var ende = text.indexOf(":Bottom")+40;
                    var textneu = text.substring(anfang, ende);
                  
                    textneu = textneu.replace(/ +(?= )/g,'').trim();
                    var anzahlLagen2 = textneu.split(/\r\n|\r|\n/).length;

                    if(anzahlLagen1 != anzahlLagen2) {
                      remUploadData("Die Anzahl der Lagen stimmt nicht überein.");
                    }
                    else {

                      let fileName = $('#uploadfeld').val().split('\\').pop();
                      fileName = truncate(fileName, 11);

                      addUpload(fileName);
                      $('[data-toggle="popover"]').popover();
                    }
                  }


                };
                reader.readAsText(input.files[0]);
           }
           else {
            remUploadData("Es muss eine Textdatei sein.");
           }
        });  


        $(document).ready(function(){ 
                    

        //anfangsstatus auslesen und reagieren
        $('#lagen').prop( "disabled", true );

        getselected = $("#status :selected").val();
        if(getselected == "neu") {
          
          $('#lagen').prop( "disabled", false);

          fertigungAus();
        
          abgeschlossenAus();

          $("#status option:nth-child(4)" ).attr("disabled","disabled");
        }
        else if (getselected == "Fertigung") {
          abgeschlossenAus();
        }


        if(getselected != "neu") {
          $('#int').prop("disabled", true);
          $('#intid').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='int/ext kann erst wieder bearbeitet werden, wenn der Status des Nutzen in den Zustand neu überführt wird.'></i>");
          $('#lagenid').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Lagen können erst wieder bearbeitet werden, wenn der Status des Nutzen in den Zustand neu überführt wird.'></i>");
          $('[data-toggle="popover"]').popover();
        }

        });





        //anfangsdatum holen
        var fertigungAnfangsDatum = $('#datepicker2').val();
        var abgeschlossenAnfangsDatum = $('#datepicker3').val();


        //Wenn int/ext geändert wird
        $('#int').change(function(){
          
          intorext = $('#int').val();
          var sel = $("#status :selected").val();

          if(intorext == 'ext') {

            intorextOff();
            statusAnimation2(false, sel);
          }
          else if(intorext == 'int' && $("#status :selected").val() != "neu") {

            intorextOn();
            statusAnimation2(true, sel);


          }
        });


        //Wenn Status geändert wird
        $('#status').change(function(){


          //aktuelles Datum ermitteln
          var d = new Date();
          if ((d.getDate()+1) < 10) {
            var currDate = '0' + (d.getDate());
          }
          else {
            var currDate = (d.getDate());
          }
                            
          if ((d.getMonth()+1) < 10) {
            var currMonth = '0' + (d.getMonth()+1);
          }
          else {
            var currMonth = (d.getMonth()+1);
          }

          var currYear = d.getFullYear();
          var today = currDate + "-" + currMonth + "-" + currYear;



          var selectedStatus = $(this).children("option:selected").val();

          var selectedfertigung = $(datepicker2).val();

          var selectedabgeschlossen = $(datepicker3).val();


          var int = $('#int').val();
          statusAnimation1(getselected, selectedStatus, int);



          if(selectedStatus == "Fertigung") {

            //nur bei neu Upload für Lagen anzeigen
            if(getselected != "abgeschlossen" && getselected != "Fertigung") {
                
                var intorext = $('#int').val();
                if(intorext == 'int') {

                  intorextOn();

                }
                //$('#uploadfeld').attr("required", true);
            } 

            //warnung löschen
            $('#warnungStatus').hide();


            if(selectedfertigung == "") {

              document.getElementById("datepicker2").value = today;
              document.getElementById("datepicker3").value = "";
              
              fertigungAn();
            
              $('#status option:nth-child(4)').removeAttr('disabled');

            }
            else{
              document.getElementById("datepicker3").value = "";

              abgeschlossenAus();

            }

            if(getselected == "Fertigung") {
              document.getElementById("datepicker2").value = fertigungAnfangsDatum;
            }
            
            if(getselected == "abgeschlossen") {
              document.getElementById("datepicker2").value = fertigungAnfangsDatum;
            }

          }




          if(selectedStatus == "abgeschlossen") {
            //warnung löschen
            $('#warnungStatus').hide();

            if(selectedabgeschlossen == "") {
              document.getElementById("datepicker3").value = today;

              abgeschlossenAn();

            }

            if(getselected == "abgeschlossen") {
              document.getElementById("datepicker3").value = abgeschlossenAnfangsDatum;
            }



          }



          if(selectedStatus == "neu") {
            
            $('#uploadfeld').val(null);
            $('#collapse3').collapse('hide');
            $("#uploadfeld").prop('required',false); 
            $('#statuslabel').text("Status: ");




            document.getElementById("datepicker2").value = "";
            document.getElementById("datepicker3").value = "";

            fertigungAus();

            abgeschlossenAus();

            $("#status option:nth-child(4)" ).attr("disabled","disabled");
           
            if(getselected == "neu") {
              $('#upload-info').animate({opacity: 0,fontSize: '0px'},500);
              $('#inputbild').animate({opacity: 0,fontSize: '0px'},500);
              $('#delfile').hide();
              $('#fehleraddlagen').hide();
              $('#lagen').prop( "disabled", false);
              $('#uploadfeld').val('');
              $('#collapse3').collapse('hide');
              $('#lagenid').text("Lagen: ");
            }
            else {
              $('#warnungStatus').text("Warnung: Die Kupferflächen(.txt) Daten der Lagen werden gelöscht. Außerdem wird das Fertigung und abgeschlossen Datum gelöscht, sobald der Status auf neu geändert wird");
              $('#warnungStatus').show();
            }

            
            $('#lagenid').addClass("iconaus");


          }

          
        });







        //Datum reset
        $("#reset-date2").click( function()
          {
            $('#status').val('neu').trigger('change');    
          }
        );

        $("#reset-date3").click( function()
        {

        
          if (!($('#status').val() == 'neu')){
          $('#status').val('Fertigung').trigger('change');    
          }
        }
        );










        //sonstige funktionen
        function fertigungAn() {
          $("#datepicker2" ).removeClass('ausschalten');
          $(".datepickericon2" ).parent('button').parent('span').removeClass('ausschalten');
        }


        function fertigungAus() {
          $("#datepicker2" ).addClass('ausschalten');
          $(".datepickericon2" ).parent('button').parent('span').addClass('ausschalten');
        }


        function abgeschlossenAn() {
          $("#datepicker3" ).removeClass('ausschalten');
          $(".datepickericon3" ).parent('button').parent('span').removeClass('ausschalten');
        }

        function abgeschlossenAus() {
          $("#datepicker3" ).addClass('ausschalten');
          $(".datepickericon3" ).parent('button').parent('span').addClass('ausschalten');
        }

}