

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});


// alert('userIdsssssssss' + userId);

window.Echo.private(`App.Models.User.${userId}`)
    .notification(function (data) {
        console.log(data);
        alert('data.name_student');
    });


console.log('pusssssssher');;

window.Echo.private('webhook-channel.' + userId)
    .listen('.webhook-event', (e) => {
        alert(e.message.text);
        alert('data.name_student');

        console.log(e.message);
        // toastr.success(e.message.text);
    });
