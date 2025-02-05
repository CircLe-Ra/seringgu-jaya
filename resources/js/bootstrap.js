import axios from 'axios';
import { Modal, Dismiss } from 'flowbite';
import { Datepicker } from 'flowbite';

window.Modal = Modal;
window.Dismiss = Dismiss;
window.Datepicker = Datepicker;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
