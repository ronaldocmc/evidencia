// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado

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

function send_data(){
    var data =
    {
        'tipo_servico_pk': $('#tipo_servico_pk').val(),
        'tipo_servico_nome': $('#nome-input').val(),
        'tipo_servico_abreviacao': $('#abreviacao-input').val(),
        'tipo_servico_desc': $('#descricao-input').val(),
        'prioridade_padrao_fk': $('#prioridade_pk').val(),
        'departamento_fk': $('#departamento_pk').val(),
        'senha': $('#senha-input').val(),
    }

    btn_load($('.submit'));
    btn_load($('#pula-para-confirmacao'));

    $.post(base_url + '/tipo_servico/save', data).done(function (response) {

        btn_ativar($('.submit'));
        btn_ativar($('#pula-para-confirmacao'));

        if (response.code == 400) {
            alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s) de validação');
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            alerts('success', 'Sucesso', 'Operação realizada com sucesso');
            document.location.reload(false);
        }
    }, "json");
}

$("#botao-finalizar").click(function () {
    send_data();
});


$(document).on('click', '.btn_reativar', function (event) {
    $('#btn-reativar').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
});

$(document).on('click', '.btn_novo', function (event) {
    $("#ce_title").text("Novo Tipo de Serviço");    
});

$(document).on('click', '.btn_editar', function (event) {
    $("#ce_title").text("Editar Tipo de Serviço");

    $('#tipo_servico_pk').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
    $('#nome-input').val(tipos_servicos[$(this).val()]["tipo_servico_nome"]);
    $('#abreviacao-input').val(tipos_servicos[$(this).val()]["tipo_servico_abreviacao"]);
    $('#descricao-input').val(tipos_servicos[$(this).val()]["tipo_servico_desc"]);
    
    if (tipos_servicos[$(this).val()]["prioridade_padrao_fk"] !== null) {
        $('#prioridade_pk').val(tipos_servicos[$(this).val()]["prioridade_padrao_fk"]);
    } else {
        $('#prioridade_pk').val('');
    }

    $('#departamento_pk').val(tipos_servicos[$(this).val()]["departamento_fk"]);
});


$(document).on('click', '.btn-desativar', function (event) {
    $('#btn-desativar').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
    var data =
    {
        'tipo_servico_pk': tipos_servicos[$(this).val()]["tipo_servico_pk"]
    }
    $('#loading-tipo-servico-deactivate').show();
    $('#servicos-dependentes').hide();
    

    $.post(base_url + '/tipo_servico/get_dependent_services', data, function (response, textStatus, xhr) {

        if (response.code == 400) {
            alerts('failed', 'Erro!', 'Erro inesperado, falha ao localizar serviços dependentes.');
        }
        else if(response.code == 200){
            html = ''; 
            title = '';
            if(response.data.length == 0 || response.data == false){ 
                title = "Não há nenhum serviço dependente deste tipo de serviço.";
            }
            else { 
                var mensagem = "";
                if(response.data.length == 1){ 
                    title = 'Este é o serviço que será afetado:';
                }
                else if(response.data.length > 1){
                     title = 'Estes são os serviços que serão afetados:';
                }
                html += "<ul style='margin-left: 15px'>";
                for( var i in response.data){
                    html += '<li>'+ response.data[i].servico_nome +'</li>';
                }
                html += "</ul>";
                mensagem = "<br> Você não poderá desativar este tipo de serviço enquanto houver(em) serviço(s) dependente(s).<br>";
                html += mensagem;

            } //fecha o 1 ou mais serivços dependentes
            $('#servicos-dependentes').html('<br>'+'<h5>'+title + '</h5>' + html+'</br>');
            $('#servicos-dependentes').show();
            $('#loading-tipo-servico-deactivate').hide();
        } //fecha o se for SUCESSO

       
    });//fecha o post
});

$(document).on('click', '#btn-desativar', function (event) {
    var data =
    {
        'tipo_servico_pk': $(this).val(),
        'senha': $('#pass-modal-desativar').val()
    }
    btn_load($('#btn-desativar'));
    $.post(base_url + '/tipo_servico/deactivate', data, function (response, textStatus, xhr) {
        btn_ativar($('#btn-desativar'));


        if (response.code == 400) {
            alerts('failed', 'Erro!', response.data.mensagem);
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            alerts('success', 'Sucesso!', 'Tipo de Serviço desativado com sucesso');
            document.location.reload(false);
        }
    });
});



$(document).on('click', '#btn-reativar', function (event) {
    var data =
    {
        'tipo_servico_pk': $(this).val(),
        'senha': $('#pass-modal-reativar').val()
    }

     btn_load($('#btn-reativar'));

    $.post(base_url + '/tipo_servico/activate', data, function (response, textStatus, xhr) {
        btn_ativar($('#btn-reativar'));

        if (response.code == 400) {
            alerts('failed', 'Erro!', 'O formulário apresenta algum erro de validação');
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            alerts('success', 'Sucesso!', 'Tipo de Serviço reativado com sucesso');
            document.location.reload(false);
        }
    });
});