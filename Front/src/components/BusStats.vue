<template>
  <div class="vehicule-card p-card">
    <!-- Image -->
    <div class="vehicule-image">
      <img :src="photo" alt="Photo du véhicule" />
    </div>

    <!-- Infos -->
    <div class="vehicule-info">
      <h5 class="immatriculation">{{ immatriculation }}</h5>
      <p class="label">Revenu</p>
      <h4 class="total">{{ formatCurrency(total) }}</h4>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  immatriculation: String,
  photo: String,
  total: Number
})

// Format montant
const formatCurrency = (value) => {
  return new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: "XOF",
    minimumFractionDigits: 0
  }).format(value)
}
</script>

<style scoped>
.vehicule-card {
  display: flex;
  align-items: center;
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: transform 0.2s;
  background: #fff;
  margin-bottom: 1rem;
}

.vehicule-card:hover {
  transform: translateY(-2px);
}

.vehicule-image {
  flex: 0 0 200px;
  height: 140px;
  overflow: hidden;
}

.vehicule-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.vehicule-info {
  flex: 1;
  padding: 1rem;
}

.immatriculation {
  font-weight: 600;
  margin: 0;
}

.label {
    font-size: 0.85rem;
    color: #777;
    margin: 0.2rem 0;
}

.total {
    color: #008CBA;
    font-weight: bold;
    margin: 0.5rem 0 0;
}
</style>
