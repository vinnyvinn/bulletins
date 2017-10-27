<template>
    <div>
        <div class="top-nav hidden-print">
            <h4 class="pull-left brand">
                <strong>{{ app }}</strong>
            </h4>
            <router-link style="margin-right: 30px;margin-top: 3px" to="/station-selection" class="pull-right btn btn-success" v-if="showSwitch">
                Change Station
            </router-link>
        </div>

        <div class="bottom-nav hidden-print">

            <router-link to="/wsh/dashboard" class="nav-items">
                <img src="/images/home.png" alt="home" class="img-responsive">
                <div class="caption">Home</div>
            </router-link>

            <router-link to="/wsh/breakdown" class="nav-items">
                <img src="/images/breakdown.png" alt="breakdown" class="img-responsive">
                <div class="caption">Breakdown</div>
            </router-link>

            <router-link to="/wsh/job-card" class="nav-items">
                <img src="/images/inspection.png" alt="job-card" class="img-responsive">
                <div class="caption">Job Cards</div>
            </router-link>

            <router-link to="/wsh/parts" class="nav-items">
                <img src="/images/parts.png" alt="parts" class="img-responsive">
                <div class="caption">Parts Requisition</div>
            </router-link>
            
            <router-link to="/wsh/gatepass" class="nav-items" v-if="$root.can('create-gatepass') || $root.can('view-gatepass') || $root.can('edit-gatepass') || $root.can('approve-gatepass') || $root.can('delete-gatepass')">
                <img src="/images/gatepass.png" alt="fuel" class="img-responsive">
                <div class="caption">Gate Pass</div>
            </router-link>

          <!--
            <router-link to="/wsh/reports" class="nav-items" v-if="$root.can('reports')">
                <img src="/images/report.png" alt="card" class="img-responsive">
                <div class="caption">Reports</div>
            </router-link> -->

            <a href="/ls" class="nav-items">
                <img src="/images/switch.png" alt="logout" class="img-responsive">
                <div class="caption">Local Shunting</div>
            </a>

            <a href="/" class="nav-items">
                <img src="/images/switch.png" alt="logout" class="img-responsive">
                <div class="caption">Long Haul</div>
            </a>

            <div @click.prevent="logout" class="nav-items">
                <img src="/images/logout.png" alt="logout" class="img-responsive">
                <div class="caption">Logout</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      created() {
        this.$root.user = JSON.parse(localStorage.getItem("fewuia32rfwe"));
      },
      data() {
        return {
          timer: null
        };
      },

      props: {
        app: {
          required: true,
          default: ""
        }
      },

      computed: {
        showSwitch() {
          return this.$root.user.stations.length > 1;
        }
      },

      mounted() {
        // this.idleTimer();
      },

      methods: {
        logout() {
          localStorage.removeItem("foeiwafwfuwe");
          localStorage.removeItem("fewuia32rfwe");
          http.post("/logout", {}).then(() => (window.location = "/login"));
        },

        can(permission) {
          return window.can(permission);
        },
        resetTimer() {
          clearTimeout(this.timer);
          this.timer = setTimeout(this.logout, 300000);
        },
        idleTimer() {
          window.onmousemove = this.resetTimer; // catches mouse movements
          window.onmousedown = this.resetTimer; // catches mouse movements
          window.onclick = this.resetTimer; // catches mouse clicks
          window.onscroll = this.resetTimer; // catches scrolling
          window.onkeypress = this.resetTimer; //catches keyboard actions
        }
      }
    };
</script>
