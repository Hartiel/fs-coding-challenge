<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  contacts: Array,
  selectedContactId: Number,
});
</script>

<template>
  <ul class="flex flex-col space-y-1">
    <li v-for="contact in contacts" :key="contact.id">
      
      <Link
        :href="`/?contact_id=${contact.id}`"
        :preserve-scroll="true" class="flex flex-row items-center rounded-xl p-2"
        :class="{
          'bg-gray-200': contact.id === selectedContactId,
          'hover:bg-gray-100': contact.id !== selectedContactId,
        }"
      >
        <div
          class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full"
        >
          {{ contact.name[0] }}
        </div>
        <div class="ml-2 text-sm font-semibold">
          {{ contact.name }}
          <p v-if="contact.latest_message" class="text-xs text-gray-500 font-normal truncate w-40">
            {{ contact.latest_message.content }}
          </p>
          <p v-else class="text-xs text-gray-500 font-normal italic">
            Nenhuma mensagem
          </p>
        </div>
      </Link>
    </li>
  </ul>
</template>