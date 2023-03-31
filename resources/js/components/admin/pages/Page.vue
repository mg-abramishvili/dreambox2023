<template>
    <div>
        <div class="content-header px-4">
            <h1>
                <router-link v-if="page.parent_id" :to="{name: 'Page', params: {id: page.parent_id} }" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                    </svg>
                </router-link>
                <router-link v-else :to="{name: 'Pages'}" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                    </svg>
                </router-link>

                {{ page.name }}
            </h1>

            <div>
                <router-link :to="{name: 'PageMaster', query: {type: 'page', from: page.id} }" class="btn btn-primary">Создать страницу</router-link>
                <router-link :to="{name: 'PageMaster', query: {type: 'folder', from: page.id} }" class="btn btn-outline-primary ms-2">Создать папку</router-link>
            </div>
        </div>

        <div v-if="views.loading" class="p-4">
            <Loader />
        </div>

        <div v-if="!views.loading" class="content p-4">
            <table v-if="page.children.length" class="table table-hover align-middle">
                <tbody>
                    <tr v-for="page in page.children">
                        <td @click="goToPage(page)" class="w-50" style="cursor: pointer">
                            {{ page.name }}
                        </td>
                        <td>
                            <template v-if="!page.is_folder">{{ $filters.datetime(page.updated_at) }}</template>
                        </td>
                        <td class="text-end">
                            <router-link v-if="!page.is_folder" :to="{ name: 'PageMaster', params: {id: page.id} }" class="btn btn-sm btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </router-link>
                            <router-link v-if="page.is_folder" :to="{ name: 'PageMaster', params: {id: page.id} }" class="btn btn-sm btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-else>Пусто.</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            page: {},

            views: {
                loading: true,
            }
        }
    },
    created() {
        this.loadPages()
    },
    methods: {
        loadPages() {
            axios.get(`/api/admin/page/${this.$route.params.id}`)
            .then(response => {
                this.page = response.data

                this.views.loading = false
            })
        },
        goToPage(page) {
            if(page.is_folder) {
                this.$router.push({name: 'Page', params: {id: page.id} })
            }
        },
    },
}
</script>
