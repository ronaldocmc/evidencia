
// const google = "6LfwtV4UAAAAANnXXJhkM87IgNRNQghpwW467CEc";
const base_url = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname.split('/')[1];

//Função que verifica se o usuário inseriu uma entrada corretamente
verify_data = () => {

  //Recebendo a entrada inserida pelo usuário
  var e = $('#username').val(), s = $('#password').val();

  //Verificando se os campos foram preenchidos corretamente
  if ($.isEmptyObject(e) || $.isEmptyObject(s)) {
    $('.area-acesso').append(alerts_access('no_data'));
  } else {
    login_send(e, s);
  }

}

verify_email = () => {
  //Recebendo a entrada inserida pelo usuário
  var e = $('#email_recover').val();

  //Verificando se o campo email foi preenchido e se é um email válido

  if ($.isEmptyObject(e)) {
    $('.area-acesso').append(alerts_access('no_data'));
  } else {
    email_send(e);
  }

}


//Função que envia os dados inseridos pelo usuário no formulário de login via AJAX - JSON
login_send = (e, s) => {
  pre_loader_show();
  var t;
  //Solicitando autenticação recaptcha para o usuário (Não sou robo).
  //grecaptcha.execute(google, { action: 'homepage' }).then(function (token) {

  t = 'token';
  //Enviando os dados via post (AJAX)
  $.post(base_url + '/access/login/', { login: e, password: s, 'g-recaptcha-response': t }).done(async function (response) {
    wich_alert(response);
    pre_loader_hide();
    if (response.code == 200) {
      await localStorage.setItem('permissions', response.data.permissions);
      await localStorage.setItem('is_superusuario', response.data.superusuario);
      window.location.reload();
    }
  }, "json");
}

//Função que envia uma requisição para recuperação de senha
email_send = (email) => {
  // pre_loader_show();
  var t;

  //Solicitando autenticação recaptcha para o usuário (Não sou robo).
  // grecaptcha.execute(google, { action: 'homepage' }).then(function (token) {
  // t = token;

  //Enviando os dados via post (AJAX)
  $.post(base_url + '/contact/restore_password', { email, 'g-recaptcha-response': true }).done(function (response) {
    wich_alert(response);
  }, "json");

  // });

}

var noty_id = 0;

alerts_access = async (status, title = null, msg = null) => {
  var reply;
  switch (status) {
    case 'no_data': {
      reply = '<div style="text-align: justify;" class="alert alert-warning alert-dismissible fade show" role="alert">' +
        '<strong>Preencha o formulário!</strong><br> Você não preencheu os dados necessários.' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
      break
    };
    case 'success_login': {
      reply = '<div style="text-align: justify;" class="alert alert-success alert-dismissible fade show" role="alert">' +
        '<strong>Bem-vindo ao Evidência!</strong><br> Para navegar sobre o Sistema, utilize o menu lateral.' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
      break
    };
    case 'success_email': {
      reply = '<div style="text-align: justify;" class="alert alert-success alert-dismissible fade show" role="alert">' +
        '<strong>E-mail enviado!</strong><br> Foi enviado um e-mail para ' + $('#email_recover').val() + '. Caso não encontre a mensagem, verifique também a caixa de spam e o lixo eletrônico.' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
      break
    };
    case 'incorrect_data': {
      reply = '<div style="text-align: justify;" class="alert alert-danger alert-dismissible fade show" role="alert">' +
        '<strong>E-mail ou senha incorretos!</strong> Preencha seu e-mail e senha corretamente.' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
      break
    };
    case 'response': {
      reply = '<div style="text-align: justify;" class="alert alert-danger alert-dismissible fade show" role="alert">' +
        '<strong>' + title + '</strong><br>' + msg +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
      break
    };
    case 'failed_email': {
      reply = '<div style="text-align: justify;" class="alert alert-danger alert-dismissible fade show" role="alert">' +
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
    layout: 'topLeft',
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

$(".press_enter").on("keydown", function (event) {
  if (event.which == 13) {
    verify_data();
  }
});


$('#esqueci-minha-senha').click(function () {
  $('#modal-acesso').modal('hide');
});


$(window).on('load', function () {
  $('.inner').hide();
  $('#preloader .inner').delay(1000).fadeOut();
  $('#preloader').delay(350).fadeOut('slow');
})

pre_loader_show = () => {
  $('.inner').show();
  $('#preloader .inner').delay(1000).fadeIn();
  $('#preloader').delay(350).fadeIn('slow');
}

pre_loader_hide = () => {
  $('.inner').hide();
  $('#preloader .inner').delay(1000).fadeOut();
  $('#preloader').delay(350).fadeOut('slow');
}