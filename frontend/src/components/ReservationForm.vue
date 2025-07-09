<script setup>
import { ref } from 'vue';

const props = defineProps({
  isCreating: Boolean
});

const emit = defineEmits(['create-reservation']);

function getLocalDate() {
  const date = new Date();
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  return date.toISOString().split('T')[0];
}

const newReservation = ref({
  guest_name: '',
  check_in_date: getLocalDate(),
  check_out_date: '',
  guest_count: 1
});

function submitForm() {
  if (!newReservation.value.guest_name || !newReservation.value.check_in_date || !newReservation.value.check_out_date) {
    alert("Por favor, preencha todos os campos da nova reserva.");
    return;
  }
  emit('create-reservation', { ...newReservation.value });
  newReservation.value = { guest_name: '', check_in_date: getLocalDate(), check_out_date: '', guest_count: 1 };
}
</script>

<template>
  <div class="card form-card">
    <h2>Criar Nova Reserva</h2>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="guest_name">Nome do Hóspede</label>
        <input id="guest_name" type="text" v-model="newReservation.guest_name" required>
      </div>
      <div class="form-group">
        <label for="guest_count">Nº de Hóspedes</label>
        <input id="guest_count" type="number" v-model="newReservation.guest_count" min="1" required>
      </div>
      <div class="form-group">
        <label for="check_in">Data de Check-in</label>
        <input id="check_in" type="date" v-model="newReservation.check_in_date" required>
      </div>
      <div class="form-group">
        <label for="check_out">Data de Check-out</label>
        <input id="check_out" type="date" v-model="newReservation.check_out_date" required>
      </div>
      <button type="submit" class="btn btn-primary" :disabled="isCreating">
        {{ isCreating ? 'Salvando...' : 'Adicionar Reserva' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.form-group {
  margin-bottom: 1.5rem;
}
.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.75rem;
  font-size: 1rem;
}
.form-group input {
  width: 100%;
  padding: 1rem;
  font-size: 1rem;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-sizing: border-box;
}
.btn-primary {
  width: 100%;
  padding: 1.2rem;
  font-size: 1.2rem;
  font-weight: 700;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  background-color: var(--primary-color);
  color: white;
  margin-top: 1rem;
  transition: background-color 0.2s;
}
.btn-primary:disabled {
  background-color: #a5b4fc;
  cursor: not-allowed;
}
.btn-primary:hover:not(:disabled) {
  background-color: #3730a3;
}
</style>