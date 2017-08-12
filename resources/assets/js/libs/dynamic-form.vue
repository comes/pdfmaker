<template>
	<div class="dynamic-form-wrapper" :id="uniqid">
		<div class="modal fade" tabindex="-1" role="dialog" v-if="modal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						<form @submit.prevent='onSubmit'>
							<div class="form-group" v-for="input in config.inputs">
								<dynamic-input :input="input"
											   :class="{'has-error':form.errors.has(input.name)}"
											   :error="form.errors.has(input.name) ? form.errors.get(input.name) : false"
								></dynamic-input>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click.prevent="onSubmit">Save changes</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div v-else="modal">
			<!-- api call render form with onSubmit method -->
			<h3 v-show="config.title">{{config.title}}</h3>
			<form @submit.prevent='onSubmit'>
				<div class="form-group" v-for="input in config.inputs">
					<dynamic-input :input="input"
								   :class="{'has-error':form.errors.has(input.name)}"
								   :error="form.errors.has(input.name) ? form.errors.get(input.name) : false"
					></dynamic-input>
				</div>
				<input type="submit" :class="config.submitClass" :value="config.submitText || 'Submit'">
			</form>
		</div>

	</div>

</template>

<script>
//added the form class
import { Form } from './classes/Form'
//added the dynamic input component
import dynamicInput  from './dynamic-input.vue'
/**
 * @module Dynamic forms component
 * @param {Object} config the configuration of the whole form
 * @see module:dynamic-input
 * @see class:classes/Form.js
 * @see clas:classes/Errors.js
*/
export default {
	components:{
		dynamicInput
	},
	props: {
        'config': {
            type: Object,
			default: {}
		},
        'modal': {
            type: Boolean,
			default: true
		}
    },
	data () {
		return {
		    uniqid: '',
			form:new Form(this.config.inputs,this.config.request)
		};
	},
	watch: {
	    config () {
            this.form = new Form(this.config.inputs,this.config.request)
        },
	    'config.ModalShow' (val) {
	        let vm = this
	        if (val === true) {
                $('#' + this.uniqid + ' .modal').modal('show')
            } else {
                $('#' + this.uniqid + ' .modal').modal('hide')
            }

            $('#' + this.uniqid + ' .modal').on('hide.bs.modal', function (e) {
                // do something...
                vm.config.ModalShow = false
            })
        }
	},
    mounted () {
	    this.uniqid = window.guid()
    },
	methods:{
		onSubmit() {
			this.form.submit()
			.then((response) => {
				this.$emit('success',{
				    res:response,data:this.form.data()
				})
                this.config.ModalShow = false

			})
			.catch((err) => {
				this.$emit('fail',err)
			})
		}
	}
};
</script>