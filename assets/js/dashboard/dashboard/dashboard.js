/*
 * == Variáveis globais: ==
 *
 * @Boolean: is_superusuario
 *
 *
 * @String: base_url
 *
 */

var table = $('#ordens_servico').DataTable();
var days = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-feira', 'Quinta-Feira', 'Sexta-feira', 'Sábado'];
// var myColours = ['','','','','','','','','','','','','','','','','','','','','','','','','','',''] 
var is_superusuario = false; 
var months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

class View extends GenericView {

	constructor() {
		super();
	}

	renderQuickAccess() {
		const allCards = [
			{
				text: "nova ordem",
				url: "Ordem_Servico",
				color: "blue",
				icon: "fa-thumbtack",
				action: "ver",
				controller: "ordem_servico"
			},
			{
				text: "novo relatório",
				url: "relatorio/novo",
				color: "orange",
				icon: "fa-tasks",
				action: "novo",
				controller: "relatorio"
			},
			{
				text: "mapa",
				url: "mapa",
				color: "red",
				icon: "fa-map-marker-alt",
				action: "ver",
				controller: "mapa"
			},
			{
				text: "atualizar",
				color: "green",
				icon: "fa-refresh",
				reload: true
			}
		];

		let cards;

		cards = this.getCardsWithPermission(allCards);

		const html = this.returnHtmlFromCards(cards);

		$(".quick-access").html(html);
	}

	renderOrdersByMonth(months, data){
	
		data.splice((data.length-1), 1);

		var ctx = document.getElementById("ordens_mes").getContext("2d");
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: months,
				datasets: [
					{
						label: 'Quantidade de Ordens de Serviço',
						data: data,
						backgroundColor: 'rgba(0,123,255, 0.5)',
						borderColor: 'rgba(0,123,255, 1)',
						borderWidth: 1,
						fill: 'start',
						lineTension: 0
					}]
				},
			options: {
				scales: {
					yAxes: [{
						ticks:{
							beginAtZero: true,
						},
					}],
				},
				tooltips: {
					mode: 'index',
					intersect: true
				}
				// annotation: {
				// 	annotations: [{
				// 		type: 'line',
				// 		mode: 'horizontal',
				// 		scaleID: 'y-axis-0',
				// 		value: 7,
				// 		borderColor: 'rgba(0, 35, 7, 1)',
				// 		borderWidth: 1,
				// 		label: {
				// 		enabled: false,
				// 		content: 'Média por Mês'
				// 		}
				// 	}]
				// },
			},
		});
	}


	renderOrdersByWeek(days, data){
		var ctx = document.getElementById("ordens_semana");
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: days,
				datasets: [
					{
						label: 'Abertas',
						data: data[0],
						backgroundColor: 'rgba(62, 119, 233, 1)',
						borderColor: 'rgba(62, 119, 233, 1)',
						borderWidth: 3,
						fill: false,
						lineTension: 0.5
					},
					{
						label: 'Finalizadas',
						data: data[1],
						borderColor : 'rgba(79, 199, 117, 1)',
						backgroundColor: 'rgba(79, 199, 117, 1)',
						borderWidth: 3,
						fill: false,
						lineTension: 0.5

					},
					{
						label: 'Andamento',
						data: data[2],
						borderColor : 'rgba(255, 214, 86, 1)',
						backgroundColor: 'rgba(255, 214, 86, 1)',
						borderWidth: 3,
						fill: false,
						lineTension: 0.5
					},
					{
						label: 'Recusadas (Repetidas)',
						data: data[3],
						borderColor : 'rgba(255, 89, 82, 1)',
						backgroundColor: 'rgba(255, 89, 82, 1)',
						borderWidth: 3,
						fill: false,
						lineTension: 0.5
					},
					{
						label: 'Recusadas (Não Procede)',
						data: data[4],
						borderColor : 'rgba(255, 89, 82, 0.5)',
						backgroundColor: 'rgba(255, 89, 82, 0.5)',
						borderWidth: 3,
						lineTension: 0.5,
						fill: false,
					}]
				},
			options: {
				scales: {
					yAxes: [{
						ticks:{
							beginAtZero: true,	
						},
					}],
					xAxes: [{
						ticks:{
							autoSkip: false
						},
					}],
				responsive: true
				},
				tooltips: {
					mode: 'point',
					intersect: true
				},
				annotation: {
					annotations: [{
						type: 'line',
						mode: 'horizontal',
						scaleID: 'y-axis-0',
						value: 7,
						borderColor: 'rgba(0, 35, 7, 1)',
						borderWidth: 1,
						label: {
						enabled: false,
						content: 'Média por Mês'
						}
					}]
				}
			},
		});	
	}

	renderOrdersbySector(sectors, data){
		var ctx = document.getElementById("ordens_setor_semana");
		var set = this.generateDataset(sectors, data, true);
		
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: days,
				datasets: set
				},
			options: {
				scales: { 
					xAxes: [{ stacked: true }],
            		yAxes: [{ stacked: true }],
					responsive: true,
				},
				tooltips: {
					enabled: true,
					mode: 'label'
					// intersect: true
				}
			},
		});	
	}

	renderOrdersbyServices(types, data){
	
		var ctx = document.getElementById("ordens_tipo_servico");
		
		var coloursBackground = [];
		var coloursBorders = [];

		//Generating colors for sections
		for(var i in types){
			var color = this.generateRandomColors();
			coloursBackground.push(color[0]);
			coloursBorders.push(color[1]);
		}		

		var myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				datasets: [{
					data:data,
					backgroundColor: coloursBackground,
					borderColor: coloursBorders,
				}],
				labels: types
				},
			options: {
				scales: { 
					responsive: true,
				},
				tooltips: {
					enabled: true,
					mode: 'label'
					// intersect: true
				}
			},
		});	

	}

	generateRandomColors(){
		var graphColors = [];
		var randomR = Math.floor(Math.random() * 256);
		var randomG = Math.floor(Math.random() * 256);
		var randomB = Math.floor(Math.random() * 256);
	
		var graphBackground = "rgba(" 
				+ randomR + ", " 
				+ randomG + ", " 
				+ randomB + ",1)";
		graphColors.push(graphBackground);
		
		var graphBorderColor = "rgba(" 
				+ randomR + ", " 
				+ randomG + ", " 
				+ randomB + ", 1)";
		graphColors.push(graphBorderColor);
		
		return graphColors;
	}

	generateDataset(sectors, data, fill){
		let sets = [];
		let coloursBackground = [];
		let coloursBorders = [];
		var j = 0; 

		//Generating colors for sections
		for(var i in sectors){
			var color = this.generateRandomColors();
			coloursBackground.push(color[0]);
			coloursBorders.push(color[1]);
		}

		for (var i in data){
			if(j ==  sectors.length) j = 0;

			var dataset = {
				label: sectors[j],
				data: data[i],
				backgroundColor: coloursBackground[j],
				borderColor: coloursBorders[j],
				fill: fill,
				borderWidth: 2
			}
			sets.push(dataset);
			j++;
		}
		 
		return sets;
	}

	returnHtmlFromCards(cards) {
		let html = "";

		let columnOfCard = 12 / cards.length;

		cards.forEach(card => {
			let onClick;
			let textUpdate = "";

			if ("reload" in card && card.reload == true) {
				onClick = `window.location.reload();`;
				textUpdate = `<small id="texto-atualizacao"></small>`;
			} else {
				onClick = `window.location = '${base_url + "/" + card.url}';`;
			}

			html += `
				<div class="col-sm-12 col-md-6 col-lg-${columnOfCard}">
					<div class="statistic__item statistic__item--${card.color} acesso-rapido">
						<div class="geral" onclick="${onClick}">
							<div class="bag">
								<div class="icones color-${card.color}">
									<i class="fa fas ${card.icon}"></i>
								</div>
								<div class="text">
									<h2>${card.text}</h2>
									${textUpdate}
								</div>
							</div>
						</div>
					</div>
				</div>
			`;
		});

		return html;
	}

	getCardsWithPermission(allCards) {
		let cards = [];

		allCards.forEach(card => {
			if (
				"action" in card &&
				"controller" in card &&
				this.hasPermissions(card.action, card.controller)
			) {
				cards.push(card);
			} else {
				if ("reload" in card) {
					cards.push(card);
				}
			}
		});

		return cards;
	}
}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/dashboard';
    }

}

class Control extends GenericControl {

    constructor() {

		super();
		
		this.primaryKey = "ordem_servico_pk";
        this.fields = ['ordem_servico_desc', 'servico_fk', 'procedencia_fk', 'prioridade_fk', 'situacao_inicial_fk', 'setor_fk', 'localizacao_municipio', 'localizacao_rua', 'localizacao_num', 'localizacao_bairro', 'localizacao_ponto_referencia','localizacao_lat', 'localizacao_long', 'setor_nome', 'servico_nome', 'prioridade_nome', 'procedencia_nome', 'ordem_servico_cod'];
        this.tableFields = ['ordem_servico_pk', 'ordem_servico_cod', 'prioridade_nome', 'servico_nome', 'funcionario_nome', 'situacao_atual_nome'];
		this.verifyDependences = false;
		
	}

	async init() {
        this.data = await this.myRequests.init();
		this.myView.init(this.data, this.tableFields, this.primaryKey);
		this.myView.renderOrdersByWeek(days, this.data.semana);
		this.myView.renderOrdersbySector(this.data.semana_setores[0], this.data.semana_setores[1]);
		this.myView.renderOrdersbyServices(this.data.semana_tipos[0], this.data.semana_tipos[1]);
		this.myView.renderOrdersByMonth(months, this.data.ano);
	}
}

const myControl = new Control();

myControl.init();
myControl.myView.renderQuickAccess();
myControl.myView.renderButtonsBasedOnPermissions();

$("#tabela-funcionario").click(function() {
	$("#table-funcionario").show();
	$(".heatmap").hide();
});

$("#tabela-grafico").click(function() {
	$(".heatmap").show();
	$("#table-funcionario").hide();
});

$(document).ready(function() {
	preencheAtualizacao("texto-atualizacao");
});

function preencheAtualizacao(id_element) {
	let options = {
		hour: "numeric",
		minute: "numeric",
		second: "numeric",
		day: "numeric",
		month: "numeric",
		year: "numeric"
	};
	let formatter = Intl.DateTimeFormat("pt-BR", options);
	let atual = formatter.format(new Date());
	$("#" + id_element).text("última atualização: " + atual);
}
