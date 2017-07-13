<template>
    <div class="container-fluid">
        <div class="row">
            <router-link to="/contracts/r/print">
                <div class="col-sm-2 panel panel-default">
                    <h4 class="text-center">Contracts</h4>
                </div>
            </router-link>


        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/contract').then(response => {
                this.contracts = response.contracts;
                this.setupConfirm();
                prepareTable(true, [0, 1, 11]);
            });
            $(document).ready(() => {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    endDate: '0d',
                    clearBtn: true,
                    todayBtn: true,
                    todayHighlight: true,
                }).on('change', (e) => {
                    this.endMonth = e.target.value;
                });
            });
        },

        data() {
            return {
                contracts: [],
                endMonth: null,
                period: null,
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

            edit(contract) {
                window._router.push({path: '/contracts/' + contract.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/contract/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    prepareTable(true, [0, 1, 11]);
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            filterRows() {
                if (! this.period) return;
                if (! this.endMonth) return;
                this.$root.isLoading = true;
                http.get('/api/contract?duration=' + this.period + '&date=' + this.endMonth).then(response => {
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    this.setupConfirm();
                    prepareTable(true, [0, 1, 11]);
                    this.$root.isLoading = false;
                });
            }
        }
    }
</script>
