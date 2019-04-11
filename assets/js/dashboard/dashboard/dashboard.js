/*
 * == Variáveis globais: == 
 * 
 * @Boolean: is_superusuario
 * 
 * 
 * @String: base_url
 * 
 */

class View extends GenericView {

    constructor() {
        super();
	}

	
	renderQuickAccess() {
		const allCards = [
			{
				text: 'nova ordem',
				url: 'ordem_servico',
				color: 'blue',
				icon: 'fa-thumbtack',
				action: 'ver',
				controller: 'ordem_servico'
			},
			{
				text: 'novo relatório',
				url: 'relatorio/novo',
				color: 'orange',
				icon: 'fa-tasks',
				action: 'novo',
				controller: 'relatorio'
			}, 
			{
				text: 'mapa',
				url: 'mapa',
				color: 'red',
				icon: 'fa-map-marker-alt',
				action: 'ver',
				controller: 'mapa'
			},
			{
				text: 'atualizar',
				color: 'green',
				icon: 'fa-refresh',
				reload: true
			}
		];
		
		let cards;

		cards = this.getCardsWithPermission(allCards);

		const html = this.returnHtmlFromCards(cards);	

		$('.quick-access').html(html);

	}

	returnHtmlFromCards(cards) {
		let html = '';

		let columnOfCard = 12 / cards.length;
		
		cards.forEach( (card) => {
			let onClick;
			let textUpdate = '';

			if('reload' in card && card.reload == true) {
				onClick = `window.location.reload();`;
				textUpdate = `<small id="texto-atualizacao"></small>`;
			} else {
				onClick = `window.location = '${base_url + '/' + card.url}';`;
				textUpdate = '';
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

	getCardsWithPermission(allCards){
		let cards = [];

		allCards.forEach( (card) => {
			if( 'action' in card && 'controller' in card &&
				this.hasPermissions(card.action, card.controller)) {
					cards.push(card);
			} else {
				if('reload' in card){
					cards.push(card);
				}
			}
		});

		return cards;
	}
}

const myView = new View();

myView.renderQuickAccess();

myView.renderButtonsBasedOnPermissions();


$('#tabela-funcionario').click(function(){
	$('#table-funcionario').show();
	$('.heatmap').hide();
});


$('#tabela-grafico').click(function(){
	$('.heatmap').show();
	$('#table-funcionario').hide();
});


$( document ).ready(function() {

	preencheAtualizacao('texto-atualizacao');
});

function preencheAtualizacao(id_element){
	var data = new Date();
	var dia = data.getDate();
	var mes = data.getMonth();
	var ano = data.getFullYear();
	var hora = data.getHours();
	var minutos = data.getMinutes();
	var seg = data.getSeconds();

	var atual = dia+'/'+(mes+1)+"/"+ano+" "+hora+":"+minutos+":"+seg;
	$('#'+id_element).text('última atualização às '+atual);
}