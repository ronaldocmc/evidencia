class GenericAction {
    constructor() {

    }

    async send(endPoint, data) {
        try {
            const response = await $.post(base_url + this.route + endPoint, data);
            return response;
        } catch (err) {
            console.log(err);
            return false;
        }
    }

}