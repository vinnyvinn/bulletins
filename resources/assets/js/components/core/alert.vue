<template>
    <transition name="custom-classes-transition"
                enter-active-class="animated slideInLeft"
                leave-active-class="animated slideOutLeft"
    >
        <div id="smo-alert" class="hidden-print">
            <div :class="getErrorClass()">
                <h3 v-if="level == 'danger'">Whoops!</h3>
                <ul>
                    <li v-for="error in errors"><h5>{{ error }}</h5></li>
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
    #smo-alert {
        position: fixed;
        top: 60px;
        left: 10px;
        z-index: 10000;
        width: 300px;
    }

    #smo-alert ul {
        list-style-type: none;
        margin-left: -40px;
    }

    #smo-alert .alert {
        padding: 5px 10px;
        margin-bottom: 22px;
        border: 1px solid transparent;
        border-radius: 0 !important;
    }

    #smo-alert :hover {
        pointer-events: none;
        opacity: 0.4;
    }
</style>
