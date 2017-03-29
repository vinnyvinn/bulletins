<template>
    <transition name="custom-classes-transition"
                enter-active-class="animated flipInX"
                leave-active-class="animated flipOutX"
    >
        <div id="alert-error">
            <div :class="getErrorClass()">
                <h3 v-if="level == 'danger'">Whoops!</h3>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            errors: {
                type: Array,
                required: true,
                default() {
                    return []
                }
            },
            level: {
                required: true,
                default: 'info'
            }
        },

        methods: {
            getErrorClass() {
                return 'alert alert-' + this.level;
            }
        }
    }
</script>

<style>
    #alert-error {
        position: fixed;
        bottom: 20px;
        right: 10px;
        z-index: 10000;
        width: 300px;
    }

    #alert-error ul {
        list-style-type: none;
        margin-left: -40px;
    }
</style>
