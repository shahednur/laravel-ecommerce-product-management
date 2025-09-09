<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const name = ref('')
const price = ref('')
const description = ref('')
const image = ref<File | null>(null)
const categories = ref<number[]>([]) // selected category ids
const allCategories = ref<{ id: number; name: string }[]>([])

const loading = ref(false)
const errors = ref<Record<string, string[]>>({})


const goToProducts = () => {
  window.location.href = '/products'
}
// Fetch categories on mount
onMounted(async () => {
  const { data } = await axios.get('/categories') // you need an endpoint
  allCategories.value = data
})

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    image.value = target.files[0]
  }
}

const submit = async () => {
  loading.value = true
  errors.value = {}

  try {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('price', price.value)
    formData.append('description', description.value)
    if (image.value) formData.append('image', image.value)
    categories.value.forEach(id => formData.append('categories[]', id.toString()))

    const { data } = await axios.post('/products', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    console.log('Product created:', data)
    alert('Product created successfully!')
    // redirect back to products index
    window.location.href = '/products'
  } catch (err: any) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Create Product</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input v-model="name" type="text" class="mt-1 block w-full border rounded px-3 py-2" />
        <p v-if="errors.name" class="text-red-600 text-sm">{{ errors.name[0] }}</p>
      </div>

      <!-- Price -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Price</label>
        <input v-model="price" type="number" step="0.01" class="mt-1 block w-full border rounded px-3 py-2" />
        <p v-if="errors.price" class="text-red-600 text-sm">{{ errors.price[0] }}</p>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea v-model="description" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        <p v-if="errors.description" class="text-red-600 text-sm">{{ errors.description[0] }}</p>
      </div>

      <!-- Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" @change="handleFileChange" class="mt-1 block w-full border rounded px-3 py-2" />
        <p v-if="errors.image" class="text-red-600 text-sm">{{ errors.image[0] }}</p>
      </div>

      <!-- Categories -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Categories</label>
        <div class="flex flex-wrap gap-2 mt-2">
          <label
            v-for="cat in allCategories"
            :key="cat.id"
            class="inline-flex items-center space-x-2"
          >
            <input
              type="checkbox"
              :value="cat.id"
              v-model="categories"
              class="rounded"
            />
            <span>{{ cat.name }}</span>
          </label>
        </div>
        <p v-if="errors.categories" class="text-red-600 text-sm">{{ errors.categories[0] }}</p>
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-2">
        <button
          type="button"
          @click="goToProducts"
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded"
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="loading"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        >
          {{ loading ? 'Saving...' : 'Create' }}
        </button>
      </div>
    </form>
  </div>
</template>
