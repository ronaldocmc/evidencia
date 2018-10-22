
// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado
var acao;

/**
* Listener do modal ao pressionar enter
*/
$(document).keydown(function(e) {
    if ($("#ce_situacao").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#d-situacao").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#r-situacao").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
});


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
        'situacao_pk': $('#situacao_pk').val(),
        'situacao_nome': $('#nome-input').val(),
        'situacao_descricao': $('#descricao-input').val(),
        'situacao_foto_obrigatoria': $('#foto-input:checked').length,
        'senha': $('#senha-input').val(),
    }

    btn_load($('#pula-para-confirmacao'));
    btn_load($('.submit'));

    $.post(base_url + '/situacao/insert_update', data).done(function (response) {

        btn_ativar($('#pula-para-confirmacao'));
        btn_ativar($('.submit'));

        if (response.code == 400) {
            show_errors(response);
            alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s) de validação');
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            sit =
            {
                'situacao_pk': data['situacao_pk'],
                'situacao_nome': data['situacao_nome'],
                'situacao_descricao': data['situacao_descricao'],
                'situacao_foto_obrigatoria': data['situacao_foto_obrigatoria'],
                'situacao_ativo': 1
            }

            if (data['situacao_pk'] == '') {
                sit['situacao_pk'] = response.data.situacao_pk;
                situacoes.push(sit);
                alerts('success', 'Sucesso!', 'Situação inserida com sucesso');
            }
            else {
                for (var i in situacoes) {
                    if (situacoes[i]['situacao_pk'] == data['situacao_pk'])
                        break;
                }
                situacoes[i] = (sit);
                alerts('success', 'Sucesso!', 'Situação modificada com sucesso');
            }
            $('#filter-ativo').change();
            $('#ce_situacao').modal('hide');
        }
    }, "json");   
}

$(".submit").click(function () {
    send_data();
});

$(document).on('click', '.btn_reativar', function (event) {
    acao = "#btn-reativar";
    $('#btn-reativar').val(situacoes[$(this).val()]["situacao_pk"]);
});

$(document).on('click', '.btn-novo', function (event) {
    acao = ".submit";
    $("ce_situacao").find(".modal-title").text("Nova Situação");
});

$(document).on('click', '.btn_editar', function (event) {
    acao = ".submit";
    $("#ce_situacao").find(".modal-title").text("Editar Situação");

    $('#situacao_pk').val(situacoes[$(this).val()]["situacao_pk"]);
    $('#nome-input').val(situacoes[$(this).val()]["situacao_nome"]);
    $('#descricao-input').val(situacoes[$(this).val()]["situacao_descricao"]);
    if(situacoes[$(this).val()]["situacao_foto_obrigatoria"] == '1'){
        $('#foto-input').prop('checked', true); 
    }else{
        $('#foto-input').prop('checked', false); 
    }

});

$(document).on('click', '.btn-desativar', function (event) {
    acao = "#btn-desativar";
    $('#btn-desativar').val(situacoes[$(this).val()]["situacao_pk"]);

    $('#loading-situacao-deactivate').show();
    $('#servicos-dependentes').hide();

    var data = 
    {
        'situacao_pk': situacoes[$(this).val()]["situacao_pk"]
    }

    $.post(base_url + '/situacao/get_dependents', data, function (response, textStatus, xhr) {
        if (response.code == 400) {
            alerts('failed', 'Erro!', 'O formulário apresenta algum erro de validação');
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 200) {
            html = ''; //esta variável vai servir para eu preencher a div tipo-servicos-dependentes
            title = '';
            if(response.data.length == 0 || response.data == false){ //se não houver nenhum serviço:
                title = "Não há nenhum serviço dependente desta situação.";
            }
            else { //se tiver 1 ou mais tipos de serviço dependentes:
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
                mensagem = "<br><b>OBS:</b> Você não poderá desativar esta prioridade enquanto houver(em) serviço(s) dependente(s).<br>";
                html += mensagem;

            } //fecha o 1 ou mais serivços dependentes
            $('#servicos-dependentes').html('<br>'+'<h5>'+title + '</h5>' + html+'</br>');
            $('#servicos-dependentes').show();
            $('#loading-situacao-deactivate').hide();
        }       

    });

});

$(document).on('click', '#btn-desativar', function (event) {
    var data =
    {
        'situacao_pk': $(this).val(),
        'senha': $('#pass-modal-desativar').val()
    }

    btn_load($('#btn-desativar'));

    $.post(base_url + '/situacao/deactivate', data, function (response, textStatus, xhr) {

        btn_ativar($('#btn-desativar'));

        if (response.code == 400) {
            alerts('failed', 'Erro!', response.data.erro);
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            alerts('success', 'Sucesso!', 'Situação desativada com sucesso');
            for (var i in situacoes) {
                if (situacoes[i]['situacao_pk'] == data['situacao_pk'])
                    break;
            }
            situacoes[i]['situacao_ativo'] = 0;
            $('#pass-modal-desativar').val('');
            $('#filter-ativo').change();
            $('#d-situacao').modal('hide');
        }
    });
});



$(document).on('click', '#btn-reativar', function (event) {
    var data =
    {
        'situacao_pk': $(this).val(),
        'senha': $('#pass-modal-reativar').val()
    }

    btn_load($('#btn-reativar'));

    $.post(base_url + '/situacao/activate', data, function (response, textStatus, xhr) {

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
            alerts('success', 'Sucesso!', 'Situação reativada com sucesso');
            for (var i in situacoes) {
                if (situacoes[i]['situacao_pk'] == data['situacao_pk'])
                    break;
            }
            situacoes[i]['situacao_ativo'] = 1;
            $('#pass-modal-reativar').val('');
            $('#filter-ativo').change();
            $('#r-situacao').modal('hide');
        }
    });
});

$('#filter-ativo').on('change', function () {
    table.clear().draw();

    switch ($(this).val()) {

        case "todos":
        $.each(situacoes, function (i, sit) {

            if (sit.situacao_ativo == 1) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    sit.situacao_foto_obrigatoria == true ? '<i style="color:green" class="far fa-check-circle"></i>' : '<i style="color:red" class="far fa-times-circle"></i>' ,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Desativar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-times fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '</div>'

                    ]).draw(false);
            }
            else {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    sit.situacao_foto_obrigatoria == true ? '<i style="color:green" class="far fa-check-circle"></i>' : '<i style="color:red" class="far fa-times-circle"></i>',
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r-situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Reativar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-check-circle fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '</div>'

                    ]).draw(false);
            }
        });
        break;
        case "ativos":
        $.each(situacoes, function (i, sit) {

            if (sit.situacao_ativo == 1) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    sit.situacao_foto_obrigatoria == true ? '<i style="color:green" class="far fa-check-circle"></i>' : '<i style="color:red" class="far fa-times-circle"></i>',
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Desativar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-times fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '</div>'

                    ]).draw(false);
            }
        });
        break;
        case "desativados":
        $.each(situacoes, function (i, sit) {

            if (sit.situacao_ativo == 0) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    sit.situacao_foto_obrigatoria == true ? '<i style="color:green" class="far fa-check-circle"></i>' : '<i style="color:red" class="far fa-times-circle"></i>',
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r-situacao">' +
                    '<div class="d-none d-sm-block">' +
                    'Reativar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-check-circle fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '</div>'

                    ]).draw(false);
            }
        });
        break;
    }
});


