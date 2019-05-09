
class GenericControl {

    constructor() {
        this.state = {
            selectedId: null,
        }

        this.myView = new View();
        this.myRequests = new Request();
    }

    async init() {

        this.data = await this.myRequests.init();
        this.myView.init(this.data, this.tableFields, this.primaryKey);

        // Send request
        $(document).on('click', '.submit', () => { this.save() });
        $(document).on('click', '.action_deactivate', () => { this.deactivate(); });
        $(document).on('click', '.action_activate', () => { this.activate() });

        // Open modal
        $(document).on('click', '.btn_edit', () => { this.handleFillFields() });
        $(document).on('click', '.btn_deactivate', () => { this.handleDependences(); });
        $(document).on('click', '.btn_activate', () => { });

        $(document).on('change', '#filter-ativo', (e) => { this.handleFilter(e.target); });

        $('#filter-ativo').val(1);
        $('#filter-ativo').trigger('change');
    }

    handleResponse(response, data) {
        if (!response) {
            this.myView.showMessage('failed', 'Falha', 'Entre em contato com a central!');
            return;
        }

        if (response.code == 200) {

            if (this.state.selectedId) {
                this.updateObject(data);
            } else {
                this.addNewObject(data, response);
            }

            this.myView.closeModal();
            this.myView.showMessage('success', 'Sucesso', 'Operação realizada!');
            this.handleFilter($('#filter-ativo').val());
            $('#filter-ativo').trigger('change');
            // this.myView.render(this.data.self);
        } else {
            this.myView.showMessage('failed', 'Falha', response.data.mensagem);
        }
    }

    // @object moreFields
    async save(moreFields = null) {
        this.myView.initLoad();

        const sendData = this.myView.createJsonWithFields(this.fields, this.data);

        if(moreFields != null){
            Object.assign(sendData, moreFields);
        }

        if (is_superusuario) sendData['senha'] = this.myView.getPassword('save')['senha'];
       
        sendData[this.primaryKey] = this.state.selectedId ? this.data.self[this.state.selectedId][this.primaryKey] : '';

        const response = await this.myRequests.send('/save', sendData);

        this.myView.endLoad();

        delete sendData['senha'];

        this.handleResponse(response, sendData);

        return response;
    }

    async switchState(action) {
        this.myView.initLoad();

        const sendData = is_superusuario ? this.myView.getPassword(action) : {};
        sendData[this.primaryKey] = this.data.self[this.state.selectedId][this.primaryKey];

        const response = await this.myRequests.send(`/${action}`, sendData);

        this.myView.endLoad();

        sendData.ativo = this.handleActiveOrDeactive();

        this.handleResponse(response, sendData);
    }

    async activate() {
        await this.switchState('activate');
    }


    async deactivate() {
        await this.switchState('deactivate');
    }

    async handleDependences() {
        let response = {
            data: {
                dependences: [],
                dependence_type: '',
            }
        };

        if (this.verifyDependences) {
            const sendData = {};
            sendData[this.primaryKey] = this.data.self[this.state.selectedId][this.primaryKey];

            response = await this.myRequests.send('/get_dependents', sendData);
        }

        this.myView.handleDependences(response.data);
    }

    handleActiveOrDeactive() {
        return (this.data.self[this.state.selectedId].ativo == 1) ? 0 : 1;
    }

    addNewObject(data, response) {
        data.ativo = 1;
        data[this.primaryKey] = response.data.id;

        if (response.data.new !== undefined) {
            this.data.self.push(response.data.new);
        } else {
            this.data.self.push(data);
        }


    }

    updateObject(data) {
        Object.assign(this.data.self[this.state.selectedId], data);
    }

    handleFillFields() {
        this.fillFields(this.data.self[this.state.selectedId], this.fields);
    }

    fillFields(object, fields) {
        fields.forEach(field => {
            $(`#${field}`).val(object[field]);
        });
    }

    clearSelectedId() {
        this.state.selectedId = undefined;
    }

    handleFilter(target) {
        this.myView.filter(this.data.self, target);
    }

    setSelectedId(id) {
        this.state.selectedId = id;
    }
}