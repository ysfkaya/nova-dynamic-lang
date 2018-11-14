<template>
    <loading-view :loading="loading">
        <heading class="mb-3">{{ __('Language') }} {{__('Edit')}}</heading>

        <form @submit.prevent="updateLanguage" autocomplete="off">
            <card class="overflow-hidden">

                <form-text-field :field="shortNameField" :errors="errors"/>

                <form-file-field class="file-field" :field="fileField" :errors="errors"/>

                <form-boolean-field :field="statusField" :errors="errors"/>

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

            <language-sections @saved="handleSaved" :edit="true" :language-id="languageCode"/>
        </form>
    </loading-view>
</template>
<script>

    import {Errors} from 'laravel-nova'
    import LanguageSections from "./LanguageSections";

    export default {
        name: "UpdateLanguage",
        components: {LanguageSections},
        props: {
            languageCode: {
                type: [Number, String],
                required: true
            }
        },
        data: function () {
            return {
                loading: true,
                working: false,
                languageFields: [],
                language: null,
                errors: new Errors(),
                fileField: {
                    attribute: 'flag',
                    name: this.__('Flag'),
                    helpText: this.__('Max Width : 32px , Max Height : 32px')
                },
                shortNameField: {
                    attribute: 'short_name',
                    name: this.__('Short Name'),
                    helpText: this.__('Example: ENG')
                },
                statusField: {
                    attribute: 'status',
                    name: this.__('Status'),
                    value: true
                }
            }
        },
        created() {
            this.getLanguage();
        },
        methods: {
            handleSaved(fields) {
                this.languageFields = fields;
            },
            async getLanguage() {
                const {data} = await Nova.request().get(
                    `${requestPrefix}/language/${this.languageCode}`
                )

                this.language = data;

                this.shortNameField.value = this.language.short_name;
                this.statusField.value = this.language.status;

                if (this.language.flag) {
                    this.fileField.thumbnailUrl = this.language.flag.thumb_url;
                }


                this.loading = false;
            },
            async updateLanguage() {
                this.working = true;

                try {
                    const response = await this.updateRequest();

                    this.$toasted.show(
                        this.__('The :resource was updated!', {
                            resource: this.__('language'),
                        }),
                        {type: 'success'}
                    );

                    this.$router.push({name: 'languages'})
                } catch ({response}) {
                    this.working = false;

                    if (response.status === 422) {
                        this.errors = new Errors(response.data.errors);
                    }
                }


            },
            updateRequest() {
                return Nova.request().post(
                    `${requestPrefix}/update/${this.languageCode}`,
                    this.updateLanguageFormData()
                )
            },
            updateLanguageFormData() {
                return _.tap(new FormData(), formData => {
                    formData.append('fields', JSON.stringify(this.languageFields));

                    this.fileField.fill(formData);
                    this.shortNameField.fill(formData);
                    this.statusField.fill(formData);
                })
            },
        }
    }
</script>


<style>
    .btn-success {
        background-color: var(--success);
        color: var(--white);
    }

    .btn-black {
        background-color: var(--black);
        color: var(--white);
    }

    .file-field .card {
        max-width: 3rem !important;
    }

    .file-field .card img {
        max-width: 32px;
        max-height: 32px;
        margin: auto;
    }
</style>