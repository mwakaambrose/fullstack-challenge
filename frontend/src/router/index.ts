import { createRouter, createWebHistory } from "vue-router";
import IndexView from "../views/IndexView.vue";
import DetailView from "../views/DetailView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: IndexView,
    },
    {
      path: "/user/:id",
      name: "detail",
      component: DetailView,
    },
  ],
});

export default router;
