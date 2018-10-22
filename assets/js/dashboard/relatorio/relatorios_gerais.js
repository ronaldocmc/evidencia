
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