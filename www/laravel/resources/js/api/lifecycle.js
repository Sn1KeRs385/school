import Actions from '../actions';
import axios from './axios';
import router from '../router'

let makeRequest = async ({ method, url, data = undefined }) => {
    return await axios({ method, url, data });    
};

export default async ({method, url, data}) => {

  let response = await makeRequest({ method, url, data });

  if (response.data.status == false) {

    for (let error of response.data.errors) {
      console.error('error', error);
      switch (error.message) {
        case "TOKEN_EXPIRED_EXCEPTION":
          await Actions.refresh();
          break;

        case "AUTHORIZATION_EXCEPTION":
          localStorage.removeItem("user");
          localStorage.removeItem("access_token");
          localStorage.removeItem("refresh_token");
          
          router.push('/auth/login');

        default:
          console.error('Неизвестная ошибка', error);
          break;
      }
    }

    let newResponse = await makeRequest({ method, url, data });
    
    return newResponse;
  }
    return response;
}
