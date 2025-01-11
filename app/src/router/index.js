import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/home',
    name: 'home',
    component: HomeView,
    
  },
  {
    path: '/',
    name: 'Login',
    component: () => import('../views/LoginRegisterView.vue')
  },

  {
    path: '/tasks',
    name: 'tasks',
    component: () => import('../views/AdminTasks.vue'),
    beforeEnter: (to, from, next) => {
      const userType = localStorage.getItem('typeAccount');
      if (userType === 'admin') {
        next(); 
      } else {
        alert('Você não tem permissão para acessar esta página.'); 
        next('/home'); 
      }
    },
  },

]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
