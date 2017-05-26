<template lang="html">
  <div>
    <div v-for="udf in udfs">

      <div v-if="udf.input_type === 'Short Text'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="text"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name"
        v-model="state[udf.slug]"
        >
      </div>

      <div v-else-if="udf.input_type === 'Long Text'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <textarea
        :name="udf.slug"
        :id="udf.slug" rows="3" cols="30"
        class="form-control"
        :placeholder="udf.name"
        v-model="state[udf.slug]">{{ udf.name }}
        </textarea>
      </div>

      <div v-else-if="udf.input_type === 'Number'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="number"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name"
        v-model="state[udf.slug]"
        >
      </div>

      <div v-else-if="udf.input_type === 'Document'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="file"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        @change="state[udf.slug]">
      </div>

      <div v-else-if="udf.input_type === 'Image'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="file"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        @change="state[udf.slug]">
      </div>

      <div v-else-if="udf.input_type === 'Yes/No'" class="form-group">
        <input type="radio" name="udf.slug" value="">{{ udf.name }}
      </div>

      <div v-else-if="udf.input_type === 'DateTime'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="Date"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name"
        v-model="state[udf.slug]">
      </div>

      <div v-else-if="udf.input_type === 'Select'" class="form-group">
        <label :for="udf.slug" class="control-label">{{udf.name}} </label>
        <select class="form-control" :name="udf.slug" v-model="state[udf.slug]">
          <option value="">Select {{ udf.name }}</option>
          <option v-for="option in udf.value.split(';')" :value="option">{{option}}</option>
        </select>
        </div>
        <div v-else-if="udf.input_type === 'Select'" class="form-group">
        <label :for="udf.slug" class="control-label">{{udf.name}} </label>
        <select class="form-control" :name="udf.slug" v-model="state[udf.slug]">
          <option value="">Select {{ udf.name }}</option>
          <option v-for="option in udf.value.split(';')" :value="option">{{option}}</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    //asasas;aasasasa;asasaasas
  created() {
    // this.$emit('udfAdded', this.udf.slug);
  },
  data () {
    return {
      udfs: [],
    }
  },
  props: ['module','object', 'state'],
  mounted () {
    this.getUdfs()
  },
  methods: {
    getUdfs () {

        http.get('/api/module-udfs/'+this.module).then(response => {
            response.forEach((udf) => {
              if (! this.state['udf.slug']) {
                this.$emit('udfAdded', udf.slug);
              }
            });

            this.udfs = response;


        });
      }
    }
  }
</script>

<style lang="css">
</style>
