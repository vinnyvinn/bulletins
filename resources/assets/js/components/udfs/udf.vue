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
        class="form-control document"
        :id="udf.slug"
        :name="udf.slug"
        @change="state[udf.slug]">
      </div>

      <div v-else-if="udf.input_type === 'Image'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="file"
        class="form-control image"
        :id="udf.slug"
        :name="udf.slug">
      </div>

      <div v-else-if="udf.input_type === 'Yes/No'" class="form-group">
        <input type="radio" name="udf.slug" value="">{{ udf.name }}
      </div>

      <div v-else-if="udf.input_type === 'Date'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="text"
        class="datepicker form-control"
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
  data () {
    return {
      udfs: []
    }
  },
  props: ['module','object', 'state', 'uploads'],
  mounted () {
    this.getUdfs()
  },
  methods: {
    updateState(udf) {
      if (udf.input_type === 'Document' || udf.input_type === 'Image') {
          let el = [];
          el[udf.slug] = '#' + udf.slug;

          this.uploads.push(el);
          return;
      }

      if (! this.state[udf.slug]) {
          Vue.set(this.state, udf.slug, '');
      }
    },
    getUdfs () {
        http.get('/api/module-udfs/' + this.module).then(response => {
          
            response.forEach((udf) => {
              this.updateState(udf);
            });

            this.udfs = response;

            setTimeout(() => {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                }).on('change', (e) => {
                    this.state[e.target.id] = e.target.value;
                });
            }, 1000)

        });
      }
    }
}
</script>

<style lang="css">
</style>
