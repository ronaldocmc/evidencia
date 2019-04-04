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
        console.log(data);
        super.init(data, tableFields, primaryKey);

        this.generateSelect(data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_padrao_fk');
        this.generateSelect(data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/tipo_servico';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "tipo_servico_pk";
        this.fields = ['tipo_servico_nome', 'tipo_servico_abreviacao', 'tipo_servico_desc', 'prioridade_padrao_fk', 'departamento_fk'];
        this.tableFields = ['tipo_servico_nome', 'tipo_servico_abreviacao', 'tipo_servico_desc', 'prioridade_nome', 'departamento_nome'];
        this.verifyDependences = true;
    }

    
}

const myControl = new Control();

myControl.init();

