/*
 * == Variáveis globais: ==
 *
 * @Boolean: is_superusuario
 *
 * @String: base_url
 *
 */

class View extends GenericView {
	constructor() {
		super();
	}

	async fillPermissions(permissions) {
		let title;
		let checkbox = "";
		let entities = [];
		let exists;
		let check_all;
		let tooltip;

		let tooltip_texts = {
			"Alterar e Criar": "Permissão de criação e alteração de dados",
			"Desativar": "Permitir que essa função desative itens",
			"Ativar": "Permitir que essa função ative itens",
			"Ver": "Permissão de visualização de dados de uma entidade",
			"Modificar Situação": "Permitir que a função modifique a situaçãod euma Ordem de Serviço",
			"Excluir": "Permitir que a função remova uma Ordem de Serviço completamente",
			"Alterar senha": "Permitir a alteração de senha",
			"Receber Relatório": "Permitir que funcionários com esse cargo recebam relatórios",
			"Trocar Funcionário": "Permitir que portadores dessa função alterem a designação de funcionários para relatórios",
			"Acessar Web": "Permissão que funcionários com essa funções sejam capazes de acessar a aplicação Web",
			"Acessar App": "Permissão que funcionários com essa funções sejam capazes de acessar o app",
		}

		permissions.forEach(permission => {
			exists = false;

			checkbox = this.generateCheckBox(
				"acao_nome",
				"permissao_pk",
				permission,
				"permissoes"
			);

			entities.forEach(e => {
				if (e === permission.entidade) {
					exists = true;
				}
			});

			if (!exists) {
				$("#permissions").append(
					`<div id='${permission.entidade
						.split(" ")
						.join("_")}' class='container'>
						</div>`
				);
				entities.push(permission.entidade);
				title = this.generateTitle(permission.entidade, 4);
				tooltip = this.generateToolTip(permission.entidade);
				$(`#${permission.entidade.split(" ").join("_")}`).append(title);
				$(`#${permission.entidade.split(" ").join("_")}`).append(
					tooltip + "<br>"
				);
			}

			$(`#${permission.entidade.split(" ").join("_")}`).append(
				checkbox + "&nbsp" + "&nbsp"
			);
		});

		entities.forEach(e => {
			check_all = this.generateCheckAll(e.split(" ").join("_"));
			$(`#${e.split(" ").join("_")}`).append(
				check_all + "&nbsp" + "&nbsp"
			);

			$(`#${e.split(" ").join("_")}`).append(`<br><hr>`);
		});
	}

	checkPermissions(permissions) {
		permissions.forEach(permission => {
			$(`#id-${permission.id}`).prop("checked", true);
		});
	}

	generateCheckAll(entity) {
		let render = `<div title='Selecionar todas as permissões.' style='display:inline-block'>
		            <input class='check_all' type='checkbox'
                    id='id-${entity}'
                    name='check_all'>
                    <label for='id-${entity}'> Selecionar Todas </label></div>`;

		return render;
	}

	generateToolTip(tooltip_text) {
		let tooltip = `<i class="fas fa-question-circle" data-toggle="tooltip" data-placement="auto" data-selector="true" title="${tooltip_text}" style="display: inline-block"></i>`;
		return tooltip;
	}
}

class Request extends GenericRequest {
	constructor() {
		super();
		this.route = "/funcao";
	}
}

class Control extends GenericControl {
	constructor() {
		super();

		this.primaryKey = "funcao_pk";
		this.fields = ["funcao_nome"];
		this.tableFields = ["funcao_nome"];
		this.verifyDependences = true;
		this.permissions = null;
	}

	async init() {
		super.init();

		let response = await this.myRequests.send("/get_all_permissions", {});
		this.permissions = response.data.permissions;

		this.myView.fillPermissions(this.permissions);

		$(document).on("click", ".check_all", e => {
			this.handleCheckAll(e.target);
		});
	}

	async handleFillFields() {
		super.handleFillFields("edit");

		const response = await this.myRequests.send("/get_all_permissions", {
			funcao: this.data.self[this.state.selectedId][this.primaryKey]
		});

		this.myView.checkPermissions(response.data.permissions);
	}

	save() {
		let permissions = [];
		const data = {};
		$.each($('input[name="permissoes"]:checked'), function() {
			let id = $(this).attr("id");
			id = id.split("-");
			permissions.push(id[1]);
		});

		data.permissions = permissions;
		super.save(data);
	}

	handleCheckAll(e) {
		let entity = this.getEntity($(e).attr("id"));
		let status;

		if ($(e).is(":checked")) {
			status = true;
		} else {
			status = false;
		}

		this.permissions.forEach(permission => {
			if (permission.entidade === entity) {
				$(`#id-${permission.permissao_pk}`).prop("checked", status);
			}
		});

		return;
	}

	getEntity(id) {
		let splitted = id.split("-");
		splitted[1] = splitted[1].split("_").join(" ");

		return splitted[1];
	}
}

const myControl = new Control();

myControl.init();
