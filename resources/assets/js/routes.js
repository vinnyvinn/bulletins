

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

    { path: '/contracts', component: require('./components/contracts/index.vue') },
    { path: '/contracts/create', component: require('./components/contracts/form.vue') },
    { path: '/contracts/:id/edit', component: require('./components/contracts/form.vue') },

    { path: '/allocation', component: require('./components/allocation/index.vue') },
    { path: '/allocation/create', component: require('./components/allocation/form.vue') },
    { path: '/allocation/:id/edit', component: require('./components/allocation/form.vue') },

];