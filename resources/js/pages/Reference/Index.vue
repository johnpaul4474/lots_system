<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';

const form = ref({
    region: '',
    province: '',
    municipality: '',
    barangay: '',
    keyword: '',
});

const regions = ref([]);
const provinces = ref([]);
const municipalities = ref([]);
const barangays = ref([]);
const files = ref([]);

const loading = ref({
    region: false,
    province: false,
    municipality: false,
    barangay: false,
    search: false,
});

// --- Fetch PSA data ---
const fetchRegions = async () => {
    loading.value.region = true;
    try {
        const { data } = await axios.get('/regions');
        regions.value = data; // assume array of { code, name }
    } catch (err) {
        console.error('Failed to fetch regions:', err);
    } finally {
        loading.value.region = false;
    }
};

const fetchProvinces = async (regionCode) => {
    if (!regionCode) return;
    loading.value.province = true;
    try {
        const { data } = await axios.get('/provinces', { params: { region: regionCode } });
        provinces.value = data;
    } catch (err) {
        console.error('Failed to fetch provinces:', err);
    } finally {
        loading.value.province = false;
    }
};

const fetchMunicipalities = async (provinceCode) => {
    if (!provinceCode) return;
    loading.value.municipality = true;
    try {
        const { data } = await axios.get('/municipalities', { params: { province: provinceCode } });
        municipalities.value = data;
    } catch (err) {
        console.error('Failed to fetch municipalities:', err);
    } finally {
        loading.value.municipality = false;
    }
};

const fetchBarangays = async (municipalityCode) => {
    if (!municipalityCode) return;
    loading.value.barangay = true;
    try {
        const { data } = await axios.get('/barangays', { params: { municipality: municipalityCode } });
        barangays.value = data;
    } catch (err) {
        console.error('Failed to fetch barangays:', err);
    } finally {
        loading.value.barangay = false;
    }
};

// On mount
onMounted(fetchRegions);

// Watch parent selections and reset children
watch(() => form.value.region, (newVal) => {
    form.value.province = '';
    form.value.municipality = '';
    form.value.barangay = '';
    provinces.value = [];
    municipalities.value = [];
    barangays.value = [];
    if (newVal) fetchProvinces(newVal);
});

watch(() => form.value.province, (newVal) => {
    form.value.municipality = '';
    form.value.barangay = '';
    municipalities.value = [];
    barangays.value = [];
    if (newVal) fetchMunicipalities(newVal);
});

watch(() => form.value.municipality, (newVal) => {
    form.value.barangay = '';
    barangays.value = [];
    if (newVal) fetchBarangays(newVal);
});

// --- Computed: disable search until at least one filter/keyword ---
const canSearch = computed(() => {
    return form.value.region || form.value.province || form.value.municipality || form.value.barangay || form.value.keyword.trim();
});

// Search files
const searchFiles = async () => {
    loading.value.search = true;
    try {
        const { data } = await axios.get('/reference-points/search', { params: form.value });
        if (form.value.keyword.trim() !== '') {
            files.value = data.filter(f =>
                f.toLowerCase().includes(form.value.keyword.trim().toLowerCase())
            );
        } else {
            files.value = data;
        }
    } catch (err) {
        console.error('Search failed', err);
        files.value = [];
    } finally {
        loading.value.search = false;
    }
};
</script>

<template>
  <AppLayout>
<div class="p-6 space-y-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800">Reference File Search</h1>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Region -->
        <div>
            <label class="font-semibold mb-1 block text-gray-700">Region</label>
            <select v-model="form.region" class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">{{ loading.region ? 'Loading Regions...' : 'Select Region' }}</option>
                <option v-for="r in regions" :key="r.code" :value="String(r.code)">
                    {{ r.name }}
                </option>
            </select>
        </div>

        <!-- Province -->
        <div>
            <label class="font-semibold mb-1 block text-gray-700">Province</label>
            <select v-model="form.province" :disabled="!form.region || loading.province"
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">{{ loading.province ? 'Loading Provinces...' : 'Select Province' }}</option>
                <option v-for="p in provinces" :key="p.code" :value="String(p.code)">
                    {{ p.name }}
                </option>
            </select>
        </div>

        <!-- Municipality -->
        <div>
            <label class="font-semibold mb-1 block text-gray-700">Municipality</label>
            <select v-model="form.municipality" :disabled="!form.province || loading.municipality"
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">{{ loading.municipality ? 'Loading Municipalities...' : 'Select Municipality' }}</option>
                <option v-for="m in municipalities" :key="m.code" :value="String(m.code)">
                    {{ m.name }}
                </option>
            </select>
        </div>

        <!-- Barangay -->
        <div>
            <label class="font-semibold mb-1 block text-gray-700">Barangay</label>
            <select v-model="form.barangay" :disabled="!form.municipality || loading.barangay"
                    class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">{{ loading.barangay ? 'Loading Barangays...' : 'Select Barangay' }}</option>
                <option v-for="b in barangays" :key="b.code" :value="String(b.code)">
                    {{ b.name }}
                </option>
            </select>
        </div>

        <!-- Keyword -->
        <div>
            <label class="font-semibold mb-1 block text-gray-700">Keyword</label>
            <input v-model="form.keyword" placeholder="Optional: BaguioReference"
                   class="w-full border rounded p-2 focus:ring focus:ring-blue-200" />
        </div>
    </div>

    <!-- Search Button -->
    <button @click="searchFiles"
            :disabled="!canSearch || loading.search"
            class="mt-4 px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 hover:shadow-lg transition-all cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center">
        <svg v-if="loading.search" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4l-3 3 3 3H4z"></path>
        </svg>
        Search
    </button>

    <!-- Results -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800">Results</h2>
        <div v-if="files.length === 0" class="text-gray-500 mt-2">No files found.</div>
        <ul v-else class="mt-2 space-y-2">
            <li v-for="f in files" :key="f" class="p-3 bg-gray-50 rounded shadow hover:bg-gray-100 transition-all">
                <a :href="`/references/${f}`" target="_blank" class="text-blue-600 underline font-medium">
                    {{ f }}
                </a>
            </li>
        </ul>
    </div>
</div>
</AppLayout>
</template>
