import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";
import App from "./App.vue";
import "./App.css";
import api from "./utils/api";
import { isEmpty } from "lodash/lang";

const app = createApp(App);
const pinia = createPinia();

pinia.use(({ store }) => {
  store.$onAction(({
    name, // name of the action
    store, // store instance, same as `someStore`
    args, // array of parameters passed to the action
    after, // hook after the action returns or resolves
    onError, // hook if the action throws or rejects
  }) => {
    if ([
      "setLocation", "setDestination", "populate", "setPersonalId", "setUrl",
      "resetLocation", "resetDestination", "resetPersonalId", "resetUrl", "removeSerial",
    ].includes(name)) {
      after((result) => {
        if (['stock', 'task', 'user'].includes(store.$id)) {
          const data = store.collectSessionData();
          if (!isEmpty(data)) {
            api.setSession(store.session.session.id, data);
          }
        }
      });
    }
  });
});

app.use(pinia);

app.use(router);

app.mount("#mobile-app");
