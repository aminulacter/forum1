require('./bootstrap');
//import authorizations from './authorizations';
import InstantSearch from 'vue-instantsearch';

window.Vue = require('vue');
Vue.use(InstantSearch);

let authorizations = require('./authorizations');

Vue.prototype.authorize = function (...params) {
    if (! window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
};
window.Vue.prototype.signedIn = window.App.signedIn
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('flash', require('./components/Flash.vue').default);
//Vue.component('replies', require('./components/Replies.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);

Vue.component('thread-view', require('./pages/Thread.vue').default);
Vue.component('search-res', require('./pages/SearchRes.vue').default);
Vue.component("Paginator", require("./components/Paginator.vue").default);
Vue.component("avatar-form", require("./components/AvatarForm.vue").default);
Vue.component("ExampleSearch", require("./components/ExampleSearch.vue").default);
Vue.component("wysiwyg", require("./components/Wysiwyg.vue").default)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
