import axios from 'axios';

export function useApi(config = {}) {
    return axios.create(Object.assign({
        baseURL: yabeWebfont.rest_api.url,
        headers: {
            'content-type': 'application/json',
            'accept': 'application/json',
            'X-WP-Nonce': yabeWebfont.rest_api.nonce,
        },
    }, config));
}