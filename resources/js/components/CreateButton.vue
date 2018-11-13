<template>
    <div>
        <!-- Create Related Models -->
        <router-link
                tag="button"
                dusk="create-button"
                :class="classes"
                :to="{name: 'languages.create'}">
            {{ __('Create') }} {{ __('Language') }}
        </router-link>
    </div>
</template>

<script>
    export default {
        name: "CreateButton",
        props: {
            classes: {default: 'btn btn-default btn-primary'},
        },
    }
</script>

<style scoped>

</style>