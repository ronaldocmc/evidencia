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

$(".submit").click(function(){
	var data = 
		{
			'setor_pk': $('#setor_pk').val(),
			'setor_nome': $('#nome-input').val(),
           	'senha': $('#senha-input').val()
		}

	// btn_load($(".submit"));
    
    $.post(base_url+'/setor/save',data).done(function (response) {
    	console.log(response);
    	btn_ativar($(".submit"));

  		if(response.code == 200)
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
		$('#btn-reativar').val(setores[$(this).val()]["setor_pk"]);
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
	btn_load($('#btn-desativar'));

	$.post(base_url+'/setor/deactivate', data, function(response, textStatus, xhr) {
		wich_alert(response);
		btn_ativar($('#btn-desativar'));

		if (response.code == 200)
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
	btn_load($('#btn-reativar'));
	$.post(base_url+'/setor/activate', data, function(response, textStatus, xhr) {
		wich_alert(response);
		btn_ativar($('#btn-reativar'));

		if (response.code == 200)
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
		          			'<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" data-target="#r-setor" value="'+ (i) +'" title="Reativar">'+
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
		          			'<button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" data-target="#r-setor" value="'+ (i) +'" title="Reativar">'+
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
