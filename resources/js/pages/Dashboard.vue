<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import LotSearch from '../lots/LotSearch.vue';
const regions: any[] = [];
const provinces: any[] = [];
const municipalities: any[] = [];
const barangays: any[] = [];
const defaultIds = {
    region: 0,
    province: 0,
    municipality: 0,
    barangay: 0,
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Geodetic Lots System',
        href: dashboard().url,
    },

    
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <LotSearch
                    :regions="regions"
                    :provinces="provinces"
                    :municipalities="municipalities"
                    :barangays="barangays"
                    :defaultIds="defaultIds"
                />            
        </div>
    </AppLayout>
</template>
