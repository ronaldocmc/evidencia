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

    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        super.init(data, tableFields, primaryKey);

        this.generateSelect(data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
        this.generateSelect(data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_padrao_fk');
    }
    
}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/Tipo_Servico';
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

    handleFillFields() {
        this.myView.generateSelect(this.data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');

        this.myView.generateSelect(this.data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_padrao_fk');
 
        super.handleFillFields();
    }    


}

const myControl = new Control();
const myView = new View();

myControl.init();

// myView.generateSelect(this.data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
// myView.generateSelect(this.data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_padrao_fk');