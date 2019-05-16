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


var botao = null;


$(document).ready(function () {
	if(is_superusuario) {
		$("#senha-input").addClass("press_enter");
	}
	else {
		$("#nome-input").addClass("press_enter");
	}

	$( ".press_enter" ).on( "keydown", function( event ) {
		if(event.which == 13){
			$(botao).trigger("click");
		}
	});
});

function send_data(){
	var data = 
	{
		'funcao_pk': $('#funcao_pk').val(),
		'funcao_nome': $('#nome-input').val(),
		'senha': $('#senha-input').val()
	}

	btn_load($('#pula-para-confirmacao'));
	btn_load($('.submit'));
	$('#senha-input').val('')

	$.post(base_url+'/funcao/save',data).done(function (response) {

		console.log(response);

		btn_ativar($('#pula-para-confirmacao'));
		btn_ativar($('.submit'));

		if (response.code == 200)
		{
			alerts('success', 'Sucesso', 'Operação realizada com sucesso!');
			document.location.reload(false);
		} else {
			alerts('failed', 'Falha', 'Operação falha!');
		}
	}, "json");
}

$(document).on('click','.btn-desativar',function(event) {
	botao = "#btn-desativar";
	$('.modal-title').html("Desativar Função");
	$('#btn-desativar').val(funcoes[$(this).val()]["funcao_pk"]);

	$('#loading-funcao-deactivate').show();
	$('#tipo-servicos-dependentes').hide();

	var data = 
	{
		'funcao_pk': funcoes[$(this).val()]["funcao_pk"]
	}

	$.post(base_url + '/funcao/get_dependents', data, function (response, textStatus, xhr) {

		if (response.code == 200) {
		  	var title = '';
		  	var mensagem = '';
            if(! response.data){ //se não houver nenhum serviço:
            	$('#btn-desativar').removeAttr('disabled');
            	title = 'Tudo certo para desativação!'
            	mensagem = "Não há nenhum funcionário que possui esta função, portanto você pode desativá-la.";
            }
            else { //se tiver 1 ou mais tipos de serviço dependentes:
            	$('#btn-desativar').attr('disabled', 'disabled');
            	title = 'Impossível desativar a função!'
            	mensagem = "Há funcionários relacionados à esta função. Os desative antes.";

            } //fecha o 1 ou mais serivços dependentes
            $('#tipo-servicos-dependentes').html('<br>'+'<b>'+title + '</b> <br>' + mensagem +'</br>');
            $('#tipo-servicos-dependentes').show();
            $('#loading-funcao-deactivate').hide();
        } else {
        	$('#d-funcao').modal('toggle');
        	$('#loading-funcao-deactivate').hide();
        }
    });

});




/**
* submit();
* @ var  $('#funcao_pk').val()  - id do funcao (apenas quando é atualizado)
* @ var  $('#nome-input).val()  - nome do funcao
* @ var  $('#senha-input).val()  - senha do superusuário ativo, caso seja um superusuario
*/
$(".submit").click(function(){
	send_data();
});

/*
* Ação para abrir o formulário para editar as informações do funcao.
* Envia as informações necessárias para preencher os campos
*/

$(document).on('click','.btn_editar',function() {
	botao = ".submit";
	$('.modal-title').html("Editar Função");
	$('#funcao_pk').val(funcoes[$(this).val()]["funcao_pk"]);
	$('#nome-input').val(funcoes[$(this).val()]["funcao_nome"]);	
});


/*
* Ação para abrir o formulário para criar um novo funcao.
*/

$(document).on('click','.btn_novo',function() {
	botao = ".submit";
	$('.modal-title').html("Nova Função");	
});

/*
* Ação para abrir o formulário para desativar um funcao.
*/

$(document).on('click','.btn-desativar',function(event) {
	botao = "#btn-desativar";
	$('.modal-title').html("Desativar Função");
	$('#btn-desativar').val(funcoes[$(this).val()]["funcao_pk"]);

	$('#loading-funcao-deactivate').show();
	$('#tipo-servicos-dependentes').hide();

	var data = 
	{
		'funcao_pk': funcoes[$(this).val()]["funcao_pk"]
    }
    
    $('#d-funcao').modal('toggle');
    $('#loading-funcao-deactivate').hide();
});

/*
* Ação para abrir o formulário para desativar um funcao, caso seja um superusúário,
* ou reativar diretamente caso seja um administrador.
*/

$(document).on('click','.btn_reativar',function(event) {
	// if (is_superusuario)
	// {
		botao = "#btn-reativar";
		$('#r-funcao').modal("show");
		$('#btn-reativar').val(funcoes[$(this).val()]["funcao_pk"]);
	// }
	// else
	// {
	// 	activate(funcoes[$(this).val()]["funcao_pk"],null);
	// }
});

/*
* Ação para abrir o atualizar a tabela de funcoes de acordo com o filtro.
*/

$('#filter-ativo').on('change',function() {
	change_table($(this));
});

/*
* deactivate();
* @var $('#btn-desativar').val() - id do funcao que será desativado
* @var $('#pass-modal-desativar').val() - senha do superusuário caso seja um superusuário
*/


$(document).on('click','#btn-desativar',function(event) {
	var data = 
	{
		'funcao_pk' : $(this).val(),
		'senha': $('#pass-modal-desativar').val()
	}

	btn_load($('#btn-desativar'));
	$('#pass-modal-desativar').val('')

	$.post(base_url+'/funcao/deactivate', data, function(response, textStatus, xhr) {
		
		wich_alert(response);
		btn_ativar($('#btn-desativar'));

		if (response.code == 200)
		{
			alerts('success', 'Sucesso', 'Desativação concluída!');
			document.location.reload(false);
		} else {

			alerts('failed', 'Falha', 'Desativação falhou!');
		}
	});
});

/*
* activate();
* @var $('#btn-desativar').val() - id do funcao que será desativado
* @var $('#pass-modal-desativar').val() - senha do superusuário caso seja um superusuário
*/

$(document).on('click','#btn-reativar',function(event) {
	activate($(this).val(),$('#pass-modal-reativar').val());
});

activate = (dep_pk, pass) => {
	var data = 
	{
		'funcao_pk' : dep_pk,
		'senha': pass
	}


	btn_load($('#btn-reativar'));

	$.post(base_url+'/funcao/activate', data, function(response, textStatus, xhr) {

		wich_alert(response);
		btn_ativar($('#btn-reativar'));

		if (response.code == 200)
		{
			alerts('success', 'Sucesso', 'Rativação concluída!');
			document.location.reload(false);
		} else {

			alerts('failed', 'Falha', 'Reativação falhou!');
		}
	});
}


change_table = (select_options) => {
	table.clear().draw(); 
	switch (select_options.val()) { 
		case "todos": 
		$.each(funcoes, function (i, dep) {
			if (dep.ativo == 1) 
			{ 
				table.row.add([ 
					dep.funcao_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_funcao" title="Editar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-edit fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-funcao" title="Desativar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-times fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'</div>' 
					]).draw(false);
			}
			else
			{
				table.row.add([ 
					dep.funcao_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-success btn_reativar" value="'+ (i) +'" title="Reativar" title="Reativar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-power-off fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'</div>' 
					]).draw(false);
			} 
		}); 
		break; 
		case "ativos": 
		$.each(funcoes, function (i, dep) { 
			if (dep.ativo == 1) 
			{ 
				table.row.add([ 
					dep.funcao_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_funcao" title="Editar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-edit fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-funcao" title="Desativar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-times fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'</div>' 
					]).draw(false); 
			} 
		}); 
		break; 
		case "desativados": 
		$.each(funcoes, function (i, dep) { 
			if (dep.ativo == 0) 
			{ 
				table.row.add([ 
					dep.funcao_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-success btn_reativar" value="'+ (i) +'" title="Reativar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-power-off fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'</div>'  
					]).draw(false); 
			} 
		}); 
		break; 
	}
};
