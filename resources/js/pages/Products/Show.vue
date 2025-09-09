<script setup lang="ts">
import { computed } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import type { Product } from '../../types'

// Props
const props = defineProps<{
  product: Product & {
    categories?: Array<{
      id: number
      name: string
      slug: string
    }>
  }
}>()

// Computed properties
const formattedPrice = computed(() => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(Number(props.product.price))
})

const stockStatus = computed(() => {
  const stock = props.product.stock
  if (stock === 0) return { text: 'Out of Stock', class: 'bg-red-100 text-red-800' }
  if (stock < 10) return { text: 'Low Stock', class: 'bg-yellow-100 text-yellow-800' }
  return { text: 'In Stock', class: 'bg-green-100 text-green-800' }
})

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<template>
  <div>
    <!-- <Head :title="product.name" /> -->
    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <Link 
                href="/products" 
                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
              >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Products
              </Link>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ product.name }}</span>
              </div>
            </li>
          </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
          <!-- Image section -->
          <div class="flex flex-col-reverse">
            <div class="mt-6 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
              <div class="grid grid-cols-4 gap-6" aria-orientation="horizontal">
                <!-- Thumbnail images placeholder -->
                <button 
                  v-for="i in 4" 
                  :key="i"
                  class="relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-indigo-500"
                >
                  <span class="sr-only">Image {{ i }}</span>
                  <span class="absolute inset-0 rounded-md overflow-hidden">
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                  </span>
                </button>
              </div>
            </div>

            <div class="w-full aspect-w-1 aspect-h-1">
              <div class="w-full h-80 sm:h-96 bg-white rounded-lg overflow-hidden flex items-center justify-center">
                <div v-if="product.image" class="w-full h-full">
                  <img 
                    :src="product.image" 
                    :alt="product.name"
                    class="w-full h-full object-center object-cover"
                  />
                </div>
                <div v-else class="flex flex-col items-center justify-center text-gray-400">
                  <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <p class="text-lg font-medium">No Image Available</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Product info -->
          <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ product.name }}</h1>

            <div class="mt-3">
              <h2 class="sr-only">Product information</h2>
              <p class="text-3xl tracking-tight text-gray-900 dark:text-white">{{ formattedPrice }}</p>
            </div>

            <!-- Stock status -->
            <div class="mt-4">
              <span 
                :class="stockStatus.class"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ stockStatus.text }}
              </span>
              <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                {{ product.stock }} units available
              </span>
            </div>

            <!-- Categories -->
            <div v-if="product.categories && product.categories.length > 0" class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Categories</h3>
              <div class="flex flex-wrap gap-2 mt-2">
                <span 
                  v-for="category in product.categories" 
                  :key="category.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200"
                >
                  {{ category.name }}
                </span>
              </div>
            </div>

            <!-- Status -->
            <div class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</h3>
              <div class="mt-2">
                <span 
                  :class="product.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                >
                  {{ product.active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>

            <!-- SKU -->
            <div class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">SKU</h3>
              <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ product.sku || 'N/A' }}</p>
            </div>

            <!-- Description -->
            <div class="mt-6">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">Description</h3>
              <div class="mt-3">
                <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                  {{ product.description || 'No description available.' }}
                </p>
              </div>
            </div>

            <!-- Product Details -->
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Product Details</h3>
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(product.created_at) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(product.updated_at) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Product ID</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">#{{ product.id }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ product.slug }}</dd>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4">
              <Link
                :href="`/products/${product.id}/edit`"
                class="flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Product
              </Link>
              
              <Link
                href="/products"
                class="flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.aspect-w-1 {
  position: relative;
  padding-bottom: 100%;
}

.aspect-h-1 {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
</style>