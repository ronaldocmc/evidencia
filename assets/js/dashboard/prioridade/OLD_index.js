
// Variável que diz ao listener da tecla ENTER qual ação deve ser feita ao ser pressionado
var acao;

/**
* Listener do modal ao pressionar enter
*/
$(document).keydown(function(e) {
	if ($("#ce_prioridade").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
		$(acao).trigger("click");
	}
	else if($("#d-prioridade").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
		$(acao).trigger("click");
	}
});


/**
* Submição dos dados fazendo uma requisição ao servidor, para criar ou alterar uma prioridade 
*/
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
		'prioridade_pk': $('#prioridade_pk').val(),
		'prioridade_nome': $('#prioridade_nome').val()
	}

	btn_load($('.submit'));

	$.post(base_url + '/prioridade/save', data).done(function (response) {

		btn_ativar($('.submit'));

		if (response.code == 400) {
			alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s) de validação');
			$('#senha-input').val('');
		}
		else if (response.code == 401) {
			alerts('failed', 'Erro!', 'Senha informada incorreta');
			$('#senha-input').val('');
			$('#senha-input').focus();
		}
		else if (response.code == 500) {
			alerts('failed', 'Erro!', 'Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
			$('#senha-input').val('');
		}
		else if (response.code == 200) {
			alerts('success', 'Sucesso!', 'Operação realizada com sucesso');
			document.location.reload(false);
		}
	}, "json");
}


$(".submit").click(function () {
	send_data();
});

/*
* Ação para abrir o formulário para editar as informações do prioridade.
* Envia as informações necessárias para preencher os campos
*/

$(document).on('click', '.btn_editar', function () {
	acao = ".submit";
	$("#ce_prioridade").find(".modal-title").text("Editar Prioridade");

	$('#prioridade_pk').val(prioridades[$(this).val()]["prioridade_pk"]);
	$('#prioridade_nome').val(prioridades[$(this).val()]["prioridade_nome"]);
});

/*
* Ação para abrir o formulário de nova prioridade.
*/

$(document).on('click', '.btn-novo', function () {
	acao = ".submit";
	$("#ce_prioridade").find(".modal-title").text("Nova Prioridade");
});




/*
* Ação para abrir o formulário para desativar um prioridade.
*/

$(document).on('click', '.btn-desativar', function (event) {
	acao = "#btn-desativar";
	$('#btn-desativar').val(prioridades[$(this).val()]["prioridade_pk"]);

	$('#loading-prioridade-deactivate').show();
    $('#tipo-servicos-dependentes').hide();
    
	var data = 
	{
		'prioridade_pk': prioridades[$(this).val()]["prioridade_pk"]
	}

	$.post(base_url + '/prioridade/get_dependents', data, function (response, textStatus, xhr) {
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
                title = "Não há nenhum tipo de serviço dependente desta prioridade.";
            }
            else { //se tiver 1 ou mais tipos de serviço dependentes:
                var mensagem = "";
                if(response.data.length == 1){ 
                    title = 'A prioridade é padrão nesse tipo de serviço:';
                }
                else if(response.data.length > 1){
                     title = 'A prioridade é padrão nesses tipos de serviços:';
                }
                html += "<ul style='margin-left: 15px'>";
                for( var i in response.data){
                    html += '<li>'+ response.data[i].tipo_servico_nome +'</li>';
                }
                html += "</ul>";
                mensagem = "<br> Você não poderá desativar esta prioridade enquanto houver(em) tipo(s) de serviço(s) dependente(s).<br>";
                html += mensagem;

            } //fecha o 1 ou mais serivços dependentes
            $('#tipo-servicos-dependentes').html('<br>'+'<h5>'+title + '</h5>' + html+'</br>');
            $('#tipo-servicos-dependentes').show();
            $('#loading-prioridade-deactivate').hide();
		}		

	});
});

$(document).on('click', '.btn-reativar', function (event) {
	$('#btn-reativar').val(prioridades[$(this).val()]["prioridade_pk"]);
});

$(document).on('click', '#btn-reativar', function (event) {

	btn_load($('#btn-reativar'));

	var data = 
	{
		'prioridade_pk': $(this).val()
	}

	$.post(base_url + '/prioridade/activate', data, function (response, textStatus, xhr) {

		btn_ativar($('#btn-reativar'));

		if (response.code == 400) {
			alerts('failed', 'Erro!', 'O formulário apresenta algum erro de validação');
		}
		else if (response.code == 401) {
			alerts('failed', 'Erro!', 'Senha informada incorreta');
		}
		else if (response.code == 200) {
			alerts('success', 'Sucesso', 'Prioridade reativada com sucesso');
		  	document.location.reload(false);
		}		

	});
});



/*
* deactivate();
* @var $('#btn-desativar').val() - id do prioridade que será desativado
* @var $('#pass-modal-desativar').val() - senha do superusuário caso seja um superusuário
*/


$(document).on('click', '#btn-desativar', function (event) {
	var data =
	{
		'prioridade_pk': $(this).val(),
		'senha': $('#pass-modal-desativar').val()
	}

	btn_load($('#btn-desativar'));

	$.post(base_url + '/prioridade/deactivate', data, function (response, textStatus, xhr) {

		btn_ativar($('#btn-desativar'));

		if (response.code == 400) {
			alerts('failed', 'Erro!', response.data.mensagem);
		}
		else if (response.code == 401) {
			alerts('failed', 'Erro!', 'Senha informada incorreta');
		}
		else if (response.code == 500) {
			alerts('failed', 'Erro!', 'Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 200) {
			alerts('success', 'Sucesso!', 'Prioridade desativada com sucesso');
			document.location.reload(false);
		}
	});
});




draw_table = () => {
	table.clear().draw();
	$.each(prioridades, function (i, prioridade) {
		if (prioridade.prioridade_desativar_tempo == null) {
			table.row.add(
				[
				prioridade.prioridade_nome,
				prioridade.prazo_duracao,
				'<div class="btn-group">' +
				'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_prioridade">' +
				'<div class="d-none d-sm-block">' +
				'Editar' +
				'</div>' +
				'<div class="d-block d-sm-none">' +
				'<i class="fas fa-edit fa-fw"></i>' +
				'</div>' +
				'</button>' +
				'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-prioridade">' +
				'<div class="d-none d-sm-block">' +
				'Apagar' +
				'</div>' +
				'<div class="d-block d-sm-none">' +
				'<i class="fas fa-times fa-fw"></i>' +
				'</div>' +
				'</button>' +
				'</div>'
				]
				).draw(false);
		}
	});
}
