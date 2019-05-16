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
                $(`#${permission.entidade.split(' ').join('_')}`).append(title + '<br>');
            }

            $(`#${permission.entidade.split(' ').join('_')}`).append(checkbox + '&nbsp' + '&nbsp');
        });

        entities.forEach(e => {
            $(`#${e.split(' ').join('_')}`).append(`<br><hr>`);
        });
    }

    checkPermissions(permissions) {
        permissions.forEach(permission => {
            $(`#id-${permission.id}`).prop('checked', true);
        });
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
    }

    async init() {
        super.init();

        let response = await this.myRequests.send('/get_all_permissions', {});
        let permissions = response.data.permissions;

        this.myView.fillPermissions(permissions);
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
}

const myControl = new Control();

myControl.init();