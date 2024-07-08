import "./bootstrap";
import { createApp } from "vue";
import App from "./src/App.vue";
import helper from "./src/mixins/helper.js";
import router from './src/router';
import VuePlyr from 'vue-plyr'
import { createPinia } from 'pinia'
const pinia = createPinia()

const app = createApp(App);
app.mixin(helper).use(router).use(pinia).use(VuePlyr, {
    plyr: {}
}).mount("#app");