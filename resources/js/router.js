import Vue from 'vue'
import VueRouter from 'vue-router'
import PostsComponent from "./components/Admin/PostsComponent";
import PostComponent from "./components/Admin/PostComponent";

import LogoutComponent from "./components/auth/LogoutComponent";
import LoginComponent from "./components/auth/LoginComponent";

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {path: '/posts', name: "Posts", component: PostsComponent},
        {path: '/post/:slug', name: "PostDetails", component: PostComponent, props: true},
        {path: '/login/', name: "Login", component: LoginComponent},
        {path: '/logout/', name: "Logout", component: LogoutComponent},
        { path: '', name: 'login', component: LoginComponent },
        { path: '/', name: 'login', component: LoginComponent },
        { path: '*', redirect: '/' }
    ],
    mode: 'history'
})
