// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example

$(function () {
  let id = $('#aluno_id').val()

  $.get('/api/graficoFaixas/'+id)
  .done((res) => {
    console.log(res)
    montaGrafico2(res)
  })
  .fail((err) => {
    console.log(err)
  })
})

function montaGrafico2(res){
  var ctx2 = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: res.labels,
      datasets: [{
        data: res.data,
        restante: res.restante,
        andamento: res.andamento,
        // data: [res.total_branca, res.total_azul, res.total_roxa, res.total_marrom, res.total_preta],
        // backgroundColor: [
        // 'rgba(250, 250, 250, 1)', 
        // "rgba(0, 74, 173, 0.3)", 
        // "rgba(96, 35, 116, 0.3)", 
        // "rgba(77, 49, 49, 0.3)", 
        // "rgba(0, 0, 0, 0.3)"
        // ],
        borderColor: "rgba(0,0,0, 0.1)",

        backgroundColor: res.colors
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].data || '';

            let indice = tooltipItem.index
            let restante = chart.datasets[tooltipItem.datasetIndex].restante
            let andamento = chart.datasets[tooltipItem.datasetIndex].andamento
            if(andamento == datasetLabel[indice]) {
              return datasetLabel[indice] + ' aulas na faixa'
            }else if(restante == datasetLabel[indice]) {
              return datasetLabel[indice] + ' aulas para troca de faixa'
            }

            if(restante == 0){

              if(datasetLabel.length == indice+1){
                return "Faixa preta! ainda sem parametros para graduação!"
              }
            }

            return datasetLabel[indice] + ' total de aulas';
          }
        }
      },
      legend: {
        display: false
      },
      cutoutPercentage: 70,
    },
  });
}
