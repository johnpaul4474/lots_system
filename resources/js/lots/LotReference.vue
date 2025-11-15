<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const filters = ref({
  region_id: '',
  province_id: '',
  municipality_id: '',
  barangay_id: '',
  lot_reference: '',
})

const regions = ref<any[]>([])
const provinces = ref<any[]>([])
const municipalities = ref<any[]>([])
const barangays = ref<any[]>([])
const lots = ref<any[]>([])
const lot = ref<any | null>(null)
const searched = ref(false)

// Fetch regions on load
onMounted(async () => {
  const res = await axios.get('/psa/regions')
  regions.value = res.data
})

// Fetch provinces when region changes
watch(() => filters.value.region_id, async (val) => {
  if (!val) return
  const res = await axios.get(`/psa/provinces/${val}`)
  provinces.value = res.data
  municipalities.value = []
  barangays.value = []
  filters.value.province_id = ''
  filters.value.municipality_id = ''
  filters.value.barangay_id = ''
})

// Fetch municipalities when province changes
watch(() => filters.value.province_id, async (val) => {
  if (!val) return
  const res = await axios.get(`/psa/municipalities/${val}`)
  municipalities.value = res.data
  barangays.value = []
  filters.value.municipality_id = ''
  filters.value.barangay_id = ''
})

// Fetch barangays when municipality changes
watch(() => filters.value.municipality_id, async (val) => {
  if (!val) return
  const res = await axios.get(`/psa/barangays/${val}`)
  barangays.value = res.data
  filters.value.barangay_id = ''
})

// Search lots by reference
const searchLots = async () => {
  searched.value = true
  const res = await axios.get('/lots/search', { params: filters.value })
  lots.value = res.data
  lot.value = lots.value.length ? lots.value[0] : null
}

// Reset form
const resetFilters = () => {
  filters.value = { region_id: '', province_id: '', municipality_id: '', barangay_id: '', lot_reference: '' }
  lots.value = []
  lot.value = null
  searched.value = false
}
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 shadow-md rounded-xl space-y-4">
      <h2 class="text-xl font-semibold text-gray-700 mb-4">Search Lot by Reference</h2>

      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <select v-model="filters.region_id" class="border rounded-lg p-2">
          <option value="">Select Region</option>
          <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
        </select>

        <select v-model="filters.province_id" class="border rounded-lg p-2" :disabled="!filters.region_id">
          <option value="">Select Province</option>
          <option v-for="p in provinces" :key="p.id" :value="p.id">{{ p.name }}</option>
        </select>

        <select v-model="filters.municipality_id" class="border rounded-lg p-2" :disabled="!filters.province_id">
          <option value="">Select Municipality</option>
          <option v-for="m in municipalities" :key="m.id" :value="m.id">{{ m.name }}</option>
        </select>

        <select v-model="filters.barangay_id" class="border rounded-lg p-2" :disabled="!filters.municipality_id">
          <option value="">Select Barangay</option>
          <option v-for="b in barangays" :key="b.id" :value="b.id">{{ b.name }}</option>
        </select>

        <input
          type="text"
          v-model="filters.lot_reference"
          placeholder="Enter Lot Reference Number"
          class="border rounded-lg p-2 col-span-2"
        />
      </div>

      <div class="flex gap-3 mt-4">
        <button @click="searchLots" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Search</button>
        <button @click="resetFilters" class="bg-gray-300 px-4 py-2 rounded-lg">Reset</button>
      </div>

      <div v-if="searched" class="mt-6">
        <div v-if="lot" class="border rounded-lg p-4 bg-green-50">
          <h3 class="font-bold text-green-700">Lot Found:</h3>
          <p>Reference: {{ lot.lot_reference }}</p>
          <p>Location: {{ lot.barangay }}, {{ lot.municipality }}, {{ lot.province }}</p>
        </div>
        <div v-else class="text-red-600 font-semibold">No lot found.</div>
      </div>
    </div>
  </AppLayout>
</template>
