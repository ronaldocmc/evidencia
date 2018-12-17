try{
  var table = $(".table-datatable").DataTable({
      "info" : false,
      "language":{
          "emptyTable": "Nenhum dado encontrado.",
          "search":     "Procurar:",
          "lengthMenu": "Mostrar Por Página _MENU_ registros",
          "processing":     "Aguarde...",
          "zeroRecords": "Nenhum registro encontrado",
          "info": "Página _PAGE_ de _PAGES_",
          "infoEmpty": "Nenhum registro encontrado",
          "paginate": {                                        
                  "first":      "Primeiro",
                  "last":       "Último",
                  "next":       "Próximo",
                  "previous":   "Anterior"
              }
      }
  });
}catch(err){
  console.log(err);
}


$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
var noty_id = 0;

alerts = async(status, title = null, msg = null, layout = 'bottomLeft') => {
  var reply;
  switch (status) {
    case 'success': {
      reply = '<div style="text-align: justify;" class="alert alert-success alert-dismissible fade show" role="alert">' +
      '<strong>' + title + '</strong><br>' + msg +
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
      '<span aria-hidden="true">&times;</span>' +
      '</button>' +
      '</div>';
      break
    };
    case 'failed': {
      reply = '<div style="text-align: justify;" class="alert alert-danger alert-dismissible fade show" role="alert">' +
      '<strong>' + title + '</strong><br>' + msg +
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
      '<span aria-hidden="true">&times;</span>' +
      '</button>' +
      '</div>';
      break
    };
    case 'warning': {
      reply = '<div style="text-align: justify;" class="alert alert-warning alert-dismissible fade show" role="alert">' +
      '<strong>' + title + '</strong><br>' + msg +
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
      '<span aria-hidden="true">&times;</span>' +
      '</button>' +
      '</div>';
      break
    }


  }

  $('.metroui').remove();


  var n = noty({
    text: reply,
    layout: layout,
    closeWith: ['click'],
    theme: 'metroui',
    animation: {
      open: 'animated flipInX',
      close: 'animated flipOutX',
      easing: 'swing',
      speed: 500
    }
  });

  setTimeout(() => {
    $('.metroui').fadeOut();
  }, 4000);

}

$(window).on('load', function () {
    $('.inner').hide();
    $('#preloader .inner').delay(1000).fadeOut();
    $('#preloader').delay(350).fadeOut('slow');
})

// pre_loader_show = () => {
//     $('.inner').show();
//     $('#preloader .inner').delay(1000).fadeIn();
//     $('#preloader').delay(350).fadeIn('slow');
// }

async function pre_loader_show(){
  $('.inner').show();
  // $('#preloader .inner').delay(1000).fadeIn();
  $('#preloader .inner').delay(500).fadeIn();
  $('#preloader').delay(350).fadeIn('slow');
}

pre_loader_hide = () => {
  $('.inner').hide();
  $('#preloader .inner').delay(1000).fadeOut();
  $('#preloader').delay(350).fadeOut('slow');
}


function reformatDate(date) {
  date_hour = date.split(" ");

  dArr = date_hour[0].split("-");  
  return dArr[2] + "/" + dArr[1] + "/" + dArr[0] + " " + date_hour[1]; 
}