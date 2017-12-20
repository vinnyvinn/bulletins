<template></template>
<script>
    export default{
        mounted(){
            http.get('/api/fetchemployees').then((response) => {
             console.log("response is new response", response)
            });
        }
    }
</script>