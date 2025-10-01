<template>
    <li class="depense-item d-flex justify-content-between align-items-center p-3 mb-2 shadow-sm rounded" :style="{backgroundColor : bgColor}">
        <span class="d-flex align-items-center">
        <i :class="icon" class="me-2"></i>
        <span class="categorie">{{ props.categorie }}</span>
        </span>
        <span class="total">{{ formatCFA(props.total) }}</span>
    </li>
</template>

<script setup>
import { computed } from 'vue';
import { formatCFA } from '@/utils/format';
const props = defineProps({
    categorie: String,
    total: Number
});

// icône selon la catégorie
const icon = computed(() => {
    switch(props.categorie) {
        case 'entretien': return 'bi bi-tools';
        case 'reparation': return 'bi bi-wrench';
        case 'assurance': return 'bi bi-shield-check';
        case 'taxe': return 'bi bi-receipt';
        case 'controle_technique': return 'bi bi-card-checklist';
        case 'amende': return 'bi bi-exclamation-triangle';
        case 'salaire': return 'bi bi-cash-stack';
        case 'prime': return 'bi bi-gift';
        case 'divers': return 'bi bi-question-circle';
        default: return 'bi bi-file';
    }
});

// couleur de fond selon la catégorie
const bgColor = computed(() => {
  switch(props.categorie) {
    case 'entretien': return '#e0f7fa';
    case 'reparation': return '#fff3e0';
    case 'assurance': return '#e8f5e9';
    case 'taxe': return '#f3e5f5';
    case 'controle_technique': return '#e1f5fe';
    case 'amende': return '#ffebee';
    case 'salaire': return '#fff9c4';
    case 'prime': return '#fce4ec';
    case 'divers': return '#eeeeee';
    default: return '#f5f5f5';
  }
});
</script>

<style scoped>
.depense-item {
  border-left: 5px solid transparent;
  transition: all 0.3s ease;
  background-color: var(--bg-color);
  cursor: pointer;
}

.depense-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.categorie {
  font-weight: 600;
  text-transform: capitalize;
}

.total {
  font-weight: bold;
  color: #424242;
}

i {
  font-size: 1.5rem;
}

/* bind le bgColor en CSS variable */
.depense-item {
  --bg-color: #f5f5f5;
}
</style>
