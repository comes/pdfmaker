<template>
    <div class="List">
        <alert v-if="loading" v-bind:closeable="false" v-bind:rotate="true" icon="autorenew" state="rose">Lade...</alert>

        <alert v-if="error" v-bind:closeable="false" icon="error_outline" state="danger">{{ error }}</alert>

        <div v-if="data" class="content">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">table</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title pull-left">{{ vname }}</h4>
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-rose">
                            <tr>
                                <th v-for="key in keys">
                                    {{ key }}
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="column in data.data">
                                <td v-for="key in keys" v-html>
                                    {{ column[key] }}
                                </td>
                                <td class="td-actions text-right">
                                    <div class="btn-group" role="group" aria-label="action">
                                        <a @click.prevent="edit(column.id)" class="btn btn-default btn-sm">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a @click.prevent="destroy(column.id)" class="btn btn-danger btn-sm">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li v-bind:class="[data.prev_page_url != null ? '' : 'disabled']">
                                <a href="#" aria-label="Previous" @click.prevent="selectPage(1)">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li v-bind:class="[data.prev_page_url != null ? '' : 'disabled']">
                                <a href="#" aria-label="Previous" @click.prevent="showPrevPage">
                                    <span aria-hidden="true">&lsaquo;</span>
                                </a>
                            </li>
                            <li class="page-item" v-for="n in getPages()" :class="{ 'active': n == data.current_page }">
                                <a class="page-link" href="#" @click.prevent="selectPage(n)">{{ n }}</a>
                            </li>
                            <li v-bind:class="[data.next_page_url != null ? '' : 'disabled']">
                                <a aria-label="Next" @click.prevent="showNextPage">
                                    <span aria-hidden="true">&rsaquo;</span>
                                </a>
                            </li>
                            <li v-bind:class="[data.next_page_url != null ? '' : 'disabled']">
                                <a aria-label="Next" @click.prevent="selectPage(data.last_pages)">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Alert from './Alert'
    export default {
        components: {
            Alert,
        },
        props: [
            'vname',
            'apipath'
        ],
        data () {
            return {
                loading: true,
                keys: [],
                data: null,
                error: null,
                blacklist: [
                    'created_at',
                    'updated_at',
                    'objecttype',
                    'objecttypes_id',
                    'password',
                    'deleted_at',
                    'tags'
                ]
            }
        },
        mounted () {
            // Lade die Daten, wenn die Komponente erstellt wurde und die
            // Daten bereits überwacht werden.
            this.fetchData()
        },
        watch: {
            // Rufe die Methode erneut auf, wenn sich die Route ändert.
            '$route': 'fetchData'
        },
        methods: {
            selectPage(n) {
                axios.get(this.data.path,{
                    params : {
                        page:n
                    }
                }).then((r) => {
                    this.data = r.data
                })
            },
            getPages: function() {

                var start = 1,
                    end   = this.data.last_page,
                    current = this.data.current_page,
                    pages = [],
                    index;


                for (index = start; index <= end; index++) {
                    pages.push(index);
                }


                return pages;
            },
            fetchData () {
                this.error = this.post = null
                this.loading = true

                axios.get(this.apipath)
                    .then(response => {
                        this.loading = false
                        this.data = response.data

                        var blacklist = this.blacklist
                        this.keys = Object.keys(this.data.data[0]).filter((key) => {
                            return blacklist.indexOf(String(key).toLowerCase()) < 0
                        })
                    }, (response) => {
                        // error callback
                        this.loading = false
                        this.error = 'something went wrong'
                    })
            },
            showPrevPage() {
                axios.get(this.data.prev_page_url).then((r) => {
                    console.log(r);
                    this.data = r.data;
                });
            },
            showNextPage() {
                axios.get(this.data.next_page_url).then((r) => {
                    this.data = r.data;
                });
            },
            refresh() {
                axios.get(this.data.path, {
                    params: {
                        page : this.data.current_page
                    }
                }).then((r) => {
                    this.data = r.data
                })
            },
            destroy(key) {
                if (confirm('do you really want to delete this item?')) {
                    axios.delete(this.data.path + '/' + key)
                        .then(response => {
                            this.refresh()
                        }, (response) => {
                            // error callback
                            this.error = 'something went wrong'
                        })
                }
            },
            edit(key) {
                console.log('you click on edit')
            }
        }
    }
</script>
