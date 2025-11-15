<script setup>
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

// Form data
const form = ref({
    region: '',
    province: '',
    municipality: '',
    barangay: '',
    keyword: '',
});

// PSA data
const regions = ref([]);
const provinces = ref([]);
const municipalities = ref([]);
const barangays = ref([]);

// Dropdown loading states
const provinceDisabled = ref(true);
const municipalityDisabled = ref(true);
const barangayDisabled = ref(true);

// Results from Laravel
const props = defineProps({
    results: Array,
});

// PSA base URL
const PSA_API = 'https://psgc.gitlab.io/api';

// Load Regions on page load
const loadRegions = async () => {
    const { data } = await axios.get(`${PSA_API}/regions/`);
    regions.value = data;
};

// Load Provinces
const loadProvinces = async (regionCode) => {
    const { data } = await axios.get(`${PSA_API}/regions/${regionCode}/provinces/`);
    provinces.value = data;
};

// Load Municipalities
const loadMunicipalities = async (provinceCode) => {
    const { data } = await axios.get(`${PSA_API}/provinces/${provinceCode}/cities-municipalities/`);
    municipalities.value = data;
};

// Load Barangays
const loadBarangays = async (municipalityCode) => {
    const { data } = await axios.get(`${PSA_API}/cities-municipalities/${municipalityCode}/barangays/`);
    barangays.value = data;
};

// Event handlers
const onRegionChange = async () => {
    const region = regions.value.find((r) => r.name === form.value.region);
    if (!region) return;
    form.value.province = '';
    form.value.municipality = '';
    form.value.barangay = '';
    provinces.value = [];
    municipalities.value = [];
    barangays.value = [];
    provinceDisabled.value = false;
    municipalityDisabled.value = true;
    barangayDisabled.value = true;
    await loadProvinces(region.code);
};

const onProvinceChange = async () => {
    const province = provinces.value.find((p) => p.name === form.value.province);
    if (!province) return;
    form.value.municipality = '';
    form.value.barangay = '';
    municipalities.value = [];
    barangays.value = [];
    municipalityDisabled.value = false;
    barangayDisabled.value = true;
    await loadMunicipalities(province.code);
};

const onMunicipalityChange = async () => {
    const muni = municipalities.value.find((m) => m.name === form.value.municipality);
    if (!muni) return;
    form.value.barangay = '';
    barangays.value = [];
    barangayDisabled.value = false;
    await loadBarangays(muni.code);
};

// Submit search
const submitSearch = () => {
    router.get('/reference-points/search', form.value);
};

onMounted(() => {
    loadRegions();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 px-6 py-10">
        <div class="mx-auto max-w-5xl rounded-2xl bg-white p-6 shadow-xl">
            <h1 class="mb-6 text-2xl font-bold text-blue-700">Reference Point Search</h1>

            <!-- Search Form -->
            <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-5">
                <select v-model="form.region" @change="onRegionChange" class="rounded border p-2">
                    <option value="">Select Region</option>
                    <option v-for="r in regions" :key="r.code" :value="r.name">{{ r.name }}</option>
                </select>

                <select v-model="form.province" @change="onProvinceChange" class="rounded border p-2" :disabled="provinceDisabled">
                    <option value="">Select Province</option>
                    <option v-for="p in provinces" :key="p.code" :value="p.name">{{ p.name }}</option>
                </select>

                <select v-model="form.municipality" @change="onMunicipalityChange" class="rounded border p-2" :disabled="municipalityDisabled">
                    <option value="">Select Municipality</option>
                    <option v-for="m in municipalities" :key="m.code" :value="m.name">{{ m.name }}</option>
                </select>

                <select v-model="form.barangay" class="rounded border p-2" :disabled="barangayDisabled">
                    <option value="">Select Barangay</option>
                    <option v-for="b in barangays" :key="b.code" :value="b.name">{{ b.name }}</option>
                </select>

                <input v-model="form.keyword" type="text" placeholder="Keyword (file name)" class="rounded border p-2" />
            </div>

            <button @click="submitSearch" class="mb-6 rounded-lg bg-blue-600 px-4 py-2 text-white transition-all hover:bg-blue-700">Search</button>

            <!-- Results -->
            <div v-if="props.results && props.results.length">
                <h2 class="mb-3 font-semibold text-gray-700">Results:</h2>
                <ul class="divide-y divide-gray-200">
                    <<li v-for="(item, index) in props.results" :key="index">
                        <strong>{{ item.file_name }}</strong>
                        <a :href="item.file_url" target="_blank" class="ml-2 text-blue-600 underline"> Open File </a>
                    </li>
                </ul>
            </div>

            <div v-else>
                <p class="text-gray-500">No files found.</p>
            </div>

            <p v-else class="text-gray-500">No files found.</p>
        </div>
    </div>
</template>
