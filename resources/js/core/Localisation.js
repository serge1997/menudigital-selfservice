export class Localisation {
    constructor(){
        this.handle()
    }

    handle() {
        if (navigator.geolocation) {
            const options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0,
            }
            return navigator.geolocation.watchPosition(this.postLocation, this.redirectIfNoLocation)
        }
    }

    postLocation(position = null) {
        const geo = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
        }
        axios.post('/api/ip', geo)
        .then(response => console.log(`latitude: ${geo.latitude}, longitude: ${geo.longitude}`))
        .catch(error => console.log(error))

    }

    redirectIfNoLocation(error){
        const isRedirected = false;
        if ( error.code ) {
            localStorage.removeItem('token');
            localStorage.removeItem('stockAccess');
            localStorage.removeItem('managerAccess');
            localStorage.removeItem('administrativeAccess');
            localStorage.removeItem('table');
            localStorage.removeItem('tokenExpireTime');
            isRedirected = ! isRedirected;
            if (isRedirected) {
                isRedirected = ! isRedirected;
                location.reload();
            };
        }
    }

    static storageHasLatLnt() {
        //verificar se o a localisação está setada
    }
}
