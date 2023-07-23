import { createRouter, createWebHashHistory, createMemoryHistory } from "vue-router";

import SessionView from "@/views/SessionView.vue";
import LocationView from "@/views/LocationView.vue";
import AnyCodeView from "@/views/AnyCodeView.vue";
import CompleteView from "@/views/CompleteView.vue";

const routes = [
  {
    name: "notFound",
    path: "/:path(.*)+",
    redirect: {
      name: "session",
    },
  },
  {
    name: "session",
    path: "/",
    component: SessionView,
    meta: {
      title: "Select or create session",
    },
  },
  {
    name: "location",
    path: "/location",
    component: LocationView,
    meta: {
      title: "Select location",
    },
  },
  {
    name: "any-code",
    path: "/any-code",
    component: AnyCodeView,
    meta: {
      title: "Scan any code",
    },
  },
  {
    name: "complete",
    path: "/complete",
    component: CompleteView,
    meta: {
      title: "Compltete",
    },
  },
];

const router = createRouter({
  history: createMemoryHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  if (title) {
    document.title = title;
  }
  next();
});

export default router;
