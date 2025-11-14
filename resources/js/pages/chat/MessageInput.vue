<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
  channels: Array,
  contactId: Number,
});

const form = useForm({
  content: '',
  channel_id: props.channels[0]?.id || null,
  contact_id: props.contactId,
});

watch(() => props.contactId, (newId) => {
  form.contact_id = newId;
});

const submit = () => {
  form.post('/messages', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('content');
    },
    onError: (errors) => {
      if (errors.send_error) {
        alert(errors.send_error);
      }
    }
  });
};
</script>

<template>
  <form @submit.prevent="submit" class="flex items-center w-full space-x-2">
    
    <div class="flex-grow">
      <input
        v-model="form.content"
        :disabled="!contactId || form.processing"
        type="text"
        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50"
        placeholder="Escreva sua mensagem..."
      />
    </div>
    
    <div>
      <select
        v-model="form.channel_id"
        :disabled="!contactId || form.processing"
        class="rounded-xl border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50"
      >
        <option v-for="channel in channels" :key="channel.id" :value="channel.id">
          {{ channel.name }}
        </option>
      </select>
    </div>
    
    <div>
      <button
        type="submit"
        :disabled="!contactId || form.processing"
        class="rounded-xl bg-indigo-600 px-4 py-2 text-white transition-colors hover:bg-indigo-700 disabled:opacity-50"
      >
        <span v-if="form.processing">...</span>
        <span v-else>Enviar</span>
      </button>
    </div>
  </form>
</template>