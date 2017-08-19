<template>
    <div>
        <h1>Produkt</h1>

        <alert v-if="loading" v-bind:closeable="false" icon="glyphicon glyphicon-ok" state="success">Loading...</alert>
        <alert v-if="error" v-bind:closeable="false" icon="fa fa-info-circle" state="danger">{{ error }}</alert>
        <pre v-if="model" >{{ model }}</pre>
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
                let url = '/api/product/' + this.$route.params.id

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