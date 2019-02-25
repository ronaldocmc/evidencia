var form;

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


function lastWeek(){
    var today = new Date();
    var lastweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()-7);
    return lastweek;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

$(document).ready(function (){
	$('#data_inicial').val(formatDate(lastWeek()));
	$('#data_final').val(formatDate(new Date()));
});


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


$("#gerar_relatorio").click(function() {

	btn_load($('#gerar_relatorio'));
	form = $('form#submit-form').serialize();
	console.log(form);

	$.post(base_url+'/Relatorio/select_os_by_filter',form).done(function (response) {
		btn_ativar($('#gerar_relatorio'));
		if (response.code == 200) {
			$("#confirmar_criacao").modal('show');
			$("#p_qtd").text("Esse relatório terá "+ response.data + " ordens de serviço!");
		}
		else if (response.code == 400) {
			alerts('failed','Erro!',response.data.message);
		}

	}, "json");
});


$("#confirmar").click(function() {

	btn_load($('#confirmar'));

	$.post(base_url+'/Relatorio/create_new_report',form).done(function (response) {
		btn_ativar($('#confirmar'));
		console.log(response);
		if (response.code == 200) {
			alerts('success','Sucesso!', response.data.message);
			window.location.href = base_url+'/Relatorio/report_details/'+response.data.id;
		}
		else if (response.code == 400) {
			alerts('failed','Erro!',response.data.message);
		}
		else{
			alerts('failed', 'Erro!', 'Tem de conexão excedido.<br>Por favor, recarregue a página.');
		}

	}, "json");
});