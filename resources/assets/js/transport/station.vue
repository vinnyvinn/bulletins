<template>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <strong>Station Selections news</strong>
                </div>

                <div class="panel-body">
                    <h4 class="text-center">Please select a station</h4>
                    <form action="/station-selection" method="post" role="form">
                        <input type="hidden" name="_token" :value="$root.csrf">
                        <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="station">Station</label>
                                    <select name="station" id="station" class="form-control">
                                        <option v-for="station in $root.user.stations" :value="station.id">{{ station.name }}</option>
                                    </select>
                                </div>
                            </div>

                        <br>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">Login</button>
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
        created() {
            http.get('/api/user');
        }
    }
</script>

