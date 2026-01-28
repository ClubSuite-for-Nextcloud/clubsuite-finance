<template>
  <div class="member-search">
    <div class="search-input-wrapper">
        <span class="icon"><AccountSearchIcon :size="20"/></span>
        <input 
            type="text" 
            v-model="query" 
            @input="onInput" 
            class="form-control" 
            placeholder="Mitglied suchen..." 
            @focus="showResults = true"
        />
        <span v-if="loading" class="loading-spinner">...</span>
    </div>
    
    <ul v-if="showResults && results.length > 0" class="search-results">
        <li v-for="m in results" :key="m.user_id" @click="selectMember(m)">
            {{ m.firstname }} {{ m.lastname }} ({{ m.mitgliedsnummer || 'Keine Nr.' }})
        </li>
    </ul>
    <div v-if="showResults && results.length === 0 && query.length > 2 && !loading" class="no-results">
        Keine Mitglieder gefunden
    </div>
  </div>
</template>

<script>
/**
 * Member Search Component
 * © 2026 Stefan Schulz – Alle Rechte vorbehalten.
 */
import AccountSearchIcon from 'vue-material-design-icons/AccountSearch.vue'

export default {
    name: 'MemberSearch',
    components: { AccountSearchIcon },
    props: {
        initialMemberId: { type: String, default: null },
        initialMemberName: { type: String, default: null }
    },
    data() {
        return {
            query: this.initialMemberName || '',
            results: [],
            showResults: false,
            loading: false,
            timeout: null
        }
    },
    watch: {
        initialMemberId: {
            immediate: true,
            handler(newVal) {
                if (newVal && !this.initialMemberName) {
                    this.fetchMemberDetails(newVal)
                }
            }
        },
        initialMemberName(newVal) {
            if (newVal) this.query = newVal
        }
    },
    methods: {
        onInput() {
            if (this.timeout) clearTimeout(this.timeout)
            this.showResults = false
            if (this.query.length === 0) {
                 this.$emit('selected', null)
                 return
            }
            this.timeout = setTimeout(this.search, 300)
        },
        async search() {
            if (this.query.length < 2) {
                this.results = []
                return
            }
            this.loading = true
            try {
                const url = OC.generateUrl('/apps/clubsuite-core/members/search') + '?q=' + encodeURIComponent(this.query)
                const res = await fetch(url, {
                    headers: { 'requesttoken': OC.requestToken }
                })
                if (res.ok) {
                    const data = await res.json()
                    this.results = data
                    this.showResults = true
                }
            } catch (e) {
                console.error(e)
            } finally {
                this.loading = false
            }
        },
        async fetchMemberDetails(id) {
            this.loading = true
            try {
                const url = OC.generateUrl('/apps/clubsuite-core/members/' + id)
                const res = await fetch(url, {
                    headers: { 'requesttoken': OC.requestToken }
                })
                if (res.ok) {
                    const data = await res.json()
                    this.query = `${data.firstname} ${data.lastname}`
                }
            } catch (e) {
                console.error(e)
            } finally {
                this.loading = false
            }
        },
        selectMember(member) {
            this.query = `${member.firstname} ${member.lastname}`
            this.showResults = false
            this.$emit('selected', member.user_id)
        }
    }
}
</script>

<style scoped>
.member-search { position: relative; margin-bottom: 10px; }
.search-input-wrapper { display: flex; align-items: center; position: relative; }
.search-input-wrapper .icon { position: absolute; left: 8px; color: #888; display: flex; align-items: center; pointer-events: none;}
.search-input-wrapper input { padding-left: 35px; width: 100%; box-sizing: border-box; }
.loading-spinner { position: absolute; right: 8px; font-size: 0.8em; }

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--color-main-background);
    border: 1px solid var(--color-border);
    border-radius: 0 0 3px 3px;
    list-style: none;
    padding: 0;
    margin: 0;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-height: 200px;
    overflow-y: auto;
}
.search-results li {
    padding: 8px 10px;
    cursor: pointer;
    border-bottom: 1px solid var(--color-border);
    color: var(--color-main-text);
}
.search-results li:last-child { border-bottom: none; }
.search-results li:hover { background: var(--color-background-hover); }
.no-results {
    position: absolute; top: 100%; left: 0; right: 0;
    padding: 10px; background: var(--color-main-background); border: 1px solid var(--color-border); font-style: italic;
    color: var(--color-text-maxcontrast);
    z-index: 1000;
}
</style>
