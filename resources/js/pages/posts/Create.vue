<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useCategories } from "../../composables/categories";
import usePosts from "../../composables/posts";

const { categories, getCategories } = useCategories();
const { storePosts, validationErrors, isFormSubmitting } = usePosts();
const selectedCategory = ref("");

const form = reactive({
  title: "",
  content: "",
  category_id: "",
  image: "",
});

onMounted(() => {
  getCategories();
});

watch(selectedCategory, (current, previous) => {
  form.category_id = selectedCategory.value;
});

const onSubmit = () => {
  storePosts(form);
};
</script>
<template>
  <form @submit.prevent="onSubmit">
    <!-- Title -->
    <div>
      <label for="post-title" class="block font-medium text-sm text-gray-700">
        Title
      </label>
      <input
        id="post-title"
        type="text"
        class="
          block
          mt-1
          w-full
          rounded-md
          shadow-sm
          border-gray-300
          focus:border-indigo-300
          focus:ring
          focus:ring-indigo-200
          focus:ring-opacity-50
        "
        v-model="form.title"
      />
      <div class="text-red-600 mt-1">
        <div
          v-for="(message, index) in validationErrors?.title"
          :key="`title-${index}`"
        >
          {{ message }}
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="mt-4">
      <label for="post-content" class="block font-medium text-sm text-gray-700">
        Content
      </label>
      <textarea
        id="post-content"
        class="
          block
          mt-1
          w-full
          rounded-md
          shadow-sm
          border-gray-300
          focus:border-indigo-300
          focus:ring
          focus:ring-indigo-200
          focus:ring-opacity-50
        "
        v-model="form.content"
      ></textarea>
      <div class="text-red-600 mt-1">
        <div
          v-for="(message, index) in validationErrors?.content"
          :key="`content-${index}`"
        >
          {{ message }}
        </div>
      </div>
    </div>

    <!-- Category -->
    <div class="mt-4">
      <label
        for="post-category"
        class="block font-medium text-sm text-gray-700"
      >
        Category
      </label>
      <select
        id="post-category"
        v-model="form.category_id"
        class="
          block
          mt-1
          w-full
          rounded-md
          shadow-sm
          border-gray-300
          focus:border-indigo-300
          focus:ring
          focus:ring-indigo-200
          focus:ring-opacity-50
        "
      >
        <option value="" selected>-- Choose category --</option>
        <option
          v-for="category in categories"
          :value="category.id"
          :key="`category-${category.id}`"
        >
          {{ category.name }}
        </option>
      </select>
      <div class="text-red-600 mt-1">
        <div
          v-for="(message, index) in validationErrors?.category_id"
          :key="`category_id-${index}`"
        >
          {{ message }}
        </div>
      </div>
    </div>

    <!-- Image -->
    <!-- <div class="mt-4">
      <label for="image" class="block font-medium text-sm text-gray-700">
        Image
      </label>

      <input
        type="file"
        @change="form.image = $event.target.files[0]"
        id="image"
      />

      <div class="text-red-600 mt-1">
        <div
          v-for="(message, index) in validationErrors?.image"
          :key="`image-${index}`"
        >
          {{ message }}
        </div>
      </div>
    </div> -->

    <!-- Buttons -->
    <div class="mt-4">
      <button
        :disabled="isFormSubmitting"
        class="
          inline-flex
          items-center
          px-3
          py-2
          bg-blue-600
          text-white
          rounded
          disabled:opacity-75 disabled:cursor-not-allowed
        "
      >
        <div
          v-show="isFormSubmitting"
          class="
            inline-block
            animate-spin
            w-4
            h-4
            mr-2
            border-t-2
            border-t-white
            border-r-2
            border-r-white
            border-b-2
            border-b-white
            border-l-2
            border-l-blue-600
            rounded-full
          "
        ></div>
        <span v-if="isFormSubmitting">Processing...</span>
        <span v-else>Save</span>
      </button>
    </div>
  </form>
</template>
