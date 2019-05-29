
try {
  var table = $(".table-datatable").DataTable({
    "info": false,
    "language": {
      "emptyTable": "Nenhum dado encontrado.",
      "search": "Procurar:",
      "lengthMenu": "Mostrar Por Página _MENU_",
      "processing": "Aguarde...",
      "zeroRecords": "Nenhum registro encontrado",
      "info": "Página _PAGE_ de _PAGES_",
      "infoEmpty": "Nenhum registro encontrado",
      "paginate": {
        "first": "Primeiro",
        "last": "Último",
        "next": "Próximo",
        "previous": "Anterior"
      }
    }
  });
} catch (err) {
  console.log(err);
  alert(err);
}


try {
  var ostable = $("#ordens_servico").DataTable({
    "columnDefs": [
      {
        "targets": [0],
        "visible": false,
        "searchable": false
      }
    ],
    "order": [[0, 'desc']],
    "info": false,
    "language": {
      "emptyTable": "Nenhum dado encontrado.",
      "search": "Procurar:",
      "lengthMenu": "Mostrar Por Página _MENU_",
      "processing": "Aguarde...",
      "zeroRecords": "Nenhum registro encontrado",
      "info": "Página _PAGE_ de _PAGES_",
      "infoEmpty": "Nenhum registro encontrado",
      "paginate": {
        "first": "Primeiro",
        "last": "Último",
        "next": "Próximo",
        "previous": "Anterior"
      }
    }
  });
} catch (err) {
  console.log(err);
}


$(document).ready(function () {
  $(window).keydown(function (event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
var noty_id = 0;

function btn_load(button) {
  button.attr('disabled', 'disabled');
  button.css('cursor', 'default');
  button.find('i').removeClass();
  button.find('i').addClass('fa fa-refresh fa-spin');
}


function btn_ativar(button) {
  button.removeAttr('disabled');
  button.css('cursor', 'pointer');
  button.find('i').removeClass();
  button.find('i').addClass('fa fa-dot-circle-o');
}

alerts = async (status, title = null, msg = "", layout = 'bottomLeft') => {
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

async function pre_loader_show() {
  $('.inner').show();
  // $('#preloader .inner').delay(1000).fadeIn();
  $('#preloader .inner').delay(500).fadeIn();
  $('#preloader').delay(350).fadeIn('slow');
}

const pre_loader_hide = () => {
  $('.inner').hide();
  $('#preloader .inner').delay(1000).fadeOut();
  $('#preloader').delay(350).fadeOut('slow');
}


function reformatDate(date) {
  date_hour = date.split(" ");

  dArr = date_hour[0].split("-");
  return dArr[2] + "/" + dArr[1] + "/" + dArr[0] + " " + date_hour[1];
}

function TestaCPF(cpf) {  
 cpf = cpf.replace(/[^\d]+/g,'');    
 if(cpf == '') return false;
   // Elimina CPFs invalidos conhecidos    

 if (cpf.length != 11 ||
   cpf == "00000000000" ||
   cpf == "11111111111" ||
   cpf == "22222222222" ||
   cpf == "33333333333" ||
   cpf == "44444444444" ||
   cpf == "55555555555" ||
   cpf == "66666666666" ||
   cpf == "77777777777" ||
   cpf == "88888888888" ||
   cpf == "99999999999")
   return false;   

 // Valida 1o digito
 add = 0;    
 for (i=0; i < 9; i ++)      
   add += parseInt(cpf.charAt(i)) * (10 - i);  
 rev = 11 - (add % 11);  
 if (rev == 10 || rev == 11)    
   rev = 0;    
 if (rev != parseInt(cpf.charAt(9)))    
   return false;      

 // Valida 2o digito
 add = 0;    
 for (i = 0; i < 10; i ++)       
   add += parseInt(cpf.charAt(i)) * (11 - i);  
 rev = 11 - (add % 11);  
 if (rev == 10 || rev == 11)
   rev = 0;    
 if (rev != parseInt(cpf.charAt(10)))
   return false;      
 return true;  
}
