<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Issued Parts Requisitions</strong>


                        <router-link to="/wsh/parts/closed" class="btn btn-danger btn-xs pull-right">
                            <i class="fa fa-plus"></i> Closed
                        </router-link>

                        <router-link to="/wsh/parts/issue" class="btn btn-warning btn-xs pull-right">
                            <i class="fa fa-plus"></i> Pending Issue
                        </router-link>

                        <router-link to="/wsh/parts" class="btn btn-primary btn-xs pull-right">
                            <i class="fa fa-plus"></i> Pending Approval
                        </router-link>

                        <router-link to="/wsh/parts/create" class="btn btn-success btn-xs pull-right">
                            <i class="fa fa-plus"></i> New
                        </router-link>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Requisition #</th>
                                        <th>Card #</th>
                                        <th>Status</th>
                                        <th>Vehicle</th>
                                        <th>Requested On</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(item, index) in requisitions">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            <a @click.prevent="viewRequisition(item.id)">PR-{{ item.id }}</a>
                                        </td>
                                        <td>
                                            <a @click.prevent="viewCard(item.job_card_id)">JC-{{ item.job_card_id }}</a>
                                        </td>
                                        <td>
                                            <span v-if="item.status == 'Pending Approval'" class="label label-warning">PENDING</span>
                                            <span v-if="item.status == 'Approved'" class="label label-primary">APPROVED</span>
                                            <span v-if="item.status == 'Declined'" class="label label-danger">DECLINED</span>
                                            <span v-if="item.status == 'Issued'" class="label label-success">ISSUED</span>
                                            <span v-if="item.status == 'Closed'" class="label label-default">CLOSED</span>
                                        </td>
                                        <td>{{ item.job_card.vehicle_number }}</td>
                                        <td>{{ formatDate(item.created_at) }}</td>
                                        <td class="text-center">
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Requisition #</th>
                                        <th>Card #</th>
                                        <th>Status</th>
                                        <th>Vehicle</th>
                                        <th>Requested On</th>
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
            http.get('/api/parts?status=Issued').then(response => {
                this.requisitions = response.requisitions;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                requisitions: [],
            };
        },

        methods: {
            formatDate(date) {
                date = new Date(date);
                let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                let month = months[date.getMonth()];
                let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

                return day + ' ' + month + ' ' + date.getFullYear();
            },

            editRequisition(record) {
                window._router.push({ path: '/wsh/parts/' + record + '/edit' })
            },

            viewRequisition(record) {
                window._router.push({ path: '/wsh/parts/' + record })
            },

            destroy(id) {
                //this.$root.isLoading = true;

                // http.destroy('api/truck/' + id).then(response => {
                //     if (response.status != 'success') {
                //         this.$root.isLoading = false;
                //         alert2(this.$root, [response.message], 'danger');
                //         return;
                //     }
                //     $('table').dataTable().fnDestroy();
                //     this.trucks = response.trucks;
                //     prepareTable();
                //     this.$root.isLoading = false;
                //     alert2(this.$root, [response.message], 'success');
                // }).catch((error) => {
                //     this.$root.isLoading = false;
                //     alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                // });
            }
        }
    }
</script>
