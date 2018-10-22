jQuery(document).ready(function($) {
	console.log();
	$(this).find('.overview-item').each(function(index, el) {
		$(this).parent().addClass('col-md-'+Math.trunc(12/$.find('.overview-item').length)+' pt-4 pt-md-0');
    var value = $.find('.overview-item').length%2===1?$.find('.overview-item').length+1:$.find('.overview-item').length;
    $(this).parent().addClass('col-sm-'+Math.trunc(12/value *2));
		$(this).addClass('overview-item--c'+((($(this).index('.overview-item')+1)%4)+1));
	});
});



(function ($) {
  // USE STRICT
  "use strict";

  try {
    //CHART CURVA COLUNA
    var ctx_a = $(".curva_continua");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      if (ctx) {
        ctx.height = 130;
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: values[0],
            type: 'line',
            datasets: [{
              data: values[1],
              backgroundColor: 'rgba(255,255,255,.1)',
              borderColor: 'rgba(255,255,255,.55)',
            },]
          },
          options: {
            maintainAspectRatio: false,
            legend: {
              display: false
            },
            layout: {
              padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: -13
              }
            },
            responsive: true,
            scales: {
              xAxes: [{
                gridLines: {
                  color: 'transparent',
                  zeroLineColor: 'transparent'
                },
                ticks: {
                  fontSize: 2,
                  fontColor: 'transparent'
                }
              }],
              yAxes: [{
                display: false,
                ticks: {
                  display: false,
                  beginAtZero: true
                }
              }]
            },
            title: {
              display: false,
            },
            elements: {
              line: {
                borderWidth: 0
              },
              point: {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 4
              }
            }
          }
        });
      }
    });



    //LINHA PONTILHADA
    var ctx_a = $(".linha_pontilhada");

    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      if (ctx) {
        ctx.height = 130;
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: values[0],
            type: 'line',
            datasets: [{
              data: values[1],
              backgroundColor: 'transparent',
              borderColor: 'rgba(255,255,255,.55)',
            },]
          },
          options: {

            maintainAspectRatio: false,
            legend: {
              display: false
            },
            responsive: true,
            tooltips: {
              mode: 'index',
              titleFontSize: 12,
              titleFontColor: '#000',
              bodyFontColor: '#000',
              backgroundColor: '#fff',
              titleFontFamily: 'Montserrat',
              bodyFontFamily: 'Montserrat',
              cornerRadius: 3,
              intersect: false,
            },
            scales: {
              xAxes: [{
                gridLines: {
                  color: 'transparent',
                  zeroLineColor: 'transparent'
                },
                ticks: {
                  fontSize: 2,
                  fontColor: 'transparent'
                }
              }],
              yAxes: [{
                display: false,
                ticks: {
                  display: false,
                  beginAtZero: true
                }
              }]
            },
            title: {
              display: false,
            },
            elements: {
              line: {
                tension: 0.00001,
                borderWidth: 1
              },
              point: {
                radius: 4,
                hitRadius: 10,
                hoverRadius: 4
              }
            }
          }
        });
      }
    });


    //Curva pontilhada
    var ctx_a = $(".curva_pontilhada");

    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      if (ctx) {
        ctx.height = 130;
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: values[0],
            type: 'line',
            datasets: [{
              data: values[1],
              backgroundColor: 'transparent',
              borderColor: 'rgba(255,255,255,.55)',
            },]
          },
          options: {

            maintainAspectRatio: false,
            legend: {
              display: false
            },
            responsive: true,
            tooltips: {
              mode: 'index',
              titleFontSize: 12,
              titleFontColor: '#000',
              bodyFontColor: '#000',
              backgroundColor: '#fff',
              titleFontFamily: 'Montserrat',
              bodyFontFamily: 'Montserrat',
              cornerRadius: 3,
              intersect: false,
            },
            scales: {
              xAxes: [{
                gridLines: {
                  color: 'transparent',
                  zeroLineColor: 'transparent'
                },
                ticks: {
                  fontSize: 2,
                  fontColor: 'transparent'
                }
              }],
              yAxes: [{
                display: false,
                ticks: {
                  display: false,
                  beginAtZero: true
                }
              }]
            },
            title: {
              display: false,
            },
            elements: {
              line: {
                borderWidth: 1
              },
              point: {
                radius: 4,
                hitRadius: 10,
                hoverRadius: 4
              }
            }
          }
        });
      }
    });


    //BARRA
    var ctx_a = $(".barra");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      if (ctx) {
        ctx.height = 130;
        var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: values[0],
            datasets: [
              {
                data: values[1],
                borderColor: "transparent",
                borderWidth: "0",
                backgroundColor: "rgba(255,255,255,0.3)"
              }
            ]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                display: false,
                categoryPercentage: 1,
                barPercentage: 0.65
              }],
              yAxes: [{
                display: false,
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      }
    });


    //Barra com cores
    var ctx_a = $(".barra-com-cores");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      var colors = ctx.data('colors');
      if (ctx) {
        ctx.height = 130;
        var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: values[0],
            datasets: [
              {
                data: values[1],
                borderColor: "transparent",
                borderWidth: "0",
                backgroundColor: colors,
                hoverBackgroundColor: colors
              }
            ],
            labels: values[0]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                display: true,
                categoryPercentage: .3,
                barPercentage: .3
              }],
              yAxes: [{
                display: true,
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      }
    });


    // Percent Chart
    var ctx_a = $(".porcentagem-rosca");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      var colors = ctx.data('colors');
      if (ctx) {
        ctx.width  = 280;
        var myChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            datasets: [
              {
                label: "Sets",
                data: values[1],
                backgroundColor: colors,
                hoverBackgroundColor: colors
              }
            ],
            labels: values[0]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            cutoutPercentage: 55,
            animation: {
              animateScale: true,
              animateRotate: true
            },
            legend: {
              display: false
            },
            tooltips: {
              titleFontFamily: "Poppins",
              xPadding: 15,
              yPadding: 10,
              caretPadding: 0,
              bodyFontSize: 16
            }
          }
        });
      }
    });

    // Recent Report
    var ctx_a = $(".recent-rep-chart");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      var colors = ctx.data('colors');

      var lb = values[1];
      var sets = [];
      var max_g;
      for (var i in values[0])
      {
        var max_l = Math.max(...values[(parseInt(i)+1)*2]);
        max_g = max_l>max_g?max_l:max_g;
        var r = parseInt(colors[i].substring(1,3),16);
        var g = parseInt(colors[i].substring(3,5),16);
        var b = parseInt(colors[i].substring(5,7),16);
        var color = 'rgba(' + r + ', ' + g + ', ' + b + ', .3)';
        var dataset = {
          label: values[0][i],
          backgroundColor: color,
          borderColor: 'transparent',
          pointHoverBackgroundColor: '#fff',
          borderWidth: 0,
          data: values[(parseInt(i)+1)*2]
        }
        sets.push(dataset);
      }
      if (ctx) {
        ctx.height = 250;
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: lb,
            datasets: sets
          },
          options: {
            maintainAspectRatio: true,
            legend: {
              display: false
            },
            responsive: true,
            scales: {
              xAxes: [{
                gridLines: {
                  drawOnChartArea: true,
                  color: '#f2f2f2'
                },
                ticks: {
                  fontFamily: "Poppins",
                  fontSize: 12
                }
              }],
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  maxTicksLimit: 5,
                  stepSize: max_g/4,
                  max: max_g,
                  fontFamily: "Poppins",
                  fontSize: 12
                },
                gridLines: {
                  display: true,
                  color: '#f2f2f2'

                }
              }]
            },
            elements: {
              point: {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 4,
                hoverBorderWidth: 3
              }
            }


          }
        });
      }




    });


    // Recent Report
    var ctx_a = $(".recent-rep2-chart");
    ctx_a.each(function(index, ctx) {
      ctx = $(ctx);
      var values = ctx.data('params');
      var colors = ctx.data('colors');

      var lb = values[1];
      var sets = [];
      var max_g;
      for (var i in values[0])
      {
        var max_l = Math.max(...values[(parseInt(i)+1)*2]);
        max_g = max_l>max_g?max_l:max_g;
        var r = parseInt(colors[i].substring(1,3),16);
        var g = parseInt(colors[i].substring(3,5),16);
        var b = parseInt(colors[i].substring(5,7),16);
        var color = 'rgba(' + (r+100) + ', ' + (g+100) + ', ' + (b+100) + ', 0.9)';
        var dataset = {
          label: values[0][i],
          backgroundColor: color,
          borderColor: 'rgba(' + r + ', ' + g + ', ' + b + ', 0.9)',
          pointHoverBackgroundColor: '#fff',
          borderWidth: 0,
          data: values[(parseInt(i)+1)*2]
        }
        sets.push(dataset);
      }
      if (ctx) {
        ctx.height = 250;
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: lb,
            datasets: sets
          },
          options: {
            maintainAspectRatio: true,
            legend: {
              display: false
            },
            responsive: true,
            scales: {
              xAxes: [{
                gridLines: {
                  drawOnChartArea: true,
                  color: '#f2f2f2'
                },
                ticks: {
                  fontFamily: "Poppins",
                  fontSize: 12
                }
              }],
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  maxTicksLimit: 5,
                  stepSize: max_g/4,
                  max: max_g,
                  fontFamily: "Poppins",
                  fontSize: 12
                },
                gridLines: {
                  display: true,
                  color: '#f2f2f2'

                }
              }]
            },
            elements: {
              point: {
                radius: 0,
                hitRadius: 10,
                hoverRadius: 4,
                hoverBorderWidth: 3
              },
              line: {
                tension: 0
              }
            }
          }
        });
      }
    });

  } catch (error) {
    console.log(error);
  }


})(jQuery);
