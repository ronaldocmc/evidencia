// function btn_load(button_submit){
// 	button_submit.attr('disabled', 'disabled');
// 	button_submit.css('cursor', 'default');
// 	button_submit.find('i').removeClass();
// 	button_submit.find('i').addClass('fa fa-refresh fa-spin');
// }


// function btn_ativar(button_submit){
// 	button_submit.removeAttr('disabled');
// 	button_submit.css('cursor', 'pointer');
// 	button_submit.find('i').removeClass();
// 	button_submit.find('i').addClass('fa fa-dot-circle-o');
// }


// var botao = null;


// $(document).ready(function () {
// 	if(is_superusuario) {
// 		$("#senha-input").addClass("press_enter");
// 	}
// 	else {
// 		$("#nome-input").addClass("press_enter");
// 	}

// 	$( ".press_enter" ).on( "keydown", function( event ) {
// 		if(event.which == 13){
// 			$(botao).trigger("click");
// 		}
// 	});
// });

// function send_data(){
// 	var data = 
// 	{
// 		'departamento_pk': $('#departamento_pk').val(),
// 		'departamento_nome': $('#nome-input').val(),
// 		'senha': $('#senha-input').val()
// 	}

// 	btn_load($('#pula-para-confirmacao'));
// 	btn_load($('.submit'));
// 	$('#senha-input').val('');

// 	$.post(base_url+'/departamento/save',data).done(function (response) {

// 		console.log(response);

// 		btn_ativar($('#pula-para-confirmacao'));
// 		btn_ativar($('.submit'));

// 		if (response.code == 200)
// 		{
// 			alerts('success', 'Sucesso', 'Operação realizada com sucesso!');
// 			document.location.reload(false);
// 		} else {
// 			alerts('failed', 'Falha', 'Operação falha!');
// 		}
// 	}, "json");
// }



/**
* submit();
* @ var  $('#departamento_pk').val()  - id do departamento (apenas quando é atualizado)
* @ var  $('#nome-input).val()  - nome do departamento
* @ var  $('#senha-input).val()  - senha do superusuário ativo, caso seja um superusuario
*/
// $(".submit").click(function(){
// 	send_data();
// });

/*
* Ação para abrir o formulário para editar as informações do departamento.
* Envia as informações necessárias para preencher os campos
*/

// $(document).on('click','.btn_editar',function() {
// 	botao = ".submit";
// 	$('.modal-title').html("Editar Departamento");
// $('#departamento_pk').val(departamentos[$(this).val()]["departamento_pk"]);
// $('#nome-input').val(departamentos[$(this).val()]["departamento_nome"]);
// });


/*
* Ação para abrir o formulário para criar um novo departamento.
*/

// $(document).on('click','.btn_novo',function() {
// 	botao = ".submit";
// 	$('.modal-title').html("Novo Departamento");	
// });

/*
* Ação para abrir o formulário para desativar um departamento.
*/

// $(document).on('click','.btn-desativar',function(event) {
// 	botao = "#btn-desativar";
// 	$('.modal-title').html("Desativar Departamento");
// 	$('#btn-desativar').val(departamentos[$(this).val()]["departamento_pk"]);

// 	$('#loading-departamento-deactivate').show();
// 	$('#tipo-servicos-dependentes').hide();

// 	var data = 
// 	{
// 		'departamento_pk': departamentos[$(this).val()]["departamento_pk"]
// 	}

// 	$.post(base_url + '/departamento/get_dependents', data, function (response, textStatus, xhr) {

// 		if (response.code == 200) {
// 		  	var title = '';
// 		  	var mensagem = '';
//             if(! response.data){ //se não houver nenhum serviço:
//             	$('#btn-desativar').removeAttr('disabled');
//             	title = 'Tudo certo para desativação!'
//             	mensagem = "Não há nenhum tipo de serviço dependente deste departamento, portanto você pode desativar este departamento.";
//             }
//             else { //se tiver 1 ou mais tipos de serviço dependentes:
//             	$('#btn-desativar').attr('disabled', 'disabled');
//             	title = 'Impossível desativar o departamento!'
//             	mensagem = "Há tipos de serviços relacionados à esse departamento. Os desative antes.";

//             } //fecha o 1 ou mais serivços dependentes
//             $('#tipo-servicos-dependentes').html('<br>'+'<b>'+title + '</b> <br>' + mensagem +'</br>');
//             $('#tipo-servicos-dependentes').show();
//             $('#loading-departamento-deactivate').hide();
//         } else {
//         	$('#d-departamento').modal('toggle');
//         	$('#loading-departamento-deactivate').hide();
//         }
//     });

});

/*
* Ação para abrir o formulário para desativar um departamento, caso seja um superusúário,
* ou reativar diretamente caso seja um administrador.
*/

// $(document).on('click','.btn_reativar',function(event) {
// 	// if (is_superusuario)
// 	// {
// 		botao = "#btn-reativar";
// 		$('#r-departamento').modal("show");
// 		$('#btn-reativar').val(departamentos[$(this).val()]["departamento_pk"]);
// 	// }
// 	// else
// 	// {
// 	// 	activate(departamentos[$(this).val()]["departamento_pk"],null);
// 	// }
// });

/*
* Ação para abrir o atualizar a tabela de departamentos de acordo com o filtro.
*/

$('#filter-ativo').on('change',function() {
	change_table($(this));
});

/*
* deactivate();
* @var $('#btn-desativar').val() - id do departamento que será desativado
* @var $('#pass-modal-desativar').val() - senha do superusuário caso seja um superusuário
*/


// $(document).on('click','#btn-desativar',function(event) {
// 	var data = 
// 	{
// 		'departamento_pk' : $(this).val(),
// 		'senha': $('#pass-modal-desativar').val()
// 	}

// 	btn_load($('#btn-desativar'));
// 	$('#pass-modal-desativar').val('')

// 	$.post(base_url+'/departamento/deactivate', data, function(response, textStatus, xhr) {
		
// 		wich_alert(response);
// 		btn_ativar($('#btn-desativar'));

// 		if (response.code == 200)
// 		{
// 			alerts('success', 'Sucesso', 'Desativação concluída!');
// 			document.location.reload(false);
// 		} else {

// 			alerts('failed', 'Falha', 'Desativação falhou!');
// 		}
// 	});
// });

/*
* activate();
* @var $('#btn-desativar').val() - id do departamento que será desativado
* @var $('#pass-modal-desativar').val() - senha do superusuário caso seja um superusuário
*/

$(document).on('click','#btn-reativar',function(event) {
	activate($(this).val(),$('#pass-modal-reativar').val());
});

activate = (dep_pk, pass) => {
	var data = 
	{
		'departamento_pk' : dep_pk,
		'senha': pass
	}


	btn_load($('#btn-reativar'));

	$.post(base_url+'/departamento/activate', data, function(response, textStatus, xhr) {

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
		$.each(departamentos, function (i, dep) {
			if (dep.ativo == 1) 
			{ 
				table.row.add([ 
					dep.departamento_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_departamento" title="Editar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-edit fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-departamento" title="Desativar">'+
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
					dep.departamento_nome, 
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
		$.each(departamentos, function (i, dep) { 
			if (dep.ativo == 1) 
			{ 
				table.row.add([ 
					dep.departamento_nome, 
					'<div class="btn-group">'+
					'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_departamento" title="Editar">'+
					'<div class="d-none d-sm-block">'+
					'<i class="fas fa-edit fa-fw"></i>'+
					'</div>'+
					'</button>'+
					'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-departamento" title="Desativar">'+
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
		$.each(departamentos, function (i, dep) { 
			if (dep.ativo == 0) 
			{ 
				table.row.add([ 
					dep.departamento_nome, 
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
