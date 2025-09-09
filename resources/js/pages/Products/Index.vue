<script setup lang="ts">
import { ref, computed } from 'vue'
import type { Product } from '../../types'
import { Link } from '@inertiajs/vue3'
// Props
const props = defineProps<{
  products: Array<Product>
  totalProducts: number
}>()

// Pagination
const currentPage = ref(1)
const perPage = 10

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return props.products.slice(start, start + perPage)
})

const totalPages = computed(() => {
  const total = Number(props.totalProducts ?? props.products.length)
  return Math.max(1, Math.ceil(total / perPage))
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++
}

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--
}

// Action Handlers
const viewProduct = (id: number) => {
  console.log('View', id)
}

const editProduct = (id: number) => {
  console.log('Edit', id)
}

const deleteProduct = (id: number) => {
  if (confirm('Are you sure you want to delete this product?')) {
    console.log('Delete', id)
  }
}

const createProduct = () => {
  window.location.href = '/products'
}

</script>

<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Products Dashboard</h1>
      <Link
      :href="route('products.create')"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow"
    >
      Create Product
    </Link>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr
            v-for="(product, index) in paginatedProducts"
            :key="product.id"
            class="hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              {{ (currentPage - 1) * perPage + index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ product.sku ?? 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">${{ Number(product.price).toFixed(2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ product.stock }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="product.active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'"
              >
                {{ product.active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
              <button
                @click="viewProduct(product.id)"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded text-sm"
              >
                View
              </button>
              <button
                @click="editProduct(product.id)"
                class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-sm"
              >
                Edit
              </button>
              <button
                @click="deleteProduct(product.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="paginatedProducts.length === 0">
            <td colspan="7" class="text-center py-4 text-gray-500">No products found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-end mt-4 space-x-2">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50"
      >
        Previous
      </button>
      <button
        v-for="page in totalPages"
        :key="page"
        @click="currentPage = page"
        :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white'"
        class="px-3 py-1 rounded"
      >
        {{ page }}
      </button>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50"
      >
        Next
      </button>
    </div>
  </div>
</template>
