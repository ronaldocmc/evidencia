
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
		'prioridade_nome': $('#nome-input').val(),
		'prioridade_duracao': $('#prazo-input-dias').val() * 24 + $('#prazo-input-horas').val() * 1,
		'senha': $('#senha-input').val()
	}

	btn_load($('.submit'));
	btn_load($('#pula-para-confirmacao'));

	$.post(base_url + '/prioridade/insert_update', data).done(function (response) {

		btn_ativar($('.submit'));
		btn_ativar($('#pula-para-confirmacao'));


		if (response.code == 400) {
			show_errors(response);
			alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s) de validação');
			$('#senha-input').val('');
		}
		else if (response.code == 401) {
			show_errors(response);
			alerts('failed', 'Erro!', 'Senha informada incorreta');
			$('#senha-input').val('');
			$('#senha-input').focus();
		}
		else if (response.code == 500) {
			alerts('failed', 'Erro!', 'Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
			$('#senha-input').val('');
		}
		else if (response.code == 200) {
			var hora;
			if(data['prioridade_duracao'] == 1){
				hora = ' hora';
			}else{
				hora = ' horas';
			}
			prioridade =
			{
				'prioridade_nome': data['prioridade_nome'],
				'prazo_duracao': data['prioridade_duracao'] + hora,
				'prioridade_desativar_tempo': null,
			}

			if (data['prioridade_pk'] == '' || data['prioridade_pk'] == undefined) {
				prioridade['prioridade_pk'] = response.data['prioridade_pk'];
				prioridade['prioridade_fk'] = response.data['prioridade_pk'];
				prioridades.push(prioridade);
				alerts('success', 'Sucesso!', 'Prioridade inserida com sucesso');
			}
			else {
				prioridade['prioridade_pk'] = data['prioridade_pk'];
				for (var i in prioridades) {
					if (prioridades[i]['prioridade_pk'] == data['prioridade_pk'])
						break;
				}
				prioridades[i] = (prioridade);
				alerts('success', 'Sucesso!', 'Prioridade atualizada com sucesso');
			}
			draw_table();
			$('#ce_prioridade').modal('hide');

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
	var duracao_number = prioridades[$(this).val()]["prazo_duracao"].split(' ')[0];

	var duracao_horas = duracao_number % 24;
	var duracao_dias = Math.floor(duracao_number / 24);
	$('#prioridade_pk').val(prioridades[$(this).val()]["prioridade_pk"]);
	$('#nome-input').val(prioridades[$(this).val()]["prioridade_nome"]);
	$('#prazo-input-horas').val(duracao_horas);
	$('#prazo-input-dias').val(duracao_dias);
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
                    title = 'Este é o tipo de serviço que será afetado:';
                }
                else if(response.data.length > 1){
                     title = 'Estes são os tipos de serviços que serão afetados:';
                }
                html += "<ul style='margin-left: 15px'>";
                for( var i in response.data){
                    html += '<li>'+ response.data[i].tipo_servico_nome +'</li>';
                }
                html += "</ul>";
                mensagem = "<br><b>OBS:</b> Você não poderá desativar esta prioridade enquanto houver(em) tipo(s) de serviço(s) dependente(s).<br>";
                html += mensagem;

            } //fecha o 1 ou mais serivços dependentes
            $('#tipo-servicos-dependentes').html('<br>'+'<h5>'+title + '</h5>' + html+'</br>');
            $('#tipo-servicos-dependentes').show();
            $('#loading-prioridade-deactivate').hide();
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
			alerts('failed', 'Erro!', response.data.erro);
		}
		else if (response.code == 401) {
			alerts('failed', 'Erro!', 'Senha informada incorreta');
		}
		else if (response.code == 500) {
			alerts('failed', 'Erro!', 'Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 200) {
			alerts('success', 'Sucesso!', 'prioridade apagada com sucesso');
			for (var i in prioridades) {
				if (prioridades[i]['prioridade_pk'] == data['prioridade_pk']){
					prioridades[i]['prioridade_desativar_tempo'] = true;
					draw_table();
					break;
				}
			}
			$('#d-prioridade').modal('hide');
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
