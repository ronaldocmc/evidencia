var id_botao = null;

$( ".press_enter" ).on( "keydown", function( event ) {
	if(event.which == 13){
		$(id_botao).trigger("click");
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
		//pre_loader_show();
		

		var data = 
		{
			// 'organizacao_pk': $('#dominio-input').val(),
			'organizacao_nome': $('#nome-input').val(),
			'organizacao_cnpj': $('#cnpj-input').val(),
			'localizacao_rua': $('#logradouro-input').val(),
			'localizacao_num': $('#numero-input').val(),
			'localizacao_bairro': $('#bairro-input').val(),
			'localizacao_municipio': $('#cidade-input').val(),
			'municipio_nome': $('#municipio-nome').val(),
			'estado_fk': $('#estado-input').val(),
			'senha': $('#senha-input').val()
		}

		if($('#organizacao_pk').val() == '' || $('#organizacao_pk').val() == undefined){
			data['organizacao_pk'] = $('#dominio-input').val();
		} else {
			data['organizacao_pk'] = $('#organizacao_pk').val();
		}

		btn_load($('.submit'));
		btn_load($('#pula-para-confirmacao'));

		$.post(base_url+'/organizacao/save',data).done(function (response) {
			btn_ativar($('.submit'));
			btn_ativar($('#pula-para-confirmacao'));
			
    	//pre_loader_hide();
    	if (response.code == 400)
    	{
    		show_errors(response);
    		alerts('failed','Erro!','O formulário apresenta algum(ns) erro(s) de validação');
    	}
    	else if (response.code == 401)
    	{
    		alerts('failed','Erro!','Senha informada incorreta');
    	}
    	else if (response.code == 500)
    	{
    		alerts('failed','Erro!','Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
    	}
    	else
    	{
    		org  = 
    		{
    			'organizacao_pk': data['organizacao_pk'],
    			'organizacao_nome': data['organizacao_nome'],
    			'organizacao_cnpj': data['organizacao_cnpj'],
    			'localizacao_rua': data['localizacao_rua'],
				'localizacao_num': data['localizacao_num'],
				'localizacao_municipio': data['localizacao_municipio'],
				'municipio_nome': data['municipio_nome'],				
				'localizacao_bairro':data['localizacao_bairro'],
				'estado_fk': data['estado_fk'],
    			'ativo': 1
			}

    		if ($('#organizacao_pk').val() == '')
    		{
    			organizacoes.push(org);
				alerts('success','Sucesso!','Organização inserida com sucesso');
				document.location.reload(false);
    		}
    		else
    		{
				
    			for (var i in organizacoes)
    			{
    				if (organizacoes[i]['organizacao_pk']==data['organizacao_pk'])
    					break;
				}
				
    			organizacoes[i] = (org);
    			alerts('success','Sucesso!','Organização modificada com sucesso');
    		}
    		$('#filter-ativo').change();
    		$('#ce_organizacao').modal('hide');
    	}
    }, "json");
	}


	$(".submit").click(function(){
		send_data();
	});

	$(document).on('click','.btn_reativar',function(event) {
		$('.modal-title').html("Reativar Organização");
		id_botao = '#btn-reativar';
		$('#btn-reativar').val(organizacoes[$(this).val()]["organizacao_pk"]);
	});

	$(document).on('click','.btn_nova',function(event) {
		$('.modal-title').html("Nova Organização");
		id_botao = '.submit';
	});

	$(document).on('click','.btn_editar',function(event) {
		$('.modal-title').html("Editar Organização");
		id_botao = '.submit';
		var id_org = $(this).val();
		$('#dominio-input').val(organizacoes[$(this).val()]["organizacao_pk"]);
		$('#organizacao_pk').val(organizacoes[$(this).val()]["organizacao_pk"]);
		$('#nome-input').val(organizacoes[$(this).val()]["organizacao_nome"]);
		$('#cnpj-input').val(organizacoes[$(this).val()]["organizacao_cnpj"]);
		$('#logradouro-input').val(organizacoes[$(this).val()]["localizacao_rua"].toLowerCase().replace(/\b\w/g, l => l.toUpperCase()));
		$('#numero-input').val(organizacoes[$(this).val()]["localizacao_num"]);
		$('#bairro-input').val(organizacoes[$(this).val()]["localizacao_bairro"]);
		$('#estado-input').val(organizacoes[$(this).val()]['estado_fk']);
		$("#cidade-input").val(organizacoes[$(this).val()]["municipio_pk"]);
		$("#municipio-nome").val(organizacoes[$(this).val()]["municipio_nome"]);		
		// if ($("#uf-input :selected").text() != organizacoes[$(this).val()]["estado_pk"])
		// {
		// 	$("#uf-input option").filter(function() {
		// 		return this.text == organizacoes[id_org]["estado_pk"]; 
		// 	}).attr('selected', true);

		// 	change_uf($("#uf-input").val(),$("#uf-input option:selected").text(),organizacoes[$(this).val()]["municipio_pk"]);
		// }
		// else
		// {
		// 	$("#cidade-input").val(organizacoes[$(this).val()]["municipio_pk"]);
		// }	
	});

	$(document).on('click','.btn-desativar',function(event) {
		$('.modal-title').html("Desativar Organização");
		id_botao = '#btn-desativar';
		$('#btn-desativar').val(organizacoes[$(this).val()]["organizacao_pk"]);
	});

	$(document).on('click','#btn-desativar',function(event) {
		var data = 
		{
			'organizacao_pk' : $(this).val(),
			'senha': $('#pass-modal-desativar').val()
		}
		

		btn_load($('#btn-desativar'));

		$.post(base_url+'/organizacao/deactivate', data, function(response, textStatus, xhr) {
			btn_ativar($('#btn-desativar'));

			if (response.code == 400)
			{
				alerts('failed','Erro!','O formulário apresenta algum erro de validação');
			}
			else if (response.code == 401)
			{
				alerts('failed','Erro!','Senha informada incorreta');
			}
			else if (response.code == 500)
			{
				alerts('failed','Erro!','Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
			}
			else
			{
				alerts('success','Sucesso!','Organização desativada com sucesso');
				for (var i in organizacoes)
				{
					if (organizacoes[i]['organizacao_pk']==data['organizacao_pk'])
						break;
				}
				organizacoes[i]['ativo'] = 0;
				$('#pass-modal-desativar').val('');
				$('#filter-ativo').change();
				$('#d-organizacao').modal('hide');
			}
		});
	});


	$(document).on('click','.btn-acessar',function(event) {
		var data = 
		{
			'organizacao_pk' : organizacoes[$(this).val()]["organizacao_pk"]
		}
		pre_loader_show();
		//btn_load($('.btn-acessar'));

		$.post(base_url+'/organizacao/access', data, function(response, textStatus, xhr) {
			
		//btn_ativar($('.btn-acessar'));

		if (response.code == 200)
		{
			window.location.replace(base_url);
		}
		else
		{
			alerts('failed','Erro!','Não foi possível acessar a organização');
		}
	});
	});

	$(document).on('click','#btn-reativar',function(event) {
		var data = 
		{
			'organizacao_pk' : $(this).val(),
			'senha': $('#pass-modal-reativar').val()
		}

		btn_load($('#btn-reativar'));

		$.post(base_url+'/organizacao/activate', data, function(response, textStatus, xhr) {

			btn_ativar($('#btn-reativar'));

			if (response.code == 400)
			{
				alerts('failed','Erro!','O formulário apresenta algum erro de validação');
			}
			else if (response.code == 401)
			{
				alerts('failed','Erro!','Senha informada incorreta');
			}
			else if (response.code == 500)
			{
				alerts('failed','Erro!','Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
			}
			else
			{
				alerts('success','Sucesso!','Organização reativada com sucesso');
				for (var i in organizacoes)
				{
					if (organizacoes[i]['organizacao_pk']==data['organizacao_pk'])
						break;
				}
				organizacoes[i]['ativo'] = 1;
				$('#pass-modal-reativar').val('');
				$('#filter-ativo').change();
				$('#r-organizacao').modal('hide');
			}
		});
	});

	$('#filter-ativo').on('change',function() {
		table.clear().draw(); 
		
		switch ($(this).val()) { 
			
			case "todos": 
			$.each(organizacoes, function (i, org) {
				if (org.ativo == 1) 
				{ 
					table.row.add([
						org.organizacao_nome,
						org.organizacao_pk,
						org.localizacao_rua.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+', '+org.localizacao_num+' - '+org.localizacao_bairro.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+' - '+org.municipio_nome+'/'+ org.estado_fk,
						'<div class="btn-group">'+
						'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+(i)+'" data-target="#ce_organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Editar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-edit fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="'+(i)+'" data-target="#d-organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Desativar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-times fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'<button type="button" class="btn btn-sm btn-success btn-acessar" value="'+(i)+'">'+
						'<div class="d-none d-sm-block">'+
						'Acessar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-sign-in-alt fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'</div>' 

						]).draw(false);
				}
				else
				{
					table.row.add([
						org.organizacao_nome,
						org.organizacao_pk,
						org.localizacao_rua.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+', '+org.localizacao_num+' - '+org.localizacao_bairro.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+' - '+org.municipio_nome+'/'+org.estado_fk,
						'<div class="btn-group">'+
						'<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="'+ (i) +'" data-target="#r-organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Reativar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-check-circle fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'</div>'   

						]).draw(false);
				}
			}); 
			break; 
			case "ativos": 
			$.each(organizacoes, function (i, org) { 
				if (org.ativo == 1) 
				{ 
					table.row.add([
						org.organizacao_nome,
						org.organizacao_pk,
						org.localizacao_rua.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+', '+org.localizacao_num+' - '+org.localizacao_bairro.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+' - '+org.municipio_nome+'/'+org.estado_fk,						
						'<div class="btn-group">'+
						'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+(i)+'" data-target="#ce_organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Editar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-edit fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="'+(i)+'" data-target="#d-organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Desativar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-times fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'<button type="button" class="btn btn-sm btn-success btn-acessar" value="'+(i)+'">'+
						'<div class="d-none d-sm-block">'+
						'Acessar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-sign-in-alt fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'</div>' 

						]).draw(false);
				} 
			}); 
			break; 
			case "desativados": 
			$.each(organizacoes, function (i, org) { 
				if (org.ativo == 0) 
				{ 
					table.row.add([
						org.organizacao_nome,
						org.organizacao_pk,
						org.localizacao_rua.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+', '+org.localizacao_num+' - '+org.localizacao_bairro.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())+' - '+org.municipio_nome+'/'+org.estado_fk,						
						'<div class="btn-group">'+
						'<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="'+ (i) +'" data-target="#r-organizacao">'+
						'<div class="d-none d-sm-block">'+
						'Reativar'+
						'</div>'+
						'<div class="d-block d-sm-none">'+
						'<i class="fas fa-check-circle fa-fw"></i>'+
						'</div>'+
						'</button>'+
						'</div>'   

						]).draw(false);
				} 
			}); 
			break; 
		} 
	});

