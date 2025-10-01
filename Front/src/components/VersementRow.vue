<template>
  <div
    class="versement-row d-flex align-items-center shadow-sm p-3 mb-3 rounded"
    role="listitem"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    :class="{ 'hover-effect': hover }"
  >
    <!-- Véhicule -->
    <div class="d-flex align-items-center me-4">
      <img
        :src="item.vehicule.photo || defaultImage"
        alt="Photo du véhicule"
        class="rounded img-vehicule me-2"
        @error="handleImageError"
        aria-label="Photo du véhicule"
      />
      <div>
        <h6 class="fw-bold mb-0">{{ item.vehicule.immatriculation || 'N/A' }}</h6>
      </div>
    </div>

    <!-- Chauffeur -->
    <div class="d-flex align-items-center me-4 flex-fill">
      <img
        :src="item.chauffeur.photo || defaultImage"
        alt="Photo du chauffeur"
        class="rounded-circle img-person me-2"
        @error="handleImageError"
        aria-label="Photo du chauffeur"
      />
      <div>
        <p class="fw-semibold mb-0">{{ item.chauffeur.nom || 'Inconnu' }}</p>
        <p class="text-success fw-bold mb-0">
          {{ formatMontant(item.chauffeur.montant) }} FCFA
        </p>
      </div>
    </div>

    <!-- Contrôleur -->
    <div class="d-flex align-items-center me-4 flex-fill">
      <img
        :src="item.controleur.photo || defaultImage"
        alt="Photo du contrôleur"
        class="rounded-circle img-person me-2"
        @error="handleImageError"
        aria-label="Photo du contrôleur"
      />
      <div>
        <p class="fw-semibold mb-0">{{ item.controleur.nom || 'Inconnu' }}</p>
        <p class="text-primary fw-bold mb-0">
          {{ formatMontant(item.controleur.montant) }} FCFA
        </p>
      </div>
    </div>

    <!-- Total -->
    <div class="ms-auto text-end">
      <p class="fw-bold text-dark mb-0">Total</p>
      <p class="fw-bold text-lg text-danger mb-0">
        {{ formatMontant(item.total) }} FCFA
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  item: {
    type: Object,
    required: true,
    validator: (item) => {
      return (
        item.vehicule &&
        typeof item.vehicule.immatriculation === 'string' &&
        item.chauffeur &&
        typeof item.chauffeur.nom === 'string' &&
        typeof item.chauffeur.montant === 'number' &&
        item.controleur &&
        typeof item.controleur.nom === 'string' &&
        typeof item.controleur.montant === 'number' &&
        typeof item.total === 'number'
      )
    },
  },
})

const defaultImage = '/images/placeholder.jpg' // Remplace par une image par défaut
const hover = ref(false)

const handleImageError = (event) => {
  event.target.src = defaultImage
}

const formatMontant = (montant) => {
  return montant ? montant.toLocaleString('fr-FR') : '0'
}
</script>

<style scoped>
.versement-row {
  background: #fff;
  border: 1px solid #eaeaea;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.versement-row.hover-effect {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.img-vehicule {
  width: 60px;
  height: 60px;
  object-fit: cover;
}

.img-person {
  width: 45px;
  height: 45px;
  object-fit: cover;
}

.text-lg {
  font-size: 1.25rem;
}
</style>