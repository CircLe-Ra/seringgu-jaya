import axios from 'axios';
import 'jodit/esm/plugins/resizer/resizer';
import 'jodit/esm/plugins/video/video';
import { Modal, Dismiss } from 'flowbite';
import { Jodit } from 'jodit';
import { Datepicker } from 'flowbite';

window.Jodit = Jodit;
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
