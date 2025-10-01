<template>
    <div class="bus-card">
        <!-- Image du bus -->
        <img :src="busImage" alt="Bus" class="bus-image" />

        <!-- Infos du bus -->
        <div class="bus-info">
            <h3 class="bus-id">{{ props.busImmatriculation }}</h3>
            <span class="status" :class="statusClass">{{ statusText }}</span>

            <div class="stats">
            <p>Recette du jour: <strong>{{ props.revenue.toLocaleString() }} FCFA</strong></p>
            <p>Total réparations: <strong>{{ props.repairs }}</strong></p>
            </div>

            <div class="actions">
                <RouterLink :to="{ name: 'businfos', params: { id: props.busId } }">
                    <button class="btn-details">Voir détails</button>
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';

const props = defineProps({
  busId: { type: Number, default: null },
  busImmatriculation: { type: String, default: "Inconnu" },
  status: { type: String, default: "Inconnu" },
  revenue: { type: Number, default: 0 },
  repairs: { type: Number, default: 0 },
  busImage: { type: String, default: "Inconnu" },
});

const statusText = computed(() => {
  const status = props.status?.toLowerCase();
  switch (status) {
    case "actif": return "Actif";
    case "inactif": return "À l'arrêt";
    case "repair": return "En réparation";
    default: return "Inconnu";
  }
});

const statusClass = computed(() => {
  const status = props.status?.toLowerCase();
  switch (status) {
    case "actif": return "status-active";
    case "inactif": return "status-stopped";
    case "repair": return "status-repair";
    default: return "";
  }
});
</script>

<style scoped>
.bus-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  overflow: hidden;
  margin: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
}

.bus-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.bus-image {
    margin: 15px auto;
    width: 95%;
    height: 180px;
    object-fit: cover;
    border-radius: 15px;
}

.bus-info {
  padding: 15px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.bus-id {
  font-size: 18px;
  font-weight: 600;
  color: #222;
}

.status {
  align-self: flex-start;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  color: #fff;
}

.status-active { background: #27ae60; }
.status-stopped { background: #e74c3c; }
.status-repair { background: #f39c12; }

.stats p {
  margin: 4px 0;
  font-size: 14px;
  color: #555;
}

.stats strong { color: #111; }

.actions {
  margin-top: 10px;
}

.btn-details {
  background: #111;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-details:hover {
  background: #333;
}

a { text-decoration: none; }
</style>
