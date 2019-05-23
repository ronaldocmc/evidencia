
class GenericView {
    constructor() {
        this.state = {
            data: [],
            tableFields: [],
            is_superusuario: this.isSuperUsuario(),
            permissions: this.getPermissions()
        }
    }

    init(data, tableFields, primaryKey) {
        this.state.tableFields = tableFields;
        this.state.self = data.self;
        this.primaryKey = primaryKey;
        
        this.conditionalRender();
    
        this.render(data.self);
        $('.table-responsive').show();
        $('#loading').hide();
        // Para remover o style fantasma na tabela
        $('.table-striped').removeAttr('style');
    }

    render(data) {

        let fields, buttons;

        table.clear().draw();

        data.forEach((d, i) => {

            let id = this.findPositionInOriginalArray(d);

            fields = this.generateFields(this.state.tableFields, d);
            
            buttons = this.generateButtons(d.ativo, id);

            table.row.add([...fields, buttons]).draw(false);
        });
    }

    findPositionInOriginalArray(object) {
        let id;
        this.state.self.forEach((selfObject, i) => {
            if (selfObject[this.primaryKey] == object[this.primaryKey]) {
                id = i;
                return;
            }
        });

        return id;
    }

    getPermissions() {
        if (localStorage.getItem('is_superusuario') !== '1') {
            return JSON.parse(localStorage.getItem('permissions'));
        } else {
            return {};
        }
    }

    isSuperUsuario() {
        return localStorage.getItem('is_superusuario');
    }

    renderButtonsBasedOnPermissions() {
        this.renderMenu();

        this.renderButton('new', 'alterar e criar', this.getEntity());

        this.renderButton('btn_exportar', 'ver', 'ordem_servico');

        this.renderButton('new_report', 'criar relatório', 'relatorio');
        this.renderButton('receive_report', 'receber relatorio', 'relatorio');
        this.renderButton('report_detail', 'ver', 'relatorio');
        this.renderButton('imprimir_relatorio', 'ver', 'relatorio');
        this.renderButton('destruir_relatorio', 'ver', 'relatorio');
    }

    hasPermissions(action, controller) {
        if (this.state.is_superusuario) {
            return true;
        }

        let permissions = this.state.permissions;

        let response = false;

        permissions.forEach( (p) => {
            if( p.controller != null &&  
                p.controller.toLowerCase() == controller.toLowerCase() &&
                p.action.toLowerCase() == action ){

                response = true;
                return;
            }
        });

        return response;
    }

    renderMenu() {
        let menuButtons = [
            {
                action: 'ver',
                buttons: [
                    'departamento', 'setor', 'funcionario', 
                    'funcao', 'servico', 'prioridade', 
                    'situacao', 'ordem_servico', 'mapa',
                    'relatorio', 'tipo_servico'
                ]
            },
            {
                action: 'alterar e criar',
                buttons: ['organizacao']
            },
            {
                action: 'criar relatório',
                buttons: ['novo-relatorio']
            }     
        ];

        menuButtons.forEach( (e) => {
            e.buttons.forEach( (button) => {
                if(this.hasPermissions(e.action, button)) {
                    $(`.${button}-menu`).removeClass('d-none');
                }
            });
        });
    }


    renderButton(className, action, entity) {
        const classWithDot = `.${className}`;

        if(this.elementExistsOnDom(classWithDot)) {
            this.renderButtonBasedOnPermission(classWithDot, action, entity);
        }
    }


    renderButtonBasedOnPermission(className, permission, entity) {
        if(this.hasPermissions(permission, entity)) {
            $(className).removeClass('d-none');
        }
    }

    elementExistsOnDom(className){
        return (document.querySelector(className) != null && document.querySelector(className).textContent.length > 0);
    }

    getEntity() {
        let pathName = window.location.pathname;
        let pathArray = pathName.split('/');
        let length = pathArray.length;

        //retornamos o último elemento do pathname
        return pathArray[length -1]; 
    }

    // vai sair daqui e vai pro dashboard.js
    // renderQuickAccess() {
         
    // }

    conditionalRender() {
        if (this.state.is_superusuario == 1) {
            $('.superusuario').removeClass('d-none');
        } else {
            $('.not_superusuario').removeClass('d-none');
        }

        this.renderButtonsBasedOnPermissions();
    }

    initLoad() { btn_load($('.load')); }

    endLoad() { btn_ativar($('.load')); }

    closeModal() { $('#modal').modal('hide'); }

    showMessage(type, title, description) {
        alerts(type, title, description);
    }

    createJsonWithFields(fields) {
        const dataContainer = {};

        fields.forEach(field => {
            dataContainer[field] = $(`#${field}`).val()
        });
        return dataContainer;
    }

    filter(data, target) {
        const type = $(target).val();
        const renderData = data.filter(d => (d.ativo == type || type == -1));
        this.render(renderData);
    }

    generateFields(tableFields, object) {
        let fields = [];

        tableFields.forEach((f) => {
            fields.push(object[f]);
        });

        return fields;
    }

    createButtonsWhenIsActive(i) {
        let entity = this.getEntity();
        let html = '';

        if(this.hasPermissions('alterar e criar', entity)) {
            html += this.createButton('edit', 'save', 'primary', 'Editar', i, 'fa-edit');
        }

        if(this.hasPermissions('desativar', entity)) {
            html += this.createButton('deactivate', 'deactivate', 'danger', 'Desativar', i, 'fa-times');
        }

        return html;
    }

    createButtonsWhenIsInactive(i) {
        let entity = this.getEntity();
        let html = '';

        if(this.hasPermissions('ativar', entity)) {
            html += this.createButton('activate', 'activate', 'success', 'Ativar', i, 'fa-power-off')
        }

        return html;
    }

    generateButtons(condition, i) {
        return `<div class='btn-group'>` +
            (
                condition == 1 ?
                    this.createButtonsWhenIsActive(i)
                    :
                    this.createButtonsWhenIsInactive(i)
            ) +
            `</div>`;
    }

    createButton(action, content, type, title, i, icon) {
        return (
            `<button type="button" class="btn btn-sm btn-${type} btn_${action}"  data-title="${title}" data-contentid="${content}" data-toggle="modal" value="${i}" data-target="#modal">` +
            `<div class="d-none d-sm-block">` +
            `<i class="fas ${icon} fa-fw"></i>` +
            `</div>` +
            `</button>`
        );
    }

    getTargetId(target) {
        return $(target).closest('button');
    }

    getPassword(action) {
        return { 'senha': $(`#pass-modal-${action}`).val() };
    }

    handleDependences(data) {
        let rest;
        const { title, message, body } = this.generateMessage(data);

        $('#loading-deactivate').hide();

        body === undefined ? rest = '' : rest = body;

        $('#dependences').html(
            `<p style="margin-top: 10px; font-weight: bold">${title}</p>` +
            `<p style="margin-top: 10px; font-weight: 300">${message}</p>` +
            rest
        );
    }

    generateMessage(data) {
        let title, message, body;

        if (data.dependences == null || data.dependences.length === 0) {
            title = 'Tudo certo!';
            message = 'Este recurso do sistema não possui dependencias e pode ser desativado!';
        } else {
            title = 'Impossível desativar!'
            message = `Você não poderá desativar enquanto houver(em) ${data.dependence_type} dependente(s):`;
            body = `<ul style="font-size: 10pt; margin: 5px 10px;">`;
            data.dependences.forEach(elem => {
                body += `<li>${elem.name}</li>`
            });
            body += `</ul>`;
        }      

        return { title, message, body };
    }

    generateSelect(data, optionName, optionValue, id) {
        let render = '';
        data.forEach(option => {
            render += `
                <option 
                value='${option[optionValue]}'>
                ${option[optionName]}
                </option>`;
        });
        
        $(`#${id}`).html(render);
    }

    generateImage(id, path) {
        $(`#${id}`).attr('src', path);
    }

    generateCheckBox(inputText, inputValue, data, name) {
        let render = `<input type='checkbox'
        id='id-${data[inputValue]}'
        name='${name}'>
        <label for='id-${data[inputValue]}'> ${data[inputText]}</label>`;

        return render;
    }

    generateTitle(text, size) {
        let title = `<h${size}>${text}</h${size}>`;
        return title;
    }

    generateButtons(condition, i) {
        return `<div class='btn-group'>` +
            (
                this.createButton('edit', 'save', 'primary', 'Editar', i, 'fa-edit') +
                this.createButton('delete', 'delete', 'danger', 'Excluir Ordem', i, 'fa-times') +
                this.createButton('create_history', 'create_history', 'success', 'Criar histórico', i, 'fa-calendar-plus') +
                this.createButton('info', 'info', 'info', 'Ver informações', i, 'fa-eye')
            ) +
            `</div>`;
    }

    checkElementDom(id) {
        return document.getElementById(id);
    }

    renderTimelineHistoric(data = null) {
        let render = '';

        data.forEach((d, i) => {
       
            render += this.createTimeLine(
                d.funcionario_caminho_foto,
                d.funcionario_nome,
                d.historico_ordem_tempo,
                d.situacao_nome,
                d.historico_ordem_comentario)
        });

        return render;
    }

    renderCurrentSituation(data) {
        let render = '';
        
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
        var dataHora = (d.toLocaleString());
        let render = '';

        render += `<div class="message-item">
                        <div class="message-inner">
                            <div class="message-head clearfix">
                                <div class="user-detail">
                                    <h5 class="handle"> Adicionar Situação </h5>
                                    <div class="post-meta">
                                        <div class="asker-meta">
                                            <span class="qa-message-what"> Registrar hoje às ${ dataHora } </span>
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
                    </div>`
        return render;
    }

    renderCarouselCards(data){
        let render = '';
        let active = "active";

        data.map((img) => {
            render +=  this.createCarouselCards(img.imagem_os, img.situacao_nome, img.imagem_os_timestamp, active);
            active = "";
        });
        
        return render;
    }

    renderCarousel(data){
        let render = '';
        
        render = this.createCarousel(data);
        return render;
    }

    createTimeLine(photo_path = null, worker_name, date, situation, comment) {
        return `<div class="message-item">
                    <div class="message-inner">
                        <div class="message-head clearfix">
                            <div class="avatar pull-left">
                                <a href="#"><img class="message-foto-perfil" src="${(photo_path || base_url + '/assets/uploads/perfil_images/default.png')}"></a>
                            </div>
                            <div class="user-detail">
                                <h5 class="handle"> ${worker_name} </h5>
                                <div class="post-meta">
                                    <div class="asker-meta">
                                        <span class="qa-message-what"></span>
                                        <span class="qa-message-when">
                                            <span class="qa-message-when-data">${reformatDate(date)}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="qa-message-content">
                            <b>Situação: </b> ${situation}
                            <br> ${(comment || "Nenhum comentário adicionado.")}
                        </div>
                    </div> 
                </div>`
    }

    createCarouselCards(src, situacao, data, active) {

        return `<div class="carousel-item ${ active } col-md-4">
                    <div class="card historico">
                        <img class="card-img-top img-fluid" src="${src}">
                        <div class="card-body">
                            <h4 class="card-title"> ${situacao} </h4>
                            <p class="card-text">
                                <small class="text-muted"> ${reformatDate(data)} </small>
                            </p>
                        </div>
                    </div>
                </div>`
    }

    createCarousel(data) {
        let render="";

        if (data.length > 2) {
            render +=
                `<div id="myCarousel" class="carousel slide"data-ride="carousel">
                    <div class="carousel-inner row w-100 mx-auto"></div>
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>
                            <span class="sr-only"">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>`
                // alerts('success', 'Sucesso', 'Operação realizada com sucesso');
        } else {
            render +=
                `<div id="card_imagens">
                    <div class="carousel-inner row w-100 mx-auto"></div>
                </div>`
        }

        return render;
    }

    createImageCard(src, val){
        return (
            `<div class="col-md-3" id="card_${val.toString()}"> 
                <img src="${src}" class="img-thumbnail" alt="image_os" width="150px" height="150px" style="position:relative;">
                <div class="btn-group" style="position:relative; display:block; z-index:1; left:65%;  margin: -40px 10px 0 0;">
                    <button type="button" class="btn btn-sm btn-danger btn_remove_image" data-title="Remover Imagem" value="${val.toString()}"> 
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div> 
            <div>`
        )
    }

    removeImageCard(index){
        
        $(`#card_${index}`).remove();
        
    }

    renderDetailOrdem(data){
        
        let render = '';

        data.imagens.length !== 0 ? 
        render += this.createDetailOrdem(data.imagens[data.imagens.length-1].imagem_os, data.ordem_servico_cod, data.ordem_servico_desc):
        render += this.createDetailOrdem(null,data.ordem_servico_cod,data.ordem_servico_desc);

       $('#show_details_ordem').html(render);
    }
    
    createDetailOrdem(src = null, cod, desc){
        return `<div class="col-3"> 
                    <img src="${(src || default_image)}" height="150" width="150" style="border-radius: 10px;"> 
                </div>
                <div class="col-9" style="padding-top: 20px;">
                    <p><b>Código:</b> ${cod} </p>
                    <p><b>Descrição:</b> ${desc}</p>
                </div>`
    }
}