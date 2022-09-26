
setChart();


function setChart() {

  aktion = "auswertung";

  zeitraum = "monate";
  
  
  letzten = "4";
  jahr = "2022";
  datar = null;


  $.ajax({  
                  url:"verarbeitungAus/auswertung.php",  
                  method:"post",  
                  data:{aktion:aktion, zeitraum:zeitraum, letzten:letzten, jahr:jahr},  
                  success:function(data){
                    
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

                      set();

                    }
                        
                    window.setTimeout(function() {
                      $(".alertm").fadeTo(500, 0);
                      $(this).remove();
                    }, 5000);
                  }  
            }); 

 

}
















function set() {

  if(zeitraum == "jahre") {
    var datarTemp = new Array;
    for(var i = letzten-1; i >= 0; i--) {
      datarTemp.push(datar.data[i]);
    }
    datar = datarTemp;
  }
  else if(zeitraum == "monate") {
    var datarTemp = new Array;
    for(var i = 0; i < datar.data.length; i++) {
      datarTemp.push(datar.data[i]);
    }
    datar = datarTemp;
  }


  const ctx1 = document.getElementById('chart1').getContext('2d');
  const chart1 = new Chart(ctx1, {

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


  var laenge = 0;
  if(zeitraum == "jahre") {
    laenge = letzten;
  }
  else if(zeitraum == "monate"){
    laenge = datar.length;
  }

  for (let i = 0; i < laenge; i++) {
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







