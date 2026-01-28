<template>
    <div class="modal-overlay">
        <div class="modal">
            <h3>{{ transaction ? 'Buchung bearbeiten' : 'Neue Buchung' }}</h3>
            <form @submit.prevent="save">
                <div class="form-group">
                    <label>Datum</label>
                    <input type="date" v-model="form.date" required />
                </div>
                <div class="form-group">
                    <label>Betrag (Euro)</label>
                    <input type="number" step="0.01" v-model="amountEur" required />
                </div>
                <div class="form-group">
                    <label>Zweck</label>
                    <input v-model="form.purpose" />
                </div>
                <div class="form-group">
                    <label>Konto</label>
                    <select v-model="form.accountId" required>
                        <option v-for="acc in accounts" :key="acc.id" :value="acc.id">{{ acc.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kategorie</label>
                    <select v-model="form.categoryId">
                        <option :value="null">- Keine -</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <!-- Simple Member ID input for MVP -->
                <div class="form-group">
                    <label>Mitglied ID (Optional)</label>
                    <input type="number" v-model.number="form.memberId" />
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
    name: 'TransactionForm',
    props: ['transaction', 'accounts', 'categories'],
    data() {
        return {
            form: {
                date: new Date().toISOString().slice(0, 10),
                amount: 0,
                purpose: '',
                accountId: null,
                categoryId: null,
                memberId: null
            },
            amountEur: 0
        }
    },
    mounted() {
        if (this.transaction) {
            this.form = { ...this.transaction }
            this.amountEur = (this.transaction.amount / 100).toFixed(2)
        }
    },
    methods: {
        async save() {
            try {
                // Convert back to cents
                this.form.amount = Math.round(parseFloat(this.amountEur) * 100)
                
                if (this.transaction) {
                    await axios.put(generateUrl(`/apps/clubsuite-finance/transactions/${this.transaction.id}`), this.form)
                } else {
                    await axios.post(generateUrl('/apps/clubsuite-finance/transactions'), this.form)
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
.modal { background: white; padding: 20px; border-radius: 5px; min-width: 400px; }
.form-group { margin-bottom: 10px; display: flex; flex-direction: column; }
.actions { margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px; }
</style>
