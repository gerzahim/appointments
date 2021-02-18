<template>
    <div v-if="warningZone" class="alert alert-warning bg-warning text-white border-0" role="alert">
        <strong>Warning - </strong> No user activity detected in the last 5 minutes !
    </div>
</template>

<script>
    export default {
        name : "AutoLogout",

        data: function () {
            return {
                events : ['mousemove', 'click', 'mousedown', 'scroll', 'keypress', 'load'],

                warningTimer : null,
                logoutTimer  : null,
                warningZone  : false,
            }
        },

        mounted() {
            console.log('Component mounted.')
            this.events.forEach(function (event) {
                window.addEventListener(event, this.resetTimer);
            }, this);
        },

        destroyed() {
            console.log('Component destroyed.')
            this.events.forEach(function (event) {
                window.removeEventListener(event, this.resetTimer);
            }, this);
        },

        methods: {
            setTimers() {
                // this.warningTimer = setTimeout(this.warningMessage, 4 * 1000); // current 4 seconds => => 1000 Milliseconds make 1 second
                this.warningTimer = setTimeout(this.warningMessage, 5 * 60 * 1000); //if want 14 minutes = 14 * 60 * 1000
                this.logoutTimer  = setTimeout(this.logoutUser, 10 * 60 * 1000);
                this.warningZone  = false
            },

            warningMessage() {
                this.warningZone = true
            },

            logoutUser() {
                //logout
                //window.location.href = 'logout';
                document.getElementById('logout-form').submit();
            },

            resetTimer() {
                // If event Occurs clear TimeOut
                clearTimeout(this.warningTimer);
                clearTimeout(this.logoutTimer);

                // start over
                this.setTimers();
            },
        }
    }
</script>
