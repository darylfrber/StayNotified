import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Automatically set CSRF token if available
axios.defaults.headers.common['X-XSRF-TOKEN'] = document.cookie
    .split(';')
    .find(cookie => cookie.trim().startsWith('XSRF-TOKEN='))
    ?.split('=')[1];
