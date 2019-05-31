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
            alerts('failed','Erro!',response.data.mensagem);
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

    var URL = base_url + '/Ordem_Servico/insert_situacao/' + os;
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
