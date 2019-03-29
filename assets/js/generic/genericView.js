
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

        console.log(data);

        let fields, buttons;

        table.clear().draw();

        data.forEach((d, i) => {
            let id = this.findPositionInOriginalArray(d);
            console.log(id);

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

    conditionalRender() {
        if (localStorage.getItem('is_superusuario') == 1) {
            $('.superusuario').removeClass('d-none');
        } else {
            $('.not_superusuario').removeClass('d-none');
        }
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

    generateButtons(condition, i) {
        return `<div class='btn-group'>` +
            (
                condition == 1 ?
                    this.createButton('edit', 'save', 'primary', 'Editar', i, 'fa-edit') +
                    this.createButton('deactivate', 'deactivate', 'danger', 'Desativar', i, 'fa-times')
                    :
                    this.createButton('activate', 'activate', 'success', 'Ativar', i, 'fa-power-off')
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

        if (data.dependences.length === 0) {
            title = 'Tudo certo!';
            message = 'Você pode desativar com tranquilidade!';
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


}