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
		'departamento_pk': $('#departamento_pk').val(),
		'nome': $('#nome-input').val(),
		'senha': $('#senha-input').val()
	}

	btn_load($('#pula-para-confirmacao'));
	btn_load($('.submit'));
	$('#senha-input').val('')

	$.post(base_url+'/departamento/insert_update',data).done(function (response) {

		wich_alert(response);

		btn_ativar($('#pula-para-confirmacao'));
		btn_ativar($('.submit'));

		if (response.code == 200)
		{
			dep  = 
			{
				'departamento_nome': data['nome'],
				'ativo': 1,
			}
			if (data['departamento_pk']=='')
			{
				dep['departamento_pk'] = response.data['id'];
				departamentos.push(dep);
				change_table($('#filter-ativo'));
			}
			else
			{
				dep['departamento_pk'] = data['departamento_pk'];
				for (var i in departamentos)
				{
					if (departamentos[i]['departamento_pk']==data['departamento_pk'])
						break;
				}
				departamentos[i] = (dep);
				change_table($('#filter-ativo'));
			}

			$('#ce_departamento').modal('hide');
		}
	}, "json");
}



/**
* submit();
* @ var  $('#departamento_pk').val()  - id do departamento (apenas quando é atualizado)
* @ var  $('#nome-input).val()  - nome do departamento
* @ var  $('#senha-input).val()  - senha do superusuário ativo, caso seja um superusuario
*/
$(".submit").click(function(){
	send_data();
});

/*
* Ação para abrir o formulário para editar as informações do departamento.
* Envia as informações necessárias para preencher os campos
*/

$(document).on('click','.btn_editar',function() {
	botao = ".submit";
	$('.modal-title').html("Editar Departamento");
	$('#departamento_pk').val(departamentos[$(this).val()]["departamento_pk"]);
	$('#nome-input').val(departamentos[$(this).val()]["departamento_nome"]);	
});


/*
* Ação para abrir o formulário para criar um novo departamento.
*/

$(document).on('click','.btn_novo',function() {
	botao = ".submit";
	$('.modal-title').html("Novo Departamento");	
});

/*
* Ação para abrir o formulário para desativar um departamento.
*/

$(document).on('click','.btn-desativar',function(event) {
	botao = "#btn-desativar";
	$('.modal-title').html("Desativar Departamento");
	$('#btn-desativar').val(departamentos[$(this).val()]["departamento_pk"]);

	$('#loading-departamento-deactivate').show();
	$('#tipo-servicos-dependentes').hide();

	var data = 
	{
		'departamento_pk': departamentos[$(this).val()]["departamento_pk"]
	}

	$.post(base_url + '/departamento/get_dependents', data, function (response, textStatus, xhr) {

		wich_alert(response)

		if (response.code == 200) {
		  	html = ''; //esta variável vai servir para eu preencher a div tipo-servicos-dependentes
		  	title = '';
            if(response.data.length == 0 || response.data == false){ //se não houver nenhum serviço:
            	title = "Não há nenhum tipo de serviço dependente deste departamento, portanto você pode desativar este departamento.";
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
            	mensagem = "<br><b>OBS:</b> Você não poderá desativar este departamento enquanto houver(em) tipo(s) de serviço(s) dependente(s).<br>";
            	html += mensagem;

            } //fecha o 1 ou mais serivços dependentes
            $('#tipo-servicos-dependentes').html('<br>'+'<h5>'+title + '</h5>' + html+'</br>');
            $('#tipo-servicos-dependentes').show();
            $('#loading-departamento-deactivate').hide();
        } else {
        	$('#d-departamento').modal('toggle');
        	$('#loading-departamento-deactivate').hide();
        }

    });

});

/*
* Ação para abrir o formulário para desativar um departamento, caso seja um superusúário,
* ou reativar diretamente caso seja um administrador.
*/

$(document).on('click','.btn_reativar',function(event) {
	// if (is_superusuario)
	// {
		botao = "#btn-reativar";
		$('#r-departamento').modal("show");
		$('#btn-reativar').val(departamentos[$(this).val()]["departamento_pk"]);
	// }
	// else
	// {
	// 	activate(departamentos[$(this).val()]["departamento_pk"],null);
	// }
});

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


$(document).on('click','#btn-desativar',function(event) {
	var data = 
	{
		'departamento_pk' : $(this).val(),
		'senha': $('#pass-modal-desativar').val()
	}

	btn_load($('#btn-desativar'));
	$('#pass-modal-desativar').val('')

	$.post(base_url+'/departamento/deactivate', data, function(response, textStatus, xhr) {
		
		wich_alert(response);
		btn_ativar($('#btn-desativar'));

		if (response.code == 200)
		{
			for (var i in departamentos)
			{
				if (departamentos[i]['departamento_pk']==data['departamento_pk'])
					break;
			}
			departamentos[i]['ativo'] = 0;
			$('#filter-ativo').change();
			$('#d-departamento').modal('hide');
		}
	});
});

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
			for (var i in departamentos)
			{
				if (departamentos[i]['departamento_pk']==data['departamento_pk'])
					break;
			}
			departamentos[i]['ativo'] = 1;
			$('#filter-ativo').change();
			$('#r-departamento').modal('hide');
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
