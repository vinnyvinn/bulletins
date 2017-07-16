<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Inspection</strong>
                        <router-link v-if="$root.can('create-inspection')" to="/inspection/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap">
                                <thead>
                                <tr>
                                    <th>Inspection #</th>
                                    <th>Journey #</th>
                                    <th>Truck</th>
                                    <th>Trailer</th>
                                    <th>Suitable</th>
                                    <th>Driver</th>
                                    <th>Phone Number</th>
                                    <th>Inspected On</th>
                                    <th>From</th>
                                    <th>To</th>ro
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="inspection in inspections">
                                    <td>
                                        <router-link v-if="$root.can('view-inspection')" :to="'/inspection/' + inspection.id">INSP-{{ inspection.id }}</router-link>
                                        <span v-else>INSP-{{ inspection.id }}</span>
                                    </td>
                                    <td>JRNY-{{ inspection.journey_id }}</td>
                                    <td>{{ inspection.journey.truck.plate_number }}</td>
                                    <td>{{ inspection.journey.truck.trailer.trailer_number }}</td>
                                    <td v-if="parseInt(inspection.suitable_for_loading)"><span class="label label-success">Yes</span></td>
                                    <td v-if="!parseInt(inspection.suitable_for_loading)"><span class="label label-danger">No</span></td>
                                    <td>{{ inspection.journey.driver.first_name}} {{ inspection.journey.driver.last_name}}</td>
                                    <td>{{ inspection.journey.driver.mobile_phone }}</td>
                                    <td>{{ date2(inspection.created_at) }}</td>
                                    <td>{{ inspection.from_station }}</td>
                                    <td>{{ inspection.to_station }}</td>
                                    <td class="text-center">
                                        <span v-if="$root.can('edit-inspection')" @click="edit(inspection)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button v-if="$root.can('delete-inspection')" data-toggle="popover" :data-item="inspection.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Inspection #</th>
                                    <th>Journey #</th>
                                    <th>Truck</th>
                                    <th>Trailer</th>
                                    <th>Suitable</th>
                                    <th>Driver</th>
                                    <th>Phone Number</th>
                                    <th>Inspected On</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/inspection/?s=' + window.Laravel.station_id).then(response => {
                this.inspections = response.inspections;
                this.setupConfirm();
                prepareTable();
            });
        },

        data() {
            return {
                inspections: []
            };
        },

        methods: {
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },

            edit(inspection) {
                window._router.push({path: '/inspection/' + inspection.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/inspection/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    prepareTable();
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
