require('./bootstrap');
window.Vue = require('vue').default;

// Register Vue Components
Vue.component('task-draggable', require('./components/TaskDraggable.vue').default);

// Initialize Vue
const app = new Vue({
    el: '#app'
});
