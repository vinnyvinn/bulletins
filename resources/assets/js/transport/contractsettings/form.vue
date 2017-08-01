<template lang="html">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          New Contract Setting
        </div>
        <div class="panel-body">
          <form action="#">

            <div class="form-group" v-for="(field, index) in fields" v-if="field != 'contract_id'">
              <label>{{ field }}</label>
              <input type="text" class="input-group" v-model="setting[field]">
            </div>

            <button class="btn btn-success" type="button" @click="save()">Save</button>


          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          Configuration Fields
        </div>
        <div class="panel-body">
          <table class="table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in fields">
                <td>{{ index + 1}}</td>
                <td>{{ setting[index] }}</td>
                <td>
                  <span @click="edit(setting)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                  <span @click="deleteCategory(setting.id)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: [],
      contract_settings: [],
      setting: {
        contract_id: '',
      },
      isEditing: false,
    }
  },
  created() {
    this.fetchFields();
    this.fetchSettings();
  },

  methods: {
    fetchFields () {
      http.get('/api/config_field'). then((response) => {
        this.fields = response.fields;

        this.fields = Object.keys(this.fields).map(function (key) { return key; });
        for(var i = 0; i < this.fields.length; i++) {
          var field = this.fields[i];
          if(!(field == 'id' | field == 'created_at' | field == 'updated_at' | field == 'contract_id')) {
            this.setting[field] = '';
          } else {
            this.fields.splice(i, 1);
          }
        }
        this.setting.contract_id = this.$route.params.contract_id;
      });
    },
    fetchSettings () {
      http.get('/api/contract_config'). then((response) => {
        this.contract_settings = response.contract_settings;
      });
    },
    save() {
      this.$root.isLoading = true;
      if(this.isEditing) {
        http.put('/api/contract_config/' +this.setting.id, this.setting).then((response) => {
          this.fetchSettings();
          alert2(this.$root, [response.message], 'success');
          this.isEditing = false;
          this.setting = {};
          this.$root.isLoading = false;
        });
      } else {
        http.post('/api/contract_config', this.setting).then((response) => {
          this.setting = {};
          this.fetchSettings();
          alert2(this.$root, [response.message], 'success');
          this.$root.isLoading = false;
        });
      }

    },
    edit(setting) {
      this.setting = setting;
      this.isEditing = true;
    },
    deleteSetting(id) {
      http.destroy('/api/contract_config/' + id).then((response) => {
        this.fetchSettings();
        alert2(this.$root, [response.message], 'success');
      });
    }
  }
}
</script>

<style lang="css">
</style>
