
export class RouterSingleton {
    static getRouter() {
        if (! window._routerSingleton) {
            window._routerSingleton = MainRouter.setup();
        }

        return window._routerSingleton;
    }
}

class MainRouter {
    static getState() {
        if (! window._mainState) {
            window._mainState = {
                debug: true,
                state: {
                    routes: [],
                    drivers: [
                        {
                            id: 0,
                            truck_id: null,
                            name: 'No Driver',
                            national_id: '',
                            dl_number: '',
                            mobile: ''
                        }
                    ],
                    trucks: [],
                    clients: [
                        { id: 0, name: 'Client 1' },
                        { id: 1, name: 'Client 2' },
                        { id: 2, name: 'Client 3' },
                        { id: 3, name: 'Client 4' },
                        { id: 4, name: 'Client 5' },
                        { id: 5, name: 'Client 6' },
                        { id: 6, name: 'Client 7' },
                    ],
                    contracts: [],
                    allocations: [],
                },

                setAttribute(attribute, value) {
                    this.debug && console.log('setAttribute triggered with', value);
                    this.state[attribute] = value;
                },

            };
        }

        return window._mainState;
    }

    static setup() {
        let routes = require('./routes');
        let router = new VueRouter({
            mode: 'history',
            linkActiveClass: 'active',
            routes: routes,
            scrollBehavior (to, from, savedPosition) {
                if (to.hash) {
                    return {
                        selector: to.hash
                    }
                }
            }
        });

        this.getState();
        window._router = router;

        return new Vue({
            router,
            data: {
                mainState: window._mainState.state
            },
            watch: {
                mainState(val) {
                    // localStorage.setItem('STORAGE', JSON.parse(this.mainState));
                    console.log(val);
                }
            }
        }).$mount('#app');
    }
}
