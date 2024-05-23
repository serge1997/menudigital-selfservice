export class ApiModule {
    #baseURL = `${location.origin}/api`;
    constructor(){}

    async get(url) {
        const request = await axios.get(this.#baseURL + url);
        return request;
    }
}
