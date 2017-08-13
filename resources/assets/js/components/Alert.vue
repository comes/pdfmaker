<template>
    <div class="alert alert-white rounded" :class="[alertState,{'alert-with-icon':hasIcon}]" data-notify="container">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-if="isCloseable">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="icon" v-if="hasIcon">
            <i :class="icon" data-notify="icon"></i>
        </div>
        <span data-notify="message"><slot></slot></span>
    </div>
</template>

<script>
    export default {
        props: [
            'rotate',
            'closeable',
            'state',
            'icon',
            'rotate'
        ],
        computed: {

            isCloseable () {
                return !!this.closeable
            },
            alertState () {
                var state = 'info'
                if (this.state) {
                    state = this.state
                }
                return 'alert-' + state
            },
            hasIcon () {
                return !!this.icon
            }
        }
    }
</script>
