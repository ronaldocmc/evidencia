
// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado
var acao;
var index;

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
    var data;

    data = {
        'situacao_nome': $('#nome-input').val(),
        'situacao_descricao': $('#descricao-input').val()
    }

    if($('#situacao_pk').val() != ''){
        data.situacao_pk = $('#situacao_pk').val();
    }

    btn_load($('#pula-para-confirmacao'));
    btn_load($('.submit'));

    $.post(base_url + '/situacao/insert_update', data).done(function (response) {

        btn_ativar($('#pula-para-confirmacao'));
        btn_ativar($('.submit'));

        if (response.code == 404) {
            show_errors(response);
            
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
                'ativo': 1
            }
            
            if ($('#situacao_pk').val() == ''){
                sit['situacao_pk'] = response.data;
                situacoes.push(sit);
            }else {
                for (var i in situacoes) {
                    if (situacoes[i]['situacao_pk'] == data['situacao_pk']){
                        situacoes[i] = sit; 
                        break;
                    }     
                }    
            }

            alerts('success', 'Sucesso!', 'Situação modificada com sucesso');
            $('#filter-ativo').change();
            $('#ce_situacao').modal('hide');
        }
    }, "json");   
}

$(".submit").click(function () {
    send_data();
});

// $(document).on('click', '.btn_reativar', function (event) {
//     acao = "#btn-reativar";
//     $('#btn-reativar').val(situacoes[$(this).val()]["situacao_pk"]);
// });

$(document).on('click', '.btn-novo', function (event) {
    acao = ".submit";
    $("ce_situacao").find(".modal-title").text("Nova Situação");
});

$(document).on('click', '.btn_editar', function (event) {
    acao = ".submit";
    $("#ce_situacao").find(".modal-title").text("Editar Situação");
    let situacao_pk = $(this).val();
    $('#situacao_pk').val(situacao_pk);

    $.each(situacoes, function (i, sit){
        if(sit.situacao_pk == situacao_pk){
            $('#nome-input').val(sit.situacao_nome);
            $('#descricao-input').val(sit.situacao_descricao);
            return false;
        }
    });


});

$(document).on('click', '.btn-desativar', function (event) {
    index = $(this).val();
    $('#btn-desativar').removeAttr("disabled");
    $('#alerta').show();
    $('#servicos-dependentes').html("");
});

$(document).on('click', '.btn_reativar', function (event) {
    index = $(this).val();
});

$(document).on('click', '#btn-desativar', function (event) {

    var data =
    {
        'situacao_pk':  index,
        'senha': $('#pass-modal-desativar').val()
    }

    console.log(data);

    $.post(base_url + '/situacao/deactivate', data, function (response, textStatus, xhr) {

        btn_ativar($('#btn-desativar'));
        $('#servicos-dependentes').hide();

        if (response.code == 401) {

            let alerta = "A situação não pode ser excluída, pois é situação padrão dos serviços abaixo: ";
            let html = '';
            let servicos  = response.data.mensagem.split(",");

            html += "<ul style='margin-left: 30px'>";
            for( i = 0; i < servicos.length-1 ; i++){
                    html += '<li>'+ servicos[i] +'</li>';
                }

                html += "</ul>";
                mensagem = "<br><b>OBS:</b> Você não poderá desativar esta situação enquanto houver(em) serviço(s) dependente(s).<br>";
                html += mensagem;

            $('#alerta').hide();
            $('#servicos-dependentes').html(
                '<h4 style="text-align: center" class="text-danger">' + 
                    '<i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO' + 
                '</h4> '+ 
                '<br>'+'<h5>'+ alerta + '</h5><br>' + html+'</br>'
            );

            $('#servicos-dependentes').show();
            $('#btn-desativar').attr("disabled", true);

            alerts('failed', 'Erro!', "Não foi possível efetuar a operação!");
        }
        else if (response.code == 403) {
            alerts('failed', 'Erro!', 'Senha informada incorreta');
        }
        else if (response.code == 500) {
            alerts('failed', 'Erro!', 'Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
        }
        else {

            for (var i in situacoes) {
                if (situacoes[i]['situacao_pk'] == data['situacao_pk']){
                    situacoes[i]['ativo'] = 0;
                    break;

                }       
            }

            $('#pass-modal-desativar').val('');
            $('#filter-ativo').change();
            $('#d-situacao').modal('hide');

            alerts('success', 'Sucesso!', 'Situação desativada com sucesso');
        }
    });
});



$(document).on('click', '#btn-reativar', function (event) {

    var data =
    {
        'situacao_pk': index,
        'senha': $('#pass-modal-reativar').val()
    }
    console.log(data);

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
            situacoes[i]['ativo'] = 1;
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

            if (sit.ativo == 1) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + sit.situacao_pk + '" data-target="#ce_situacao">' +
                            '<div class="d-none d-sm-block"> Editar </div>' +
                            '<div class="d-block d-sm-none">' +
                                '<i class="fas fa-edit fa-fw"></i>' +
                            '</div>' +
                        '</button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + sit.situacao_pk + '" data-target="#d-situacao">' +
                            '<div class="d-none d-sm-block"> Desativar </div>' +
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
                    '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + sit.situacao_pk  + '" data-target="#r-situacao">' +
                            '<div class="d-none d-sm-block"> Reativar </div>' +
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

            if (sit.ativo == 1) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + sit.situacao_pk + '" data-target="#ce_situacao">' +
                            '<div class="d-none d-sm-block"> Editar </div>' +
                            '<div class="d-block d-sm-none">' +
                                '<i class="fas fa-edit fa-fw"></i>' +
                            '</div>' +
                        '</button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + sit.situacao_pk + '" data-target="#d-situacao">' +
                            '<div class="d-none d-sm-block"> Desativar </div>' +
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

            if (sit.ativo == 0) {
                table.row.add([
                    sit.situacao_nome,
                    sit.situacao_descricao,
                    '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="' + sit.situacao_pk + '" data-target="#r-situacao">' +
                            '<div class="d-none d-sm-block"> Reativar </div>' +
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


