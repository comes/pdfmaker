<template>
    <div class="alert" :class="[alertState,{'alert-with-icon':hasIcon}]" data-notify="container">
        <i class="material-icons" :class="{'icon-spin':rotate}" data-notify="icon" v-if="hasIcon">{{icon}}</i>
        <button type="button" aria-hidden="true" class="close" v-if="isCloseable">
            <i class="material-icons">close</i>
        </button>
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
