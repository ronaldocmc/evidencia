class GenericRequest {
	constructor() {}

	async init() {
		const response = await this.send("/get", {});

		return response.data;
	}

	async send(endPoint, data) {
		try {
			const response = await $.post(
				base_url + this.route + endPoint,
				data
			);
			return response;
		} catch (err) {
			return false;
		}
	}
}
