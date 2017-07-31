<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Local Shunting Contracts</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable no-wrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contract</th>
                                    <th>Client</th>
                                    <th>Progress</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="contract in contracts">
                                    <td>{{ contract.id }}</td>
                                    <td>{{ contract.name }}</td>
                                    <td>{{ contract.client.Name }}</td>
                                    <td>Progress</td>
                                    <td>
                                      <router-link :to="'/ls/trucks-allocation/create/'+ contract.id">
                                        <span class="btn btn-xs btn-success">Allocate Trucks/Employees</span>
                                      </router-link>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Contract</th>
                                  <th>Client</th>
                                  <th>Progress</th>
                                  <th>Action</th>
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
          http.get('/api/lscontracts').then( response => {
            this.contracts = response.contracts;
          });
        },

        data() {
            return {
                contracts: [],
            };
        },

        methods: {
            allocate (contract) {
              this.allocation.allocatedtrucks.push(truck);
            },
        }
    }
</script>
