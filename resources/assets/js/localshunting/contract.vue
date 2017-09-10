<template>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>Contract Selection</strong>
                </div>

                <div class="panel-body">
                    <h4 class="text-center">Please select a station</h4>
                    <form action="/contract-selection" method="post" role="form">
                        <input type="hidden" name="_token" :value="$root.csrf">
                        <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="contract">Contract</label>
                                    <select name="contract" id="contract" class="form-control">
                                        <option v-for="contract in contracts" :value="contract.id">{{ contract.name }}</option>
                                    </select>
                                </div>
                            </div>

                        <br>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">Select</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                contracts: [],
            };
        },
        created() {
            http.get('/api/contract').then(response => {
                this.contracts = response.contracts;
            });
        }
    }
</script>

