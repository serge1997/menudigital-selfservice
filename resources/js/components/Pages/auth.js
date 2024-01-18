import VueAxios from "vue-axios";
import axios from "axios";
let authuser;
window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
async function getAUth(){
   let user;
   let response = await axios.get('/api/user');
   user = await response.data

   return user;
}
let users = getAUth();
authuser = users.then(user => {return user});

export default authuser;
