
//# sourceURL=formEditor.js

$(function(){
    $('#edit').submit(function(event){
      event.preventDefault();  

      //Lagen aktivieren
      $("#lagen", this).prop("disabled", false);

      var show = false;
      var meldung;

      //Wenn Nutzen hinzufügen und Testdaten unchecked
      if(aktionx == ("Nutzen hinzufügen") && !$('#checkbox-2').is(":checked")) {
        show = true;
        meldung = "Ohne Testdaten fortfahren?"
      }
      //Wenn Nutzen bearbeiten 
      else if(aktionx == ("Nutzen bearbeiten")) {
        //nur wenn von neu auf fertigung gestellt wurde
        if(getselected == "neu" && getselected != $("#status :selected").val()) {
          if ($('#uploadfeld').get(0).files.length === 0) {
            show = true;
            meldung = "Ohne Cam360-Daten fortfahren?";
          }
        }
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
              else if(zustand == 'fehlerLagen'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Die Anzahl der Lagen stimmt nicht überein.</div>');
              }
              else if(zustand == 'dberror'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls.</div>');
              }
              else if(zustand == 'fehlerhaft'){
                $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">'+aktionText2+' konnte nicht '+aktionText+' werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
              }
                  
              window.setTimeout(function() {
              $(".alertm").fadeTo(500, 0).slideUp(500, function(){
              $(this).remove();
              });  
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
                    }, 1000);
                    }
              }
              

           }   

      });
}




















var aktionx = $(".modal-title").get(2).innerText;
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
                      $('#upload-info').text(fileName);
                      $('#upload-info').show();
                      $('#inputbild').show();
                      $('#upload-info').animate({opacity: 1,fontSize: '20px'},500);
                      $('#inputbild').animate({opacity: 1,fontSize: '16px'},500);
                      $('#delfile').show(); 
                      $('#lagen').prop( "disabled", true );
                      $('#fehleraddlagen').hide();
                      //infoicon
                      if($('#lagenid').hasClass('iconaus')) {
                        $('#lagenid').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Lagen können erst wieder bearbeitet werden, wenn keine Lagen.txt-Datei ausgewählt ist.'></i>");
                        $('#lagenid').removeClass('iconaus');
                      }
                      $("#infoicon").popover();
                    }
                  }


                };
                reader.readAsText(input.files[0]);
           }
           else {
            remUploadData("Es muss eine Textdatei sein.");
           }
        });  


      


        //Wenn auf delfile geklickt wird
        $("#delfile").click(function(ev){
          remUploadData();
        })


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
          $('#lagenid').text("Lagen: ");
          $('#lagenid').addClass('iconaus');
        }











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
          $('#lagenid').append("<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Lagen können erst wieder bearbeitet werden, wenn der Status des Nutzen in den Zustand neu überführt wird.'></i>");
          $("#infoicon").popover();
        }

        });





        //anfangsdatum holen
        var fertigungAnfangsDatum = $('#datepicker2').val();
        var abgeschlossenAnfangsDatum = $('#datepicker3').val();




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


          //$('#uploadfeld').val('');

          if(selectedStatus == "Fertigung") {

            //nur bei neu Upload für Lagen anzeigen
            if(getselected != "abgeschlossen" && getselected != "Fertigung") {
                $('#collapse3').collapse('show');
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
              $('#warnungStatus').text("Warnung: Die cam360-Daten der Lagen werden gelöscht. Außerdem wird das Fertigung und abgeschlossen Datum gelöscht, sobald der Status auf neu geändert wird");
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