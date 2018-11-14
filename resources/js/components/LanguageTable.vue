<template>
    <table
            class="table w-full"
            cellpadding="0"
            cellspacing="0"
    >
        <thead>
        <tr>

            <th class="w-16">&nbsp;</th>

            <th class="text-left w-16">
                {{ __('Code') }}
            </th>

            <th class="text-center">
                {{ __('Flag') }}
            </th>

            <th class="text-center">
                {{ __('Label') }}
            </th>

            <th class="text-center">
                {{ __('Status') }}
            </th>

            <th>&nbsp;<!-- View, Edit, Delete --></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(language,index) in dataLanguages">
            <td></td>
            <td class="whitespace-no-wrap text-left">
                {{ language.code }}
            </td>
            <td class="whitespace-no-wrap text-center">
                <p class="text-center">
                    <img :src="language.flag.thumb_url">
                </p>
            </td>
            <td class="whitespace-no-wrap text-center">
                {{ language.label }}
            </td>
            <td class="whitespace-no-wrap text-center">
                  <span class="inline-block rounded-full w-2 h-2"
                          :class="{'bg-success': language.status, 'bg-danger': !language.status}" />
            </td>
            <td class="td-fit text-right pr-6">
                <router-link
                        class="cursor-pointer text-70 hover:text-primary mr-3"
                        :to="{name: 'languages.edit',  params: {
                            code:language.code
                        }}"
                        :title="__('Edit')"
                >
                    <icon type="edit"/>
                </router-link>

                <!-- Delete Resource Link -->
                <button
                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                        @click.prevent="openModal(language)"
                        :title="__('Delete')">
                    <icon/>
                </button>
            </td>
        </tr>
        </tbody>
        <portal to="modals">
            <transition name="fade">
                <delete-language-modal
                        v-if="deleteModalOpen"
                        @confirm="confirmDelete"
                        @close="closeModal"
                        :language="currentLanguage"
                >
                </delete-language-modal>
            </transition>
        </portal>
    </table>
</template>

<script>

    import DeleteLanguageModal from './DeleteLanguageModal'

    export default {
        name: "Table",
        components: {
            DeleteLanguageModal
        },
        props: {
            languages: {
                type: Array,
                required: true
            }
        },
        data: function () {
            return {
                deleteModalOpen: false,
                dataLanguages: this.languages,
                currentLanguage: null,
            }
        },
        methods: {
            async confirmDelete(language) {
                this.deleteModalOpen = false;

                const {data} = await Nova.request().delete(
                    `${requestPrefix}/delete/${language.code}`
                )

                this.dataLanguages = data.languages;

                this.$emit('updateLanguages', this.dataLanguages)
            },
            closeModal() {
                this.deleteModalOpen = false;
            },
            openModal(language) {
                this.currentLanguage = language;
                this.deleteModalOpen = true;
            }
        }
    }
</script>

<style scoped>

</style>