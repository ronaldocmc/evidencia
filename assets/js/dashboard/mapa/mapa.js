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
	initMap();
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

class Request extends GenericRequest {
	constructor() {
		super();
		this.route = "/Ordem_Servico";
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
		return this.getOSbyFilter(filters);
	}

	async getOSbyFilter(filters) {
		const response = await this.send("/get_map", filters);
		return response.data;
	}

	async getSpecificOS(id) {
		// Request to get specific os by id
		const os_data = await this.send("/get_specific/" + id, {});
		return os_data;
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

	async showSpecificOS(id) {
		remove_data();
		btn_load($("#filtrar"));
		let os_data = await this.myRequests.getSpecificOS(id);
		this.myView.renderModalFromMap(os_data.data);
		btn_ativar($("#filtrar"));
	}
}

// Dummy View for the GenericControl
class View extends GenericView {
	constructor() {
		super();
		this.conditionalRender();
	}
	init() {}

	renderModalFromMap(data) {
		$("#v_descricao").html(data.ordem_servico[0].ordem_servico_desc);
		$("#v_codigo").html(data.ordem_servico[0].ordem_servico_cod);
		$("#v_setor").html(data.ordem_servico[0].setor_nome);
		$("#v_servico").html(data.ordem_servico[0].servico_nome);

		let carousel = "";
		let timeline = "";
		let cards = "";

		carousel = this.renderCarousel(data.imagens);
		timeline = this.renderTimelineHistoric(data.historico);
		timeline += this.renderCurrentSituation(
			createCurrentSituationOject(data.ordem_servico[0])
		);

		if (data.imagens.length > 0) {
			cards = this.renderCarouselCards(data.imagens);
		}

		$("#v_loading").hide();
		$("#card_slider").html(carousel);
		$(".carousel-inner").html(cards);
		$("#timeline").html(timeline);
		$("#v_evidencia").modal("show");
	}
}

const myControl = new Control();

const initMap = async () => {
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
			unique: false,
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

		data: myControl.data,

		useGeocoder: true,
		useCreateMarker: true
	});

	map.initMap();
	map.handleMarkerClick = async event => {
		await myControl.showSpecificOS(event.ordem_servico_pk);
	};
};
