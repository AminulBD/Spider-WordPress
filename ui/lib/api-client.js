import axios from 'axios';

const spider = window.spider || {
  config: {
    api: 'spider/v1'
  }
};

const client = axios.create({
  baseURL: `${window.location.origin}/wp-json/${spider.config.api_endpoint}`,
  timeout: 60000,
  headers: {
    'X-WP-Nonce': spider._nonce || null
  }
});

export default client;
