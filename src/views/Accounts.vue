<template>
    <div class="accounts-view">
        <div class="header">
            <h2>Konten</h2>
            <button @click="showForm = true">Neues Konto</button>
        </div>

        <div v-if="loading">Laden...</div>
        <table v-else>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Typ</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="account in accounts" :key="account.id">
                    <td>{{ account.name }}</td>
                    <td>{{ account.type }}</td>
                    <td>
                        <button @click="edit(account)">Bearbeiten</button>
                        <button @click="remove(account.id)">Löschen</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <AccountForm 
            v-if="showForm" 
            :account="selectedAccount" 
            @saved="fetchData" 
            @close="closeForm" 
        />
    </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import AccountForm from '../components/AccountForm.vue'

export default {
    name: 'Accounts',
    components: { AccountForm },
    data() {
        return {
            accounts: [],
            loading: true,
            showForm: false,
            selectedAccount: null
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.loading = true
            try {
                const response = await axios.get(generateUrl('/apps/clubsuite-finance/accounts'))
                this.accounts = response.data
            } catch (e) {
                console.error(e)
                // alert('Fehler beim Laden')
            } finally {
                this.loading = false
                this.closeForm()
            }
        },
        edit(account) {
            this.selectedAccount = account
            this.showForm = true
        },
        async remove(id) {
            if (!confirm('Wirklich löschen?')) return
            try {
                await axios.delete(generateUrl(`/apps/clubsuite-finance/accounts/${id}`))
                this.fetchData()
            } catch (e) {
                console.error(e)
                alert('Fehler beim Löschen')
            }
        },
        closeForm() {
            this.showForm = false
            this.selectedAccount = null
        }
    }
}
</script>
