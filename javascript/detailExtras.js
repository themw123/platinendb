    //wenn bei detail auf löschen geklickt wird
    $('#dataModal1').on( 'click', '#iconklasse4', function () {
      


        Id = $(this).closest('a').attr('id');
        ziel = "nutzenplatinen";
        aktion = "detail";

       
        $.ajax({  
                          url:"verarbeitungNU/detailExtras/detailLoeschenSQL.php",  
                          method:"post",  
                          dataType:"JSON",
                          data:{Id:Id, ziel:ziel, aktion:aktion},
                          success:function(data){  
      
      
                            var zustand = data.data; 
                            if (zustand == 'erfolgreich') {
                              
                            Id = NutzenId;
                            ziel = "nutzen"
  
                           
                            getDetailEinmal();
                       
                            setTimeout(function() {
                              getHinzufuegenEinmal();
                            }, 500);
                            }
  
      
                          }   
       
                    }); 
                    
  
      });
      
  
  
          //wenn bei detail auf hinzufügen geklickt wird
          $('#dataModal1').on( 'click', '#iconklasse5', function () {
            
  
  
            var Id = $(this).closest('a').attr('id');
            NutzenId;
            var anzahl = $("#anzahl2").val();
            ziel = "platinen";
            aktion = "detail";
           
            $.ajax({  
                              url:"verarbeitungNU/detailExtras/detailhinzufuegenSQL.php",  
                              method:"post",
                              dataType:"JSON",  
                              data:{Id:Id, NutzenId:NutzenId, anzahl:anzahl, ziel:ziel, aktion:aktion},
                              success:function(data){  
        
                                  var zustand = data.data;
  
                                  if (zustand == 'erfolgreich') {
                                    
                                    Id = document.querySelector('.nutzenplatinen').id;
                                    ziel = "nutzen"
  
  
                                    setTimeout(function() {
                                      getDetailEinmal();
                                    }, 500);
                                    
                                    getHinzufuegenEinmal();
  
                                    
                                  }
  
                                  else if (zustand == 'fehlerhaft'){
                                    $('#resultanzahl').hide().fadeIn(1000).html('<div class="alert alert-danger resultalert p-1">Anzahl größer null erforderlich.</div>');
                                    
                                    window.setTimeout(function() {
                                    $(".resultalert").fadeTo(500, 0).slideUp(500, function(){
                                    $(this).remove();
                                    });  
                                    }, 5000);
                                    
                                  }
  
                                  else if (zustand == 'dberror'){
                                    $('#resultanzahl').hide().fadeIn(1000).html('<div class="alert alert-danger resultalert p-1">Es gab einen Fehler bei der Durchführung des Datenbankbefehls.</div>');
                                    
                                    window.setTimeout(function() {
                                    $(".resultalert").fadeTo(500, 0).slideUp(500, function(){
                                    $(this).remove();
                                    });  
                                    }, 5000);
                                    
                                  }
  
  
                        
                                
                            
          
                                }
          
                        });           
      
          });
      
  
          var warte = 0;
          //wenn bei Detail auf saveAnzahl geklickt wird   
          $('#dataModal1').on( 'click', '.saveanzahl', function (e) {
  
            if(warte == 0) { 
            warte = 1;  
            //klicken nur alle 300 sek
            var $link = $(e.target);
            e.preventDefault();
            if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 1000) {
                
  
              Id = $(this).closest('a').attr('id');
              ziel = "nutzenplatinen";
              dieses = $(this);
              anzahl = dieses.offsetParent().children().val();
              aktion = "detail";
  
              $.ajax({  
                url:"verarbeitungNU/detailExtras/saveAnzahl.php",  
                method:"post",  
                dataType:"JSON",
                data:{Id:Id, ziel:ziel, anzahl:anzahl, aktion:aktion},
                success:function(data){  
    
    
                  var zustand = data.data; 
                  if (zustand == 'erfolgreich') {
                    
                  Id = NutzenId;
                  ziel = "nutzen"
    
                  
                  
                  dieses.children().toggleClass('fas fa-save').toggleClass('fas fa-check-circle checkedcircle');
                  setTimeout(function(){
                  dieses.children().toggleClass('fas fa-check-circle checkedcircle').toggleClass('fas fa-save');
                  warte = 0;
                  }, 500);
                  
    
                  }
                  
                  else {
                    dieses.children().toggleClass('fas fa-save').toggleClass('fas fa-exclamation-triangle errorcircle');
                    setTimeout(function(){
                    dieses.children().toggleClass('fas fa-exclamation-triangle errorcircle').toggleClass('fas fa-save');
                    warte = 0;
                    }, 1000);
                  }
                
                }  
                 
    
          }); 
  
  
  
            }
            $link.data('lockedAt', +new Date());
          }
  
          });
      
  
  
         //Wenn auf Buttton9 geklickt wird
          $('#dataModal1').on( 'click', '#button9', function (e) {
              $(".button9").toggleClass("far fa-caret-square-up far fa-caret-square-down");
          });
  