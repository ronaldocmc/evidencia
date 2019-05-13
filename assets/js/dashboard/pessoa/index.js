class View extends GenericView {

    constructor() {
        super();
    }

}

const myView = new View();

myView.renderMenu();

const google = "6LfwtV4UAAAAANnXXJhkM87IgNRNQghpwW467CEc"; //REFATORAR PARA CONSTANTES


$(document).ready(function () {
    let image = loadAvatarImage();
    image.style = "width: 50%";
    image.className = "img-responsive img-thumbnail align-center";
    document.getElementById('img-div').appendChild(image);
});


function btn_load(button_submit) {
    button_submit.attr('disabled', 'disabled');
    button_submit.css('cursor', 'default');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-refresh fa-spin');
}



function btn_ativar(button_submit) {
    button_submit.removeAttr('disabled');
    button_submit.css('cursor', 'pointer');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-dot-circle-o');
}


$('#btn-save-profile').click(function () {
    send_data();
});

$('#btn-open-modal-save').click(function () {
    var form = document.getElementById("form-profile");

    if (form.checkValidity()) {
        send_data();
    } else {
        alerts('failed', "Erro de formulário", "O formulário apresenta campos obrigatórios que não foram preenchidos ou incorretos.");
    }
});


remove_image = () => {
    $('#img-input').attr('src', '');
    removeUpload();
}


send_data = () => {
    try {
        this.send($('#img-input').cropper('getCroppedCanvas').toDataURL());
    } catch (err) {
        this.send(null);
    }


};


send = (imagem) => {
    pre_loader_show();
    btn_load($('#btn-open-modal-save'));

    const formData = {
        'funcionario_pk': $('#funcionario_pk').val(),
        'funcionario_nome': $('#funcionario_nome').val(),
        'funcionario_cpf': $('#funcionario_cpf').val(),
        'funcionario_login': $('#funcionario_login').val(),
        'img': imagem
    }

    var URL = base_url + '/funcionario/save';

    $.post({
        url: URL,
        data: formData,
        success: function (response) {

            btn_ativar($('#btn-open-modal-save'));

            if (response.code != 200) {
                if (response.code == 400) {
                    alerts('failed', response.message, "O formulário apresenta campos obrigatórios que não foram preenchidos ou incorretos.");
                } else {
                    alerts('failed', response.message, response.data);
                }
            } else {
                alerts('success', 'Dados alterados com sucesso!', 'Aguarde um momento, pois atualizaremos os dados de sua conta.');
                updateAvatar();
                window.location.replace(base_url + '/minha_conta');
            }

            pre_loader_hide();
        }
    });
}

sendForm = () => {
    //Enviando os dados via post (AJAX)
    var data = {
        'senha': $('#old_password').val(),
        'new_password': $('#new_password').val(),
        // 'confirm_new_password': $('#confirm_new_password').val()
    };

    btn_load($('#btn-change-password'));

    $.post(base_url + '/funcionario/update_password', data).done(function (response) {

        btn_ativar($('#btn-change-password'));

        console.log(response);
        if (response.code == 200) {
            $('.area-acesso').append(alerts('success', 'Sucesso', 'Senha alterada com sucesso.'));
            $('#cp-pessoa').modal('hide');
            pre_loader_hide();
        }
        else {
            $('.area-acesso').append(alerts('failed', response.message, response.data.mensagem));
            pre_loader_hide();
        }
    }, "json");
}


$('#btn-change-password').click(function () {
    let new_pass = $('#new_password').val();
    let confirm_password = $('#confirm_new_password').val();

    if (new_pass != confirm_password) {
        $('.area-acesso').append(alerts('failed', 'Erro no formulário',
            'Nova senha e confirmar senha estão diferentes.'));
    } else {
        sendForm();
    }
});