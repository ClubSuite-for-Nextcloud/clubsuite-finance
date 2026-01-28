<template>
    <div class="modal-overlay">
        <div class="modal">
            <h3>{{ account ? 'Konto bearbeiten' : 'Neues Konto' }}</h3>
            <form @submit.prevent="save">
                <div class="form-group">
                    <label>Name</label>
                    <input v-model="form.name" required />
                </div>
                <div class="form-group">
                    <label>Typ</label>
                    <select v-model="form.type" required>
                        <option value="asset">Vermögen (Asset)</option>
                        <option value="liability">Verbindlichkeit (Liability)</option>
                    </select>
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
    name: 'AccountForm',
    props: ['account'],
    data() {
        return {
            form: {
                name: '',
                type: 'asset'
            }
        }
    },
    mounted() {
        if (this.account) {
            this.form = { ...this.account }
        }
    },
    methods: {
        async save() {
            try {
                if (this.account) {
                    await axios.put(generateUrl(`/apps/clubsuite-finance/accounts/${this.account.id}`), this.form)
                } else {
                    await axios.post(generateUrl('/apps/clubsuite-finance/accounts'), this.form)
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
