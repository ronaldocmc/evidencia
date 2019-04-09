
class GenericView {
    constructor() {
        this.state = {
            data: [],
            tableFields: []
        }
    }

    init(data, tableFields, primaryKey) {
        this.state.tableFields = tableFields;
        this.state.self = data.self;
        this.primaryKey = primaryKey;

        this.conditionalRender();
        this.render(data.self);
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
        return JSON.parse(localStorage.getItem('permissions'));
    }

    renderButtonsBasedOnPermissions() {
        this.renderMenu();

        this.renderButton('new', 'criar', this.getEntity());

        this.renderButton('btn_exportar', 'exportar', 'export');

        this.renderButton('new_report', 'novo', 'relatorio');
        this.renderButton('receive_report', 'receber', 'relatorio');
        this.renderButton('report_detail', 'detalhes', 'relatorio');
        this.renderButton('imprimir_relatorio', 'imprimir', 'relatorio');
        this.renderButton('destruir_relatorio', 'imprimir', 'relatorio');
    }

    hasPermissions(action, controller) {
        let permissions = this.getPermissions();

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
                    'relatorio'
                ]
            },
            {
                action: 'editar',
                buttons: ['organizacao']
            },
            {
                action: 'novo',
                buttons: ['relatorio']
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

        console.log(className);

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
        if (localStorage.getItem('is_superusuario') == 1) {
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

        if(this.hasPermissions('alterar', entity)) {
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

        if (data.dependeces == null || data.dependences.length === 0) {
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


}