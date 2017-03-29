<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Drivers</strong>
                        <router-link to="/drivers/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>National ID</th>
                                <th>DL Number</th>
                                <th>Mobile Number</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="driver in drivers">
                                <td>{{ driver.name }}</td>
                                <td>{{ driver.national_id }}</td>
                                <td>{{ driver.dl_number }}</td>
                                <td>{{ driver.mobile }}</td>
                                <td class="text-center">
                                    <span @click="edit(driver)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(driver)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>National ID</th>
                                <th>DL Number</th>
                                <th>Mobile Number</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/driver').then(response => {
                this.drivers = response;
                prepareTable();
            });
        },

        data() {
            return {
                drivers: [],
            };
        },

        methods: {
            edit(driver) {
                window._router.push({path: '/drivers/' + driver.id + '/edit'})
            },

            destroy(driver) {
                this.sharedState.state.drivers.splice(this.sharedState.state.drivers.indexOf(driver), 1);
            }
        }
    }
</script>
