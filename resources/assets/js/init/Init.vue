<template></template>
<script>
    export default{
        mounted(){
            this.$http.get('/api/fetchemployees').then((response) => {
             console.log("response is new response", response)
            }, (err)=>{
                console.log("failed error is", err.message);
            });
        }
    }
</script>