<script setup>
import { ref, onMounted } from 'vue'
const items = ref([])
const total = ref(0)
const limit = ref(25)
const offset = ref(0)
async function load(){
  const params = new URLSearchParams({ limit: String(limit.value), offset: String(offset.value) })
  const res = await fetch('/index.php/apps/clubsuite-finance/api/transactions?' + params.toString())
  const data = await res.json()
  items.value = data.rows
  total.value = data.total
}
function next(){ if (offset.value + limit.value < total.value) { offset.value += limit.value; load(); } }
function prev(){ if (offset.value >= limit.value) { offset.value -= limit.value; load(); } }
onMounted(load)
</script>
<template>
  <div>
    <button @click="prev">Prev</button>
    <button @click="next">Next</button>
    <span>Showing {{ items.length }} of {{ total }}</span>
    <ul>
      <li v-for="it in items" :key="it.id">{{ it.date }} — {{ it.amount }} {{ it.type }}</li>
    </ul>
  </div>
</template>
