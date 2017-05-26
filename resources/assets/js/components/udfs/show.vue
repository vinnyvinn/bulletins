<template lang="html">
  <div class="row">
    <div v-for="field in fields" class="">
      <div v-if="field.datatype == 'Short Text'" class="">

        <div class="col-md-6">
          <strong>{{ field.key }}</strong>
        </div>
        <div class="col-md-6">
          <p>{{ field.value }}</p>
        </div>

      </div>

      <div v-if="field.datatype == 'Long Text'" class="">

        <div class="col-md-6">
          <strong>{{ field.key }}</strong>
        </div>
        <div class="col-md-6">
          <p>{{ field.value }}</p>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      udfs: [],
      fields: []
    }
  },
  props: ['module','state'],
  created() {
    this.getUdfs();
  },
  methods: {
    getUdfs() {
      http.get('/api/module-udfs/'+this.module).then(response => {
          this.udfs = response;
      }).then(() => {
        for(var key in this.state){          
          for(var index in this.udfs){
            var udf = this.udfs[index];
            if(key == udf.slug){
              var datatype = udf.input_type;
            }
          }
          var value = this.state[key];
          if(datatype){
            this.fields.push({key, datatype, value});
          }
        }
      });
    }
   }
 }
</script>

<style lang="css">
</style>
