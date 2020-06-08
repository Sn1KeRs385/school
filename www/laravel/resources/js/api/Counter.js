import axios from './axios';
import RequestLifecycle from './lifecycle';

let domain = process.env.MIX_API_HOST + 'admin/';

if (localStorage.getItem('access_token')) {
  axios.defaults.headers.post['Authorization'] = localStorage.getItem('access_token');
}

export default {
  async counter() {
    let { data } = await RequestLifecycle({ method: 'GET', url: `${domain}counter`});
    return data;
  },
}
