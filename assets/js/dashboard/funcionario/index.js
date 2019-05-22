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
	// FUNÇÃO GENÉRICA PARA PREENCHER UM SELECT
	// PARA PREENCHER UM MULTIPLE SELECT

	constructor() {
		super();
	}

	init(data, tableFields, primaryKey) {
		super.init(data, tableFields, primaryKey);

		this.generateSelect(data.funcoes, "funcao_nome", "funcao_pk", "funcao_fk");
		this.generateSelect(
			data.departamentos,
			"departamento_nome",
			"departamento_pk",
			"departamento_fk"
		);
		this.generateSelect(data.setores, "setor_nome", "setor_pk", "setor_fk");
	}

	hidePasswordInput() {
		$("#div-senha").hide();
	}

	showPasswordInput() {
		$("#div-senha").show();
	}

	generateButtons(condition, i) {
		let buttons = `<div class='btn-group'>`;

		if (condition == 1) {
			if (this.hasPermissions("alterar e criar", this.getEntity())) {
				buttons += this.createButton(
					"edit",
					"save",
					"primary",
					"Editar",
					i,
					"fa-edit"
				);
			}

			if (this.hasPermissions("desativar", this.getEntity())) {
				buttons += this.createButton(
					"deactivate",
					"deactivate",
					"danger",
					"Desativar",
					i,
					"fa-times"
				);
			}

			if (this.hasPermissions("alterar senha", this.getEntity())) {
				buttons += this.createButton(
					"change_password",
					"password",
					"success",
					"Alterar senha",
					i,
					"fa-lock"
				);
			}
		} else {
			buttons += this.createButton(
				"activate",
				"activate",
				"success",
				"Ativar",
				i,
				"fa-power-off"
			);
		}

		buttons += `</div>`;

		return buttons;
	}
}

class Request extends GenericRequest {
	constructor() {
		super();
		this.route = "/funcionario";
	}
}

class Control extends GenericControl {
	constructor() {
		super();

		this.primaryKey = "funcionario_pk";
		this.fields = [
			"funcionario_nome",
			"funcionario_cpf",
			"funcionario_login",
			"funcao_fk",
			"setor_fk",
			"funcionario_caminho_foto",
			"departamento_fk"
		];
		this.tableFields = ["funcionario_nome", "funcionario_login", "funcao_nome"];
		this.verifyDependences = false;
	}

	async init() {
		super.init();

		$(document).on("click", ".btn_edit", () => {
			this.handleFillFields();
			this.myView.hidePasswordInput();
			this.myView.generateImage(
				"show-img-funcionario",
				this.data.self[this.state.selectedId].funcionario_caminho_foto
			);
		});

		$(document).on("click", ".btn_new", () => {
			this.myView.showPasswordInput();
		});

		$(document).on("click", ".action_change_password", async () => {
			this.myView.initLoad();

			const sendData = {};

			if (!this.confirmPassword()) {
				this.myView.showMessage(
					"failed",
					"Falha",
					"As senhas inseridas são diferentes!"
				);
				this.myView.endLoad();
				return;
			}

			sendData[this.primaryKey] = this.data.self[this.state.selectedId][
				this.primaryKey
			];
			sendData.funcionario_senha = $("#p-senha").val();

			const response = await this.myRequests.send("/change_password", sendData);

			if (response.code == 200) {
				this.myView.closeModal();
				this.myView.showMessage("success", "Sucesso", response.message);
				this.myView.render(this.data.self);
			} else {
				this.myView.showMessage("failed", "Falha", response.message);
			}

			this.myView.endLoad();
		});
	}

	remove_image() {
		$("#img-input").attr("src", "");
		removeUploadFromForm();
	}

	blobToBase64(blob) {
		return new Promise(resolve => {
			const reader = new FileReader();
			reader.onload = function() {
				let base64 = reader.result;
				resolve(base64);
			};
			reader.readAsDataURL(blob);
		});
	}

	save() {
		const data = {};
		try {
			const index = this.state.selectedId;

			$("#img-input")
				.cropper("getCroppedCanvas")
				.toBlob(async blob => {
					data.img = await this.blobToBase64(blob);

					if (!this.state.selectedId) {
						data.funcionario_senha = $("#funcionario_senha").val();
					}

					const response = await super.save(data);

					document.location.reload(true);

					// if (this.state.selectedId) { this.data.self[index].funcionario_caminho_foto = response.data.path; }

					// this.remove_image();
				});
		} catch (err) {
			if (!this.state.selectedId) {
				data.funcionario_senha = $("#funcionario_senha").val();
			}
			super.save(data);
		}
	}

	confirmPassword() {
		let password1 = $("#p-senha").val();
		let password2 = $("#p-confirmar-senha").val();

		return password1 !== password2;
	}
}

const myControl = new Control();

myControl.init();
