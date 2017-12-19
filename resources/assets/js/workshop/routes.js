function authMiddleware(to, from, next) {
    if (to.path === '/login') {
        return next();
    }

    if (!localStorage.getItem('foeiwafwfuwe')) {
        return next({ path: '/login' });
    }

    if (!localStorage.getItem('fewuia32rfwe')) {
        return next({ path: '/login' });
    }

    if (window.Laravel.station_id) return next();

    let allowedPaths = [
        '/station-selection', '/404', '/403', '/reports','/api/fetchemployees'
    ];

    if (to.path.indexOf('/administrator') !== -1) return next();

    let i;

    for (i = 0; i < allowedPaths.length; i++) {
        if (to.path.indexOf(allowedPaths[i]) !== -1) return next();
    }

    if (!window.Laravel.station_id) {
        return next({ path: '/station-selection' });
    }

    return next();
}

module.exports = [
    { path: '/wsh', redirect: '/login', beforeEnter: authMiddleware },
    { path: '/403', component: require('../transport/403.vue'), beforeEnter: authMiddleware },
    { path: '/station-selection', component: require('../transport/station.vue'), beforeEnter: authMiddleware },
    { path: '/dashboard', component: require('./dashboard/index.vue'), beforeEnter: authMiddleware },

    { path: '/wsh/breakdown', component: require('./breakdown/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/breakdown/create', component: require('./breakdown/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/breakdown/open', component: require('./breakdown/open.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/breakdown/closed', component: require('./breakdown/closed.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/breakdown/:id', component: require('./breakdown/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/breakdown/:id/edit', component: require('./breakdown/form.vue'), beforeEnter: authMiddleware },

    { path: '/wsh/job-card', component: require('./jobcard/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/create', component: require('./jobcard/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/open', component: require('./jobcard/open.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/closed', component: require('./jobcard/closed.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/:id', component: require('./jobcard/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/:id/progress', component: require('./jobcard/progress.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/job-card/:id/edit', component: require('./jobcard/form.vue'), beforeEnter: authMiddleware },

    { path: '/wsh/parts/', component: require('./parts/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/open', component: require('./parts/open.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/closed', component: require('./parts/closed.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/create', component: require('./parts/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/:id', component: require('./parts/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/:id/edit', component: require('./parts/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/parts/:id/:issue', component: require('./parts/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/issuance', component: require('./parts/issue.vue'), beforeEnter: authMiddleware },


    { path: '/wsh/qc', component: require('./qc/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/approved', component: require('./qc/approved.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/open', component: require('./qc/open.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/disapproved', component: require('./qc/disapproved.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/waivered', component: require('./qc/waivered.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/:id', component: require('./qc/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/:id/view', component: require('./qc/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/qc/:id/edit', component: require('./qc/form.vue'), beforeEnter: authMiddleware },

    { path: '/wsh/gatepass', component: require('./gatepass/awaiting.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/closed', component: require('./gatepass/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/create', component: require('./gatepass/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/approved', component: require('./gatepass/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/disapproved', component: require('./gatepass/disapproved.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/:id', component: require('./gatepass/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/gatepass/:id/edit', component: require('./gatepass/form.vue'), beforeEnter: authMiddleware },


    { path: '/wsh/external/', component: require('./external/index.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/open', component: require('./external/open.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/closed', component: require('./external/closed.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/create', component: require('./external/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/disapproved', component: require('./external/disapproved.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/:id', component: require('./external/view.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/:id/edit', component: require('./external/form.vue'), beforeEnter: authMiddleware },
    { path: '/wsh/external/:id/:issue', component: require('./external/view.vue'), beforeEnter: authMiddleware },




    { path: '*', component: require('../transport/404.vue'), beforeEnter: authMiddleware },
    {
        path: '/login',
        component: require('../components/auth/login.vue'),
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('foeiwafwfuwe')) {
                return next({ path: '/dashboard' });
            }

            if (localStorage.getItem('fewuia32rfwe')) {
                return next({ path: '/dashboard' });
            }

            return next();
        }
    },
];
