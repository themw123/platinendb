$(document).ready(function(){ 

  

//Datumformat angeben, damit sorting funktioniert
$.fn.dataTable.moment( 'DD-MM-YYYY' );

var d = new Date();
t1 = moment(d).format('YYYY-MM-DD');



var table = $('#tabelle1').DataTable({

  
					"ajax":{
						url :"verarbeitungPl/platinen.php", // json datasource
						type: "post",  // method  , by default get 
            
            error: function(){  // error handling
              $(".tabelle1-error").html("");
            }
					},


  liveAjax: true,

  fixedHeader: true,
  
  //fixedColumns: true,

  //"scrollY": "800px",
  
  searchPanes: {
            viewTotal: true,
            controls: false,
            columns: [4]
  },



  language: {
  searchPanes: {
  emptyPanes: 'Für Filter sind noch nicht genügend unterschiedliche Einträge vorhanden.',
  title: {
   _: 'Filters Selected - %d',
   0: '0 Filter aktiv',
   1: '1 Filter aktiv',
   2: '2 Filter aktiv',
   3: '3 Filter aktiv',
   4: '4 Filter aktiv',
   5: '5 Filter aktiv'
   },
   clearMessage: 'alle ausschalten'
   }
   },    
  
dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
"<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>", buttons: {
      buttons: [
            { extend: 'csv', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], modifier: {page: 'current'}} , className: 'btn btn-aktion' },
            { extend: 'excel', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], modifier: {page: 'current'}} , className: 'btn btn-aktion' },
            { extend: 'pdf', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], modifier: {page: 'current'}} , orientation: 'landscape', pageSize: 'LEGAL' , className: 'btn btn-aktion' },
            ],
       dom: {
		  button: {
		  className: 'btn2'
	         }
       }
     },

     "stateSave": true, "scrollX": true,  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "alle"]] , "info": false, "order":[],
     
          "columnDefs": [
  
          { 
          "targets": [4],
          searchPanes:{
                options:[
                    {
                        label: 'offene',
                        value: function(rowData, rowIdx) {
                            return rowData[18] == 0;
                        }
                    },
                    {
                      label: 'ignorierte',
                      value: function(rowData, rowIdx) {
                          return rowData[17] == 1;
                      }
                  }
                ]
            },
           
          },

        
          { 
         "targets": [0] ,
         "data": null,
         "className": "ohnedetail",
         "defaultContent": "<i class='fa fa-edit iconx' id='iconklasse2'></i><i class='fa fa-trash-alt iconx' id='iconklasse'></i> <i class='fas fa-download ohnedetail' id='iconklasse4'></i>   <i class='fas fa-exclamation-triangle ohnedetail' id='iconklasse33'></i>"
        },

         {
          "targets": [16,17,18,19,20],
          "visible": false
         },

          ], 


        
          "createdRow": function( row, data){
            //wenn benutzer est ist (siehe logged_in.php, dort wird est als globale Variable deklariert)
            if(admin == "ja") {

              var erstelltam = data[13].toString();
              var t2 = erstelltam.split('-');
              t2 = t2[2] + "-" + t2[1] + "-" + t2[0];

              var start = moment(t2);      
              var end = moment(t1);
              $daysbetween = end.diff(start, "days");


              $(row).find('i:nth-child(3)').css("display", "inline");
              $(row).find('i:nth-child(4)').css("display", "inline");

              if(data[19] == 0) {
                $(row).find('i:nth-child(3)').css("visibility", "visible");
              }

              if(data[18] == 0){

                $(row).find('i:nth-child(4)').css("visibility", "visible");

                $(row).attr('id', 'blue');

                if(data[17] == 0) {
                  if($daysbetween > 16) {
                    $(row).find('i:nth-child(4)').addClass("red").css("opacity", 1);
                  }
                  else if($daysbetween > 11) {
                    $(row).find('i:nth-child(4)').addClass("orange").css("opacity", 1);
                  }
                  /*
                  if(data[20] == 2) {
                    $(row).find('i:nth-child(4)').addClass("red").css("opacity", 1);
                  }
                  else if(data[20] == 1) {
                    $(row).find('i:nth-child(4)').addClass("orange").css("opacity", 1);
                  }
                  */
                }

              }
              else {
                if(data[19] == 0) {
                  $(row).attr('id', 'orange');
                }
                else {
                  $(row).attr('id', 'green');
                }
              }

              if(data[20] == 0) {
                $(row).find('i:nth-child(3)').addClass("grey").prop('disabled', true);
              }




            }
            
 
          },  





            "oLanguage": {"sLengthMenu": "_MENU_ Reihen pro Seite","sSearch":"","sSearchPlaceholder": "Suche", "sZeroRecords":"Es wurden keine Einträge gefunden","sEmptyTable":"Die Tabelle ist leer",
            "oPaginate": {
            "sFirst":"Anfang",
            "sLast":"Ende",
            "sNext":"weiter",
            "sPrevious":"zurück"}},

//"initComplete": function(){ = wenn tabelle vollständig geladen ist
"initComplete": function(data){

    //ajax antwort überprüfen
    var zustand = data.json.data[1];
    var error = data.json.data[2];
    var pausieren = false;
      if(zustand == "dberror"){
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-danger leer2">Die Platinen konnten nicht geholt werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls. Fehler: ' + error +'</div>');
        pausieren = true;
      }
      else if(zustand == "fehlerhaft") {
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-danger leer2">Die Platinen konnten nicht geholt werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
        pausieren = true;
      }
      else if(zustand == "leer") {
        
        pausieren = true;
        
        //tabelle verstecken
        var tabellecontainer = document.getElementById("tabellex");
        tabellecontainer.style.visibility = "hidden"
        
        //container und knopf hinzufügen anzeigen
        var leiste = document.getElementById("containerleiste");
        leiste.style.visibility = "visible"
        var filterknopf = document.getElementById("button1");
        filterknopf.style.visibility = "visible"
        
        
        //meldung ausgeben
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-info leer2">Es sind keine Platinen vorhanden. Drücke auf das Plus-Symbol, um eine Platine hinzuzufügen.</div>');
        }
        else {
          //wenn tabelle nicht leer, dann filterknopf anzeigen
          var filterknopf = document.getElementById("button3");
          filterknopf.style.visibility = "visible"
          
          var leiste = document.getElementById("containerleiste");
          leiste.style.visibility = "visible"
          
          var button1knopf = document.getElementById("button1");
          button1knopf.style.visibility = "visible"
        
          var buttondefault = document.getElementById("buttondefault");
          if(buttondefault !== null && buttondefault !== undefined) {
            buttondefault.style.visibility = "visible"
          }

          var buttonLegend = document.getElementById("buttonLegend");
          buttonLegend.style.visibility = "visible"
          }

          
        if(pausieren == true) {
          //live pausieren
          setTimeout(function(){
            table.api().liveAjax.pause();
          }, 1000) 
        }

        //tabellencontainerladen, weil in css display:none;, damit nicht vor datne da sind geladen wird
        $("#tabellecontainer").show();
        //tabelle anzeigen, ebenfalls auf display:none;
        $('#tabelle1').show()      
        //table.fnDraw(false); = Tabelle wird initialisiert mit datatable plugin, ansonnsten veränderung des Layouts beim interagieren mit Tabelle
        //false, damit pagination state erhalten bleibt
        table = $(tabelle1).dataTable();
        table.fnDraw(false);
    

        //placeholder text von searchpane verändern
        $('input[placeholder="Ausstehend"]').attr('placeholder', 'Filter');


        //wenn est searchpane button, info button und filter button verstecken
        if(admin == "nein") {
          $('#buttonLegend').hide();
          $('#buttondefault').hide();
          $('#button3').hide();
        }

}



});





//wenn reihe geklickt wird und klasse nicht ohnedetail ist, dann schick id von reihe an details.php und öffne detail modal
//außerdem wiederhole ajax anfrage alle 5 sek, solange modal geöffnet ist
//versehentliche klicks auf zwei verschiedene Reihen schnell nacheinander mit einbezogen

         
var Id; 
var ziel;

var timeOutId;

var getDetailEinmal = function () {
 
  var aktion = "detail";
  $.ajax({  
                       url:"verarbeitungPl/detail.php",  
                       method:"post",  
                       data:{Id:Id, ziel:ziel, aktion:aktion},  
                       success:function(data){
                            $('#modalbody1').html(data);
                            $('#dataModal1').modal("show");     
                        },
                        complete: function () { 
    
                          if ($('#dataModal1').hasClass("show") == false) {
                          clicked = false;
                          }  
                          
    
                        }
    
  });  
       
  }

var getDetailDauerhaft = function () {

if ($('#dataModal1').hasClass('show')) {
var aktion = "detail";
$.ajax({  
                     url:"verarbeitungPl/detail.php",  
                     method:"post",  
                     data:{Id:Id, ziel:ziel, aktion:aktion},  
                     success:function(data){
                          $('#modalbody1').html(data);
                          //$('#dataModal1').modal("show");     
                      },
                      complete: function (response) { 
  
                        timeOutId = setTimeout(getDetailDauerhaft, 5000);
  
                        if ($('#dataModal1').hasClass("show") == false) {
                        clicked = false;
                        }  
                        
  
                      }
  
});  
}  
clicked = false;   
}


var clicked = false;

$('#tabelle1 tbody').on( 'click', 'td', function () {

  if (!clicked) {

  clicked = true;


  if ( !$(this).hasClass('ohnedetail') ) {

    //platinen reload pausieren
    table.api().liveAjax.pause();

         
    Id = table.api().row($(this).closest('tr')).data()[0]; 
    ziel = "platinen";
    

    getDetailEinmal();

    setTimeout(function(){
    getDetailDauerhaft();
    }, 3000);
   

  }
}
  
} );


//wenn hinzufügen geklickt wird

$('#button1').on( 'click', function () {


var modal =  $('#dataModal2') 
modal.find('.modal-title').text('Platine hinzufügen');
aktion = "modaleinfuegen";


  $.ajax({  
                    url:"verarbeitungPl/Modal.php",  
                    method:"post",
                    data:{aktion:aktion},
                    success:function(data){  
                        $('#modalbody2').html(data);

                        /*
                        $('#auftraggeber').selectpicker({
                          size: 10
                        });
                        */

                        //datepicker übergeben
                        $('#datepicker').datepicker({
                          locale: 'de-de',
                          format: 'dd-mm-yyyy',
                          uiLibrary: 'bootstrap4'
                        });

                        //datepicker reset übergeben
                        $("#reset-date").click(function(){
                        $('#datepicker').val("").datepicker("update");
                        })

                        $('#dataModal2').modal("show");  

                          
                    }  

              }); 

  
} );

//wenn auf bearbeiten geklickt wird
$('#tabelle1 tbody').on( 'click', '#iconklasse2', function () {

  var modal =  $('#dataModal2') 
  modal.find('.modal-title').text('Platine bearbeiten');

  
    ziel = "platinen";
    Id = table.api().row($(this).closest('tr')).data()[0]; 
    aktion = "modalbearbeiten";

    var Leiterkartenname = table.api().row($(this).closest('tr')).data()[1];
    var Auftraggeber = table.api().row($(this).closest('tr')).data()[2];

    var Anzahl = table.api().row($(this).closest('tr')).data()[5];
    var Material = table.api().row($(this).closest('tr')).data()[6];
    var Endkupfer = table.api().row($(this).closest('tr')).data()[7];
    var Staerke = table.api().row($(this).closest('tr')).data()[8];
    var Lagen = table.api().row($(this).closest('tr')).data()[9];
    var Groesse = table.api().row($(this).closest('tr')).data()[10];
    var Oberflaeche = table.api().row($(this).closest('tr')).data()[11];
    var Loetstopp = table.api().row($(this).closest('tr')).data()[12];
    var Wunschdatum = table.api().row($(this).closest('tr')).data()[14];
    var Kommentar = table.api().row($(this).closest('tr')).data()[15];
    var Ignorieren = table.api().row($(this).closest('tr')).data()[17];

    $.ajax({  
                    url:"verarbeitungPl/Modal.php",  
                    method:"post",  
                    data:{aktion:aktion, ziel:ziel, Id:Id, Leiterkartenname:Leiterkartenname, Auftraggeber:Auftraggeber, Anzahl:Anzahl, Material:Material, Endkupfer:Endkupfer, Staerke:Staerke, Lagen:Lagen, Groesse:Groesse, Oberflaeche:Oberflaeche, Loetstopp:Loetstopp, Wunschdatum:Wunschdatum, Kommentar:Kommentar, Ignorieren:Ignorieren},  
                    success:function(data){  
                        $('#modalbody2').html(data);  
                        
                        //datepicker übergeben
                        $('#datepicker').datepicker({
                          locale: 'de-de',
                          format: 'dd-mm-yyyy',
                          uiLibrary: 'bootstrap4'
                        });

                        //datepicker reset übergeben
                        $("#reset-date").click(function(){
                        $('#datepicker').val("").datepicker("update");
                        })
   
                  
                        $('#dataModal2').modal("show");  
                    }  
              }); 

   
            
  
} );



//wenn löschen geklickt wird frag ob wirklich gelöscht werden soll, wenn ja übertrag id von Reihe zu loeschen.php und lade tabelleninhalt neu

$('#tabelle1 tbody').on( 'click', '#iconklasse', function () {
  
  Id = table.api().row($(this).closest('tr')).data()[0];
  ziel = "platinen";

  bootbox.confirm({

    size: "small",
    message: "Platine wirklich löschen?",


    buttons: {
        cancel: {
            label: 'nein',
            className: 'btn btn-primary button7'
        },
        confirm: {
            label: 'ja',
            className: 'btn btn-primary button6'
        }
    },


    callback: function(result){

  
  
  if(result){
  
  var aktion = "loeschen";

  $.ajax({  
                    url:"verarbeitungPl/loeschen.php",  
                    method:"post",  
                    dataType:"JSON",
                    data:{Id:Id, ziel:ziel, aktion:aktion},
                    success:function(data){  

                      $('#tabelle1').DataTable().ajax.reload();
                      var zustand = data.data; 
                      var error = data.error;
                      
                      if (zustand == 'erfolgreich') {
                          setTimeout(function(){
                          $('#result').hide().fadeIn(1000).html('<div class="alert alert-success alertm">Die Platine wurde erfolgreich gelöscht.</div>');
                          }, 1000);
                      }
                      else if (zustand == 'nichtest') {  
                          setTimeout(function(){
                          $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Die Platine befindet sich bereits auf einem Nutzen und kann nur von einem Admin gelöscht werden.</div>');
                          }, 1000);
                      }
                      else if (zustand == 'nichtveraenderbar') {  
                          setTimeout(function(){
                          $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Die Platine befindet sich bereits auf einen Nutzen und kann deswegen nicht mehr gelöscht werden.</div>');
                          }, 1000);
                      }
                      else if(zustand == 'dberror'){
                          $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Die Platine konnte nicht gelöscht werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls. Fehler: ' + error +'</div>');
                      }
                      else if (zustand == 'fehlerhaft') {  
                          setTimeout(function(){
                          $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Die Platine konnte nicht gelöscht werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
                          }, 1000);
                      }  


                      window.setTimeout(function() {
                        $(".alertm").fadeTo(500, 0);
                        $(this).remove();
                      }, 5000);

                      setTimeout(function(){
                      table = $(tabelle1).dataTable();
                      table.fnDraw(false);
                      }, 6000);


                      //es wird geguckt ob tabelle leer ist, um dann darüber eine meldung auszugeben und tabellencontainer versteckt und live wird pausiert
                      setTimeout(function(){

                      if ( ! table.api().data().any() ) {

                      $('#leer').hide().fadeIn(1000).html('<div class="alert alert-info leer2">Es sind keine Platinen vorhanden. Drücke auf das Plus-Symbol, um eine Platine hinzuzufügen.</div>');

                      table.api().liveAjax.pause();

                      var tabellecontainer = document.getElementById("tabellex");
                      tabellecontainer.style.visibility = "hidden"
                      
                      var filterknopf = document.getElementById("button3");
                      filterknopf.style.visibility = "hidden"

                      var buttondefault = document.getElementById("buttondefault");
                      if(buttondefault !== null && buttondefault !== undefined) {
                        buttondefault.style.visibility = "hidden"
                      }

                      var buttonLegend = document.getElementById("buttonLegend");
                      buttonLegend.style.visibility = "hidden"
                      
                      }
                      }, 1000)

                      
                      

                    }   
 
              }); 
              

  }
  }});
});

//wenn auf downloadArchive geklickt wird
$('#tabelle1 tbody').on( 'click', '#iconklasse4', function () {
  ziel = "platinen";
  var aktion = "download";
  Id = table.api().row($(this).closest('tr')).data()[0]; 
  
  
  $.ajax({
        url:"verarbeitungPl/downloadArchive.php",  
        method: 'post',
        data:{ziel:ziel, aktion:aktion, Id:Id},
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data, status, xhr) {
            var filename = "";
          
            var disposition = xhr.getResponseHeader('Content-Disposition');
            filename = disposition.split(/;(.+)/)[1].split(/=(.+)/)[1];

            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }

            filename = decodeURIComponent(escape(filename));
            
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            a.href = url;
            a.download = filename;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        }
  });
  

  



})



//wenn auf buttonLegend geklickt wird
$('#buttonLegend').on( 'click', function () {
  $('#dataModal3').modal("show"); 
})

//wenn auf buttondefault geklickt wird
$('#buttondefault').on( 'click', function () {
  table.fnSortNeutral();
  table.api().searchPanes.clearSelections();
  table.api().search("").draw();
  $('input[type=search]').val('').change();
})



//Filter instanz laden
new $.fn.dataTable.SearchPanes(table, {});
table.searchPanes.container().insertAfter('#leiste').addClass('collapse').attr("id","spCont");










//weitere eigene functionen


window.onscroll = function() {
  if($(".alertm").is(":visible")) {
    scrollFunction()
  }
};

function scrollFunction() {
  if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
    $('.alertm').css("top", "50px");
    /*
    if($(".alertm").is(":visible")) {
      var table = $('#tabelle1').DataTable();
      table.fixedHeader.headerOffset(49);
    }
    */
  } else {
    $('.alertm').css("top", "0px");
    //$('.alertm').css("color", "green");
  }
}

//wenn um edit und bearbeiten geklickt wird, markierung bzw. auswahl der reihe verhindern
$(document).on('click','td.ohnedetail',function(event){
if ( $(event.target).hasClass('ohnedetail') ) {
  $('tr').removeClass('klick');
  clicked = false;  
  picked = false;     
  } 
});



var picked = false; 
$('#tabelle1').on('click', 'tr', function(event) {



  if(!picked) {
  clicked = true;
  picked = true;
  var reihe = this;
  $(reihe).addClass('klick').siblings().removeClass('klick');
  }




  $('#dataModal1').on('hidden.bs.modal', function (e) {
    
  $(reihe).removeClass('klick');
  clearTimeout(timeOutId);
  table.api().liveAjax.resume();
  table.api().liveAjax.reload();
  clicked = false; 
  picked = false; 

  })

  $('#dataModal2').on('hidden.bs.modal', function (e) {
    
    $(reihe).removeClass('klick');
    clicked = false;
    picked = false; 
    })


  $('div.bootbox').on('hidden.bs.modal', function (e) {
    
  $(reihe).removeClass('klick');
  clicked = false;
  picked = false; 
  })

});




});





//bug mit erneuter initialisierung der tabelle verhindern wenn filter button geklickt wird wodurch browser bei bestimmten Seitenverhältnis scrollbar erstellt. Die Scrollbar sorgt dann dafür, dass eine falsche initialisierung erfolgt, weil das Seitenverhätlnis verändert wird

$('#button3').click( function () {

if(!$("#spCont").hasClass('show')){
  
setTimeout(function() {
table = $(tabelle1).dataTable();
table.fnDraw(false);
}, 500);

}

else {
setTimeout(function() {
table = $(tabelle1).dataTable();
table.fnDraw(false);
}, 500);
}

} );
