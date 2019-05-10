const view = new GenericView();

view.conditionalRender();

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

//Variáveis globais utilizadas no JS
var main_map;
var main_marker = null;
var posicao_selecionada = null;
var primeiro_editar = false;
var adicionar_imagem = 1; 
//-----------------------------------//


$(document).ready(function () {
    verify_os_situacoes();
});

function verify_os_situacoes() {
    ordens_servico.map(function (os) {
        if (os.situacao_atual_fk === "3" || os.situacao_atual_fk === "4" || os.situacao_atual_fk === "5") {
            $('#' + os.ordem_servico_pk).val(os.situacao_atual_fk);
            change_save_fields($('#btn' + os.ordem_servico_pk), $('#' + os.ordem_servico_pk));
        }
    });
}

$(document).on('click','#btn-trocar-funcionario',function(event) {
    btn_load($('#btn-trocar-funcionario'));

	var id_relatorio = $('#id-relatorio').val();

	var data = 
	{	
		'funcionario_fk': $('#novo-funcionario').val()
    }
    
    console.log(data);

	$.post(base_url+'/Relatorio/change_worker/'+id_relatorio,data).done(function (response) {	

        if (response.code == 501)
		{
            btn_ativar($('#btn-trocar-funcionario'));
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		} else if(response.code == 401 || response.code == 400)
        {   
            btn_ativar($('#btn-trocar-funcionario'));
            alerts('failed','Erro!', response.data.mensagem);

        }
		else if(response.code == 200)
		{
            alerts('success','Sucesso!','Aguarde enquanto recarregamos a página ...');
			location.reload();
            btn_ativar($('#btn-trocar-funcionario'));
		}
	});
});


$(document).on('click','#btn-deletar-relatorio',function(event) {
    btn_load($('#btn-deletar-relatorio'));

	var id_relatorio = $('#id-relatorio').val();
    var data = {}
    
	$.post(base_url+'/Relatorio/deactivate/'+id_relatorio, data).done(function (response) {
		btn_ativar($('#btn-deletar-relatorio'));
        if (response.code == 501)
		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 500)
		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 404)
		{
			alerts('failed','Erro!','Relatório não encontrado.');
		}
        else if(response.code == 401)
        {
            alerts('failed','Erro!',response.data);
        }
		else if(response.code == 200)
		{
			window.location.href = base_url+'/relatorio/';
		}
	});
});


function padroniza_data(data){
    
    string_date = data.split(" ");
    let date = new Date(string_date[0]);

    return ((date.getDate() + 1) + ' / ' + (date.getMonth() + 1) +' / ' + date.getFullYear());
}

//Função que aguarda o clique no botão editar e preenche os campos do modal
$(document).on('click', '.btn_editar', function (event) 
{
    $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
    posicao_selecionada = $(this).val();

    //var data = get_departamento_and_tiposervico(ordens_servico[posicao_selecionada]['tipo_servico_fk'])//Aqui eu vou fazer uma função que vai requisitar percorrer departamentos e encontrar o fk
    var servico_selecionado_pk = ordens_servico[posicao_selecionada]['ordem_servico_criacao'];
    $('#os_data').val(ordens_servico[posicao_selecionada]['ordem_servico_criacao']);
    $('#codigo_os').val(ordens_servico[posicao_selecionada]['ordem_servico_cod']);
    $('#ordem_servico_pk').val(parseInt(ordens_servico[posicao_selecionada]['ordem_servico_pk']));
    $('#ordem_servico_desc').val(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
    $('#departamento').val(ordens_servico[posicao_selecionada]['departamento_nome']);
    $('#tipo_servico').val(ordens_servico[posicao_selecionada]['tipo_servico_nome']);
    $('#servico_pk').val(ordens_servico[posicao_selecionada]['servico_nome']);
    $('#situacao_pk').val(ordens_servico[posicao_selecionada]['situacao_nome']);
    // console.log($('#situacao_pk').val());
    $('#prioridade_pk').val(ordens_servico[posicao_selecionada]['prioridade_nome']);
    $('#procedencia_pk').val(ordens_servico[posicao_selecionada]['procedencia_nome']);
    $('#setor_pk').val(ordens_servico[posicao_selecionada]['setor_nome']);
    $("#latitude").val(ordens_servico[posicao_selecionada]['localizacao_lat']);
    $("#longitude").val(ordens_servico[posicao_selecionada]['localizacao_long']);
    $("#image-upload-div").hide();
    $("#bairro-input").val(ordens_servico[posicao_selecionada]['localizacao_bairro']);
    $("#logradouro-input").val(ordens_servico[posicao_selecionada]['localizacao_rua']);   
    $("#numero-input").val(ordens_servico[posicao_selecionada]['localizacao_num']);    
    $("#estado_pk").val("SP");
    $("#cidade-input").val("Presidente Prudente");
    $("#complemento-input").val(ordens_servico[posicao_selecionada]['localizacao_ponto_referencia']);

    var data_local;
    var local = "";

    var latlng = {lat: parseFloat(ordens_servico[posicao_selecionada]['localizacao_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['localizacao_long'])}
    populaLatLong(latlng);
    main_map.setCenter(latlng);
    criarMarcacao(latlng);

    $("#logradouro-input").removeClass('loading');
    $("#bairro-input").removeClass('loading');
    $('#ce_ordem_servico').modal('show');

});


$('.save_situacao').click(function () {
    let os = $(this).val();
    change_situacao(os);
    change_save_fields($(this), $('#' + os));
});


function change_situacao (os) {
    let formData = new FormData();

    formData.append('ordem_servico_comentario', 'Situação alterada no relatório.');
    formData.append('situacao_atual_fk', parseInt($('#' + os).val()));
    formData.append('image_os', null);

    var URL = base_url + '/ordem_servico/insert_situacao/' + os;
    $.ajax({
        url: URL,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.code == 200) {
                alerts('success', "Sucesso!", "Histórico criado com sucesso!");
                
            }
        }, 
        error: function (response) {
        }
    });
}


function change_save_fields(button, select) {
    $(select).prop("disabled",true);
    $(button).prop("disabled",true);
    $(button).removeClass("btn-primary").addClass("btn-success");
    $(button).html("Salvo");
}


$("#btn-restaurar").click(function() {
    btn_load($('#btn-restaurar'));
    var id_relatorio = $('#id-relatorio').val();
    var senha = $("#pass-modal-restaurar").val();

    if(senha == ""){
        alerts('failed','Erro!','Senha incorreta');
        btn_ativar($('#btn-restaurar'));
        return;
    }

    var data = 
    {
        'senha' : senha
    }

    $.post(base_url+'/Relatorio/receive_report/'+id_relatorio,data).done(function (response) {
        btn_ativar($('#btn-deletar-relatorio'));
        if (response.code == 200) {
            alerts('success','Sucesso!','Relatório recebido com sucesso.');
            $('#restaurar_os').modal('hide');
        }
        else if (response.code == 404) {
            alerts('success','Sucesso!','Relatório já foi recebido! Não há ordens de serviço para serem finalizadas.');
            $('#restaurar_os').modal('hide');
        }
        else if (response.code == 401) {
            alerts('failed','Erro!','Senha incorreta');
        }

        $("#pass-modal-restaurar").val("");
        window.location.href = base_url+'/Relatorio';
    }, "json");
});
