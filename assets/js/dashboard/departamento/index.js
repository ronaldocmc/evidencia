/*
 * == Variáveis globais: == 
 * 
 * @Boolean: is_superusuario
 * 
 * @Array<Departamento>: departamentos
 * 
 * @String: base_url
 * 
 */



class View extends GenericView {

    constructor() {
        super();
        this.state = {
            loadingButton: false,
        }
    }

    handleDependences(dependences) {

        let title = '', mensagem = '';

        console.log(dependences);

        if (!dependences.data) {
            $('.action_deactivate').removeAttr('disabled');
            title = 'Tudo certo para desativação!'
            mensagem = "Não há nenhum tipo de serviço dependente deste departamento, portanto você pode desativa-lo.";
        } else {
            $('.action_deactivate').attr('disabled', 'disabled');
            title = 'Impossível desativar o departamento!'
            mensagem = "Há tipos de serviços relacionados à esse departamento, você deve desativa-los primeiro.";
        }

        $('#dependences').html('<br>' + '<b>' + title + '</b> <br>' + mensagem + '</br>');
        $('#loading-departamento-deactivate').hide();
    }

    filter(type) {
        table.clear().draw();

        departamentos.filter(dep => (dep.ativo == type || type == -1)).forEach((dep, i) => {
            table.row.add([
                dep.departamento_nome,
                (dep.ativo == 1) ?
                    `<div class="btn-group">` +
                    `<button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="${i}" data-target="#modal"  data-title="Editar departamento" data-contentid="save">` +
                    `<div class="d-none d-sm-block">` +
                    `<i class="fas fa-edit fa-fw"></i>` +
                    `</div>` +
                    `</button>` +
                    `<button type="button" class="btn btn-sm btn-danger btn-desativar"  data-title="Desativar departamento" data-contentid="deactivate" data-toggle="modal" value="${i}" data-target="#modal">` +
                    `<div class="d-none d-sm-block">` +
                    `<i class="fas fa-times fa-fw"></i>` +
                    `</div>` +
                    `</button>` +
                    `</div>`
                    :
                    `<div class="btn-group">` +
                    `<button type="button" class="btn btn-sm btn-success btn_reativar" data-title="Ativar departamento" data-contentid="activate" data-toggle="modal" value="${i}" data-target="#modal">` +
                    `<div class="d-none d-sm-block">` +
                    `<i class="fas fa-power-off fa-fw"></i>` +
                    `</div>` +
                    `</button>` +
                    `</div>`
            ]).draw(false);
        });
    }

}

class Action extends GenericAction {

    constructor() {
        super();
        this.route = '/departamento'
    }
}



class Control extends GenericControl {

    constructor() {
        super();
        this.myView = new View();
        this.myActions = new Action();
        this.primaryKey = "departamento_pk";
    }

    init() {
        $(document).on('click', '.submit', () => { this.save() });
        $(document).on('click', '.action_deactivate', () => { this.deactivate(); });
        $(document).on('click', '.action_activate', () => { this.activate() });

        $(document).on('click', '.btn_edit', (e) => { this.handleFillFields(e.target) });
        $(document).on('click', '.btn_deactivate', (e) => { this.handleDependences(e.target); });
        $(document).on('click', '.btn_activate', (e) => { this.selectedId(e.target) });

        $(document).on('change', '#filter-ativo', (e) => { this.handleFilter(e.target); });
    }

    // Método responsável por enviar a requisição, seja de salvar ou editar.
    async save() {
        this.myView.handleLoad();
        const data = this.myView.createJsonWithFields(['departamento_nome', 'senha']);
        
        data[this.primaryKey] = this.state.selectedId ? departamentos[this.state.selectedId].departamento_pk : '';
        

        const response = await this.myActions.send('/save', data);
        this.myView.handleLoad();

        this.handleResponse(response);
    }


    // Método responsável por preencher os campos na edição 
    handleFillFields(target) {
        this.selectedId(target);
        this.fillFields(departamentos[this.state.selectedId], ['departamento_nome']);
    }

    // Método responsável por realizar a verificação de dependências
    async handleDependences(target) {
        this.selectedId(target);

        const data = {};
        data[this.primaryKey] = departamentos[this.state.selectedId].departamento_pk;

        const dependences = await this.myActions.send('/get_dependents', data);

        this.myView.handleDependences(dependences);
    }

    handleFilter(target) {
        const filterType = $(target).val();

        this.myView.filter(filterType);
    }

}

const control = new Control();

control.init();

