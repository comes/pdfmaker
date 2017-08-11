<template>
    <div class="List">

        <alert v-if="loading" v-bind:closeable="false" v-bind:rotate="true" icon="autorenew" state="rose">Lade...</alert>
        <alert v-if="error" v-bind:closeable="false" icon="error_outline" state="danger">{{ error }}</alert>

        <dynamic-form :config="this.formdata" ref="foo" :id="uniqId"></dynamic-form>

        <div v-if="data" class="panel panel-default">

            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">{{ vname }}</h3>
                </div>

                <div class="pull-right">
                    <a href="#" class="btn-link btn-xs" @click.prevent="add">
                        <i class="glyphicon glyphicon-plus"></i> Hinzufügen
                    </a>
                </div>
                <div class="clearfix"></div>

            </div>
            <div class="panel-body">
                <alert v-if="data.total == 0" v-bind:closeable="false" state="info">Keine Daten vorhanden</alert>

                <div class="table-responsive" v-if="data.total > 0">
                    <table class="table">
                        <thead>
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
                    {{ data.from }} - {{ data.to }} von {{ data.total }}
                </div>

                <nav aria-label="Page navigation"  v-if="data.total > data.per_page">
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
</template>

<script>
    import Alert from './Alert'
    import Demo from './Demo'
    import Modal from './Modal'
    import dynamicForm from '../libs/dynamic-form'
    export default {
        components: {
            Alert,
            dynamicForm,
            Modal
        },
        props: [
            'vname',
            'apipath',
            'type'
        ],
        data () {
            return {
                uniqId: '',
                formdata : {

                },
                show: true,
                loading: true,
                keys: [],
                data: null, // table data
                error: null,
                blacklist: [
                    'created_at',
                    'updated_at',
                    'objecttype',
                    'objecttypes_id',
                    'password',
                    'deleted_at',
                    'tags'
                ],
                metadata : {}
            }
        },
        mounted () {
            // Lade die Daten, wenn die Komponente erstellt wurde und die
            // Daten bereits überwacht werden.
            this.fetchData()
            this.fetchMetaData()
            this.uniqId = window.guid()
        },
        watch: {
            // Rufe die Methode erneut auf, wenn sich die Route ändert.
            '$route': 'fetchData'
        },
        methods: {
            buildFormData(prefill) {
                let inputs = []
                let keys = Object.keys(this.metadata)

                prefill = (typeof prefill === 'undefined')?[]:prefill

                keys.forEach((key, value) => {
                    let data = this.metadata[key]

                    let t = {}
                    if (key === 'options') {
                        let sub_options = {}
                        let subKeys = Object.keys(data)

                        subKeys.forEach((k,v) => {
                            let prop = data[k]
                            let modalValue = null
                            if (
                                   (typeof prefill[key] == 'object')
                                && (prefill[key] !== null)
                                && (typeof prefill[key][k] !== 'undefined')) {
                                    modalValue = prefill[key][k]
                            }

                            t = {
                                type:prop.type,
                                label:(prop.label?prop.label:k),
                                name:key + '[' + k + ']',
                                class:'form-control',
                                value: modalValue,
                                default: (prop.default)?prop.default:null
                            }

                            if (prop.hasOwnProperty('options')) {
                                t['options'] = prop.options
                            }
                            inputs.push(t)
                        })
                    } else {
                        let modalValue = null
                        if (typeof prefill[key] !== 'undefined') {
                            modalValue = prefill[key]
                        }

                        t = {
                            type:data.type,
                            label:(data.label?data.label:key),
                            name:key,
                            class:'form-control',
                            value: modalValue,
                            default: (data.default)?data.default:null
                        };

                        if (data.hasOwnProperty('options')) {
                            t['options'] = data.options
                        }
                        inputs.push(t)
                    }
                })

                this.formdata = {
                    request: {
                        //post url
                        url:this.apipath,
                        //method
                        method:'post',
                        /* headers:new Headers({
                            'X-CSRF-TOKEN':document.head.querySelector('meta[name="csrf-token"]').content
                        }),
                        credentials:'include' */
                    },
                    submitText:'Register',
                    submitClass:'btn btn-primary',
                    inputs: inputs,
                    ModalShow : false
                }
            },
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
                    index

                for (index = start; index <= end; index++) {
                    pages.push(index)
                }

                return pages
            },
            fetchMetaData () {
                axios.get(this.apipath + '/meta/describe')
                    .then(response => {
                        this.loading = false
                        this.metadata = response.data
                    }, (response) => {
                        // error callback
                        this.loading = false
                        this.error = 'something went wrong'
                    })
            },
            fetchData () {
                this.error = this.post = null
                this.loading = true

                axios.get(this.apipath)
                    .then(response => {
                        this.loading = false
                        this.data = response.data
                        if (this.data.data.length > 0) {
                            var blacklist = this.blacklist
                            this.keys = Object.keys(this.data.data[0]).filter((key) => {
                                return blacklist.indexOf(String(key).toLowerCase()) < 0
                            })
                        }
                    }, (response) => {
                        // error callback
                        this.loading = false
                        this.error = 'something went wrong'
                    })
            },
            showPrevPage() {
                axios.get(this.data.prev_page_url).then((r) => {
                    console.log(r);
                    this.data = r.data
                });
            },
            showNextPage() {
                axios.get(this.data.next_page_url).then((r) => {
                    this.data = r.data
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
            add() {
                this.buildFormData({})
                this.formdata.ModalShow = true
            },
            edit(key) {
                axios.get(this.data.path + '/' + key).then(
                    response => {
                        this.buildFormData(response.data)
                        this.formdata.ModalShow = true
                    }, //
                    response => {}  // error
                )
                //
            }
        }
    }
</script>
