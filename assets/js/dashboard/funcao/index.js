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
        let checkbox = '';
        let entities = [];
        let exists;
        let check_all;
        let tooltip;
        
        permissions.forEach(permission => {
            exists = false;

            checkbox = this.generateCheckBox('acao_nome', 'permissao_pk', permission, 'permissoes');

            entities.forEach(e => {
                if (e === permission.entidade) {
                    exists = true;
                }
            });

            if (!exists) {
                $('#permissions').append(`<div id='${permission.entidade.split(' ').join('_')}' class='conteiner'></div>`);
                entities.push(permission.entidade);
                title = this.generateTitle(permission.entidade, 4);
                tooltip = this.generateToolTip('teste');
                $(`#${permission.entidade.split(' ').join('_')}`).append(title);
                $(`#${permission.entidade.split(' ').join('_')}`).append(tooltip + '<br>');
            }

            $(`#${permission.entidade.split(' ').join('_')}`).append(checkbox + '&nbsp' + '&nbsp');
        });

        entities.forEach(e => {
            check_all = this.generateCheckAll(e.split(' ').join('_'));
            $(`#${e.split(' ').join('_')}`).append(check_all + '&nbsp' + '&nbsp');

            $(`#${e.split(' ').join('_')}`).append(`<br><hr>`);
        });
    }

    checkPermissions(permissions) {
        permissions.forEach(permission => {
            $(`#id-${permission.id}`).prop('checked', true);
        });
    }

    generateCheckAll(entity) {
        let render = `<input class='check_all' type='checkbox'
                    id='id-${entity}'
                    name='check_all'>
                    <label for='id-${entity}'> Selecionar Todas </label>`;

        return render;
    }

    generateToolTip(tooltip_text) {
        let tooltip = `<div class="tooltip"><i class="fas fa-question-circle"></i>
                            <span class="tooltiptext"> ${tooltip_text} </span>
                        </div> `;
        return tooltip;
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/funcao';
    }

}

class Control extends GenericControl {


    constructor() {
        super();

        this.primaryKey = "funcao_pk";
        this.fields = ['funcao_nome'];
        this.tableFields = ['funcao_nome'];
        this.verifyDependences = true;
        this.permissions = null;
    }

    async init() {
        super.init();

        let response = await this.myRequests.send('/get_all_permissions', {});
        this.permissions = response.data.permissions;

        this.myView.fillPermissions(this.permissions);

        $(document).on('click', '.check_all', (e) => { this.handleCheckAll(e.target); });
    }

    async handleFillFields() {
        super.handleFillFields('edit');

        const response = await this.myRequests.send('/get_all_permissions',
            {
                funcao: this.data.self[this.state.selectedId][this.primaryKey]
            });

        this.myView.checkPermissions(response.data.permissions);
    }

    save () {
        let permissions = [];
        const data = {};
        $.each($('input[name="permissoes"]:checked'), function () {
            let id = $(this).attr('id');
            id = id.split('-');
            permissions.push(id[1]);
        });

        data.permissions = permissions;
        super.save(data);
    }

    handleCheckAll (e) {
        let entity = this.getEntity($(e).attr('id'));
        let status;

        if ($(e).is(':checked')) {
            status = true;
        } else {
            status = false;
        }

        this.permissions.forEach(permission => {
            if (permission.entidade === entity) {
                $(`#id-${permission.permissao_pk}`).prop('checked', status);
            }
        });

        return;
    }

    getEntity (id) {
        let splitted = id.split('-');
        splitted[1] = splitted[1].split('_').join(' ');

        return splitted[1];
    }
}

const myControl = new Control();

myControl.init();