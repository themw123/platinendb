
getData();




function getData() {

  aktion = "auswertung";

  //var zeit = table.api().row($(this).closest('tr')).data()[1];
  var zeitraum = "jahre";
  
  
  var letzten = "0";
  var jahr = "2022";

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
                      






                    }
                        
                    window.setTimeout(function() {
                      $(".alertm").fadeTo(500, 0);
                      $(this).remove();
                    }, 5000);
                  }  
            }); 

 

}




function setChart() {

  const ctx1 = document.getElementById('chart1').getContext('2d');
  const chart1 = new Chart(ctx1, {
      type: 'bar',
      data: {
          labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
          datasets: [{
              label: '# of Votes',
              data: [12, 19, 3, 5, 2, 3],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
}


