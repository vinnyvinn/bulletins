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
    { path: '/', redirect: '/dashboard', beforeEnter: authMiddleware },
    { path: '/dashboard', component: require('./components/dashboard/index.vue'), beforeEnter: authMiddleware },

    { path: '/routes', component: require('./components/routes/index.vue'), beforeEnter: authMiddleware },
    { path: '/routes/create', component: require('./components/routes/form.vue'), beforeEnter: authMiddleware },
    { path: '/routes/:id/edit', component: require('./components/routes/form.vue'), beforeEnter: authMiddleware },
    { path: '/routes/:id', component: require('./components/routes/view.vue'), beforeEnter: authMiddleware },

    { path: '/drivers', component: require('./components/drivers/index.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/create', component: require('./components/drivers/form.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/:id/edit', component: require('./components/drivers/form.vue'), beforeEnter: authMiddleware },
    { path: '/drivers/:id', component: require('./components/drivers/view.vue'), beforeEnter: authMiddleware },

    { path: '/trucks', component: require('./components/trucks/index.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/create', component: require('./components/trucks/form.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/:id', component: require('./components/trucks/view.vue'), beforeEnter: authMiddleware },
    { path: '/trucks/:id/edit', component: require('./components/trucks/form.vue'), beforeEnter: authMiddleware },

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

    { path: '/contracts', component: require('./components/contracts/index.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/create', component: require('./components/contracts/form.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/:id', component: require('./components/contracts/view.vue'), beforeEnter: authMiddleware },
    { path: '/contracts/:id/edit', component: require('./components/contracts/form.vue'), beforeEnter: authMiddleware },

    { path: '/allocation', component: require('./components/allocation/index.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/create', component: require('./components/allocation/form.vue'), beforeEnter: authMiddleware },
    { path: '/allocation/:id/edit', component: require('./components/allocation/form.vue'), beforeEnter: authMiddleware },
    { path: '/udfs', component: require('./components/udfs/index.vue'), beforeEnter: authMiddleware },
    { path: '/udfs/create', component: require('./components/udfs/form.vue'), beforeEnter: authMiddleware },
    { path: '/udfs/:id/edit', component: require('./components/udfs/form.vue'), beforeEnter: authMiddleware },


    { path: '/job-card', component: require('./components/workshop/jobcard/index.vue'), beforeEnter: authMiddleware },
    { path: '/job-card/create', component: require('./components/workshop/jobcard/form.vue'), beforeEnter: authMiddleware },
    { path: '/job-card/:id', component: require('./components/workshop/jobcard/view.vue'), beforeEnter: authMiddleware },
    { path: '/job-card/:id/edit', component: require('./components/workshop/jobcard/form.vue'), beforeEnter: authMiddleware },

    { path: '/parts/create', component: require('./components/workshop/parts/form.vue'), beforeEnter: authMiddleware },
    { path: '/parts/:id', component: require('./components/workshop/parts/view.vue'), beforeEnter: authMiddleware },
    { path: '/parts/:id/edit', component: require('./components/workshop/parts/form.vue'), beforeEnter: authMiddleware },


    { path: '/login',
        component: require('./components/auth/login.vue'),
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('foeiwafwfuwe')) {
                return next({path: '/'});
            }

            if (localStorage.getItem('fewuia32rfwe')) {
                return next({path: '/'});
            }

            return next();
        }
    },
];
