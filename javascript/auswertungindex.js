

setDefaultSettings();

getData();




$("#zeitinterval").on("input", function() {
    jahroderletztechange = false;
    setSettings();
});

$("#jahroderletzten").on("input", function() {
    zeitintervalchange = false;
    setSettings();
});



function setDefaultSettings() {
  aktion = "auswertung";
  zeitraum = "monate";
  jahroderletztechange = false;
  zeitintervalchange = true;

  letzten = "3";
  
  jahr = new Date().getFullYear();
  for (var i = 0; i <= 5; i++) {
    $('#jahroderletzten').append(new Option((jahr-i), (jahr-i)));
  }


  datar = null;


}

function setSettings() {

  zeitraum = $("#zeitinterval option:selected").val().toLowerCase();
  jahr = $("#jahroderletzten option:selected").val();

  if(zeitraum == "jahre" && jahroderletztechange == false) {

    jahroderletztechange = true;

    $('#jahrlabel').text("Letzten:");

    var $el = $("#jahroderletzten");
    $el.empty();
    for(var i = 1; i <= 3; i++) {
      $el.append($("<option></option>").attr("value", i).text(i));
    }

    $("#jahroderletzten").val("3");
  
  }

  else if(zeitraum == "monate"  && zeitintervalchange == true){
    zeitintervalchange = true;
    $('#jahrlabel').text("Jahr:");
    jahr = new Date().getFullYear();
    $('#jahroderletzten').empty();
    for (var i = 0; i <= 5; i++) {
      $('#jahroderletzten').append(new Option((jahr-i), (jahr-i)));
    }
  }

  letzten = $("#jahroderletzten option:selected").val();

  getData();

}









function getData() {

  $.ajax({  
                  url:"verarbeitungAus/auswertung.php",  
                  method:"post",  
                  data:{aktion:aktion, zeitraum:zeitraum, letzten:letzten, jahr:jahr},  
                  success:function(data){
                    
                    try {
                      chart1.destroy();
                    }
                    catch(error){
                  
                    }


                    var zustand = data.data[1];

                    if (zustand == 'leer') {
                      $('#result').hide().fadeIn(1000).html('<div class="alert alert-warning alertm">Es wurden keine Daten gefunden</div>');
                    }
                    else if(zustand == 'fehlerhaft'){
                      $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>');
                    }
                    else if(zustand == 'dberror'){
                      $('#result').hide().fadeIn(1000).html('<div class="alert alert-danger alertm">Fehler bei der Durchführung des Datenbankbefehls. Fehler: ' + data.data[2] +'</div>');
                    }

                    //erfolgreich
                    else {

                      datar = data;

                      if(zeitraum == "jahre") {
                        var datarTemp = new Array;
                        for(var i = letzten-1; i >= 0; i--) {
                          datarTemp.push(datar.data[i]);
                        }
                        datar = datarTemp;
                      }
                      else if(zeitraum == "monate") {
                        datar = datar.data;
                      }


                      /*
                      if(zeitraum == "monate") {
                        $.each(datar, function( key, value ){
                          $('#jahroderletzten').append('<option value="">' + value + '</option>');
                        });
                      }
                      */

                      setChart();

                    }
                        
                    window.setTimeout(function() {
                      $(".alertm").fadeTo(500, 0);
                      $(this).remove();
                    }, 5000);
                  }  
            }); 

 

}
















function setChart() {

  ctx1 = document.getElementById('chart1').getContext('2d');
  chart1 = new Chart(ctx1, {

      type: 'bar',
      data: {

          labels: getLabels(),
          
          datasets: [

            {
              label: '# intern',
              data: getValues('int'),
              backgroundColor: [
                'rgba(46, 196, 87, 0.6)',
              ],
              borderColor: ['rgba(0, 0, 0, 0.6)'],
              borderWidth: 1
            },

            {
              label: '# extern',
              data: getValues('ext'),
              backgroundColor: [
                'rgba(0, 172, 240, 0.6)',
              ],
              borderColor: ['rgba(0, 0, 0, 0.6)'],
              borderWidth: 1
            },


            
        ]
      },
      options: {


          plugins: {
            /*
              title: {
                  display: true,
                  text: 'Platinenaufträge',
                  padding: {
                    bottom: 30
                  },
                  font: {
                    size: 20
                  }
              }
              */
          },

          scales: {
              x: {
                stacked: true,
              },
              y: {
                stacked: true,
                beginAtZero: true
              }
          },



      }
  });
}




function getLabels() {

  var labels = new Array;

  for (let i = 0; i < datar.length; i++) {
    labels.push(datar[i][0]+'');
  } 
  

  return labels;

}


function getValues(intorext) {
  
  var dataArray = new Array;


  for (let i = 0; i < datar.length; i++) {
    var stelle = 0;
    if(intorext == "int") {
      stelle = 2;
    }
    else {
      stelle = 3;
    }

    dataArray[i] = datar[i][stelle];
  
  } 

  return dataArray;

}







