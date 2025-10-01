<template>
  <div class="depense-card p-3 mb-3">

    <!-- Catégorie -->
    <h5 class="fw-bold text-primary mb-2 d-flex align-items-center">
      <i class="bi bi-tag-fill me-2"></i> {{ depense.categorie }}
    </h5>

    <!-- Montant -->
    <p class="mb-1 fw-bold text-success d-flex align-items-center">
      <i class="bi bi-cash-stack me-2"></i>
      {{ formatCFA(depense.montant) }}
    </p>

    <!-- Véhicule (optionnel) -->
    <p v-if="depense.vehicule" class="text-info fw-semibold d-flex align-items-center">
      <i class="bi bi-truck-front-fill me-2"></i>
      {{ depense?.vehicule_id.immatriculation }}
    </p>

    <!-- Employé (optionnel) -->
    <p v-if="depense.employe" class="text-warning fw-semibold d-flex align-items-center">
      <i class="bi bi-person-badge-fill me-2"></i>
      {{ depense?.employe?.nom }}
    </p>

    <!-- Date -->
    <small class="text-muted d-flex align-items-center mt-2">
      <i class="bi bi-calendar-event me-2"></i>
      {{ formatDate(depense.created_at) }}
    </small>
  </div>
</template>

<script setup>
import { formatCFA } from '@/utils/format';

const props = defineProps({
  depense: {
    type: Object,
    required: true
  }
});

// Formatage date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR", {
    year: "numeric",
    month: "long",
    day: "numeric"
  });
};
</script>

<style scoped>
.depense-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 1rem;
  transition: all 0.2s ease-in-out;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.depense-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.depense-card h5,
.depense-card p,
.depense-card small {
  margin: 0;
}
</style>
