<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import type { Product } from '../../types'

// Props
const props = defineProps<{
  product: Product & {
    categories?: Array<{
      id: number
      name: string
      slug: string
      pivot?: any
    }>
  }
  categories: Array<{
    id: number
    name: string
  }>
}>()

// Form data
const form = useForm({
  name: props.product.name,
  sku: props.product.sku || '',
  price: props.product.price.toString(),
  stock: props.product.stock,
  description: props.product.description || '',
  active: props.product.active,
  categories: props.product.categories?.map(cat => cat.id) || [],
  image: null as File | null,
})

// Image preview
const imagePreview = ref<string | null>(props.product.image || null)
const imageInput = ref<HTMLInputElement | null>(null)

// Handle image selection
const handleImageSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    form.image = file
    
    // Create preview URL
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

// Remove image
const removeImage = () => {
  form.image = null
  imagePreview.value = null
  if (imageInput.value) {
    imageInput.value.value = ''
  }
}

// Submit form
const submitForm = () => {
  form.transform((data) => ({
    ...data,
    name: String(data.name),
    price: Number(data.price),
    stock: Number(data.stock),
    active: Boolean(data.active)
  })).put(`/products/${props.product.id}`, {
    forceFormData: true, // Important for file uploads
    preserveScroll: true,
    onSuccess: () => {
      console.log('✅ Product updated successfully!')
    },
    onError: (errors) => {
      console.error('❌ Validation failed:', errors)
    }
  })
}


// Computed properties
const stockStatus = computed(() => {
  const stock = Number(form.stock)
  if (stock === 0) return { text: 'Out of Stock', class: 'text-red-600' }
  if (stock < 10) return { text: 'Low Stock', class: 'text-yellow-600' }
  return { text: 'In Stock', class: 'text-green-600' }
})

const isFormDirty = computed(() => {
  return form.isDirty || form.image !== null
})
</script>

<template>
  <div>
    <Head :title="`Edit ${product.name}`" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
          <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link 
                  href="/products" 
                  class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                >
                  Products
                </Link>
              </li>
              <li>
                <div class="flex items-center">
                  <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  <Link 
                    :href="`/products/${product.id}`"
                    class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                  >
                    {{ product.name }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit</span>
                </div>
              </li>
            </ol>
          </nav>
          
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Edit Product
              </h1>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Update product information and settings
              </p>
            </div>
            
            <div class="flex items-center space-x-3">
              <span :class="stockStatus.class" class="text-sm font-medium">
                {{ stockStatus.text }}
              </span>
              <span class="text-xs text-gray-500 dark:text-gray-400">
                ID: #{{ product.id }}
              </span>
            </div>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-8">
          <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-6">
              
              <!-- Basic Information -->
              <div class="space-y-6">
                <div>
                  <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                    Basic Information
                  </h2>
                  
                  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Product Name -->
                    <div class="sm:col-span-2">
                      <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Name *
                      </label>
                      <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.name }"
                      />
                      <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- SKU -->
                    <div>
                      <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        SKU
                      </label>
                      <input
                        id="sku"
                        v-model="form.sku"
                        type="text"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.sku }"
                      />
                      <p v-if="form.errors.sku" class="mt-1 text-sm text-red-600">{{ form.errors.sku }}</p>
                    </div>

                    <!-- Price -->
                    <div>
                      <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price ($) *
                      </label>
                      <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input
                          id="price"
                          v-model="form.price"
                          type="text"
                          step="0.01"
                          min="0"
                          class="block w-full pl-8 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.price }"
                        />
                      </div>
                      <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
                    </div>

                    <!-- Stock -->
                    <div>
                      <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Stock Quantity *
                      </label>
                      <input
                        id="stock"
                        v-model="form.stock"
                        type="text"
                        min="0"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.stock }"
                      />
                      <p v-if="form.errors.stock" class="mt-1 text-sm text-red-600">{{ form.errors.stock }}</p>
                    </div>

                    <!-- Status Toggle -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                      </label>
                      <div class="flex items-center">
                        <button
                          type="button"
                          @click="form.active = !form.active"
                          :class="form.active ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'"
                          class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                          <span
                            :class="form.active ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                          />
                        </button>
                        <span class="ml-3 text-sm">
                          <span class="font-medium text-gray-900 dark:text-white">
                            {{ form.active ? 'Active' : 'Inactive' }}
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                  </label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.description }"
                    placeholder="Enter product description..."
                  />
                  <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Categories -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Categories
                  </label>
                  <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <label 
                      v-for="category in categories" 
                      :key="category.id"
                      class="flex items-center space-x-2 p-3 border rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                      :class="form.categories.includes(category.id) ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'"
                    >
                      <input
                        :id="`category-${category.id}`"
                        v-model="form.categories"
                        :value="category.id"
                        type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                      />
                      <span class="text-sm text-gray-700 dark:text-gray-300">{{ category.name }}</span>
                    </label>
                  </div>
                  <p v-if="form.errors.categories" class="mt-1 text-sm text-red-600">{{ form.errors.categories }}</p>
                </div>

                <!-- Image Upload -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Product Image
                  </label>
                  <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                    <div class="space-y-4 text-center w-full">
                      <!-- Image Preview -->
                      <div v-if="imagePreview" class="relative inline-block">
                        <img 
                          :src="imagePreview" 
                          alt="Preview" 
                          class="h-32 w-32 object-cover rounded-lg"
                        />
                        <button
                          type="button"
                          @click="removeImage"
                          class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </button>
                      </div>
                      
                      <!-- Upload Area -->
                      <div v-if="!imagePreview">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                          <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload a file</span>
                            <input
                              ref="imageInput"
                              id="image"
                              name="image"
                              type="file"
                              accept="image/*"
                              class="sr-only"
                              @change="handleImageSelect"
                            />
                          </label>
                          <p class="pl-1 inline">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          PNG, JPG, GIF up to 2MB
                        </p>
                      </div>
                    </div>
                  </div>
                  <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-4 pt-6">
            <Link
              :href="`/products/${product.id}`"
              class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Cancel
            </Link>
            
            <button
              type="submit"
              :disabled="form.processing || !isFormDirty"
              class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              {{ form.processing ? 'Updating...' : 'Update Product' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>