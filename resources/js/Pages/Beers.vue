<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <template v-for="beer in beers" :key="beer.id">
                <span>{{beer.name}}</span>
            </template>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
defineProps(["beers"])
</script>

