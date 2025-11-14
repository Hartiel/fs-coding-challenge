<script setup>
import ContactList from './chat/ContactList.vue';
import ChatWindow from './chat/ChatWindow.vue';
import MessageInput from './chat/MessageInput.vue';

import { computed, onUnmounted, watch } from 'vue';

const props = defineProps({
  contacts: Array,
  channels: Array,
  selectedContact: Object,
  messages: Object,
});

const hasPendingMessages = computed(() => {
  if (!props.selectedContact || !props.messages || !props.messages.data) {
    return false;
  }
  return props.messages.data.some(msg => msg.status === 'sending');
});
</script>

<template>
  <div class="flex h-screen w-full antialiased text-gray-800 bg-gray-100">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
      
      <div class="flex flex-col py-4 w-64 bg-white flex-shrink-0">
        <div class="flex flex-col h-full overflow-y-auto">
          <ContactList 
            :contacts="contacts" 
            :selectedContactId="selectedContact ? selectedContact.id : null" 
          />
        </div>
      </div>

      <div class="flex flex-col flex-auto h-full p-4">
        <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-white h-full p-4">
          
          <div class="flex flex-col h-full overflow-x-auto mb-4">
            <ChatWindow :messages="messages" />
          </div>
          
          <div class="flex flex-row items-center h-16 rounded-xl bg-gray-100 w-full px-4">
            <MessageInput
              :channels="channels"
              :contactId="selectedContact ? selectedContact.id : null"
            />
          </div>

        </div>
      </div>
    </div>
  </div>
</template>