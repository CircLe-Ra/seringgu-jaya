import Echo from 'laravel-echo';

import Notify from "simple-notify";
import Pusher from 'pusher-js';
window.Pusher = Pusher;
window.Notify = Notify;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

const playNotificationSound = () => {
    const audio = new Audio('/audio/notif.wav');
    audio.play().catch(error => {
        console.log("Playback failed. Waiting for user interaction.");
        document.addEventListener("click", () => audio.play(), { once: true });
    });
};


window.Echo.private('notification-for-staff')
    .listen('LetterApply', (e) => {
        console.log(e);
        new window.Notify ({
            status: 'info',
            title: 'Notifikasi Baru',
            text:`<div style="margin-left: 12px; font-size: 0.875rem; font-weight: normal;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">${e.letter}</div>
                        <div style="font-size: 0.875rem; font-weight: normal;">${e.position} | ${e.name}</div>
                        <span style="font-size: 0.75rem; font-weight: 500; color: #2563eb;">a few seconds ago</span>
                    </div>`,
            effect: 'fade',
            speed: 300,
            customClass: 'background-color:black', customIcon: `<img src="${e.profile_photo ? '/storage/' + e.profile_photo : 'https://ui-avatars.com/api/?name=' + e.name}" alt="Trulli" width="50" style="margin-top: 10px; margin-left: 10px; border-radius: 50%;" />`,
            showIcon: true,
            showCloseButton: true,
            autoclose: true,
            autotimeout: 5000,
            notificationsGap: null,
            notificationsPadding: null,
            type: 'outline',
            position: 'right top',
            customWrapper: '',
        });
        playNotificationSound();
    });

