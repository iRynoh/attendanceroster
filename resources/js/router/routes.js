import {default as PageNotFound} from "@/views/pages/shared/404/Main";
import {default as PageStudents} from "@/views/pages/Main.vue";

const routes = [
    {
        name: "home",
        path: "/",
        component: PageStudents
    },
    {
        path: "/:catchAll(.*)",
        name: "notFound",
        meta: {requiresAuth: false},
        component: PageNotFound,
    },
]

export default routes;
