let view;
let main_map;
let markers = null;

let departamento = $("#departamento_pk");
let tipo_servico = $("#tipo_servico_pk");
let servico = $("#servico_pk");
let prioridade = $("#prioridade_pk");
let situacao = $("#situacao_pk");
let setor = $("#setor_pk");
let de = $("#de");
let ate = $("#ate");

// TODO: Replace with Generics
$(document).ready(function() {
	view = new GenericView();
	view.conditionalRender();

	btn_load($("#filtrar"));

	let date = new Date();

	let filters = {
		data_inicial:
			date.getFullYear() +
			"-" +
			(date.getMonth() + 1) +
			"-" +
			(date.getDate() - 7) +
			" 00:01:00",
		data_final:
			date.getFullYear() +
			"-" +
			(date.getMonth() + 1) +
			"-" +
			date.getDate() +
			" 23:59:00"
	};

	$("#de").val(formatDate(lastWeek()));
	$("#ate").val(formatDate(new Date()));

	let url = base_url + "/Ordem_Servico/get_map";

	$.post(url, filters)
		.done(function(response) {
			markers = [];
			response.data.map(function(ordem) {
				popula_markers(ordem);
			});
		})
		.fail(function(response) {});

	btn_ativar($("#filtrar"));
});

//TODO: Refactor this 
function lastWeek() {
	var today = new Date();
	var lastweek = new Date(
		today.getFullYear(),
		today.getMonth(),
		today.getDate() - 7
	);
	return lastweek;
}

//TODO: Refactor this to use Intl.formatdate
function formatDate(date) {
	var d = new Date(date),
		month = "" + (d.getMonth() + 1),
		day = "" + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = "0" + month;
	if (day.length < 2) day = "0" + day;

	return [year, month, day].join("-");
}


function seleciona_imagem(prioridade) {
	let imagem = "./assets/img/icons/Markers/Status/";

	switch (prioridade) {
		case "1": {
			imagem += "prioridade_baixa.png";
			break;
		}
		case "2": {
			imagem += "prioridade_alta.png";
			break;
		}
		case "4": {
			imagem += "prioridade_media.png";
			break;
		}
	}

	return imagem;
}

// TODO: Dafuq is this
function remove_data() {
	$("#v_descricao").html("");
	$("#v_codigo").html("");
	$("#v_procedencia").html("");
	$("#v_setor").html("");
	$("#v_servico").html("");
	$("#card_slider").html("");
	$("#timeline").html("");
	$("#v_loading").show();
}

// TODO: Replace with GenericRequests call
function request_data(id, setor) {
	remove_data();
	btn_load($("#filtrar"));
	$.ajax({
		url: base_url + "/Ordem_Servico/get_specific/" + id,
		dataType: "json",
		success: function(response) {
			$("#v_descricao").html(
				response.data.ordem_servico[0].ordem_servico_desc
			);
			$("#v_codigo").html(
				response.data.ordem_servico[0].ordem_servico_cod
			);
			$("#v_setor").html(response.data.ordem_servico[0].setor_nome);
			$("#v_servico").html(response.data.ordem_servico[0].servico_nome);

			let carousel = "";
			let indicators = "";
			let active = " active";
			let timeline = "";
			let cards = "";

			carousel = view.renderCarousel(response.data.imagens);
			timeline = view.renderTimelineHistoric(response.data.historico);
			timeline += view.renderCurrentSituation(
				createCurrentSituationOject(response.data.ordem_servico[0])
			);

			if (response.data.imagens.length > 0) {
				cards = view.renderCarouselCards(response.data.imagens);
			}

			$("#v_loading").hide();
			$("#card_slider").html(carousel);
			$(".carousel-inner").html(cards);
			$("#timeline").html(timeline);
		}
	});
	btn_ativar($("#filtrar"));
}

function createCurrentSituationOject(os) {
	let data = {
		funcionario_caminho_foto: os.funcionario_caminho_foto,
		funcionario_nome: os.funcionario_nome,
		ordem_servico_atualizacao: os.ordem_servico_atualizacao,
		situacao_atual_nome: os.situacao_nome,
		ordem_servico_comentario: os.ordem_servico_comentario
	};

	return data;
}

function has_all(selected) {
	for (var i = 0; i < selected.length; i++) {
		if (selected[i] === "-1") {
			return true;
		}
	}

	return false;
}

departamento.on("click", () => {
	if (has_all(departamento.val())) {
		all_tipos_servicos();
	} else {
		muda_depto();
	}
});

tipo_servico.on("click", () => {
	if (has_all(tipo_servico.val())) {
		all_servicos();
	} else {
		muda_tipo_servico();
	}
});

function all_tipos_servicos() {
	$("#tipo_servico_pk option").remove();

	tipo_servico.append('<option value="-1">Todos</option>');

	for (var i = 0; i < tipos_servicos.length; i++) {
		tipo_servico.append(
			'<option value="' +
				tipos_servicos[i].tipo_servico_pk +
				'">' +
				tipos_servicos[i].tipo_servico_nome +
				"</option>"
		);
	}

	all_servicos();
}

function all_servicos() {
	$("#servico_pk option").remove();

	servico.append('<option value="-1">Todos</option>');
	for (var i = 0; i < servicos.length; i++) {
		servico.append(
			'<option value="' +
				servicos[i].servico_pk +
				'">' +
				servicos[i].servico_nome +
				"</option>"
		);
	}
}

function add_options_tipo_servico() {
	$("#tipo_servico_pk option").remove();

	tipo_servico.append('<option value="-1">Todos</option>');

	let deptos = departamento.val();

	for (var i = 0; i < tipos_servicos.length; i++) {
		for (var j = 0; j < deptos.length; j++) {
			if (tipos_servicos[i].departamento_fk == deptos[j]) {
				tipo_servico.append(
					'<option value="' +
						tipos_servicos[i].tipo_servico_pk +
						'">' +
						tipos_servicos[i].tipo_servico_nome +
						"</option>"
				);
				break;
			}
		}
	}
}

function add_options_servico() {
	$("#servico_pk option").remove();
	servico.append('<option value="-1">Todos</option>');

	var tipos = tipo_servico.val();

	for (var i = 0; i < servicos.length; i++) {
		for (var j = 0; j < tipos.length; j++) {
			if (servicos[i].tipo_servico_fk == tipos[j]) {
				servico.append(
					'<option value="' +
						servicos[i].servico_pk +
						'">' +
						servicos[i].servico_nome +
						"</option>"
				);
				break;
			}
		}
	}
}

function muda_depto() {
	add_options_tipo_servico();

	add_options_servico();
}

function muda_tipo_servico() {
	add_options_servico();
}

$("#filtrar").click(function() {
	$("#p_ordens").hide();
	btn_load($("#filtrar"));

	let filters = get_filters();
	let url = base_url + "/Ordem_Servico/get_map";

	$.post(url, filters)
		.done(function(response) {
			removeAll();
			markers = [];
			response.data.map(function(ordem) {
				popula_markers(ordem);
			});
		})
		.fail(function(response) {});

	btn_ativar($("#filtrar"));
});

function removeAll() {
	if (markers !== null) {
		markers.map(marker => {
			marker.setMap();
			marker.setVisible(false);
		});
	}
}

function get_filters() {
	let filters = {
		departamento_fk: departamento.val() != -1 ? departamento.val() : null,
		tipo_servico_pk: tipo_servico.val() != -1 ? tipo_servico.val() : null,
		servico_fk: servico.val() != -1 ? servico.val() : null,
		prioridade_fk: prioridade.val() != -1 ? prioridade.val() : null,
		setor_fk: setor.val() != -1 ? setor.val() : null,
		situacao_atual_fk: situacao.val() != -1 ? situacao.val() : null,
		data_inicial: de.val() != -1 ? de.val() + " 00:01:00" : null,
		data_final: ate.val() != -1 ? ate.val() + " 23:59:00" : null
	};

	return filters;
}

// TODO: replace this with renderMarkers and such
function popula_markers(ordem) {
	let imagem = seleciona_imagem(ordem.prioridade_fk);

	var marker = new google.maps.Marker({
		position: {
			lat: parseFloat(ordem.localizacao_lat),
			lng: parseFloat(ordem.localizacao_long)
		},
		map: main_map,
		icon: imagem,
		id: ordem.ordem_servico_pk,
		departamento: ordem.departamento_fk,
		tipo_servico: ordem.tipo_servico_pk,
		servico: ordem.servico_fk,
		situacao: ordem.situacao_atual_fk,
		data_criacao: ordem.ordem_servico_criacao,
		prioridade: ordem.prioridade_fk,
		setor: ordem.setor_fk,
		title:
			ordem.localizacao_rua +
			", " +
			ordem.localizacao_num +
			" - " +
			ordem.localizacao_bairro
	});

	marker.addListener("click", function() {
		main_map.panTo(marker.getPosition());
		request_data(this.id, marker.setor);
		$("#v_evidencia").modal("show");
	});

	markers.push(marker);
}

// function initMap() {
// 	main_map = new google.maps.Map(document.getElementById("map"), {
// 		center: { lat: -22.114184, lng: -51.405798 },
// 		zoom: 14
// 	});
// }

class Request extends GenericRequest {

    constructor() {
        super();
		this.route = "/Ordem_Servico"; // get_map method
	}

	async init() {
		let date = new Date();
		let filters = {
			data_inicial:
				date.getFullYear() +
				"-" +
				(date.getMonth() + 1) +
				"-" +
				(date.getDate() - 7) +
				" 00:01:00",
			data_final:
				date.getFullYear() +
				"-" +
				(date.getMonth() + 1) +
				"-" +
				date.getDate() +
				" 23:59:00"
		};
		const response = await this.send("/get_map", filters);
		return response.data;
	}
}
class Control extends GenericControl {

	constructor() {

		super();
		this.verifyDependences = false;
	}

	async init() {
		this.data = await this.myRequests.init();
	}
}

// Dummy View for the GenericControl
class View extends GenericView {
	constructor() {
		super();
	}
	init() {}
}

const myControl = new GenericControl();

initMap = async () => {
	await myControl.init();
	const map = new GenericMap({
		mapId: "map",
		insideHideDiv: false,
		setIcons: true,
		config: {
			center: { lat: -22.121265, lng: -51.3834 },
			zoom: 14
		},
		markerConfig: {
			unique: true,
			clickable: true,
			target: "v_evidencia"
		},
		input: {
			sublocality: "localizacao_bairro",
			locality: "localizacao_municipio",
			street: "localizacao_rua",
			street_number: "localizacao_num",
			state: false,
			lat: "localizacao_lat",
			long: "localizacao_long"
		},

		data: myControl.data, // ?????

		useGeocoder: true,
		useCreateMarker: true
	});

	map.initMap();
};
