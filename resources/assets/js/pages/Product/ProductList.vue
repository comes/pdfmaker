<template>
    <div>
        <remote-data-table
                apipath="/api/product"
                vname="Produkte"
                type="product"
                :blacklist="['updated_at','created_at','deleted_at','path','parent_id','options']"
        ></remote-data-table>
    </div>
</template>
<script>
    import RemoteDataTable from '../../components/RemoteDataTable.vue'
    export default {
        components: {
            RemoteDataTable
        },
        watch: {
            '$route' (to, from) {
                // reagiere auf @Route-Änderungen
            }
        },
        created () {
            //
        },
        methods: {
            //
        }
    }
</script>