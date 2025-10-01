<template>
  <div class="d-flex justify-content-between align-items-center team-stat p-2 mb-2 shadow-sm rounded">
    <!-- Photo -->
    <div class="team-images d-flex align-items-center me-3">
      <img :src="props.photo" alt="Image du Chauffeur" class="img-fluid rounded-circle">
    </div>

    <!-- Nom du chauffeur -->
    <div class="flex-grow-1 d-flex align-items-center">
      <p class="mb-0 team-name fw-semibold">{{ props.nom }}</p>
    </div>

    <!-- Total -->
    <p class="total-team mb-0 fw-bold">{{ formatCFA(props.total) }}</p>
  </div>
</template>

<script setup>
import { formatCFA } from '@/utils/format';

const props = defineProps({
  nom: { type: String, default: "Opustic Phenix" },
  photo: { type: String, default: "" },
  total: { type: Number, default: 0 }
})
</script>

<style scoped>
.team-stat {
  max-width: 500px;
  background-color: #ffffff;
  border-radius: 0.75rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.team-stat:hover {
  transform: translateY(-2px);
  box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

.team-images img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

.team-name {
  font-size: 1rem;
  color: #495057;
}

.total-team {
  background-color: #28a745; /* vert bootstrap */
  color: #ffffff;
  padding: 0.25rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.95rem;
  min-width: 85px;
  text-align: center;
}
</style>
