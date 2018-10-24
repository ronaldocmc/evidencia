
$("#gerar_pdf_dia").click(function () {
	var relatorio = "data";
	var filtro = $("#qtd_dias").val();

	enviar(relatorio, filtro);
});

$("#gerar_pdf_setor").click(function () {
	var relatorio = "setor";
	var filtro = $("#setor").val();

	enviar(relatorio, filtro);
});

$("#gerar_pdf_dpto").click(function () {
	var relatorio = "departamento";
	var filtro = $("#departamento").val();

	enviar(relatorio, filtro);
});

$("#gerar_pdf_servico").click(function () {
	var relatorio = "servico";
	var filtro = $("#servico").val();

	enviar(relatorio, filtro);
});


function enviar(relatorio, filtro) {
	window.open(base_url+'/relatorio/gera_relatorio_geral/'+relatorio+'/'+filtro+'/'+$("#situacao_pk").val())
}

$("#btn-restaurar").click(function() {
	var senha = $("#pass-modal-restaurar").val();

	if(senha == ""){
		return;
	}

	var data = 
	{
		'senha' : senha
	}

	$.post(base_url+'/Relatorio/restaurar_os',data).done(function (response) {

		if (response.code == 200) {
			alerts('success','Sucesso!','Ordens de Serviço restauradas.');
			$('#restaurar_os').modal('hide');
		}
		else if (response.code == 404) {
			alerts('success','Sucesso!','Não há ordens de serviço para serem restauradas.');
			$('#restaurar_os').modal('hide');
		}
		else if (response.code == 401) {
			alerts('failed','Erro!','Senha incorreta');
		}

		$("#pass-modal-restaurar").val("");

	}, "json");
});