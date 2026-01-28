<template>
    <div class="transactions-view">
        <div class="header">
            <h2>Buchungen</h2>
            <button @click="showForm = true">Neue Buchung</button>
        </div>

        <div v-if="loading">Laden...</div>
        <table v-else>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Betrag</th>
                    <th>Zweck</th>
                    <th>Konto</th>
                    <th>Kategorie</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tx in transactions" :key="tx.id">
                    <td>{{ tx.date }}</td>
                    <td>{{ formatAmount(tx.amount) }}</td>
                    <td>{{ tx.purpose }}</td>
                    <td>{{ getAccountName(tx.accountId) }}</td>
                    <td>{{ getCategoryName(tx.categoryId) }}</td>
                    <td>
                        <button @click="edit(tx)">Bearbeiten</button>
                        <button @click="remove(tx.id)">Löschen</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <TransactionForm 
            v-if="showForm" 
            :transaction="selectedTransaction" 
            :accounts="accounts"
            :categories="categories"
            @saved="fetchData" 
            @close="closeForm" 
        />
    </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import TransactionForm from '../components/TransactionForm.vue'

export default {
    name: 'Transactions',
    components: { TransactionForm },
    data() {
        return {
            transactions: [],
            accounts: [],
            categories: [],
            loading: true,
            showForm: false,
            selectedTransaction: null
        }
    },
    async mounted() {
        await Promise.all([
            this.fetchAccounts(),
            this.fetchCategories()
        ])
        this.fetchData()
    },
    methods: {
        async fetchAccounts() {
            try {
                const response = await axios.get(generateUrl('/apps/clubsuite-finance/accounts'))
                this.accounts = response.data
            } catch(e) {}
        },
        async fetchCategories() {
            try {
                const response = await axios.get(generateUrl('/apps/clubsuite-finance/categories'))
                this.categories = response.data
            } catch(e) {}
        },
        async fetchData() {
            this.loading = true
            try {
                const response = await axios.get(generateUrl('/apps/clubsuite-finance/transactions'))
                this.transactions = response.data
            } catch (e) {
                console.error(e)
            } finally {
                this.loading = false
                this.closeForm()
            }
        },
        getAccountName(id) {
            const acc = this.accounts.find(a => a.id === id)
            return acc ? acc.name : id
        },
        getCategoryName(id) {
            const cat = this.categories.find(c => c.id === id)
            return cat ? cat.name : '-'
        },
        formatAmount(cents) {
            return (cents / 100).toFixed(2) + ' €'
        },
        edit(tx) {
            this.selectedTransaction = tx
            this.showForm = true
        },
        async remove(id) {
            if (!confirm('Wirklich löschen?')) return
            try {
                await axios.delete(generateUrl(`/apps/clubsuite-finance/transactions/${id}`))
                this.fetchData()
            } catch (e) {
                console.error(e)
                alert('Fehler beim Löschen')
            }
        },
        closeForm() {
            this.showForm = false
            this.selectedTransaction = null
        }
    }
}
</script>
