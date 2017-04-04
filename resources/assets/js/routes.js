

module.exports = [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', component: require('./components/dashboard/index.vue') },

    { path: '/routes', component: require('./components/routes/index.vue') },
    { path: '/routes/create', component: require('./components/routes/form.vue') },
    { path: '/routes/:id/edit', component: require('./components/routes/form.vue') },

    { path: '/drivers', component: require('./components/drivers/index.vue') },
    { path: '/drivers/create', component: require('./components/drivers/form.vue') },
    { path: '/drivers/:id/edit', component: require('./components/drivers/form.vue') },

    { path: '/trucks', component: require('./components/trucks/index.vue') },
    { path: '/trucks/create', component: require('./components/trucks/form.vue') },
    { path: '/trucks/:id', component: require('./components/trucks/form.vue') },
    { path: '/trucks/:id/edit', component: require('./components/trucks/form.vue') },

    { path: '/trailers', component: require('./components/trailers/index.vue') },
    { path: '/trailers/create', component: require('./components/trailers/form.vue') },
    { path: '/trailers/:id', component: require('./components/trailers/form.vue') },
    { path: '/trailers/:id/edit', component: require('./components/trailers/form.vue') },

    { path: '/progress', component: require('./components/truck-progress/index.vue') },
    { path: '/progress/pre-loading', component: require('./components/truck-progress/stage-preloading.vue') },
    { path: '/progress/pre-loading/:id', component: require('./components/truck-progress/preloading.vue') },

    { path: '/progress/loading', component: require('./components/truck-progress/stage-loading.vue') },
    { path: '/progress/loading/:id', component: require('./components/truck-progress/loading.vue') },

    { path: '/progress/enroute', component: require('./components/truck-progress/stage-enroute.vue') },
    { path: '/progress/enroute/:id', component: require('./components/truck-progress/enroute.vue') },

    { path: '/progress/offloading', component: require('./components/truck-progress/stage-offloading.vue') },
    { path: '/progress/in-yard', component: require('./components/truck-progress/stage-inyard.vue') },



    { path: '/contracts', component: require('./components/contracts/index.vue') },
    { path: '/contracts/create', component: require('./components/contracts/form.vue') },
    { path: '/contracts/:id/edit', component: require('./components/contracts/form.vue') },

    { path: '/allocation', component: require('./components/allocation/index.vue') },
    { path: '/allocation/create', component: require('./components/allocation/form.vue') },
    { path: '/allocation/:id/edit', component: require('./components/allocation/form.vue') },

];