<template>
    <div>
        <heading class="mb-3">{{ __('Language') }} {{__('Edit')}}</heading>

        <form @submit.prevent="updateLanguage" autocomplete="off">
            <card class="overflow-hidden">

                <language-sections @saved="handleSaved" :edit="true" :language-id="languageId"/>

                <!-- Create Button -->
                <div class="bg-30 flex px-8 py-4">
                    <button dusk="create-button" class="btn btn-default btn-primary ml-auto" :disabled="working">
                        <loader v-if="working"/>
                        <span v-else>
                            {{ __('Language') }} {{__('Update')}}
                        </span>
                    </button>
                </div>
            </card>
        </form>
    </div>
</template>
<script>

    import LanguageSections from "./LanguageSections";
    import LanguageSearch from "./LanguageSearch";

    export default {
        name: "UpdateLanguage",
        components: {LanguageSearch, LanguageSections},
        props: {
            languageId: {
                type: [Number, String],
                required: true
            }
        },
        data: () => ({
            loading: false,
            working: false,
            languageFields: [],
        }),
        methods: {
            handleSaved(fields) {
                this.languageFields = fields;
            },
            async updateLanguage() {
                this.working = true;

                const response = await this.updateRequest();

                this.$toasted.show(
                    this.__('The :resource was updated!', {
                        resource: this.__('language'),
                    }),
                    {type: 'success'}
                );

                this.$router.push({name: 'languages'})

            },
            updateRequest() {
                return Nova.request().post(
                    `${requestPrefix}/update/${this.languageId}`,
                    this.updateLanguageFormData()
                )
            },
            updateLanguageFormData() {
                return _.tap(new FormData(), formData => {
                    formData.append('fields', JSON.stringify(this.languageFields));
                })
            },
        }
    }
</script>


<style scoped>
    .btn-success {
        background-color: var(--success);
        color: var(--white);
    }

    .btn-black {
        background-color: var(--black);
        color: var(--white);
    }
</style>