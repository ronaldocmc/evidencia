/*
 * == Variáveis globais: ==
 *
 * @Boolean: is_superusuario
 * @String: base_url
 *
 */

var table = $("#ordens_servico").DataTable();
var is_superusuario = false;
var default_image =
	"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACoCAMAAABt9SM9AAAAMFBMVEXMzMz////Jycn19fXQ0ND8/Pzl5eXf39/U1NT4+Pjt7e3q6ury8vLOzs7n5+fb29vHz2+HAAACFUlEQVR4nO3c246qMABAUSzITZH//9tR8QKIYo+J5qRrvQ1gYnaYUoohywAAAAAAAAAAAAAAAAAAAIDfCJ/49Zf/stDmHyh+/fW/Kuw3n9j++vt/Vdh+FGuT1KklVgSxIgyx2jLeIdVY1T/MGrp0Y8V/shDrfWJFEOu05c1wYoWiL8uqeKdX8rFCO8ye9t36J5OP1URMNhOPFfL7zHz9DjnxWP34PuawNm6lHWu6WrNdiBXq0cbEY01vkevHY8vxHEOsV7FCNdkq1otY4TykbW+TisRj5eNW8zErXIb//Lo98VjVONZuGivU1x3tZUfasbLQjGLN5vD1fU851Eo8Vtbd15hnI1YxXn3uz0enHivLLlOtfH4rPV2pPxcSK9TlblfW8wlpM2k1DP5iDU/z58fkm5nTJVGsxUMWHlofr5ViLR3RPrY6fUKshQN2S62Ol0uPwh73H5Zbbba1WPPd1ZNWx1pizfY+b3WReqzxY7FGrJHHWF3Z3f/qxRqZxzqvLrS3KfzqqZVyrOvFL++HefzqoJVurNDdb2yaMiwsn4p1W8+ajVC7IpweUoh1NV4pfZyr74//ja9/R5lmrFAsjuVN//rUSjLW85HcmXVzjbW4tPCGBGMdVmfqYmV+Bx9FrAhiRRArglgRxIogVgSxIogVQawIYkUQK4JXFcTwEowYXq8CAAAAAAAAAAAAAAAAAAAA/6U/gAcVDMYj22gAAAAASUVORK5CYII=";

class View extends GenericView {
	// FUNÇÃO GENÉRICA PARA PREENCHER UM SELECT
	// PARA PREENCHER UM MULTIPLE SELECT

	constructor() {
		super();
	}

	init(data, tableFields, primaryKey) {
		super.init(data, tableFields, primaryKey);

		this.generateSelect(
			data.departamentos,
			"departamento_nome",
			"departamento_pk",
			"departamento_fk"
		);
		this.generateSelect(
			data.tipos_servicos,
			"tipo_servico_nome",
			"tipo_servico_pk",
			"tipo_servico_fk"
		);
		this.generateSelect(
			data.servicos,
			"servico_nome",
			"servico_pk",
			"servico_fk"
		);
		this.generateSelect(
			data.procedencias,
			"procedencia_nome",
			"procedencia_pk",
			"procedencia_fk"
		);
		this.generateSelect(
			data.prioridades,
			"prioridade_nome",
			"prioridade_pk",
			"prioridade_fk"
		);
		this.generateSelect(
			data.situacoes,
			"situacao_nome",
			"situacao_pk",
			"situacao_inicial_fk"
		);
		this.generateSelect(
			data.municipios,
			"municipio_nome",
			"municipio_pk",
			"localizacao_municipio"
		);
		this.generateSelect(data.setores, "setor_nome", "setor_pk", "setor_fk");
	}

	createJsonWithFields(fields) {
		const dataContainer = {};
		const dataName = {};

		fields.forEach(field => {
			dataContainer[field] = $(`#${field}`).val();

			if ($(`#${field}`).is("select")) {
				dataName[field] = $(`#${field} option:selected`).text();
			}
		});

		dataContainer.prioridade_nome = dataName["prioridade_fk"];
		dataContainer.servico_nome = dataName["servico_fk"];
		dataContainer.setor_nome = dataName["setor_fk"];

		return dataContainer;
	}

	filter(data, target) {
		const type = $(target).val();
		const renderData = data.filter(
			d => d.situacao_atual_fk == type || type == -1
		);
		this.render(renderData);
	}

	generateButtons(condition, i) {
		return (
			`<div class='btn-group'>` +
			(this.createButton(
				"edit",
				"save",
				"primary",
				"Editar",
				i,
				"fa-edit"
			) +
				this.createButton(
					"delete",
					"delete",
					"danger",
					"Excluir Ordem",
					i,
					"fa-times"
				) +
				this.createButton(
					"create_history",
					"create_history",
					"success",
					"Criar histórico",
					i,
					"fa-calendar-plus"
				) +
				this.createButton(
					"info",
					"info",
					"info",
					"Ver informações",
					i,
					"fa-eye"
				)) +
			`</div>`
		);
	}

	checkElementDom(id) {
		return document.getElementById(id);
	}

	renderTimelineHistoric(data = null) {
		let render = "";

		data.forEach((d, i) => {
			render += this.createTimeLine(
				d.funcionario_caminho_foto,
				d.funcionario_nome,
				d.historico_ordem_tempo,
				d.situacao_nome,
				d.historico_ordem_comentario
			);
		});

		return render;
	}

	renderCurrentSituation(data) {
		let render = "";

		render += this.createTimeLine(
			data.funcionario_caminho_foto,
			data.funcionario_nome,
			data.ordem_servico_atualizacao,
			data.situacao_atual_nome,
			data.ordem_servico_comentario
		);

		return render;
	}

	renderTimeLineInput() {
		var d = new Date();
		var dataHora = d.toLocaleString();
		let render = "";

		render += `<div class="message-item">
                        <div class="message-inner">
                            <div class="message-head clearfix">
                                <div class="user-detail">
                                    <h5 class="handle"> Adicionar Situação </h5>
                                    <div class="post-meta">
                                        <div class="asker-meta">
                                            <span class="qa-message-what"> Registrar hoje às ${dataHora} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="qa-message-content">
                                <div class="col-8 col-md-12">
                                    <div style="width: 250px; margin-bottom: 10px">
                                        <label for="situacao_pk">Nova Situação</label>
                                        <select class="form-control" id="situacao_atual_fk" name="situacao_fk" required="true">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="ordem_servico_desc">Novo comentário</label>
                                    <textarea class="form-control" id="ordem_servico_comentario" name="comentario"
                                    class="form-control" required="true" maxlength="200"></textarea>
                                    <small class="form-text text-muted">Por favor, informe a descrição da Ordem de Serviço</small>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12" id="image-upload-div" style="margin-left: 2px;">
                                    <div class="image-upload-wrap" style="height: 100px;">
                                        <input class="file-upload-input" type="file" onchange="readURL(this);" accept="image/*" id="input-upload" required="false"/>
                                        <div class="drag-text">
                                            <h3 style="padding: 20px;">Ou clique/arraste e solte uma imagem aqui</h3>
                                        </div>
                                    </div>
                                    <div class="file-upload-content">
                                        <img id="img-input" class="file-upload-image" src="#" alt="your image" required="false"/>
                                        <div class="col-12" id="images_buttons">
                                            <button type="button" class="btn btn-danger clean_input_images" style="margin:15px;">Remover</button>
                                            <button type="button" class="btn btn-success save_images" style="margin:15px;">Salvar</button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Por favor, se necessário, carregue a imagem</small>
                                    <div class="col-12" id="images_saved" style="margin-top: 20px; display:flex;"></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
		return render;
	}

	renderCarouselCards(data) {
		let render = "";
		let active = "active";

		data.map(img => {
			render += this.createCarouselCards(
				img.imagem_os,
				img.situacao_nome,
				img.imagem_os_timestamp,
				active
			);
			active = "";
		});

		return render;
	}

	renderCarousel(data) {
		let render = "";

		render = this.createCarousel(data);
		$("#card_slider_historic").html(render);
	}

	createTimeLine(photo_path = null, worker_name, date, situation, comment) {
		return `<div class="message-item">
                    <div class="message-inner">
                        <div class="message-head clearfix">
                            <div class="avatar pull-left">
                                <a href="#"><img class="message-foto-perfil" src="${photo_path ||
									base_url +
										"/assets/uploads/perfil_images/default.png"}"></a>
                            </div>
                            <div class="user-detail">
                                <h5 class="handle"> ${worker_name} </h5>
                                <div class="post-meta">
                                    <div class="asker-meta">
                                        <span class="qa-message-what"></span>
                                        <span class="qa-message-when">
                                            <span class="qa-message-when-data">${reformatDate(
												date
											)}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="qa-message-content">
                            <b>Situação: </b> ${situation}
                            <br> ${comment || "Nenhum comentário adicionado."}
                        </div>
                    </div> 
                </div>`;
	}

	createCarouselCards(src, situacao, data, active) {
		return `<div class="carousel-item ${active} col-md-4">
                    <div class="card historico">
                        <img class="card-img-top img-fluid" src="${src}">
                        <div class="card-body">
                            <h4 class="card-title"> ${situacao} </h4>
                            <p class="card-text">
                                <small class="text-muted"> ${reformatDate(
									data
								)} </small>
                            </p>
                        </div>
                    </div>
                </div>`;
	}

	createCarousel(data) {
		let render = "";

		if (data.length > 2) {
			render += `<div id="myCarousel" class="carousel slide"data-ride="carousel">
                    <div class="carousel-inner row w-100 mx-auto"></div>
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>
                            <span class="sr-only"">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>`;
			alerts("success", "Sucesso", "Operação realizada com sucesso");
		} else {
			render += `<div id="card_imagens">
                    <div class="carousel-inner row w-100 mx-auto"></div>
                </div>`;
		}

		return render;
	}

	createImageCard(src, val) {
		return `<div class="col-md-3" id="card_${val.toString()}"> 
                <img src="${src}" class="img-thumbnail" alt="image_os" width="150px" height="150px" style="position:relative;">
                <div class="btn-group" style="position:relative; display:block; z-index:1; left:65%;  margin: -40px 10px 0 0;">
                    <button type="button" class="btn btn-sm btn-danger btn_remove_image" data-title="Remover Imagem" value="${val.toString()}"> 
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div> 
            <div>`;
	}

	removeImageCard(index) {
		$(`#card_${index}`).remove();
	}

	renderDetailOrdem(data) {
		let render = "";

		data.imagens.length !== 0
			? (render += this.createDetailOrdem(
					data.imagens[data.imagens.length - 1].imagem_os,
					data.ordem_servico_cod,
					data.ordem_servico_desc
			  ))
			: (render += this.createDetailOrdem(
					null,
					data.ordem_servico_cod,
					data.ordem_servico_desc
			  ));

		$("#show_details_ordem").html(render);
	}

	createDetailOrdem(src = null, cod, desc) {
		return `<div class="col-3"> 
                    <img src="${src ||
						default_image}" height="150" width="150" style="border-radius: 10px;"> 
                </div>
                <div class="col-9" style="padding-top: 20px;">
                    <p><b>Código:</b> ${cod} </p>
                    <p><b>Descrição:</b> ${desc}</p>
                </div>`;
	}

	// `<div class="card col-6" align="center" style="width: 18rem; margin-left:25%">
	//                 <img class="card-img-top" src="${(src || default_image)}" alt="card-image">
	//                 <div class="card-body">
	//                     <h5 class="card-title"> ${cod}</h5>
	//                     <p class="card-text"> ${desc}</p>
	//                 </div>
	//             </div>`
}

class Request extends GenericRequest {
	constructor() {
		super();
		this.route = "/Ordem_Servico";
	}
}

class Control extends GenericControl {
	constructor() {
		super();
		this.primaryKey = "ordem_servico_pk";
		this.fields = [
			"ordem_servico_desc",
			"servico_fk",
			"procedencia_fk",
			"prioridade_fk",
			"situacao_inicial_fk",
			"setor_fk",
			"localizacao_municipio",
			"localizacao_rua",
			"localizacao_num",
			"localizacao_bairro",
			"localizacao_ponto_referencia",
			"localizacao_lat",
			"localizacao_long",
			"setor_nome",
			"servico_nome",
			"prioridade_nome",
			"procedencia_nome",
			"ordem_servico_cod"
		];
		this.tableFields = [
			"ordem_servico_pk",
			"ordem_servico_cod",
			"ordem_servico_criacao",
			"prioridade_nome",
			"localizacao_rua",
			"servico_nome",
			"situacao_atual_nome",
			"setor_nome"
		];
		this.verifyDependences = false;
		this.counter = 0;
	}

	init() {
		super.init();

		$(document).on("click", ".submit_os", () => {
			this.saveNewOrdem();
		});
		$(document).on("click", ".btn_save_activity", () => {
			this.saveNewSituation();
		});
		$(document).on("click", ".btn_create_history", () => {
			this.handleNewSituation();
		});
		$(document).on("click", ".btn_info", () => {
			this.handleTimelineHistoric();
		});
		$(document).on("click", "#btn-mapa-historico", () => {
			this.handleMapHistoric();
		});
		$(document).on("click", ".clean_input_images", () => {
			this.cleanInputImage();
		});
		$(document).on("click", ".save_images", () => {
			this.readImages();
		});
		$(document).on("click", ".btn_remove_image", () => {
			this.deleteImage($(".btn_remove_image").val());
		});
		$(document).on("click", ".action_export", () => {
			this.exportData();
		});
		$(document).on("click", ".btn_delete", () => {
			this.handleDelete();
		});
		$(document).on("click", "#confirm_delete", () => {
			this.delete();
		});
		$(document).on("click", ".new", () => {
			this.handleSelects($("#departamento_fk").val());
		});
		$(document).on("change", "#tipo_servico_fk", () => {
			this.optionsServices($("#tipo_servico_fk").val());
		});
		$(document).on("click", ".btn_edit", () => {
			this.handleFillFields();
			this.handleSelectEdit();
		});
	}

	deleteImage(index) {
		this.myView.removeImageCard(index);
	}

	cleanInputImage() {
		$("#img-input").attr("src", "");
		removeUpload();
	}

	saveImages() {
		let images = [];
		$(".img-thumbnail").each(function() {
			images.push(this.src);
		});
		return images;
	}

	readImages() {
		let render = "";
		try {
			let imageData = $("#img-input")
				.cropper("getCroppedCanvas")
				.toDataURL();
			render = this.myView.createImageCard(imageData, this.counter);
			$("#images_saved").append(render);
			this.cleanInputImage();
			this.counter++;
		} catch (err) {
			console.log(err);
		}
	}

	exportData() {
		let string = `/export/execute?data_inicial=${$(
			"#data_inicial"
		).val()}&data_final=${$("#data_final").val()}`;
		window.open(base_url + string, "target=_blank");
	}

	async saveNewOrdem() {
		if (
			$("#localizacao_lat").val() === "" &&
			$("#localizacao_long").val() === ""
		) {
			let local = "";

			local = $("#localizacao_municipio option:selected").text() + " ";
			local += $("#localizacao_rua").val() + " ";
			local += $("#localizacao_num").val() + " ";
			local += $("#localizacao_bairro").val();

			await map.state.geocoder.geocode({ address: local }, 
				async (results, status) => {
				if (status === "OK") {

					$("#localizacao_lat").val(results[0].geometry.location.lat());
					$("#localizacao_long").val(results[0].geometry.location.lng());

					let response = this.save({
						imagens: this.saveImages(),
						situacao_atual_fk: $("#situacao_inicial_fk").val()
					});
			
					if (response.code == 200) {
						this.counter = 0;
						$("#images_saved").html("");
					}
				}
			});
		}else{
			let response = this.save({
				imagens: this.saveImages(),
				situacao_atual_fk: $("#situacao_inicial_fk").val()
			});
	
			if (response.code == 200) {
				this.counter = 0;
				$("#images_saved").html("");
			}
		}
	}

	sendNewOrderToServer() {
		let response = this.save({
			imagens: this.saveImages(),
			situacao_atual_fk: $("#situacao_inicial_fk").val()
		});

		if (response.code == 200) {
			this.counter = 0;
			$("#images_saved").html("");
		}
	}

	async saveNewSituation(moreFields = null) {
		//Aprimorar esse load para funcionar
		// this.myView.initLoad();

		const data = this.myView.createJsonWithFields(this.fields);
		data[this.primaryKey] = this.state.selectedId
			? this.data.self[this.state.selectedId][this.primaryKey]
			: "";

		const sendData = {
			imagens: this.saveImages(),
			situacao_atual_fk: $("#situacao_atual_fk").val(),
			ordem_servico_comentario: $("#ordem_servico_comentario").val(),
			situacao_atual_nome: $("#situacao_atual_fk option:selected").text()
		};

		const response = await this.myRequests.send(
			"/insert_situacao/" + data[this.primaryKey],
			sendData
		);
		sendData.imagens = response.data.new.imagens;
		// this.myView.endLoad();

		this.handleResponse(response, sendData);
		$("#images_saved").html("");
		this.counter = 0;
	}

	async handleNewSituation() {
		let render = "";

		await this.myView.checkElementDom("#otimeline");
		render += this.myView.renderCurrentSituation(
			this.data.self[this.state.selectedId]
		);
		render += this.myView.renderTimeLineInput();

		$("#otimeline").html(render);
		this.myView.generateSelect(
			this.data.situacoes,
			"situacao_nome",
			"situacao_pk",
			"situacao_atual_fk"
		);
	}

	async handleTimelineHistoric() {
		this.myView.initLoad();

		var render = "";
		var cards = ";";
		const sendData = this.myView.createJsonWithFields(this.fields);

		sendData[this.primaryKey] = this.state.selectedId
			? this.data.self[this.state.selectedId][this.primaryKey]
			: "";
		const response = await this.myRequests.send(
			"/get_historico/" + sendData[this.primaryKey]
		);

		if (!response) {
			this.myView.showMessage(
				"failed",
				"Falha",
				"Entre em contato com a central!"
			);
			return;
		}

		this.myView.endLoad();

		//Handle with historic
		this.fillHistoricFields(
			this.data.self[this.state.selectedId],
			this.fields
		);
		if (response.data.historicos.length > 0) {
			render += this.myView.renderTimelineHistoric(
				response.data.historicos
			);
		}

		//Handle with images
		this.myView.renderCarousel(
			this.data.self[this.state.selectedId].imagens
		);
		if (this.data.self[this.state.selectedId].imagens.length > 0) {
			cards = this.myView.renderCarouselCards(
				this.data.self[this.state.selectedId].imagens
			);
			$(".carousel-inner").html(cards);
		}

		render += this.myView.renderCurrentSituation(
			this.data.self[this.state.selectedId]
		);
		$("#timeline_historic").html(render);
		$("#card_slider_historico").show();
	}

	handleMapHistoric() {
		if ($("#btn-mapa-historico").hasClass("btn-primary")) {
			this.showDivMapHistoric();
		} else {
			this.hideDivMapHistoric();
		}
	}

	showDivMapHistoric() {
		$("#mapa_historico").removeAttr("hidden");
		$("#btn-mapa-historico").removeClass("btn-primary");
		$("#btn-mapa-historico").addClass("btn-danger");
		$("#btn-mapa-historico")
			.children()
			.removeClass("fa fa-map-marker");
		$("#btn-mapa-historico")
			.children()
			.addClass("fa fa-times");
	}

	hideDivMapHistoric() {
		$("#mapa_historico").attr("hidden", true);
		$("#btn-mapa-historico").removeClass("btn-danger");
		$("#btn-mapa-historico").addClass("btn-primary");
		$("#btn-mapa-historico")
			.children()
			.removeClass("fa fa-times");
		$("#btn-mapa-historico")
			.children()
			.addClass("fa fa-map-marker");
	}

	fillHistoricFields(object, fields) {
		let local = " ";

		fields.forEach(field => {
			if (!field.indexOf("localizacao")) {
				object[field] !== null &&
				object[field] != undefined &&
				field != "localizacao_lat" &&
				field != "localizacao_long" &&
				field != "localizacao_municipio"
					? (local += object[field] + " ")
					: (local += "");
			} else {
				$(`#${field}_historic`).text(object[field]);
			}
		});

		$("#address_historic").text(local);
	}

	async delete() {
		this.myView.initLoad();

		const sendData = is_superusuario ? this.myView.getPassword(action) : {};
		sendData[this.primaryKey] = this.data.self[this.state.selectedId][
			this.primaryKey
		];

		const response = await this.myRequests.send(
			`/delete/${sendData[this.primaryKey]}`
		);
		if (response.code !== 200) {
			this.myView.showMessage("failed", "Falha", response.data.mensagem);
			return;
		}

		this.myView.endLoad();
		this.removeObject();

		this.myView.closeModal();
		this.myView.showMessage("success", "Sucesso", "Operação realizada!");

		this.handleFilter($("#filter-ativo").val());
		$("#filter-ativo").trigger("change");
		this.myView.render(this.data.self);
	}

	handleDelete() {
		this.myView.initLoad();
		this.myView.renderDetailOrdem(this.data.self[this.state.selectedId]);
	}

	removeObject() {
		this.data.self.splice(this.state.selectedId, 1);
	}

	optionsByDepartament(val) {
		let tipos_servicos = [];
		this.data.tipos_servicos.forEach(tp => {
			if (tp.departamento_fk == val) {
				tipos_servicos.push(tp);
			}
		});
		return tipos_servicos;
	}

	optionsServices(val) {
		let selecteds = [];

		this.data.servicos.forEach(s => {
			if (s.tipo_servico_fk == val) {
				selecteds.push(s);
			}
		});

		this.myView.generateSelect(
			selecteds,
			"servico_nome",
			"servico_pk",
			"servico_fk"
		);
	}

	async handleSelects(val) {
		await this.myView.checkElementDom("#servico_fk");
		let tipos_servicos = [];

		tipos_servicos = this.optionsByDepartament(val);
		this.myView.generateSelect(
			tipos_servicos,
			"tipo_servico_nome",
			"tipo_servico_pk",
			"tipo_servico_fk"
		);
		this.optionsServices(tipos_servicos[0].tipo_servico_pk);
	}

	async handleSelectEdit() {
		await this.myView.checkElementDom("#servico_fk");
		let val = $("#servico_fk").val();

		this.data.servicos.forEach(s => {
			if (s.servico_pk == val) {
				this.handleSelects(s.departamento_fk);
				this.optionsServices(s.tipo_servico_fk);
				$("#departamento_fk").val(s.departamento_fk);
			}
		});
	}
}

const myControl = new Control();
myControl.init();

var map;

initMap = () => {
	map = new GenericMap({
		mapId: "map",
		insideHideDiv: true,
		config: {
			center: { lat: -22.121265, lng: -51.3834 },
			zoom: 13
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

		data: [],

		useGeocoder: true,
		useCreateMarker: true
	});

	// Comportamento de um marker quando clicado
	map.handleMarkerClick = function(event) {
		alert("Clicou!");
		console.log(event);
	};

	// Comportamento de um clique no mapa
	map.handleClick = async function(event) {
		const { useGeocoder, useCreateMarker } = this.state.steps;

		const location = { lat: event.latLng.lat(), lng: event.latLng.lng() };

		// this.state.map.setCenter(event.latLng);

		this.state.lastPositionClicked = location;

		if (useCreateMarker) {
			this.createMarker(location);
		}

		if (useGeocoder) {
			const response = await this.translateLocation(location);
			this.fillInputs(response.address_components, location);
		}
	};

	map.handleDivOpen = function() {
		$("#modal").on("shown.bs.modal", event => {
			// myControl.handleSelects($("#departamento_fk".val()));

			if (myControl.getSelectedId()) {
				const {
					localizacao_lat,
					localizacao_long
				} = myControl.data.self[myControl.getSelectedId()];
				const location = {
					lat: parseFloat(localizacao_lat),
					lng: parseFloat(localizacao_long)
				};
				// map.setMap(new google.maps.Map(document.getElementById(this.state.mapId), this.state.mapConfig));
				map.initMap();
				map.createMarker(location);
				map.getMap().setCenter(location);
			} else {
				map.initMap();
			}
		});
	};

	map.handleCity = function(id, name) {
		// let exists = false;

		myControl.data.municipios.forEach(municipio => {
			if (name == municipio.municipio_nome) {
				$(`#${id}`).val(municipio.municipio_pk);
				$(`#${id} option:selected`).val(municipio.municipio_pk);
				exists = true;
			}
		});

		// if(!exists){
		//     alert("Infelizmente a cidade em questão não está sob responsabilidade da empresa");
		// }
	};

	map.initMap();
};
