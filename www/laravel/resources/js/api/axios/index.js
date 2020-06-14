import axios from 'axios';

if (localStorage.getItem('access_token')) {
  axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('access_token');
}

export default axios;
