
class GenericControl {

    constructor() {
        this.state = {
            selectedId: null,
        }
    }

    handleResponse(response) {
        if (!response) {
            this.myView.showMessage('failed', 'Falha', 'Entre em contato com a central!');
            return;
        }

        if (response.code == 200) {
            this.myView.showMessage('success', 'Sucesso', 'Operação realizada!');
            document.location.reload(false);
        } else {
            this.myView.showMessage('failed', 'Falha', response.message);
        }
    }

    async deactivate() {
        this.myView.handleLoad();

        const data = is_superusuario ? this.myView.getDeactivatePassword() : {};
        data[this.primaryKey] = departamentos[this.state.selectedId].departamento_pk;

        const response = await this.myActions.send('/deactivate', data);

        this.myView.handleLoad();

        this.handleResponse(response);
    }

    async activate() {
        this.myView.handleLoad();

        const data = is_superusuario ? this.myView.getActivatePassword() : {};
        data[this.primaryKey] = departamentos[this.state.selectedId].departamento_pk;

        console.log(data);

        const response = await this.myActions.send('/activate', data);

        this.myView.handleLoad();

        this.handleResponse(response);
    }

    fillFields(object, fields) {
        fields.forEach(field => {
            $(`#${field}`).val(object[field]);
        });
    }

    clearSelectedId() {
        this.state.selectedId = undefined;
    }

    selectedId(target) {
        const button = $(target).closest('button');
        this.state.selectedId = $(button).val();
        console.log(this.state.selectedId);
    }
}