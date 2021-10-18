$(document).ready(function(){ 


    //Datumformat angeben, damit sorting funktioniert
    $.fn.dataTable.moment( 'DD-MM-YYYY' );
    
    
    var table = $('#tabelle1').DataTable({
    
      
                        "ajax":{
                            url :"verarbeitungNU/nutzen.php", // json datasource
                            type: "post",  // method  , by default get
                          	
                            error: function(){  // error handling
                                $(".tabelle1-error").html("");
                            }
                        },
    
      liveAjax: true,
    
    
      searchPanes: {
                viewTotal: true,
                controls: false,
                columns: [3]
                
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
                { extend: 'csv', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], modifier: {page: 'current'}} , className: 'btn btn-aktion' },
                { extend: 'excel', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], modifier: {page: 'current'}} , className: 'btn btn-aktion' },
                { extend: 'pdf', exportOptions: {columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], modifier: {page: 'current'}} , orientation: 'landscape', pageSize: 'LEGAL' , className: 'btn btn-aktion' },
                ],
           dom: {
              button: {
              className: 'btn2'
                 }
           }
         },
    
         "stateSave": true, "scrollX": true,  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "alle"]] , "info": false, "order":[], 
         
         
         "columnDefs": [{ 
    
    
    

            
          
            
                "targets": [3], 


                searchPanes:{
                      options:[
                          {
                              label: 'neu',
                              value: function(rowData) {
                                  return rowData[3] == "neu";
                              }
                          },
                          {
                              label: 'Fertigung',
                              value: function(rowData) {
                                  return rowData[3] == "Fertigung";
                              }
                          },
                          {
                              label: 'abgeschlossen',
                              value: function(rowData) {
                                  return rowData[3] == "abgeschlossen";
                              }
                          }
                      ]
                  },

                render: function(data, type, row, meta) { 
        
                  if (data == "neu") { 
                  return '<div style="color: #005ea9;">' + data + '</div>';
                  } 
                  else if (data == "Fertigung") {
                    return '<div style="color: #e89b02;">' + data + '</div>';
                  }
                  else if (data == "abgeschlossen") {
                    return '<div style="color: #06a130;">' + data + '</div>';
                  }
                  else  {
                  return data;
                  }
      
                }
            },
    
              {
              "targets": [4], 
              render: function(data, type, row, meta) { 
              if (row[3] == "neu") {
               return '<div style="color: #005ea9;">' + data + '</div>';
              }
              else {
                return data;
              }
              }
              },
    
              {
              "targets": [5], 
              render: function(data, type, row, meta) {
              if (row[3] == "Fertigung") { 
               return '<div style="color: #e89b02;">' + data + '</div>';
              }
              else {
                return data;
              }
              }
              },
    
              {
              "targets": [6], 
              render: function(data, type, row, meta) { 
              if (row[3] == "abgeschlossen") {
               return '<div style="color: #06a130;">' + data + '</div>';
              }
              else {
                return data;
              }
              }
              },
    
    
              {
              "targets": [13], 
              render: function(data, type, row, meta) { 
    
              if (data == 1) { return '<span class="fas fa-check check"></span>' + '<span style="visibility: hidden;">' + data + '</span>'} 
              else {return '<span class="fas fa-times error"></span>' + '<span style="visibility: hidden;">' + data + '</span>'}
              ;
              
              return data;
    
              }
              },
    
    
              { 
             "targets": [0] ,
             "data": null,
             "className": "ohnedetail",
             "defaultContent": "</i><i class='fa fa-edit iconx' id='iconklasse2'></i><i class='fa fa-trash-alt iconx' id='iconklasse'></i>"
             
             }
    
              ], 
    
    
    
    
    
    
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
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-danger leer2">Die Nutzen konnten nicht geholt werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls. Fehler: ' + error +'</div>');
        pausieren = true;
      }
      else if(zustand == "fehlerhaft") {
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-danger leer2">Die Nutzen konnten nicht geholt werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
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
        $('#leer').hide().fadeIn(1000).html('<div class="alert alert-info leer2">Es sind keine Nutzen vorhanden. Drücke auf das Plus-Symbol, um ein Nutzen hinzuzufügen.</div>');
        }
        else {
          //wenn tabelle nicht leer, dann filterknopf anzeigen
          var filterknopf = document.getElementById("button3");
          filterknopf.style.visibility = "visible"
          
          var leiste = document.getElementById("containerleiste");
          leiste.style.visibility = "visible"
          
          var button1knopf = document.getElementById("button1");
          button1knopf.style.visibility = "visible"
          
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

    //anzahl der reihen in variable reinpacken
    //var api = this.api();
    //var eintraege = api.page.info().recordsDisplay;
    //live pausieren
    /*
    setTimeout(function(){
    table.api().liveAjax.pause();
    }, 1000) 
    */
    
    
    
    
    }
    
    
    
    });
    
    
    
    
    
    
    
    
    
    
    //wenn reihe geklickt wird und klasse nicht ohnedetail ist, dann schick id von reihe an details.php und öffne detail modal
    //außerdem wiederhole ajax anfrage alle 5 sek, solange modal geöffnet ist
    //versehentliche klicks auf zwei verschiedene Reihen schnell nacheinander mit einbezogen
    

    var Id; 
    var ziel;
    NutzenId = 0;
  

    timeOutId = 0;
    timeOutId2 = 0;

     getDetailEinmal = function (showmodal) {
    
      aktion = "detail";
      $.ajax({  
                     url:"verarbeitungNU/detail.php",  
                     method:"post",  
                     data:{Id:Id, ziel:ziel, aktion:aktion},  
                     success:function(data){
                          $('.dynamischetabelle').html(data); 
                          if(showmodal) {
                            $('#dataModal1').modal("show");
                          }

                      },
                      complete: function (response) { 

                        if ($('#dataModal1').hasClass("show") == false) {
                        clicked = false;
                        }  
  
                      }
  
      });  
      
      }


    getHinzufuegenEinmal = function (showmodal) {
     var einmal = true;
     aktion = "detail";
      $.ajax({  
        url:"verarbeitungNU/detailExtras/detailhinzufuegen.php",  
        method:"post",  
        data:{Id:Id, ziel:ziel, einmal:einmal, aktion:aktion},  
        success:function(data){
            
            $('.platinenadd').html(data);
            if(showmodal) {
             $('#dataModal1').modal("show");
            }


            $('#tabelle3 tbody tr').each(function(){
              
              $(this).attr('id', 'blue');

              var dringlichkeit = $(':nth-child(6)',this).text();

              if(dringlichkeit == 2) {
                $(this).find('i:nth-child(2)').addClass("darkBlue").css("opacity", 1);
              }
              else if(dringlichkeit == 1) {
                $(this).find('i:nth-child(2)').addClass("lightBlue").css("opacity", 1);
              }
            });

       

         }
    })
  }




    var clicked = false;
    
    $('#tabelle1 tbody').on( 'click', 'td', function () {
    
      if (!clicked) {
      
      clicked = true;

      if ( !$(this).hasClass('ohnedetail') ) {
    
        //platinen reload pausieren
        table.api().liveAjax.pause();
    
         
        Id = table.api().row($(this).closest('tr')).data()[0]; 
        NutzenId = Id;
        ziel = "nutzen";
           
        getDetailEinmal(true);

        getHinzufuegenEinmal(true);

    
      }
    }
      
    } );


    
    //wenn hinzufügen geklickt wird
    
    $('#button1').on( 'click', function () {
    
    var modal =  $('#dataModal2') 
    modal.find('.modal-title').text('Nutzen hinzufügen');
    aktion = "modaleinfuegen";

      $.ajax({  
                        url:"verarbeitungNU/Modal.php",  
                        method:"post", 
                        data:{aktion:aktion},
                        success:function(data){  
                            $('#modalbody2').html(data);

                            /*
                            //datepicker übergeben
                            $('#datepicker').datepicker({
                              locale: 'de-de',
                              format: 'dd-mm-yyyy',
                              uiLibrary: 'bootstrap4'
                            });
                            
                            //aktuelles Datum mit übergeben
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

                            $('#datepicker').val(today);

    
                            /*datepicker reset übergeben
                            $("#reset-date").click(function(){
                            $('#datepicker').val("").datepicker("update");
                            })
                            */
                            
                      
                            $('#dataModal2').modal("show");   
                        }  
                  }); 
    
      
    } );
    
    //wenn auf bearbeiten geklickt wird
    
    $('#tabelle1 tbody').on( 'click', '#iconklasse2', function () {
      
      var modal =  $('#dataModal2') 
      modal.find('.modal-title').text('Nutzen bearbeiten');
      aktion = "modalbearbeiten";

        ziel = "nutzen";
        Id = table.api().row($(this).closest('tr')).data()[0]; 

        var Nr = table.api().row($(this).closest('tr')).data()[1];
        var Bearbeiter = table.api().row($(this).closest('tr')).data()[2];
        var Status = table.api().row($(this).closest('tr')).data()[3];
        var Erstellt = table.api().row($(this).closest('tr')).data()[4];
        var Fertigung = table.api().row($(this).closest('tr')).data()[5];
        var Abgeschlossen = table.api().row($(this).closest('tr')).data()[6];
        var Material = table.api().row($(this).closest('tr')).data()[7];
        var Endkupfer = table.api().row($(this).closest('tr')).data()[8];
        var Staerke = table.api().row($(this).closest('tr')).data()[9];
        var Lagen = table.api().row($(this).closest('tr')).data()[10];
        var Groesse = table.api().row($(this).closest('tr')).data()[11];
        var Int = table.api().row($(this).closest('tr')).data()[12];
        var Testdaten = table.api().row($(this).closest('tr')).data()[13];
        var Kommentar = table.api().row($(this).closest('tr')).data()[14];
    
        $.ajax({  
                        url:"verarbeitungNU/Modal.php",  
                        method:"post",  
                        data:{aktion:aktion, Id:Id, ziel:ziel, Nr:Nr, Bearbeiter:Bearbeiter, Status:Status, Material:Material, Endkupfer:Endkupfer, Staerke:Staerke, Lagen:Lagen, Erstellt:Erstellt, Fertigung:Fertigung, Abgeschlossen:Abgeschlossen, Groesse:Groesse, Int:Int, Testdaten:Testdaten, Kommentar:Kommentar},  
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
       


                            //datepicker2 übergeben
                            $('#datepicker2').datepicker({
                              locale: 'de-de',
                              format: 'dd-mm-yyyy',
                              uiLibrary: 'bootstrap4',
                              icons: {
                                rightIcon: '<i class="gj-icon datepickericon2">event</i></span>'
                              }
                            });

                            //datepicker2 reset übergeben
                            $("#reset-date2").click(function(){
                            $('#datepicker2').val("").datepicker("update");
                            })



                            //datepicker3 übergeben
                            $('#datepicker3').datepicker({
                              locale: 'de-de',
                              format: 'dd-mm-yyyy',
                              uiLibrary: 'bootstrap4',
                              icons: {
                                rightIcon: '<i class="gj-icon datepickericon3">event</i></span>'
                              }
                            });
                            //datepicker3 reset übergeben
                            $("#reset-date3").click(function(){
                            $('#datepicker3').val("").datepicker("update");
                            })


                      
                            $('#dataModal2').modal("show");  
                        }  
                  }); 
    
       
    
      
    } );
    
    
    
    //wenn löschen geklickt wird frag ob wirklich gelöscht werden soll, wenn ja übertrag id von Reihe zu loeschen.php und lade tabelleninhalt neu
    
    $('#tabelle1 tbody').on( 'click', '#iconklasse', function () {
      
      Id = table.api().row($(this).closest('tr')).data()[0];
      ziel = "nutzen";
      aktion = "loeschen";


      bootbox.confirm({
        size: "small",
        message: "Nutzen wirklich löschen?",
    
    
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
      
      $.ajax({  
                        url:"verarbeitungNU/loeschen.php",  
                        method:"post",  
                        dataType:"JSON",
                        data:{Id:Id, ziel:ziel, aktion:aktion},
                        success:function(data){  
    
                          $('#tabelle1').DataTable().ajax.reload();
  
                          var zustand = data.data;
                          var error = data.error;

                          if (zustand == 'erfolgreich') {
                            $('#result').hide().fadeIn(1000).html('<div class="alert alert-success alertm">Der Nutzen wurde erfolgreich gelöscht.</div>');
                          }
                          else if(zustand == 'dberror'){
                            $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Der Nutzen konnte nicht gelöscht werden. Der Fehler liegt bei der Durchführung des Datenbankbefehls. Fehler: ' + error +'</div>');
                          }
                          else if(zustand == 'fehlerhaft'){
                            $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Der Nutzen konnte nicht gelöscht werden. Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
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
    
    
                          //es wird geguckt ob tabelle leer ist, um dann darüber eine meldung auszugeben und tabellencontainer versteckt und live wird pausiert
                          setTimeout(function(){
                          if ( ! table.api().data().any() ) {
    
                          $('#leer').hide().fadeIn(1000).html('<div class="alert alert-info leer2">Es sind keine Nutzen vorhanden. Drücke auf das Plus-Symbol, um ein Nutzen hinzuzufügen.</div>');
    
                          table.api().liveAjax.pause();
    
                          var tabellecontainer = document.getElementById("tabellex");
                          tabellecontainer.style.visibility = "hidden"
    
                          var filterknopf = document.getElementById("button3");
                          filterknopf.style.visibility = "hidden"
                          }
                          }, 1000)
    
                        }   
     
                  }); 
                  
    
      }
      }});
    });







    
    //Filter instanz laden
    new $.fn.dataTable.SearchPanes(table, {});
    table.searchPanes.container().insertAfter('#leiste').addClass('collapse').attr("id","spCont");
    
    
    
    
    
    
    
    
    
    
    
    
    //weitere eigene functionen

    //damit modal Hintergründe noch stimmen wenn zwei geöffnet werden
    $(document).on('show.bs.modal', '.modal', function (event) {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
      pad = $('body').css('padding-right');
    });
    
    
    //damit modal noch scrollbar ist nachdem bei bootbox abfrage auf nein geklickt wurde
    $(document).on('hidden.bs.modal', '.bootbox.modal', function (e) {    
      if($(".modal").hasClass('show')) {
          $('body').addClass('modal-open');
          $('body').css("padding-right", pad);
      }
    })


    
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
      /*
      clearTimeout(timeOutId);
      timeOutId = 0;
      clearTimeout(timeOutId2);
      timeOutId2 = 0;
      */
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




