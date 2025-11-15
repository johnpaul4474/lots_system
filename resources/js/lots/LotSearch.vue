<template>
    <div class="mx-auto max-w-5xl p-8">
        <!-- Search Card -->
        <div class="mx-auto mb-6 w-full max-w-7xl rounded-2xl bg-gray-200 p-6 shadow-lg">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">🔍 Search Lot</h2>

            <!-- Filters Form -->
            <form @submit.prevent="searchLots" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 ">
                <!-- Row 1 -->
                <select
                    v-model="filters.region_id"
                    @change="fetchProvinces"
                    class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 bg-white"
                >
                    <option value="">Select Region</option>
                    <option v-for="region in regions" :key="region.id" :value="region.id">
                        {{ region.name }}
                    </option>
                </select>

                <select
                    v-model="filters.province_id"
                    @change="fetchMunicipalities"
                    class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 bg-white"
                >
                    <option value="">Select Province</option>
                    <option v-for="province in provinces" :key="province.id" :value="province.id">
                        {{ province.name }}
                    </option>
                </select>

                <select
                    v-model="filters.municipality_id"
                    @change="fetchBarangays"
                    class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 bg-white"
                >
                    <option value="">Select Municipality</option>
                    <option v-for="mun in municipalities" :key="mun.id" :value="mun.id">
                        {{ mun.name }}
                    </option>
                </select>

                <!-- Row 2 -->
                <select v-model="filters.barangay_id" class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Select Barangay</option>
                    <option v-for="brgy in barangays" :key="brgy.id" :value="brgy.id">
                        {{ brgy.name }}
                    </option>
                </select>

                <input
                    type="text"
                    v-model="filters.lot_number"
                    placeholder="Enter Lot Number"
                    class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 bg-white"
                />

                <!-- Buttons (full width) -->
                <div class="flex w-full space-x-2">
                    <button type="submit" class="w-1/2 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">Search</button>
                    <button
                        type="button"
                        @click="resetFilters"
                        class="w-1/2 rounded-lg bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
                    >
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div v-if="searched" class="mb-6 text-center">
            <p v-if="lot" class="inline-block rounded-lg bg-green-100 px-4 py-2 font-semibold text-green-700">✅ Lot exists</p>
            <p v-else class="inline-block rounded-lg bg-red-100 px-4 py-2 font-semibold text-red-700">❌ Lot doesn’t exist</p>
        </div>

        <!-- View button -->
        <div v-if="lot" class="text-center">
            <button @click="showModal = true" class="rounded-lg bg-green-600 px-6 py-2 text-white shadow-md transition hover:bg-green-700">
                View Lot
            </button>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black">
            <div class="relative w-96 rounded-2xl bg-white p-6 shadow-lg">
                <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" @click="showModal = false">✖</button>

                <h2 class="mb-4 text-xl font-bold text-gray-800">💰 Payment Required</h2>
                <p class="mb-2 text-gray-700">Please pay the corresponding amount:</p>
                <ul class="mb-4 list-inside list-disc text-gray-800">
                    <li>Lot LDC: <span class="font-bold">100 Php</span></li>
                    <li>Lot Map: <span class="font-bold">150 Php</span></li>
                </ul>

                <input
                    v-model="officialReceipt"
                    type="text"
                    placeholder="Enter Official Receipt No."
                    class="mb-4 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                />

                <div class="flex justify-between">
                    <button
                        :disabled="!officialReceipt"
                        class="mr-2 flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:opacity-50"
                        @click="viewFile('ldc')"
                    >
                        View LDC
                    </button>

                    <button
                        :disabled="!officialReceipt"
                        class="ml-2 flex-1 rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700 disabled:opacity-50"
                        @click="viewFile('map')"
                    >
                        View Map
                    </button>
                </div>

                <button class="mt-6 w-full rounded-lg bg-red-100 py-2 text-red-600 transition hover:bg-red-200" @click="showModal = false">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

interface Region {
    id: number;
    name: string;
}
interface Province {
    id: number;
    name: string;
    region_id: number;
}
interface Municipality {
    id: number;
    name: string;
    province_id: number;
}
interface Barangay {
    id: number;
    name: string;
    municipality_id: number;
}
interface Lot {
    id: number;
    lot_number: string;
    barangay: {
        id: number;
        name: string;
        municipality: {
            id: number;
            name: string;
            province: {
                id: number;
                name: string;
                region: { id: number; name: string };
            };
        };
    };
    lot_ldc: { file_path: string; file_name: string };
    lot_map: { file_path: string; file_name: string };
}

// Dropdowns
const regions = ref<Region[]>([]);
const provinces = ref<Province[]>([]);
const municipalities = ref<Municipality[]>([]);
const barangays = ref<Barangay[]>([]);

// Lots
const lots = ref<Lot[]>([]);

// Blob URLs
const lotPdfUrls = ref<Record<number, string>>({});
const lotMapUrls = ref<Record<number, string>>({});

const lot = ref<Lot | null>(null);
const searched = ref(false);
const showModal = ref(false);
const officialReceipt = ref('');

// Filters
const filters = ref({
    lot_number: '',
    region_id: 16, // default: Cordillera
    province_id: 3, // default: Benguet
    municipality_id: 1, // default: Baguio City
    barangay_id: 87, // default: Quirino Hill
});

const fetchLotPdf = async (lotId: number) => {
    if (lotPdfUrls.value[lotId]) return;
    const res = await axios.get(`/lot_ldc/${lotId}`, { responseType: 'blob' });
    lotPdfUrls.value[lotId] = URL.createObjectURL(res.data);
};

// Fetch PNG blob for a lot
const fetchLotMap = async (lotId: number) => {
    if (lotMapUrls.value[lotId]) return;
    const res = await axios.get(`/lot_map/${lotId}`, { responseType: 'blob' });
    lotMapUrls.value[lotId] = URL.createObjectURL(res.data);
};

// const fetchAllBlobs = async () => {
//     lotPdfUrls.value = {};
//     lotMapUrls.value = {};
//     await Promise.all(
//         lots.value.map(async (lot) => {
//             if (lot.lot_ldc) await fetchLotPdf(lot.id);
//             if (lot.lot_map) await fetchLotMap(lot.id);
//         }),
//     );
// };

// Fetch endpoints
const fetchRegions = async () => {
    const res = await axios.get('/regions');
    regions.value = res.data;
};

const fetchProvinces = async () => {
    if (!filters.value.region_id) return;
    const res = await axios.get(`/provinces?region_id=${filters.value.region_id}`);
    provinces.value = res.data;
};

const fetchMunicipalities = async () => {
    if (!filters.value.province_id) return;
    const res = await axios.get(`/municipalities?province_id=${filters.value.province_id}`);
    municipalities.value = res.data;
};

const fetchBarangays = async () => {
    if (!filters.value.municipality_id) return;
    const res = await axios.get(`/barangays?municipality_id=${filters.value.municipality_id}`);
    barangays.value = res.data;
};

// Search lots
const searchLots = async () => {
    const res = await axios.get('/lots/search', { params: filters.value });
    lots.value = res.data;
    lot.value = lots.value.length > 0 ? lots.value[0] : null; // Set the found lot
    searched.value = true; // Ensure this is set so the result message and button show


};

// Reset filters
const resetFilters = () => {
    filters.value = { lot_number: '', region_id: 16, province_id: 3, municipality_id: 1, barangay_id: 87 };
    filters.value.lot_number = '';
    filters.value.region_id = 16; // default: Cordillera
    filters.value.province_id = 3; // default: Benguet
    filters.value.municipality_id = 1; // default: Baguio City
    filters.value.barangay_id = 87; // default: Quirino Hill

    lot.value = null; // clear current lot
    lots.value = []; // clear results
    searched.value = false; // reset searched flag
    //searchLots();
};

// const viewFile = (type: string) => {
   
    
//     if (!lot.value) return;
//     if (type === 'ldc' && lot.value.lot_ldc) {
//          console.log('type:', type);
//         console.log('lot.value:', lot.value);       
        
//         console.log('lot_map:', lot.value?.lot_map);
//         window.open(`${lot.value.lot_ldc.file_path}`, '_blank');
//     }
//     if (type === 'map' && lot.value.lot_map) {
//         window.open(`${lot.value.lot_map.file_path}`, '_blank');
//     }
// };

const viewFile = async (type: string) => {
    // If no lot is selected, exit the function
    if (!lot.value) {
        console.error('No lot data available.');
        return;
    }

    // Check if the type is 'ldc' and the lot_ldc_url property exists
    if (type === 'ldc' && lot.value.lot_ldc_url) {
        console.log('type:', type);
        console.log('lot.value:', lot.value);
        console.log('url:', lot.value.lot_ldc_url);

        // Open the URL directly from the top-level property
        window.open(lot.value.lot_ldc_url, '_blank');
    }

    // You can apply the same logic for the lot map
    if (type === 'map' && lot.value.lot_map_url) {
        console.log('type:', type);
        console.log('lot.value:', lot.value);
        console.log('url:', lot.value.lot_map_url);

        window.open(lot.value.lot_map_url, '_blank');
    }
};
// Cascading watchers
watch(
    () => filters.value.region_id,
    async (val) => {
        filters.value.province_id = 0;
        filters.value.municipality_id = 0;
        filters.value.barangay_id = 0;
        provinces.value = [];
        municipalities.value = [];
        barangays.value = [];
        await fetchProvinces();
    },
);

watch(
    () => filters.value.province_id,
    async () => {
        filters.value.municipality_id = 0;
        filters.value.barangay_id = 0;
        municipalities.value = [];
        barangays.value = [];
        await fetchMunicipalities();
    },
);

watch(
    () => filters.value.municipality_id,
    async () => {
        filters.value.barangay_id = 0;
        barangays.value = [];
        await fetchBarangays();
    },
);

// Initialize
onMounted(async () => {
    await fetchRegions();
    await fetchProvinces();
    await fetchMunicipalities();
    await fetchBarangays();
    //searchLots();
});
</script>

<style scoped>
table th,
table td {
    text-align: center;
}
</style>
