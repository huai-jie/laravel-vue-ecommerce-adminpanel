import Vue from 'vue'
import Router from 'vue-router'

//basic tutorial pages
import firstpage from './components/pages/myFirstPage'
import newRoutePage from './components/pages/newRoutePage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

//project pages
import products from './admin/pages/products'
import orders from './admin/pages/orders'
import categories from './admin/pages/categories'


Vue.use(Router)

const routes = [
    //project routes
    {
        path:'/',
        component: orders,
    },
    
    {
        path:'/products',
        component: products,
    },
    {
        path:'/categories',
        component: categories,
    },
    


    //basic tutorial routes
    {
        path:'/my-new-vue-route',
        component: firstpage,
    },

    {
        path:'/new-route',
        component: newRoutePage,
    },

    // vue hooks
    {
        path:'/hooks',
        component: hooks,
    },
    // more basics
    {
        path:'/methods',
        component: methods,
    },
    
]

export default new Router({
    mode:'history',
    routes, 
})