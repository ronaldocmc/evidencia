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

$("#btn-restaurar").click(function() {
	btn_load($('#btn-restaurar'));
	var senha = $("#pass-modal-restaurar").val();
	console.log(senha);

	if(senha == ""){
		alerts('failed','Erro!','Informe a senha!');
		btn_ativar($('#btn-restaurar'));
		return;
	}

	var data = 
	{
		'senha' : senha
	}

	$.post(base_url+'/Relatorio/receive_report/', data).done(function (response) {
		btn_ativar($('#btn-restaurar'));
		if (response.code == 200) {
			alerts('success','Sucesso!','Relatórios recebidos com sucesso.');
			$('#restaurar_os').modal('hide');
		}
		else if (response.code == 404) {
			alerts('success','Sucesso!','Não há relatórios para serem recebidos!');
			$('#restaurar_os').modal('hide');
		}
		else if (response.code == 401) {
			alerts('failed','Erro!','Senha incorreta');
		}

		$("#pass-modal-restaurar").val("");
		window.location.href = base_url+'/Relatorio/';
	}, "json");
});
