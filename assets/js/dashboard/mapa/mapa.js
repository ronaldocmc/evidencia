let departamento = $("#departamento_pk");
let tipo_servico = $("#tipo_servico_pk");
let servico = $("#servico_pk");
let prioridade = $("#prioridade_pk");
let situacao = $("#situacao_pk");
let setor = $("#setor_pk");
let de = $("#de");
let ate = $("#ate");

$(document).ready(function() {
	de.val(formatDate(lastWeekDate()));
	initMap();
});

// Get last Week date
function lastWeekDate() {
	let d = new Date();
	d.setDate(d.getDate() - 7);
	return d;
}

// format given date to String YYYY-MM-DD
function formatDate(date) {
	let d = new Date(date);
	return d.toISOString().split("T")[0];
}

// Remove HTML content on modal div
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

function has_all(selected) {
	for (let i = 0; i < selected.length; i++) {
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
	for (let i = 0; i < tipos_servicos.length; i++) {
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
	for (let i = 0; i < servicos.length; i++) {
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
	for (let i = 0; i < tipos_servicos.length; i++) {
		for (let j = 0; j < deptos.length; j++) {
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

	let tipos = tipo_servico.val();

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

class Request extends GenericRequest {
	constructor() {
		super();
		this.route = "/Ordem_Servico";
	}

	async init() {
		let filters = {
			data_inicial: formatDate(lastWeekDate()) + " 00:00:00",
			data_final: formatDate(new Date()) + " 23:59:59"
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

	async showFilteredOS() {
		let filters = this.myView.get_filters();
		let data = this.myRequests.getOSbyFilter(filters);
		return data;
	}
}

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

	get_filters() {
		let filters = {
			departamento_fk: departamento.val() != -1 ? departamento.val() : null,
			tipo_servico_pk: tipo_servico.val() != -1 ? tipo_servico.val() : null,
			servico_fk: servico.val() != -1 ? servico.val() : null,
			prioridade_fk: prioridade.val() != -1 ? prioridade.val() : null,
			setor_fk: setor.val() != -1 ? setor.val() : null,
			situacao_atual_fk: situacao.val() != -1 ? situacao.val() : null,
			data_inicial: de.val() != "" ? de.val() + " 00:00:00" : null,
			data_final: ate.val() != "" ? ate.val() + " 23:59:59" : null
		};
	
		return filters;
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

	$("#filtrar").click(async () => {
		$("#p_ordens").hide();
		btn_load($("#filtrar"));
		map.clearMarkers();
		map.state.markers = await myControl.showFilteredOS();
		map.renderMarkers();
		btn_ativar($("#filtrar"));
	});
};
