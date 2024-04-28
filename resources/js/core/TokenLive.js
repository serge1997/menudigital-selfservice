export class TokenLive {
    #expireTime;
    constructor(expireTime = null){
        this.#expireTime = expireTime;
        this.getExpireTime();
        this.checkExpireTime();
    }

    getExpireTime(){
        return this.#expireTime = localStorage.getItem('tokenExpireTime');
    }

    addZero(param){
        if ( Number(param) < 10 ) {
            return "0" + param;
        }
        return param;
    }

    formatDate(){
        const date = new Date().toLocaleDateString('pt-BR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        const format = date.substring(6, 10)+
        '-'+this.addZero(date.substring(4, 5))+
        '-'+this.addZero(date.substring(0, 2))+
        ' '+date.substring(12, 14)+
        ':'+date.substring(15, 17);
        return format;
    }

    checkExpireTime() {
       //console.log(`now: ${this.formatDate()} expire: ${this.#expireTime} `);
       setTimeout(() => {
            if (this.formatDate() > this.#expireTime) {
                localStorage.removeItem('token');
                localStorage.removeItem('stockAccess');
                localStorage.removeItem('managerAccess');
                localStorage.removeItem('administrativeAccess');
                localStorage.removeItem('table');
                localStorage.removeItem('tokenExpireTime');
            }
       }, 10000)
    }
}
