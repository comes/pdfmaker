<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Only for debuggin'</h3>
            </div>
            <div class="panel-body">
                <alert v-if="loading" v-bind:closeable="false" icon="glyphicon glyphicon-ok" state="success">Loading...</alert>
                <alert v-if="error" v-bind:closeable="false" icon="fa fa-info-circle" state="danger">{{ error }}</alert>
                <pre v-if="model" >{{ model }}</pre>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                error: false,
                loading: true,
                model: null
            }
        },
        watch: {
            '$route' (to, from) {
                // reagiere auf @Route-Änderungen
                this.fetchData()
            }
        },
        created () {
            // Lade die Daten, wenn die Komponente erstellt wurde und die
            // Daten bereits observed ("beobachtet") werden.
            this.fetchData()
        },
        methods: {
            fetchData () {
                this.loading = true
                let url = this.$route.meta.api + this.$route.params.id

                axios.get(url, {
                    params : {
                        //
                    }
                }).then((r) => {
                    this.model = r.data
                    this.loading = false
                    this.error = false
                }, ( /* response */ ) => {
                    // error callback
                    this.loading = false
                    this.error = 'something went wrong'
                })
            }
        }
    }
</script>