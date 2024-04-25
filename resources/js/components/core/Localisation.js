export class Localisation {
    constructor(){
        this.handle()
    }

    handle() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(this.getLocation, this.redirectIfNoLocation)
        }
        //location.assign('/')
    }

    getLocation(position) {
        const geo = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
        }
        console.log(geo);
        // axios.post('', geo)
        // .then(response => console.log(`latitude: ${geo.latitude}, longitude: ${geo.longitude}`))
        // .catch(error => console.log(error))

    }

    redirectIfNoLocation(error){
        if ( error.code ) {
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('stockAccess', response.data.stockAccess);
            localStorage.setItem('managerAccess', response.data.managerAccess);
            localStorage.setItem('administrativeAccess', response.data.administrativeAccess)
        }
    }

    static storageHasLatLnt() {
        //verificar se o a localisação está setada
    }
}
