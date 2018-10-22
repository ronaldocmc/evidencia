
/**
* Funções de tratamento das mudanças nos campos de select
*/

$("#departamento_pk").change(function () {
	if ($(this).val() != -1) {
		muda_tipos_servico();
	} 
	else {
		all_tipos_servicos();
	}
}).change();


$("#tipo_servico_pk").change(function () {
	if ($(this).val() != -1) {
		muda_servicos();
	} 
	else {
		if ($("#departamento_pk").val() != -1) {
			muda_servicos_depto();
		}
		else{
			all_servicos();
		}
	}
}).change();


$("#estado_pk").change(function () {
	if ($(this).val() != -1) {
		muda_municipios();
	} 
	else {
		all_municipios();
	}
}).change();


$("#municipio_pk").change(function () {
	if ($(this).val() != -1) {
		muda_bairros();
	} else {
		if ($("#estado_pk").val() != -1) {
			var muns = get_muns(); 
			muda_bairros_municipios(muns);
		}
		else{
			all_bairros();
		}
	}
}).change();


/**
* Funções para mudar as opções disponíveis nos selects
*/
function muda_tipos_servico() {
	var deptos = $("#departamento_pk").val();

	if(deptos[0] == "-1" || deptos.length == 0) {
		all_tipos_servicos();
	}
	else {
		$("#tipo_servico_pk option").remove();
		$("#tipo_servico_pk").append('<option value="-1">Todos</option>');

		for (var i = 0; i < tipos_servicos.length; i++) {
			for (var j = 0; j < deptos.length; j++) {
				if (tipos_servicos[i].departamento_fk == deptos[j]) {
					$("#tipo_servico_pk").append('<option value="' + tipos_servicos[i].tipo_servico_pk + '">' + tipos_servicos[i].tipo_servico_nome + '</option>')
					break;
				}
			}
		}

		muda_servicos_depto();
	}
}


function muda_servicos() {
	var tipos_s = $("#tipo_servico_pk").val();

	if(tipos_s[0] == "-1" || tipos_s.length == 0) {
		all_servicos();
	}
	else {
		$("#servico_pk option").remove();
		$("#servico_pk").append('<option value="-1">Todos</option>');

		for (var i = 0; i < servicos.length; i++) {
			for (var j = 0; j < tipos_s.length; j++) {
				if (servicos[i].tipo_servico_fk == tipos_s[j]) {
					$("#servico_pk").append('<option value="' + servicos[i].servico_pk + '">' + servicos[i].servico_nome + '</option>')
					break;
				}
			}
		}
	}
}


function muda_servicos_depto() {
	var deptos = $("#departamento_pk").val();

	if(deptos[0] == "-1" || deptos.length == 0) {
		all_servicos();
	}
	else {
		$("#servico_pk option").remove();
		$("#servico_pk").append('<option value="-1">Todos</option>');

		for (var i = 0; i < servicos.length; i++) {
			for (var j = 0; j < deptos.length; j++) {
				if (servicos[i].departamento_fk == deptos[j]) {
					$("#servico_pk").append('<option value="' + servicos[i].servico_pk + '">' + servicos[i].servico_nome + '</option>')
					break;
				}
			}
		}
	}
}


function all_tipos_servicos() {
	$("#tipo_servico_pk option").remove();

	$("#tipo_servico_pk").append('<option value="-1">Todos</option>');
	for (var i = 0; i < tipos_servicos.length; i++) {
		$("#tipo_servico_pk").append('<option value="' + tipos_servicos[i].tipo_servico_pk + '">' + tipos_servicos[i].tipo_servico_nome + '</option>');
	}

	all_servicos();
}


function all_servicos() {
	$("#servico_pk option").remove();

	$("#servico_pk").append('<option value="-1">Todos</option>');
	for (var i = 0; i < servicos.length; i++) {
		$("#servico_pk").append('<option value="' + servicos[i].servico_pk + '">' + servicos[i].servico_nome + '</option>');
	}
}


// Parte da localização -------------------

function muda_municipios() {
	var muns = [];
	var cont = 0;
	var estados = $("#estado_pk").val();

	if(estados[0] == "-1" || estados.length == 0) {
		all_municipios();
	}
	else {
		$("#municipio_pk option").remove();
		$("#municipio_pk").append('<option value="-1">Todos</option>');

		for (var i = 0; i < municipios.length; i++) {
			for (var j = 0; j < estados.length; j++) {
				if (municipios[i].estado_fk == estados[j]) {
					$("#municipio_pk").append('<option value="' + municipios[i].municipio_pk + '">' + municipios[i].municipio_nome + '</option>')
					muns[cont] = municipios[i].municipio_pk;
					cont++;
					break;
				}
			}
		}
		if (cont > 0) {
			muda_bairros_municipios(muns);
		}
	}
}


function muda_bairros_municipios(muns) {
	$("#bairro_pk option").remove();
	$("#bairro_pk").append('<option value="-1">Todos</option>');

	for (var i = 0; i < bairros.length; i++) {
		for (var j = 0; j < muns.length; j++) {
			if (bairros[i].municipio_fk == muns[j]) {
				$("#bairro_pk").append('<option value="' + bairros[i].bairro_pk + '">' + bairros[i].bairro_nome + '</option>')
				break;
			}
		}
	}
}


function muda_bairros() {
	var muns = $("#municipio_pk").val();

	if(muns[0] == "-1" || muns.length == 0) {
		all_bairros();
	}
	else {
		$("#bairro_pk option").remove();
		$("#bairro_pk").append('<option value="-1">Todos</option>');

		for (var i = 0; i < bairros.length; i++) {
			for (var j = 0; j < muns.length; j++) {
				if (bairros[i].municipio_fk == muns[j]) {
					$("#bairro_pk").append('<option value="' + bairros[i].bairro_pk + '">' + bairros[i].bairro_nome + '</option>')
					break;
				}
			}
		}
	}
}


function all_municipios() {
	$("#municipio_pk option").remove();

	$("#municipio_pk").append('<option value="-1">Todos</option>');
	for (var i = 0; i < municipios.length; i++) {
		$("#municipio_pk").append('<option value="' + municipios[i].municipio_pk + '">' + municipios[i].municipio_nome + '</option>');
	}

	all_bairros();
}


function all_bairros() {
	$("#bairro_pk option").remove();

	$("#bairro_pk").append('<option value="-1">Todos</option>');
	for (var i = 0; i < bairros.length; i++) {
		$("#bairro_pk").append('<option value="' + bairros[i].bairro_pk + '">' + bairros[i].bairro_nome + '</option>');
	}
}


function get_muns() {
	var muns = [];
	$("#municipio_pk option").each(function () {
		muns = $(this).val();
	});

	return muns;
}


// Fim do tratamento de selects ----------------------------

// Tratamento de visualização dos filtros

// $("#visualizar").click(function () {
// 	var data = 
// 	{
// 		"departamento_fk" : $("#departamento_pk").val(),
// 		"setor_fk" : $("#setor_pk").val(),
// 		"procedencia_fk" : $("#procedencia_pk").val(),
// 		"situacao_fk" : $("#situacao_pk").val(),
// 		"prioridade_fk" : $("#prioridade_pk").val(),
// 		"tipo_servico_fk" : $("#tipo_servico_pk").val(),
// 		"servico_fk" : $("#servico_pk").val(),
// 		"data_criacao" : $("#data_criacao").val(),
// 		"data_fin" : $("#data_fi").val(),
// 		"hr_inicial" : $("#hr_inicial").val(),
// 		"hr_final" : $("#hr_final").val(),
// 		"estado_pk" : $("#estado_pk").val(),
// 		"municipio_pk" : $("#municipio_pk").val(),
// 		"bairro_pk" : $("#bairro_pk").val()
// 	}

// 	$.post(base_url+'/relatorio/ordens_servico',data).done(function (response) {

// 	}, "json");
// });