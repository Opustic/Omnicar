<template>
    <div v-if="props.name" class="staff-card" :class="roleClass">
        <img :src="photo" alt="Photo" class="staff-photo" />
        
        <div class="staff-info">
        <div class="staff-name">{{ props.name }}</div>
        <div v-if="props.permitNumber" class="staff-permit">N° Permis: {{ props.permitNumber }}</div>
        <div class="staff-role">{{ props.role }}</div>
        </div>

        <!-- Bouton oeil -->
        <button class="btn btn-light staff-btn-eye" @click="props.onViewClick">
            <i class="bi bi-eye fs-3"></i>
        </button>

    </div>

    <div class="d-flex justify-content-center align-items-center staff-card" v-else>
        <h2>Aucun</h2>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    name: String,
    permitNumber: String,
    photo: String,
    role: String,
    onViewClick: Function
});

const roleClass = computed(() => {
    return props.role === 'Chauffeur' ? 'role-driver' : 'role-controller';
});


</script>

<style scoped>
.staff-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 15px;
    width: 100%;
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
    position: relative;
}
.staff-card:hover {
    transform: translateY(-5px);
}

.staff-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 20px;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.staff-info {
    flex: 1;
}

.staff-name {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.staff-permit {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 10px;
}

.staff-role {
  font-size: 16px;
  font-weight: 500;
  padding: 5px 15px;
  border-radius: 20px;
  display: inline-block;
  color: #fff;
}

.role-driver .staff-role {
  background: #3498db;
}

.role-controller .staff-role {
  background: #e74c3c;
}

.role-driver {
  border-left: 5px solid #3498db;
}

.role-controller {
  border-left: 5px solid #e74c3c;
}

/* Bouton œil */
.staff-btn-eye {
    border: none;
    background: #f1f3f5;
    color: #495057;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.staff-btn-eye:hover {
    background: #e2e6ea;
    color: #212529;
}

.staff-btn-eye i {
    font-size: 18px;
}
</style>
