const google = "6LfwtV4UAAAAANnXXJhkM87IgNRNQghpwW467CEc"; //REFATORAR PARA CONSTANTES

function btn_load(button_submit){
  button_submit.attr('disabled', 'disabled');
  button_submit.css('cursor', 'default');
  button_submit.find('i').removeClass();
  button_submit.find('i').addClass('fa fa-refresh fa-spin');
}


function btn_ativar(button_submit){
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
        $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => {
            this.send(blob);
        });
    } catch (err) {
        this.send(null);
    }


};


send = (imagem) => {
    pre_loader_show();
    btn_load($('#btn-open-modal-save'));

    const formData = new FormData();
    formData.append('pessoa_pk', $('#pessoa_pk').val());
    formData.append('pessoa_nome', $('#nome-input').val());
    formData.append('pessoa_cpf', $('#cpf-input').val());
    formData.append('contato_email', $('#email-input').val());
    formData.append('contato_tel', $('#telefone-input').val());
    formData.append('contato_cel', $('#celular-input').val());

    formData.append('logradouro_nome', $('#logradouro-input').val());
    formData.append('bairro', $('#bairro-input').val());
    formData.append('local_num', $('#numero-input').val());
    formData.append('complemento', $('#complemento-input').val());
    formData.append('estado_pk', $('#uf-input').val());
    formData.append('municipio_pk', $('#cidade-input').val());

    formData.append('img', imagem);
    var URL = base_url + '/pessoa/update';

    $.ajax({
        url: URL,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
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

                window.location.replace(base_url+'/pessoa/profile');
            }

            pre_loader_hide();
        }
    });
}

$('#btn-change-password').click(function(){

    //Enviando os dados via post (AJAX)
    var data = {
        'old_password': $('#old_password').val(),
        'new_password': $('#new_password').val(),
        'confirm_new_password': $('#confirm_new_password').val()
    };

    btn_load($('#btn-change-password'));

    $.post(base_url + '/pessoa/update_password', data).done(function (response) {

        btn_ativar($('#btn-change-password'));

        console.log(response);
        if (response.code == 200) {
            $('.area-acesso').append(alerts('success', 'Sucesso', 'Senha alterada com sucesso.'));
            $('#cp-pessoa').modal('hide');
            pre_loader_hide();
        }
        else {
            $('.area-acesso').append(alerts('failed', response.message, response.data));
            pre_loader_hide();
        }
    }, "json");
});