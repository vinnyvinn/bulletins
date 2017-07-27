function authMiddleware(to, from, next) {
    if (! localStorage.getItem('foeiwafwfuwe')) {
        return next({path: '/login'});
    }

    if (! localStorage.getItem('fewuia32rfwe')) {
        return next({path: '/login'});
    }

    if (! window.Laravel.station_id && to.path !== '/station-selection') {
        return next({path: '/station-selection'});
    }

    return next();
}

module.exports = [
    { path: '/', redirect: '/login', beforeEnter: authMiddleware },
    { path: '/ls', component: require('./dashboard/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/dashboard', component: require('./dashboard/index.vue'), beforeEnter: authMiddleware },

    { path: '/progress', component: require('../components/truck-progress/index.vue'), beforeEnter: authMiddleware },
    { path: '/progress/pre-loading', component: require('../components/truck-progress/stage-preloading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/pre-loading/:id', component: require('../components/truck-progress/preloading.vue'), beforeEnter: authMiddleware },

    { path: '/progress/loading', component: require('../components/truck-progress/stage-loading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/loading/:id', component: require('../components/truck-progress/loading.vue'), beforeEnter: authMiddleware },

    { path: '/progress/enroute', component: require('../components/truck-progress/stage-enroute.vue'), beforeEnter: authMiddleware },
    { path: '/progress/enroute/:id', component: require('../components/truck-progress/enroute.vue'), beforeEnter: authMiddleware },

    { path: '/progress/offloading', component: require('../components/truck-progress/stage-offloading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/in-yard', component: require('../components/truck-progress/stage-inyard.vue'), beforeEnter: authMiddleware },

    { path: '/users', component: require('../components/users/index.vue'), beforeEnter: authMiddleware },
    { path: '/users/create', component: require('../components/users/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/contracts', component: require('../transport/contracts/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contracts/r/:print', component: require('../transport/contracts/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contracts/create', component: require('../transport/contracts/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contracts/create/:templateId', component: require('../transport/contracts/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contracts/:id', component: require('../transport/contracts/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contracts/:id/edit', component: require('../transport/contracts/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/contract-templates', component: require('../transport/contracts-templates/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contract-templates/create', component: require('../transport/contracts-templates/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contract-templates/:id', component: require('../transport/contracts-templates/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/contract-templates/:id/edit', component: require('../transport/contracts-templates/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/trucks-allocation', component: require('./trucks-allocation/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/trucks-allocation/create/:id', component: require('./trucks-allocation/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/trucks-allocation/:id', component: require('./trucks-allocation/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/trucks-allocation/:id/edit', component: require('./trucks-allocation/form.vue'), beforeEnter: authMiddleware },


    { path: '/ls/fuel', component: require('./fuel/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/fuel/create/:id/:contract', component: require('./fuel/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/fuel/:id', component: require('./fuel/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/fuel/:id/edit', component: require('./fuel/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/gatepass', component: require('./gatepass/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/mileage', component: require('./mileage/contractsindex.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/:contract', component: require('./mileage/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/create', component: require('./mileage/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/:id', component: require('./mileage/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/:approve/approve', component: require('./mileage/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/:id/edit', component: require('./mileage/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/create/:truck/:contract', component: require('./mileage/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/mileage/employee/:employee/:contract', component: require('./mileage/employeeform.vue'), beforeEnter: authMiddleware },

    // { path: '/route-card', component: require('./transport/routecard/index.vue'), beforeEnter: authMiddleware },
    // { path: '/route-card/create', component: require('../transport/routecard/form.vue'), beforeEnter: authMiddleware },

    { path: '/ls/delivery', component: require('./delivery_note/index.vue'), beforeEnter: authMiddleware },
    { path: '/ls/delivery/create/:new', component: require('./delivery_note/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/delivery/:id', component: require('./delivery_note/view.vue'), beforeEnter: authMiddleware },
    { path: '/ls/delivery/:unload/unload', component: require('./delivery_note/form.vue'), beforeEnter: authMiddleware },
    { path: '/ls/delivery/:id/edit', component: require('./delivery_note/form.vue'), beforeEnter: authMiddleware },

    { path: '/allocation', component: require('../components/allocation/index.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/create', component: require('../components/allocation/form.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/:id/edit', component: require('../components/allocation/form.vue'), beforeEnter: authMiddleware },



    { path: '/reports', component: require('./reports/index.vue'), beforeEnter: authMiddleware },
    { path: '/reports/{details}', component: require('./reports/view.vue'), beforeEnter: authMiddleware },

    { path: '/station-selection', component: require('./station.vue'), beforeEnter: authMiddleware },

    { path: '/403', component: require('../transport/403.vue'), beforeEnter: authMiddleware },



    { path: '/login',
        component: require('../components/auth/login.vue'),
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('foeiwafwfuwe')) {
                return next({path: '/ls/dashboard'});
            }

            if (localStorage.getItem('fewuia32rfwe')) {
                return next({path: '/ls/dashboard'});
            }

            return next();
        }
    },

    { path: '*', component: require('./404.vue'), beforeEnter: authMiddleware },
];
