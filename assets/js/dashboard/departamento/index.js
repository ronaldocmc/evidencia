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

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/departamento';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "departamento_pk";
        this.fields = ['departamento_nome'];
        this.tableFields = ['departamento_nome'];
        this.verifyDependences = true;
    }
}

const myControl = new Control();

myControl.init();

