<template></template>
<script>
    export default{
        created() {
            this.$root.isLoading = true;
            http.get('/api/hr-employees/create').then((response) => {
               console.log(response);
                return response;
            });
        },

    }
</script>