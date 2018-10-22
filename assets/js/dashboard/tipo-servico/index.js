// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado
var acao;

/**
* Listener do modal ao pressionar enter
*/
$(document).keydown(function(e) {
    if ($("#ce_tipo_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#d_tipo_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
        $(acao).trigger("click");
    }
    else if($("#r_tipo_servico").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
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
        'tipo_servico_pk': $('#tipo_servico_pk').val(),
        'tipo_servico_nome': $('#nome-input').val(),
        'tipo_servico_abreviacao': $('#abreviacao-input').val(),
        'tipo_servico_desc': $('#descricao-input').val(),
        'prioridade_pk': $('#prioridade_fk').val(),
        'departamento_pk': $('#departamento_fk').val(),
        'senha': $('#senha-input').val(),
    }

    btn_load($('.submit'));
    btn_load($('#pula-para-confirmacao'));

    $.post(base_url + '/tipo_servico/insert_update', data).done(function (response) {

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
            tord =
            {
                'tipo_servico_pk': data['tipo_servico_pk'],
                'tipo_servico_nome': data['tipo_servico_nome'],
                'tipo_servico_abreviacao': data['tipo_servico_abreviacao'],
                'tipo_servico_desc': data['tipo_servico_desc'],
                'prioridade_padrao_fk': data['prioridade_pk'],
                'departamento_fk': data['departamento_pk'],
                'departamento_nome' : $('#departamento_fk :selected').text(),
                'prioridade_nome' : $('#prioridade_fk :selected').text(),
                'tipo_servico_status': 1
            }

            if (data['tipo_servico_pk'] == '') {
                tord['tipo_servico_pk'] = response.data.tipo_servico_pk;
                tipos_servicos.push(tord);
                alerts('success', 'Sucesso!', 'Tipo de Serviço inserido com sucesso');
            }
            else {
                for (var i in tipos_servicos) {
                    if (tipos_servicos[i]['tipo_servico_pk'] == data['tipo_servico_pk'])
                        break;
                }
                tipos_servicos[i] = (tord);
                alerts('success', 'Sucesso!', 'Tipo de Serviço modificado com sucesso');
            }
            $('#filter-ativo').change();
            $('#ce_tipo_servico').modal('hide');
        }
    }, "json");
}

$("#botao-finalizar").click(function () {
    send_data();
});


$(document).on('click', '.btn_reativar', function (event) {
    acao = "#btn-reativar";
    $('#btn-reativar').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
});

$(document).on('click', '.btn_novo', function (event) {
    acao = ".submit";
    $("ce_tipo_servico").find("modal-title").text("Editar Tipo de Serviço");    
});

$(document).on('click', '.btn_editar', function (event) {
    acao = ".submit";
    $("ce_tipo_servico").find("modal-title").text("Editar Tipo de Serviço");

    $('#tipo_servico_pk').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
    $('#nome-input').val(tipos_servicos[$(this).val()]["tipo_servico_nome"]);
    $('#abreviacao-input').val(tipos_servicos[$(this).val()]["tipo_servico_abreviacao"]);
    $('#descricao-input').val(tipos_servicos[$(this).val()]["tipo_servico_desc"]);
    $('#prioridade_fk').val(tipos_servicos[$(this).val()]["prioridade_padrao_fk"]);
    $('#departamento_fk').val(tipos_servicos[$(this).val()]["departamento_fk"]);
});


$(document).on('click', '.btn-desativar', function (event) {
    acao = "#btn-desativar";
    $('#btn-desativar').val(tipos_servicos[$(this).val()]["tipo_servico_pk"]);
    var data =
    {
        'tipo_servico_pk': tipos_servicos[$(this).val()]["tipo_servico_pk"]
    }
    $('#loading-tipo-servico-deactivate').show();
    $('#servicos-dependentes').hide();
    

    $.post(base_url + '/tipo_servico/get_dependent_services', data, function (response, textStatus, xhr) {
        console.log(response);

       

        if (response.code == 400) { //BAD REQUEST
            alerts('failed', 'Erro!', 'Erro inesperado, falha ao localizar serviços dependentes.');
        }
        else if(response.code == 200){ //se o response code for 200, ou seja SUCESSO:
            html = ''; //esta variável vai servir para eu preencher a div servicos-dependentes
            title = '';
            if(response.data.length == 0 || response.data == false){ //se não houver nenhum serviço:
                title = "Não há nenhum serviço dependente deste tipo de serviço.";
            }
            else { //se tiver 1 ou mais serviços dependentes:
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
                mensagem = "<br><b>OBS:</b> Você não poderá desativar este tipo de serviço enquanto houver(em) serviço(s) dependente(s).<br>";
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
            alerts('failed', 'Erro!', 'O formulário apresenta algum erro de validação');
        }
        else if (response.code == 403){
            alerts('failed', 'Erro!', 'O tipo de serviço ainda possui serviços dependentes.');   
        }
        else if (response.code == 401) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {
            alerts('success', 'Sucesso!', 'Tipo de Serviço desativado com sucesso');
            for (var i in tipos_servicos) {
                if (tipos_servicos[i]['tipo_servico_pk'] == data['tipo_servico_pk'])
                    break;
            }
            tipos_servicos[i]['tipo_servico_status'] = 0;
            $('#pass-modal-desativar').val('');
            $('#filter-ativo').change();
            $('#d_tipo_servico').modal('hide');
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
            for (var i in tipos_servicos) {
                if (tipos_servicos[i]['tipo_servico_pk'] == data['tipo_servico_pk'])
                    break;
            }
            tipos_servicos[i]['tipo_servico_status'] = 1;
            $('#pass-modal-reativar').val('');
            $('#filter-ativo').change();
            $('#r_tipo_servico').modal('hide');
        }
    });
});

$('#filter-ativo').on('change', function () {
    table.clear().draw();
    switch ($(this).val()) {
        case "todos":
        $.each(tipos_servicos, function (i, tord) {
            if (tord.tipo_servico_status == 1) {
                table.row.add([
                    tord.tipo_servico_nome,
                    tord.tipo_servico_abreviacao,
                    tord.tipo_servico_desc,
                    tord.prioridade_nome,
                    tord.departamento_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_tipo_servico">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d_tipo_servico">' +
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
                    tord.tipo_servico_nome,
                    tord.tipo_servico_abreviacao,
                    tord.tipo_servico_desc,
                    tord.prioridade_nome,
                    tord.departamento_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r_tipo_servico">' +
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
        $.each(tipos_servicos, function (i, tord) {
            if (tord.tipo_servico_status == 1) {
                table.row.add([
                    tord.tipo_servico_nome,
                    tord.tipo_servico_abreviacao,
                    tord.tipo_servico_desc,
                    tord.prioridade_nome,
                    tord.departamento_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_tipo_servico">' +
                    '<div class="d-none d-sm-block">' +
                    'Editar' +
                    '</div>' +
                    '<div class="d-block d-sm-none">' +
                    '<i class="fas fa-edit fa-fw"></i>' +
                    '</div>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d_tipo_servico">' +
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
        $.each(tipos_servicos, function (i, tord) {

            if (tord.tipo_servico_status == 0) {
                table.row.add([
                    tord.tipo_servico_nome,
                    tord.tipo_servico_abreviacao,
                    tord.tipo_servico_desc,
                    tord.prioridade_nome,
                    tord.departamento_nome,
                    '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + (i) + '" data-target="#r_tipo_servico">' +
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


