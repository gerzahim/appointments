<template>                          
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Servicio</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-show="!loading_appoinments" v-for="appoinment in appoinments" :key="appoinment.id">
                            <td>{{ appoinment.name }}</td>
                            <td>{{ appoinment.service.name }}</td>
                            <td>{{ appoinment.time }}</td>
                        </tr>
                        <tr v-show="loading_appoinments">
                            <td colspan="3">
                                <div class="spinner-border text-success" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

</template>

<script>
    export default {
        props: ["props_date"],
        data: function () {
            return {

                loading_appoinments :false,
                appoinments : [],
                polling: null, // It will prevent memory leaks that setInterval() method can cause.

            }
        },
        computed: {

        },
        watch: {

        },
        methods: {
            
            loadAppoinments () {
                this.loading_appoinments = true
                axios.get('/api/list-attendes/'+this.props_date)
                    .then((response) => {
                        this.appoinments    = response.data;
                        this.loading_appoinments = false
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            

        },
        mounted() {
            console.log('Component mounted.')
        }, 
        beforeDestroy () {
            clearInterval(this.polling)
        },
        created () {
            this.loadAppoinments();
            this.polling = setInterval(() => {
                this.loadAppoinments()
            }, 600000) // 600000 refresh every 10 minutes / 10000 - refresh every 10 seconds
        }
    }
</script>
