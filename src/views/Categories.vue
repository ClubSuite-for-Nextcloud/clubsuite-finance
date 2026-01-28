<template>
    <div class="categories-view">
        <div class="header">
            <h2>Kategorien</h2>
            <button @click="showForm = true">Neue Kategorie</button>
        </div>

        <div v-if="loading">Laden...</div>
        <table v-else>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <td>{{ category.name }}</td>
                    <td>
                        <button @click="edit(category)">Bearbeiten</button>
                        <button @click="remove(category.id)">Löschen</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <CategoryForm 
            v-if="showForm" 
            :category="selectedCategory" 
            @saved="fetchData" 
            @close="closeForm" 
        />
    </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import CategoryForm from '../components/CategoryForm.vue'

export default {
    name: 'Categories',
    components: { CategoryForm },
    data() {
        return {
            categories: [],
            loading: true,
            showForm: false,
            selectedCategory: null
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.loading = true
            try {
                const response = await axios.get(generateUrl('/apps/clubsuite-finance/categories'))
                this.categories = response.data
            } catch (e) {
                console.error(e)
            } finally {
                this.loading = false
                this.closeForm()
            }
        },
        edit(category) {
            this.selectedCategory = category
            this.showForm = true
        },
        async remove(id) {
            if (!confirm('Wirklich löschen?')) return
            try {
                await axios.delete(generateUrl(`/apps/clubsuite-finance/categories/${id}`))
                this.fetchData()
            } catch (e) {
                console.error(e)
                alert('Fehler beim Löschen')
            }
        },
        closeForm() {
            this.showForm = false
            this.selectedCategory = null
        }
    }
}
</script>
