// Nạp Bootstrap JS (dropdown, modal, collapse...) và axios kèm header locale.
import 'bootstrap';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-localization'] = document.documentElement.lang || 'en';
