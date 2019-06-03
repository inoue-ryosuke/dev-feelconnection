import Vue from 'vue';
import VueRouter from 'vue-router';

import ExampleComponent from './components/ExampleComponent.vue';
import Hello from './components/Hello.vue';

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
        }
    ],
});
