import Vue from 'vue';
import VueRouter from 'vue-router';

import ExampleComponent from './components/ExampleComponent.vue';
import Hello from './components/Hello.vue';
import XHttpRequestSample from './components/XHttpRequestSample.vue';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/example',
            name: 'example',
            component: ExampleComponent
        },
        {
            path: '/hello',
            name: 'hello',
            component: Hello
        },
        {
            path: '/xhttp_sample',
            name: 'xhttp_sample',
            component: XHttpRequestSample
        }
    ],
});
