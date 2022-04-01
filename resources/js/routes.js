import AllUser from './components/AllUser.vue';
import AddUser from './components/AddUser';
import EditUser from './components/EditUser.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllUser
    },
    {
        name: 'add',
        path: '/add',
        component: AddUser
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditUser
    }
];
