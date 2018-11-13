<!--suppress EqualityComparisonWithCoercionJS -->
<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <search-input
                    @input="performSearch"
                    @clear="clearSelection"
                    @selected="selectLanguage"
                    data-testid="language-search-input"
                    :error="hasError"
                    :value='selectedLanguage'
                    :data='availableLanguages'
                    trackBy='label'
                    searchBy='display'
                    class="mb-3"
            >
                <div slot="default" v-if="selectedLanguage" class="flex items-center">
                    {{ selectedLanguage.label }}
                </div>

                <div slot="option" slot-scope="{option, selected}" class="flex items-center">
                    {{ option.label }}
                </div>
            </search-input>
        </template>
    </default-field>
</template>

<script>
    import {HandlesValidationErrors} from 'laravel-nova'

    export default {
        name: "LanguageSearch",
        mixins: [HandlesValidationErrors],
        props: {
            languages: {
                type: Array,
                required: true
            },
            language: Object
        },
        data: function () {
            return {
                field: {
                    attribute: 'language', name:this.__('Select Language'), nullable: false,
                },
                selectedLanguage: null,
                search: '',
                availableLanguages: [],
            }
        },
        mounted() {
            this.availableLanguages = this.languages;

            if (this.language) {
                this.selectedLanguage = this.language
            }
        },
        methods: {
            selectLanguage(lang) {
                this.selectedLanguage = lang;

                this.$emit('input', lang);
            },
            performSearch(search) {
                this.search = search

                const trimmedSearch = search.trim()

                if (trimmedSearch == '') {
                    this.clearSelection()

                    return
                }

                this.selectedLanguage = ''
                this.getAvailableLanguages(trimmedSearch)
            },

            getAvailableLanguages(search) {
                this.availableLanguages = this.languages.filter(lang => {
                    return lang.label.toLowerCase().includes(search.toLowerCase());
                })
            },
            /**
             * Clear the selected resource and availableResources
             */
            clearSelection() {
                this.selectedLanguage = ''
                this.$emit('input', null);
                this.availableLanguages = this.languages
            },

        }
    }
</script>

<style scoped>

</style>