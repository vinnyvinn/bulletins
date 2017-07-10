function authMiddleware(to, from, next) {
    if (! localStorage.getItem('foeiwafwfuwe')) {
        return next({path: '/login'});
    }

    if (! localStorage.getItem('fewuia32rfwe')) {
        return next({path: '/login'});
    }

    return next();
}

module.exports = [
    { path: '/', redirect: '/login', beforeEnter: authMiddleware },
    { path: '/dashboard', component: require('./transport/dashboard/index.vue'), beforeEnter: authMiddleware },

    { path: '/routes', component: require('./components/routes/index.vue'), beforeEnter: authMiddleware },
    { path: '/routes/create', component: require('./components/routes/form.vue'), beforeEnter: authMiddleware },
    { path: '/routes/:id/edit', component: require('./components/routes/form.vue'), beforeEnter: authMiddleware },
    { path: '/routes/:id', component: require('./components/routes/view.vue'), beforeEnter: authMiddleware },

    { path: '/drivers', component: require('./components/drivers/index.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/create', component: require('./components/drivers/form.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/:id/edit', component: require('./components/drivers/form.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/:id', component: require('./components/drivers/view.vue'), beforeEnter: authMiddleware },

    { path: '/trucks', component: require('./transport/trucks/index.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/create', component: require('./transport/trucks/form.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/:id', component: require('./transport/trucks/view.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/:id/edit', component: require('./transport/trucks/form.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/:id/reports', component: require('./transport/trucks/report.vue'), beforeEnter: authMiddleware },

    { path: '/trailers', component: require('./components/trailers/index.vue'), beforeEnter: authMiddleware },
    { path: '/trailers/create', component: require('./components/trailers/form.vue'), beforeEnter: authMiddleware },
    { path: '/trailers/:id', component: require('./components/trailers/view.vue'), beforeEnter: authMiddleware },
    { path: '/trailers/:id/edit', component: require('./components/trailers/form.vue'), beforeEnter: authMiddleware },

    { path: '/progress', component: require('./components/truck-progress/index.vue'), beforeEnter: authMiddleware },
    { path: '/progress/pre-loading', component: require('./components/truck-progress/stage-preloading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/pre-loading/:id', component: require('./components/truck-progress/preloading.vue'), beforeEnter: authMiddleware },

    { path: '/progress/loading', component: require('./components/truck-progress/stage-loading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/loading/:id', component: require('./components/truck-progress/loading.vue'), beforeEnter: authMiddleware },

    { path: '/progress/enroute', component: require('./components/truck-progress/stage-enroute.vue'), beforeEnter: authMiddleware },
    { path: '/progress/enroute/:id', component: require('./components/truck-progress/enroute.vue'), beforeEnter: authMiddleware },

    { path: '/progress/offloading', component: require('./components/truck-progress/stage-offloading.vue'), beforeEnter: authMiddleware },
    { path: '/progress/in-yard', component: require('./components/truck-progress/stage-inyard.vue'), beforeEnter: authMiddleware },

    { path: '/users', component: require('./components/users/index.vue'), beforeEnter: authMiddleware },
    { path: '/users/create', component: require('./components/users/form.vue'), beforeEnter: authMiddleware },

    { path: '/contracts', component: require('./transport/contracts/index.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/r/:print', component: require('./transport/contracts/index.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/create', component: require('./transport/contracts/form.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/:id', component: require('./transport/contracts/view.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/:id/edit', component: require('./transport/contracts/form.vue'), beforeEnter: authMiddleware },

    { path: '/journey', component: require('./transport/journey/index.vue'), beforeEnter: authMiddleware },
    { path: '/journey/create', component: require('./transport/journey/form.vue'), beforeEnter: authMiddleware },
    { path: '/journey/:id', component: require('./transport/journey/view.vue'), beforeEnter: authMiddleware },
    { path: '/journey/:id/edit', component: require('./transport/journey/form.vue'), beforeEnter: authMiddleware },

    { path: '/inspection', component: require('./transport/inspection/index.vue'), beforeEnter: authMiddleware },
    { path: '/inspection/create', component: require('./transport/inspection/form.vue'), beforeEnter: authMiddleware },
    { path: '/inspection/:id', component: require('./transport/inspection/view.vue'), beforeEnter: authMiddleware },
    { path: '/inspection/:id/edit', component: require('./transport/inspection/form.vue'), beforeEnter: authMiddleware },

    { path: '/fuel', component: require('./transport/fuel/index.vue'), beforeEnter: authMiddleware },
    { path: '/fuel/create', component: require('./transport/fuel/form.vue'), beforeEnter: authMiddleware },
    { path: '/fuel/:id', component: require('./transport/fuel/view.vue'), beforeEnter: authMiddleware },
    { path: '/fuel/:id/edit', component: require('./transport/fuel/form.vue'), beforeEnter: authMiddleware },

    { path: '/mileage', component: require('./transport/mileage/index.vue'), beforeEnter: authMiddleware },
    { path: '/mileage/create', component: require('./transport/mileage/form.vue'), beforeEnter: authMiddleware },
    { path: '/mileage/:id', component: require('./transport/mileage/view.vue'), beforeEnter: authMiddleware },
    { path: '/mileage/:id/edit', component: require('./transport/mileage/form.vue'), beforeEnter: authMiddleware },

    // { path: '/route-card', component: require('./transport/routecard/index.vue'), beforeEnter: authMiddleware },
    { path: '/route-card/create', component: require('./transport/routecard/form.vue'), beforeEnter: authMiddleware },

    { path: '/delivery', component: require('./transport/delivery_note/index.vue'), beforeEnter: authMiddleware },
    { path: '/delivery/create', component: require('./transport/delivery_note/form.vue'), beforeEnter: authMiddleware },
    { path: '/delivery/:id', component: require('./transport/delivery_note/view.vue'), beforeEnter: authMiddleware },
    { path: '/delivery/:id/edit', component: require('./transport/delivery_note/form.vue'), beforeEnter: authMiddleware },

    { path: '/allocation', component: require('./components/allocation/index.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/create', component: require('./components/allocation/form.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/:id/edit', component: require('./components/allocation/form.vue'), beforeEnter: authMiddleware },

    { path: '/udfs', component: require('./components/udfs/index.vue'), beforeEnter: authMiddleware },
    { path: '/udfs/create', component: require('./components/udfs/form.vue'), beforeEnter: authMiddleware },
    { path: '/udfs/:id/edit', component: require('./components/udfs/form.vue'), beforeEnter: authMiddleware },

    { path: '/reports', component: require('./transport/reports/index.vue'), beforeEnter: authMiddleware },
    { path: '/reports/{details}', component: require('./transport/reports/view.vue'), beforeEnter: authMiddleware },


    { path: '/login',
        component: require('./components/auth/login.vue'),
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('foeiwafwfuwe')) {
                return next({path: '/dashboard'});
            }

            if (localStorage.getItem('fewuia32rfwe')) {
                return next({path: '/dashboard'});
            }

            return next();
        }
    },
];
