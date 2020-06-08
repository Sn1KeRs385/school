import axios from './axios';
import RequestLifecycle from './lifecycle';

let domain = process.env.MIX_API_HOST + 'api/v1/admin/';

export default {
  async all(model_name) {
    return await RequestLifecycle({ method: 'GET', url: `${domain}${model_name}?all` });
  },
  async paginate(model_name, page, per_page, q) {
    return await RequestLifecycle({ method: 'GET', url: `${domain}${model_name}?page=${page}&per_page=${per_page}&q=${q}` } );
  },
  async find(model_name, id) {
    return await RequestLifecycle({ method:'GET', url: `${domain}${model_name}/${id}` });
  },
  async create(model_name, data) {
    return await RequestLifecycle({ method: 'POST', url: `${domain}${model_name}`, data });
  },
  async update(model_name, id, data) {
    return await RequestLifecycle({ method: 'PUT', url: `${domain}${model_name}/${id}`, data });
  },
  async delete(model_name, id) {
    return await RequestLifecycle({ method: 'DELETE', url: `${domain}${model_name}/${id}` });
  }
}
