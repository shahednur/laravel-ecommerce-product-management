<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps<{
  products: Array<{
    id: number
    name: string
    slug: string
    sku: string | null
    price: number
    stock: number
    description: string | null
    active: boolean
    image: string | null
    categories: Array<{ id: number; name: string }>
  }>
}>()
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Products</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="product in products"
        :key="product.id"
        class="p-4 border rounded-lg shadow bg-white dark:bg-gray-800"
      >
        <!-- Product Image -->
        <div v-if="product.image" class="mb-4">
          <img
            :src="product.image ? `/storage/${product.image}` : 'https://via.placeholder.com/300x200?text=No+Image'"
            :alt="product.name"
            class="w-full h-48 object-cover rounded"
          />
        </div>
        <div v-else class="mb-4 w-full h-48 bg-gray-200 rounded flex items-center justify-center text-gray-500">
          No Image
        </div>

        <h2 class="text-lg font-semibold">{{ product.name }}</h2>
        <p class="text-gray-500 text-sm">SKU: {{ product.sku ?? 'N/A' }}</p>
        <p class="text-gray-700 font-bold mt-2">${{ product.price }}</p>
        <p class="text-gray-600 mt-2">{{ product.description }}</p>

        <div class="mt-3">
          <span
            v-for="category in product.categories"
            :key="category.id"
            class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2"
          >
            {{ category.name }}
          </span>
        </div>

        <p
          class="mt-4 text-sm font-medium"
          :class="product.active ? 'text-green-600' : 'text-red-600'"
        >
          {{ product.active ? 'Active' : 'Inactive' }}
        </p>
      </div>
    </div>
  </div>
</template>
