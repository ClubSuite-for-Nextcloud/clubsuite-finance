<template>
    <div class="sepa-export-view">
        <h2>SEPA Export</h2>
        <p>Exportieren Sie alle Buchungen im SEPA Credit Transfer (pain.001) Format.</p>
        <button @click="downloadXml" :disabled="loading">
            {{ loading ? 'Generiere...' : 'XML Generieren & Herunterladen' }}
        </button>
    </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
    name: 'SepaExport',
    data() {
        return {
            loading: false
        }
    },
    methods: {
        async downloadXml() {
            this.loading = true
            const url = generateUrl('/apps/clubsuite-finance/sepa/export')
            try {
                // Trigger download via window location or hidden form. 
                // Using axios allows auth header, but handling blob download is tricky.
                // Standard NC way: window.location works if cookie auth works (standard).
                // Or create link.
                
                // Let's use fetchor axios. Since the controller returns DataResponse, it's JSON.
                const response = await axios.post(url)
                
                // DataResponse wraps content in "data". Axios wraps body in "data".
                // So the XML string is in response.data (if NC API unwraps) or response.data.data?
                // Usually NC DataResponse: { "data": payload, "status": "success" }
                // Axios response.data = { "data": "<?xml...", "status": "success" }
                
                const xmlContent = response.data.data || response.data
                
                const blob = new Blob([xmlContent], { type: 'application/xml' })
                const link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'sepa-export.xml'
                link.click()
            } catch (e) {
                console.error(e)
                alert('Fehler beim Export')
            } finally {
                this.loading = false
            }
        }
    }
}
</script>
