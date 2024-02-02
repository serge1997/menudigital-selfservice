import VueAxios from "vue-axios";
import axios from "axios";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`

export function getAuth() {
    return new Promise(async (resolve) => {
        let user ;
        const resp = await axios.get('/api/user')
        user = await resp.data
        resolve(user)

    })
}
