<template>
    <div class="modal-overlay">
        <div class="modal">
            <h3>{{ category ? 'Kategorie bearbeiten' : 'Neue Kategorie' }}</h3>
            <form @submit.prevent="save">
                <div class="form-group">
                    <label>Name</label>
                    <input v-model="form.name" required />
                </div>
                <div class="actions">
                    <button type="button" @click="$emit('close')">Abbrechen</button>
                    <button type="submit">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
    name: 'CategoryForm',
    props: ['category'],
    data() {
        return {
            form: { name: '' }
        }
    },
    mounted() {
        if (this.category) {
            this.form = { ...this.category }
        }
    },
    methods: {
        async save() {
            try {
                if (this.category) {
                    await axios.put(generateUrl(`/apps/clubsuite-finance/categories/${this.category.id}`), this.form)
                } else {
                    await axios.post(generateUrl('/apps/clubsuite-finance/categories'), this.form)
                }
                this.$emit('saved')
            } catch (e) {
                console.error(e)
                alert('Fehler beim Speichern')
            }
        }
    }
}
</script>

<style scoped>
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; }
.modal { background: white; padding: 20px; border-radius: 5px; min-width: 300px; }
.form-group { margin-bottom: 10px; display: flex; flex-direction: column; }
.actions { margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px; }
</style>
