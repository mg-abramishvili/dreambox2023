<template>
    <div>
        <div class="content-header px-4">
            <h1>Точки</h1>
            <router-link :to="{name: 'PointMaster'}" class="btn btn-primary">Добавить</router-link>
        </div>

        <div v-if="views.loading" class="p-4">
            <Loader />
        </div>

        <div v-if="!views.loading" class="content p-4">
            <table v-if="points.length" class="table align-middle">
                <tbody>
                    <tr v-for="point in points">
                        <td>{{ point.name }}</td>
                        <td class="text-end">
                            <router-link :to="{ name: 'PointMaster', params: {id: point.id} }" class="btn btn-sm btn-outline-primary">Правка</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-else>Точек нет.</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            points: [],

            views: {
                loading: true,
            }
        }
    },
    created() {
        this.loadPoints()
    },
    methods: {
        loadPoints() {
            axios.get('/api/admin/points')
            .then(response => {
                this.points = response.data

                this.views.loading = false
            })
        },
    },
}
</script>