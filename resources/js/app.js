import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

alert(userId);
var channel = Echo.private(`App.Models.User.${userId}`);

// Echo.channel(`my-channel`)
// .listen('.my-event', (e) => {
//     consolec.log(e);
// });

// var channel = Echo.private("App.Models.User.2");
channel.notification(function (data) {
    alert(data.body);
    console.log(data);
});

