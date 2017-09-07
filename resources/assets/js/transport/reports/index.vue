<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Reports
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Report</th>
                                    <th>Generate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="$root.can('create-delivery') || $root.can('view-delivery') || $root.can('edit-delivery') || $root.can('approve-delivery') || $root.can('delete-delivery')">
                                    <td>Loading</td>
                                    <td><router-link to="/reports/loading" class="btn btn-primary btn-xs">GENERATE</router-link></td>
                                </tr>
                                <!-- <tr>
                                    <td>Offloading</td>
                                    <td><router-link to="/reports/off-loading" class="btn btn-primary btn-xs">GENERATE</router-link></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>
