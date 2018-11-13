<template>
    <loading-view :loading="loading">
        <heading class="mb-3">{{__('New')}} {{ __('Language') }}</heading>

        <form @submit.prevent="createLanguage" autocomplete="off">
            <card class="overflow-hidden">
                <!-- Validation Errors -->
                <validation-errors :errors="errors"/>

                <!-- Fields -->
                <language-search :languages="languages" v-model="language" :errors="errors"/>

                <!-- Create Button -->
                <div class="bg-30 flex px-8 py-4">
                    <button dusk="show-language-fields" class="text-left btn btn-default" :class="buttonClass" type="button" @click="showLanguageSections = !showLanguageSections">
                        {{ buttonText }}
                    </button>

                    <button dusk="create-button" class="btn ml-auto btn-default btn-primary" :disabled="working">
                        <loader v-if="working"/>
                        <span v-else>
                            {{__('Create') }} {{ __('Language') }}
                        </span>
                    </button>
                </div>
            </card>
            <language-sections v-show="showLanguageSections" @saved="handleSaved"/>
        </form>

    </loading-view>
</template>
<script>

    import {Errors} from 'laravel-nova'
    import LanguageSections from "./LanguageSections";
    import LanguageSearch from "./LanguageSearch";

    export default {
        name: "CreateLanguage",
        components: {LanguageSearch, LanguageSections},
        data: () => ({
            loading: true,
            language: null,
            languages: [],
            working: false,
            languageFields: [],
            showLanguageSections: true,
            errors: new Errors(),
        }),
        created() {
            this.load()
        },
        computed: {
            buttonClass() {
                return {
                    'btn-success': !this.showLanguageSections,
                    'btn-black': this.showLanguageSections,
                }
            },
            buttonText() {
                return this.showLanguageSections ? this.__('Hide Language Fields') : this.__('Show Language Fields')
            }
        },
        methods: {
            handleSaved(fields) {
                this.languageFields = fields;
            },
            async load() {
                this.languages = [];

                const {data} = await Nova.request().get(
                    `${requestPrefix}/defaults`
                );

                this.languages = data;

                this.loading = false
            },

            async createLanguage() {
                this.working = true;

                try {
                    const response = await this.createRequest();

                    this.$toasted.show(
                        this.__('The :resource was created!', {
                            resource: this.__('language'),
                        }),
                        {type: 'success'}
                    );

                    this.$router.push({name: 'languages'})
                } catch (error) {
                    if (error.response.status == 422) {
                        this.errors = new Errors(error.response.data.errors)
                    }
                }

                this.working = false;
            },
            createRequest() {
                return Nova.request().post(
                    `${requestPrefix}/store`,
                    this.createLanguageFormData()
                )
            },
            createLanguageFormData() {
                return _.tap(new FormData(), formData => {
                    if (this.language) {
                        formData.append('language', true);

                        formData.append('label', this.language.label);
                        formData.append('code', this.language.code);

                        formData.append('fields', JSON.stringify(this.languageFields));
                    }
                })
            }
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