
class GenericView {
    constructor() {
        this.state = {
            loadingButton: false,
        }
    }

    handleLoad() {
        if (this.state.loadingButton) {
            btn_ativar($('#pula-para-confirmacao'));
            btn_ativar($('.load'));
        } else {
            btn_load($('#pula-para-confirmacao'));
            btn_load($('.load'));
        }
        this.state.loadingButton = !this.state.loadingButton;
    }

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

    getDeactivatePassword() {
        return { 'senha': $('#pass-modal-deactivate').val() };
    }

    getActivatePassword() {
        return { 'senha': $('#pass-modal-activate').val() };
    }
}