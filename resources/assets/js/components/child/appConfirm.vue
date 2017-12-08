<template>
    <app-dialog class="md-dialog--center md-dialog--confirm" alert="true" :unid="unid">
        <div class="" slot="body">
            {{body}}
        </div>
    </app-dialog>
</template>
<style>
    .md-dialog--confirm{
        z-index: 30;
    }
</style>
<script>
    import appDialog from './dialog'
    export default {
        props: {
            body: '',
            unid: ''
        },
        data: function () {
            return {}
        },
        methods: {},
        components: {
            'app-dialog': appDialog
        }
    }
</script>
