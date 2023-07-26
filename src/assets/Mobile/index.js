import { createApp } from "vue";
import { createPinia } from "pinia";
import has from "lodash/has";
import router from "./router";
import App from "./App.vue";
import api from "./utils/api";

const app = createApp(App);
const pinia = createPinia();

pinia.use(({ store }) => {
  store.$subscribe((mutation, state) => {
    if (has(state, "sessionStore.session") && state.sessionStore.session !== null) {
      // console.log(JSON.stringify(state, null, 2)); // todo: make set session request
    }
  });
});

app.use(pinia);

app.use(router);

app.mount("#mobile-app");
