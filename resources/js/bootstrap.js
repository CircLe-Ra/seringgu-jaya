import axios from 'axios';
import { Modal } from 'flowbite';

window.Modal = Modal;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
