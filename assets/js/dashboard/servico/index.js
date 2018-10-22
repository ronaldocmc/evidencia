// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado
var acao;

/**
* Listener do modal ao pressionar enter
*/
$(document).keydown(function(e) {
    if ($("#ce_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#d_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#r_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
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
        'servico_pk': $('#servico_pk').val(),
        'servico_nome': $('#nome-input').val(),
        'servico_abreviacao': $('#abreviacao-input').val(),
        'servico_desc': $('#descricao-input').val(),
        'situacao_padrao_pk': $('#situacao_fk').val(),
        'tipo_servico_pk': $('#tipo_servico_fk').val(),
        'senha': $('#senha-input').val(),
    }

    btn_load($('.submit'));
    btn_load($('#pula-para-confirmacao'));


    $.post(base_url + '/servico/insert_update', data).done(function (response) {

        btn_ativar($('.submit'));
        btn_ativar($('#pula-para-confirmacao'));


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
            serv =
            {
                'servico_pk': data['servico_pk'],
                'servico_nome': data['servico_nome'],
                'servico_abreviacao': data['servico_abreviacao'],
                'servico_desc': data['servico_desc'],
                'situacao_padrao_fk': data['situacao_padrao_pk'],
                'situacao_nome': $('#situacao_pk :selected').text(),
                'tipo_servico_nome': $('#tipo_servico_pk :selected').text(),
                'tipo_servico_fk': data['tipo_servico_pk'],
                'servico_status' : 1 
            }

            if (data['servico_pk'] == '') {
                serv['servico_pk'] = response.data.servico_pk;
                servicos.push(serv);
                alerts('success', 'Sucesso!', 'Serviço inserido com sucesso');
            }
            else {
                for (var i in servicos) {
                    if (servicos[i]['servico_pk'] == data['servico_pk'])
                        break;
                }
                servicos[i] = (serv);
                alerts('success', 'Sucesso!', 'Serviço modificado com sucesso');
            }
            $('#filter-ativo').change();
            $('#ce_servico').modal('hide');
        }
    }, "json");
}

$(".submit").click(function () {
    send_data();
});

$(document).on('click', '.btn_reativar', function (event) {
    $('#btn-reativar').val(servicos[$(this).val()]["servico_pk"]);
    acao = "#btn-reativar";
});

$(document).on('click', '.btn_novo', function (event) {
    acao = ".submit";
    $("#ce_servico").find(".modal-title").text("Novo Serviço");
});

$(document).on('click', '.btn_editar', function (event) {
    acao = ".submit";
    $("#ce_servico").find(".modal-title").text("Editar Serviço");

    $('#servico_pk').val(servicos[$(this).val()]["servico_pk"]);
    $('#nome-input').val(servicos[$(this).val()]["servico_nome"]);
    $('#abreviacao-input').val(servicos[$(this).val()]["servico_abreviacao"]);
    $('#descricao-input').val(servicos[$(this).val()]["servico_desc"]);
    $('#situacao_padrao_fk').val(servicos[$(this).val()]["situacao_padrao_fk"]);
    $('#tipo_servico_fk').val(servicos[$(this).val()]["tipo_servico_fk"]);
});

$(document).on('click', '.btn-desativar', function (event) {
    $('#btn-desativar').val(servicos[$(this).val()]["servico_pk"]);
    acao = "#btn-desativar";
});

$(document).on('click', '#btn-desativar', function (event) {
    var data =
    {
        'servico_pk': $(this).val(),
        'senha': $('#pass-modal-desativar').val()
    }

    btn_load($('#btn-desativar'));
    $('#pass-modal-desativar').val('')

    $.post(base_url + '/servico/deactivate', data, function (response, textStatus, xhr) {

        btn_ativar($('#btn-desativar'));

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
            alerts('success', 'Sucesso!', 'Serviço desativado com sucesso');
            for (var i in servicos) {
                if (servicos[i]['servico_pk'] == data['servico_pk'])
                    break;
            }
            servicos[i]['servico_status'] = 0;
            $('#pass-modal-desativar').val('');
            $('#filter-ativo').change();
            $('#d_servico').modal('hide');
        }
    });
});



$(document).on('click', '#btn-reativar', function (event) {
    var data =
    {
        'servico_pk': $(this).val(),
        'senha': $('#pass-modal-reativar').val()
    }

    btn_load($('#btn-reativar'));
    $('#pass-modal-reativar').val('')

    $.post(base_url + '/servico/activate', data, function (response, textStatus, xhr) {

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
            for (var i in servicos) {
                if (servicos[i]['servico_pk'] == data['servico_pk'])
                    break;
            }
            servicos[i]['servico_status'] = 1;
            $('#pass-modal-reativar').val('');
            $('#filter-ativo').change();
            $('#r_servico').modal('hide');
        }
    });
});

$('#filter-ativo').on('change', function () {
    table.clear().draw();
    switch ($(this).val()) {
        case "todos":
        $.each(servicos, function (i, serv) {
            if (serv.servico_status == 1) {
                table.row.add([
                    serv.servico_nome,
                    serv.servico_abreviacao,
                    serv.servico_desc,
                    serv.situacao_nome,
                    serv.tipo_servico_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_servico">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d_servico">' +
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
                    serv.servico_nome,
                    serv.servico_abreviacao,
                    serv.servico_desc,
                    serv.situacao_nome,
                    serv.tipo_servico_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r_servico">' +
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
        $.each(servicos, function (i, serv) {
            if (serv.servico_status == 1) {
                table.row.add([
                    serv.servico_nome,
                    serv.servico_abreviacao,
                    serv.servico_desc,
                    serv.situacao_nome,
                    serv.tipo_servico_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_servico">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d_servico">' +
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
        $.each(servicos, function (i, serv) {

            if (serv.servico_status == 0) {
                table.row.add([
                 serv.servico_nome,
                 serv.servico_abreviacao,
                 serv.servico_desc,
                 serv.situacao_nome,
                 serv.tipo_servico_nome,
                 '<div class="btn-group">' +
                 '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r_servico">' +
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


