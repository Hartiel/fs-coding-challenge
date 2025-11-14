<script setup>
import { ref, watch, nextTick, onUnmounted } from 'vue';
import { useIntersectionObserver } from '@vueuse/core';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  messages: Object,
});

const loadMoreTrigger = ref(null);
const chatBox = ref(null);
const localMessages = ref([]);
const isLoadingMore = ref(false);
let pollInterval = null;

const scrollToBottom = () => {
  nextTick(() => {
    if (chatBox.value) {
      chatBox.value.scrollTop = chatBox.value.scrollHeight;
    }
  });
};

const checkPendingMessages = async () => {
  const pending = localMessages.value.filter(msg => msg.status === 'sending');

  if (pending.length === 0) return;

  const ids = pending.map(msg => msg.id);

  try {
    const response = await axios.post('/messages/check-status', { ids });
    
    response.data.forEach(updatedMsg => {
      const localMsg = localMessages.value.find(m => m.id === updatedMsg.id);
      
      if (localMsg && localMsg.status !== updatedMsg.status) {
        localMsg.status = updatedMsg.status;
      }
    });
  } catch (error) {
    console.error('Erro no polling:', error);
  }
};

const managePolling = () => {
  if (pollInterval) clearInterval(pollInterval);

  pollInterval = setInterval(() => {
    const hasPending = localMessages.value.some(msg => msg.status === 'sending');
    if (hasPending) {
      checkPendingMessages();
    }
  }, 3000);
};

const loadMore = () => {
  const nextPageUrl = props.messages?.next_page_url;
  if (!nextPageUrl || isLoadingMore.value) return;

  isLoadingMore.value = true;
  
  router.get(nextPageUrl, {}, {
    preserveState: true,
    preserveScroll: true,
    only: ['messages'],
  });
};

watch(() => props.messages, (newPaginator) => {
  if (!newPaginator || !newPaginator.data) {
    localMessages.value = [];
    return;
  }

  const newData = newPaginator.data.slice().reverse();

  if (isLoadingMore.value) {
    isLoadingMore.value = false;
    const oldScrollHeight = chatBox.value?.scrollHeight || 0;
    
    localMessages.value.unshift(...newData);

    nextTick(() => {
      if (chatBox.value) {
        chatBox.value.scrollTop = chatBox.value.scrollHeight - oldScrollHeight;
      }
    });
  } else {
    localMessages.value = newData;
    scrollToBottom();
    managePolling();
  }
}, { deep: true, immediate: true });

useIntersectionObserver(loadMoreTrigger, ([{ isIntersecting }]) => {
  if (isIntersecting) loadMore();
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});
</script>

<template>
  <div ref="chatBox" class="flex flex-col h-full overflow-y-auto">
    <div v-if="!messages" class="flex flex-col h-full items-center justify-center text-gray-400">
      <p class="mt-2 text-lg">Selecione um contato para começar</p>
    </div>

    <div v-else class="grid grid-cols-12 gap-y-2">
      <div ref="loadMoreTrigger" class="h-1"></div>

      <div
        v-for="message in localMessages"
        :key="message.id"
        :class="{
          'col-start-1 col-end-8 p-3 rounded-lg': message.direction === 'in',
          'col-start-6 col-end-13 p-3 rounded-lg': message.direction === 'out',
        }"
      >
        <div
          class="flex flex-row items-center"
          :class="{ 'justify-start': message.direction === 'in', 'flex-row-reverse': message.direction === 'out' }"
        >
           <div v-if="message.direction === 'in'" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-200 flex-shrink-0">
            A
          </div>
          
          <div
            class="relative ml-3 mr-3 text-sm py-2 px-4 shadow rounded-xl"
            :class="{
              'bg-white text-gray-700': message.direction === 'in',
              'bg-indigo-100 text-gray-700': message.direction === 'out',
            }"
          >
            <div>{{ message.content }}</div>
            
            <div class="absolute text-xs bottom-0 right-2 text-gray-400 pt-2">
              {{ message.status }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>