<template>
  <NcContent app-name="clubsuite-finance">
    <NcAppNavigation>
      <NcAppNavigationItem
        name="Konten"
        :active="activeTab === 'accounts'"
        @click="activeTab = 'accounts'"
      >
        <template #icon>
            <BankIcon :size="20" />
        </template>
      </NcAppNavigationItem>

      <NcAppNavigationItem
        name="Kategorien"
        :active="activeTab === 'categories'"
        @click="activeTab = 'categories'"
      >
        <template #icon>
            <TagIcon :size="20" />
        </template>
      </NcAppNavigationItem>

      <NcAppNavigationItem
        name="Buchungen"
        :active="activeTab === 'transactions'"
        @click="activeTab = 'transactions'"
      >
        <template #icon>
            <CashMultipleIcon :size="20" />
        </template>
      </NcAppNavigationItem>

       <NcAppNavigationItem
        name="SEPA Export"
        :active="activeTab === 'sepa'"
        @click="activeTab = 'sepa'"
      >
        <template #icon>
            <DownloadIcon :size="20" />
        </template>
      </NcAppNavigationItem>
    </NcAppNavigation>

    <NcAppContent>
        <Accounts v-if="activeTab === 'accounts'" />
        <Categories v-if="activeTab === 'categories'" />
        <Transactions v-if="activeTab === 'transactions'" />
        <SepaExport v-if="activeTab === 'sepa'" />
    </NcAppContent>
  </NcContent>
</template>

<script>
import { NcContent, NcAppNavigation, NcAppNavigationItem, NcAppContent } from '@nextcloud/vue'
import BankIcon from 'vue-material-design-icons/Bank.vue'
import TagIcon from 'vue-material-design-icons/Tag.vue'
import CashMultipleIcon from 'vue-material-design-icons/CashMultiple.vue'
import DownloadIcon from 'vue-material-design-icons/Download.vue'

import Accounts from './views/Accounts.vue'
import Categories from './views/Categories.vue'
import Transactions from './views/Transactions.vue'
import SepaExport from './views/SepaExport.vue'

export default {
    name: 'App',
    components: {
        NcContent,
        NcAppNavigation,
        NcAppNavigationItem,
        NcAppContent,
        BankIcon,
        TagIcon,
        CashMultipleIcon,
        DownloadIcon,
        Accounts,
        Categories,
        Transactions,
        SepaExport
    },
    data() {
        return {
            activeTab: 'transactions' // Start with transactions
        }
    }
}
</script>
