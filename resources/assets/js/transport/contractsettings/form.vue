<template lang="html">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          Contract Settings
        </div>
        <div class="panel-body">
          <form action="#">
            <div class="form-group c0l-sm-6" v-for="(field, index) in fields" v-if="field != 'contract_id'">
              <label for="setting">{{ ucwords(field) }}</label>
              <input type="text" class="form-control" v-model="setting[field]" id="setting">
            </div>
            <button class="btn btn-success" type="button" @click="save()">Save</button>
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
    this.fetchContractSettings();
  },

  methods: {
      ucwords(str) {
          return (str + '')
              .replace('_', ' ')
              .replace(/^(.)|\s+(.)/g, function ($1) {
                  return $1.toUpperCase()
              })
      },
    fetchContractSettings () {
      this.$root.isLoading = true;
      return http.get('/api/contract_config/' + this.$route.params.contract_id)
          .then( (response) => {
            if(response.setting) {
              this.setting = response.setting;
              this.isEditing = true;
            } else {
              this.isEditing = false;
            }
            this.$root.isLoading = false;
          });
    },

    fetchFields () {
      return http.get('/api/config_field'). then((response) => {
        this.$root.isLoading = true;
        this.fields = response.fields;

        this.fields = Object.keys(this.fields).map(function (key) { return key; });
        for(var i = 0; i < this.fields.length; i++) {
          var field = this.fields[i];
          if(!(field == 'id' || field == 'created_at' || field == 'updated_at' || field == 'contract_id')) {
            this.setting[field] = '';
          } else {
            this.fields.splice(i, 1);
          }
        }
        this.setting.contract_id = this.$route.params.contract_id;
        this.$root.isLoading = false;

        return response;
      });
    },
    fetchSettings () {
      this.$root.isLoading = true;
      http.get('/api/contract_config'). then((response) => {
        this.contract_settings = response.contract_settings.length ? response.contract_settings : {};
        this.$root.isLoading = false;
      });
    },
    save() {

      this.$root.isLoading = true;
      if(this.isEditing) {
        http.put('/api/contract_config/' + this.setting.id, this.setting).then((response) => {
          this.fetchSettings();
          this.fetchContractSettings();
          alert2(this.$root, [response.message], 'success');
          this.isEditing = false;
          this.setting = {};
          this.$root.isLoading = false;
        });
      } else {

        http.post('/api/contract_config', this.setting).then((response) => {
          this.setting = {};
          this.fetchSettings();
          this.fetchContractSettings();
          alert2(this.$root, [response.message], 'success');

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
