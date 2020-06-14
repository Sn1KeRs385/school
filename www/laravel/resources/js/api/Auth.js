import axios from './axios';
import RequestLifecycle from './lifecycle';

let domain = process.env.MIX_API_HOST + 'admin/';

if (localStorage.getItem('access_token')) {
  axios.defaults.headers.post['Authorization'] = "Bearer " + localStorage.getItem('access_token');
}

export default {
  // async sendSms({ phone }) {
  //   let { data } = await RequestLifecycle({ method: 'POST', url: `${process.env.MIX_API_HOST}api/v1/sendSms`, data: { phone } });
  //   return data;
  // },
  // async signinBySmsCode({ phone, sms }) {
  //   let { data } = await RequestLifecycle({ method: 'POST', url: `${process.env.MIX_API_HOST}api/v1/signinBySmsCode`, data: { phone, sms } });
  //   return data;
  // },
  async signinByUsername({ username, password }) {
    let { data } = await RequestLifecycle({ method: 'POST', url: `${process.env.MIX_API_HOST}api/v1/signinByUsername`, data: { username, password } });
    return data;
  },
  async signout() {
    let { data } = await RequestLifecycle({ method: 'POST', url: `${process.env.MIX_API_HOST}api/v1/signout` });
    return data;
  },
  async refresh({ refresh_token }) {
    let { data } = await axios.post(`${process.env.MIX_API_HOST}api/v1/refresh`, { refresh_token });
    return data;
  }
}
