$(".submit").click(function(){
	var data = 
		{
			'setor_pk': $('#setor_pk').val(),
			'setor_nome': $('#nome-input').val(),
           	'senha': $('#senha-input').val()
		}
    $.post(base_url+'/setor/insert_update',data).done(function (response) {
  		if (response.code == 400)
  		{
			show_errors(response);
			alerts('failed','Erro!','O formulário apresenta algum(ns) erro(s) de validação');
			$('#senha-input').val('');
  		}
  		else if (response.code == 401)
  		{
  			show_errors(response);
			alerts('failed','Erro!','Senha informada incorreta');
			$('#senha-input').val('');
			$('#senha-input').focus();
  		}
  		else if (response.code == 500)
  		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
  			$('#senha-input').val('');
  		}
  		else if(response.code == 200)
  		{
  			setor  = 
	  			{
	  				'setor_nome': data['setor_nome'],
	  				'setor_status': 1,
	  			}
  			if (data['setor_pk']=='')
  			{
  					setor['setor_pk'] = response.data['id'];
		  			setores.push(setor);
		  			change_table($('#filter-ativo'));
		  			alerts('success','Sucesso!','Setor inserido com sucesso');
  			}
  			else
  			{
  				setor['setor_pk'] = data['setor_pk'];
  				for (var i in setores)
  				{
  					if (setores[i]['setor_pk']==data['setor_pk'])
  						break;
  				}
  				setores[i] = (setor);
  				change_table($('#filter-ativo'));
  			}
  			$('#ce_setor').modal('hide');
		}
	}, "json");
});

/*
* Ação para abrir o formulário para editar as informações do departamento.
* Envia as informações necessárias para preencher os campos
*/

$(document).on('click','.btn_editar',function() {
	$('#setor_pk').val(setores[$(this).val()]["setor_pk"]);
	$('#nome-input').val(setores[$(this).val()]["setor_nome"]);	
});

/*
* Ação para abrir o formulário para desativar um departamento.
*/

$(document).on('click','.btn-desativar',function(event) {
	$('#btn-desativar').val(setores[$(this).val()]["setor_pk"]);
});

/*
* Ação para abrir o formulário para desativar um departamento, caso seja um superusúário,
* ou reativar diretamente caso seja um administrador.
*/

$(document).on('click','.btn_reativar',function(event) {
	if (is_superusuario)
	{
		$('#r-setor').modal("show");
		$('#btn-reativar').val(setores[$(this).val()]["setor_pk"]);
	}
	else
	{
		activate(setores[$(this).val()]["setor_pk"],null);
	}
});

/*
* Ação para abrir o atualizar a tabela de setores de acordo com o filtro.
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
			'setor_pk' : $(this).val(),
			'senha': $('#pass-modal-desativar').val()
		}
	$.post(base_url+'/setor/deactivate', data, function(response, textStatus, xhr) {
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
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
  		}
		else
		{
			alerts('success','Sucesso!','Departamento desativado com sucesso');
			for (var i in setores)
			{
				if (setores[i]['setor_pk']==data['setor_pk'])
					break;
			}
			setores[i]['setor_status'] = 0;
			$('#filter-ativo').change();
			$('#d-setor').modal('hide');
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

activate = (setor_pk, pass) => {
	var data = 
		{
			'setor_pk' : setor_pk,
			'senha': pass
		}
	$.post(base_url+'/setor/activate', data, function(response, textStatus, xhr) {
		if (response.code == 401)
		{
			alerts('failed','Erro!','O formulário apresenta algum erro de validação');
		}
  		else if (response.code == 401)
  		{
  			show_errors(response);
			alerts('failed','Erro!','Senha informada incorreta');
  		}
  		else if (response.code == 1500 || response.code == 1501)
  		{
  			show_errors(response);
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
  		}
		else
		{
			alerts('success','Sucesso!','Departamento reativado com sucesso');
			for (var i in setores)
			{
				if (setores[i]['setor_pk']==data['setor_pk'])
					break;
			}
			setores[i]['setor_status'] = 1;
			$('#filter-ativo').change();
			$('#r-setor').modal('hide');
		}
	});
}


change_table = (select_options) => {
	table.clear().draw(); 
  	switch (select_options.val()) { 
	    case "todos": 
	      	$.each(setores, function (i, setor) {
	      		if (setor.setor_status == 1) 
	        	{ 
		        	table.row.add([ 
		          		setor.setor_nome, 
		          		'<div class="btn-group">'+
		          			'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_setor" title="Editar">'+
                                '<div class="d-none d-sm-block">'+
                                    '<i class="fas fa-edit fa-fw"></i>'+
                                '</div>'+
                            '</button>'+
                            '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-setor" title="Desativar">'+
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
		          		setor.setor_nome, 
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
	    case "ativos": 
	     	$.each(setores, function (i, setor) { 
	        	if (setor.setor_status == 1) 
	        	{ 
	          		table.row.add([ 
	            		setor.setor_nome, 
		          		'<div class="btn-group">'+
		          			'<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_setor" title="Editar">'+
                                '<div class="d-none d-sm-block">'+
                                    '<i class="fas fa-edit fa-fw"></i>'+
                                '</div>'+
                            '</button>'+
                            '<button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="' + (i) + '" data-target="#d-setor" title="Desativar">'+
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
	    	$.each(setores, function (i, setor) { 
	      		if (setor.setor_status == 0) 
	      		{ 
		        	table.row.add([ 
		          		setor.setor_nome, 
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
