<template lang="html">
  <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <strong>User Defined Fields</strong>
                  </div>

                  <div class="panel-body">
                      <form action="#" role="form" @submit.prevent="store">

                          <div class="form-group">
                              <label for="plate_number">Name</label>
                              <input v-model="udf.name" type="text" class="form-control text-uppercase" id="plate_number" name="plate_number" required>
                          </div>

                          <div class="form-group">
                              <label for="make">Input Type</label>
                              <select v-model="udf.input_type" class="form-control" name="input_type" >
                                <option v-for="input_type in input_types" value="">{{input_type}}</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="make">Status</label>
                              <select v-model="udf.input_type" class="form-control" name="input_type" >
                                <option value="">Active</option>
                                <option value="">Inactive</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="make">Section</label>
                              <select v-model="udf.section" class="form-control" name="input_type" >
                                <option value="">One</option>
                                <option value="">two</option>
                              </select>
                          </div>

                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea v-model="udf.description" name="description" rows="3" cols="30">
                            </textarea>
                          </div>

                          <div class="form-group">
                              <button class="btn btn-success">Save</button>
                              <router-link to="/udfs" class="btn btn-danger">Back</router-link>
                          </div>
                      </form>

                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    data (){
      return {
        udf: {
          name: '',
          input_type: '',
          status: '',
          section: '',
          description: ''
        },
        input_types: [{
          
        }]
      }
    },
    methods: {
  }
}
</script>

<style lang="css">
</style>
