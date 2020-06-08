import Auth from '../api/Auth';

import axios from '../api/axios';
import router from '../router'


export default {
  async signinByUsername({ username, password }) {

    let response = await Auth.signinByUsername({ username, password });
    
    if (response.status == true && response.data.access_token) {
      
      localStorage.setItem("user", response.data.user);
      localStorage.setItem("access_token", response.data.access_token);
      localStorage.setItem("refresh_token", response.data.refresh_token);

      axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('access_token');

      router.push("/");
      return true;
    }
    return false; 
  },

  async signout() {
    await Auth.signout();
    
    localStorage.removeItem("user");
    localStorage.removeItem("access_token");
    localStorage.removeItem("refresh_token");
    
    router.push('/auth/login');
  },

  async refresh() {
    let data = await Auth.refresh({refresh_token: localStorage.getItem('refresh_token')});

    if(data.status == true) {
      localStorage.setItem("access_token", data.data.access_token);
      localStorage.setItem("refresh_token", data.data.refresh_token);
      axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('access_token');
    } 
  }
};
