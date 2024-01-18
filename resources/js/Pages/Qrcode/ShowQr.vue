<template>
    <div>
      <img :src="qrCodeImage" alt="QR Code" />
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps(['qrCodeImage']);
  </script>
  