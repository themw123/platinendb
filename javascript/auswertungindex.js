setDefaultSettings();

getData();

$("#downloadpdf").on("click", function () {
  var newCanvas = document.querySelector("#chart1");

  //create image from dummy canvas
  var newCanvasImg = newCanvas.toDataURL("image/jpg", 1.0);

  //creates PDF from img
  window.jsPDF = window.jspdf.jsPDF;
  var doc = new jsPDF("landscape");
  doc.text(15, 15, "Platinenaufträge");
  doc.addImage(newCanvasImg, "JPG", 10, 20, 230, 100);
  doc.save("Platinenaufträge.pdf");
});

$('[data-toggle="popover"]').popover();

//popovers schließen wenn woanders geklickt wird
$("html").on("click", function (e) {
  if (typeof $(e.target).data("original-title") == "undefined") {
    $('[data-toggle="popover"]').popover("hide");
  }
});

$("#zeitinterval").on("input", function () {
  setSettings();
  getData();
});

$("#jahroderletzten").on("input", function () {
  setSettings();
  getData();
});

$("#auftraggeber").on("change", function () {
  setSettings();
  getData();
});

function setDefaultSettings() {
  zeitraum = "monate";
  letzten = "x";
  auftraggeber = "";
  setJahre();
  datar = null;
}

function setSettings() {
  var jahroderletzten = $("#jahrlabel").text();

  zeitraum = $("#zeitinterval option:selected").val().toLowerCase();
  jahr = $("#jahroderletzten option:selected").val();
  letzten = $("#jahroderletzten option:selected").val();
  auftraggeber = $("#auftraggeber option:selected").val();

  if (zeitraum == "jahre" && jahroderletzten == "Jahr:") {
    $("#jahrlabel").text("Letzten:");
    setLetzten();
    $("#jahroderletzten option:last").attr("selected", "selected");
  } else if (zeitraum == "monate" && jahroderletzten == "Letzten:") {
    $("#jahrlabel").text("Jahr:");
    setJahre();
  }

  zeitraum = $("#zeitinterval option:selected").val().toLowerCase();
  jahr = $("#jahroderletzten option:selected").val();
  letzten = $("#jahroderletzten option:selected").val();
  auftraggeber = $("#auftraggeber option:selected").val();
}

function setLetzten() {
  var $el = $("#jahroderletzten");
  $el.empty();
  /*
  for (var i = 1; i <= 5; i++) {
    $el.append($("<option></option>").attr("value", i).text(i));
  }
  */
  $el.append($("<option></option>").attr("value", 1).text(1));
  $el.append($("<option></option>").attr("value", 3).text(3));
  $el.append($("<option></option>").attr("value", 5).text(5));
  $el.append($("<option></option>").attr("value", 10).text(10));
}

function setJahre() {
  $("#jahroderletzten").empty();
  jahr = new Date().getFullYear();
  for (var i = 0; i <= 10; i++) {
    $("#jahroderletzten").append(new Option(jahr - i, jahr - i));
  }
}

function getData() {
  aktion = "auswertung";
  $.ajax({
    url: "verarbeitungAus/auswertung.php",
    method: "post",
    data: {
      aktion: aktion,
      zeitraum: zeitraum,
      letzten: letzten,
      jahr: jahr,
      auftraggeber: auftraggeber,
    },
    success: function (data) {
      try {
        chart1.destroy();
      } catch (error) {}

      var zustand = data.data[1];

      if (zustand == "leer") {
        $("#result")
          .hide()
          .fadeIn(1000)
          .html(
            '<div class="alert alert-warning alertm">Es wurden keine Daten gefunden</div>'
          );
      } else if (zustand == "fehlerhaft") {
        $("#result")
          .hide()
          .fadeIn(1000)
          .html(
            '<div class="alert alert-danger alertm">Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.</div>'
          );
      } else if (zustand == "dberror") {
        $("#result")
          .hide()
          .fadeIn(1000)
          .html(
            '<div class="alert alert-danger alertm">Fehler bei der Durchführung des Datenbankbefehls. Fehler: ' +
              data.data[2] +
              "</div>"
          );
      }

      //erfolgreich
      else {
        datar = data.data;

        if (zeitraum == "jahre") {
          var datarTemp = new Array();

          if (letzten == 0) {
            for (var i = datar.length; i >= 0; i--) {
              datarTemp.push(datar[i]);
            }
          } else {
            for (var i = letzten - 1; i >= 0; i--) {
              datarTemp.push(datar[i]);
            }
          }

          datar = datarTemp;
          datar = datar.filter((item) => item);
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

      window.setTimeout(function () {
        $(".alertm").fadeTo(500, 0);
        $(this).remove();
      }, 5000);
    },
  });
}

function setChart() {

  //summen zähler
  var totalizer = {
    id: 'totalizer',
    beforeUpdate: chart1 => {
        let totals = {}
        let utmost = 0
  
            chart1.data.datasets.forEach((dataset, datasetIndex) => {
                if (chart1.isDatasetVisible(datasetIndex)) {
                    utmost = datasetIndex
                        dataset.data.forEach((value, index) => {
                            totals[index] = (totals[index] || 0) + parseInt(value, 10);
                        })
                }
            })
            chart1.$totalizer = {
            totals: totals,
            utmost: utmost
        }
    }
  }


  ctx1 = document.getElementById("chart1");
  Chart.register(ChartDataLabels);

  chart1 = new Chart(ctx1, {
    type: "bar",
    data: {
      labels: getLabels(),
      datasets: [
        {
          label: "# intern",
          data: getValues("int"),
          backgroundColor: "rgba(0, 94, 169, 0.6)",
          borderColor: "rgba(0, 0, 0, 0.6)",
          borderWidth: 1,
        },

        {
          label: "# extern",
          data: getValues("ext"),
          backgroundColor: "rgba(0, 172, 240, 0.6)",
          borderColor: "rgba(0, 0, 0, 0.6)",
          borderWidth: 1,
        },
        {
          label: '# summe',
          //dumme array mit null für jede spalte
          data: Array(datar.length).fill(0),
          backgroundColor: "rgba(52, 225, 235)",
          datalabels: {
              backgroundColor: function (context) {

                  return "rgba(52, 225, 235)";

              },
              formatter: (value, ctx) => {
                  const total = ctx.chart.$totalizer.totals[ctx.dataIndex];
                  return total.toLocaleString('de-DE', {})
              },
              align: "end",
              anchor: "end",
              display: function (ctx) {
                const total = ctx.chart.$totalizer.totals[ctx.dataIndex];
                const intern = ctx.chart.data.datasets[0].data;
                const extern = ctx.chart.data.datasets[1].data;
                var multipleBars = (intern[ctx.dataIndex] >= 1 && extern[ctx.dataIndex] >= 1);
                //summe nur anzeigen wenn total größer 0 und wenn es intern und extern gibt
                return total > 0 && multipleBars && ctx.datasetIndex === ctx.chart.$totalizer.utmost;
              }
          }
        },


      ],
    },
    options: {
      plugins: {
        datalabels: {
          anchor: "end",
          align: "start",
          labels: {
            value: {
              color: "black",
            },
          },
          display: function (context) {
            return context.dataset.data[context.dataIndex] >= 1;
          },
        },
      },
      scales: {
        x: {
          stacked: true,
        },
        y: {
          stacked: true,
          beginAtZero: true,
          //höhe einen über maximaler balkenlänge setzten, damit summenangabe reinpasst in chart
          suggestedMax: Math.max(...datar.map(item => item[1])) + 1,
        },
      },
    },
    plugins: [totalizer],
  });
}

function getLabels() {
  var labels = new Array();

  for (let i = 0; i < datar.length; i++) {
    labels.push(datar[i][0] + "");
  }

  return labels;
}

function getValues(intorext) {
  var dataArray = new Array();

  for (let i = 0; i < datar.length; i++) {
    var stelle = 0;
    if (intorext == "int") {
      stelle = 2;
    } else {
      stelle = 3;
    }

    dataArray[i] = datar[i][stelle];
  }

  return dataArray;
}
