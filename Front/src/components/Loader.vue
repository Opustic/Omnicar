<template>
    <div class="loader-backdrop">
      <div class="loader-container">
        <div class="spinner"></div>
        <p v-if="text" class="loader-text">{{ text }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    text: {
      type: String,
      default: 'Chargement...'
    }
  })
  </script>
  
  <style scoped>
  .loader-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(8px) grayscale(80%);
    -webkit-backdrop-filter: blur(8px) grayscale(80%);
    background: rgba(255, 255, 255, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000; /* au-dessus de tout */
  }
  
  .loader-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.85);
    padding: 25px 40px;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
  }
  
  .spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #ddd;
    border-top-color: #2c3e50; /* couleur principale */
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  .loader-text {
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
    text-align: center;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  </style>
  