import { createRouter, createWebHashHistory, createMemoryHistory } from "vue-router";

import SessionView from "@/views/SessionView.vue";
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
      title: "Select session or create new one",
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
