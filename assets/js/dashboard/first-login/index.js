$(document).ready(function () {
    $('#first_login').modal('show');
});

$('#login-input').keyup(function () {
    $('.login').html($('#login-input').val());

    if ($('#login-input').val() == "")
        $('.login').html("login");
});


const google = "6LfwtV4UAAAAANnXXJhkM87IgNRNQghpwW467CEc"; //REFATORAR
$("#submit").click(function () {
    var data =
    {
        'pessoa_fk': $('#pessoa_fk').val(),
        'acesso_login': $('#login-input').val(),
        'acesso_senha': $('#senha-input').val(),    
        'confirme-senha': $('#confirme-senha-input').val(),
        'token': $('#token').val()
    }
    $.post(base_url + '/contact/create_access', data).done(function (response) {
        if (response.code == 400) {
            show_errors(response);
            alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s) de validação');
        } else {
            console.log(response);
            var t;
            //Solicitando autenticação recaptcha para o usuário (Não sou robo).
            grecaptcha.execute(google, { action: 'homepage' }).then(function (token) {
                console.log(data.acesso_login+ "@" + $('#organizacao_fk').val());
                t = token;
                $.post(base_url + '/access/login', { login: data.acesso_login+ "@" + $('#organizacao_fk').val(), password: data.acesso_senha, 'g-recaptcha-response': t }).done(function (response) {
                    if (response.code == 200) {
                        
                        window.location.replace(base_url + '/access');
                    }
                });
            });
        }
    });
});