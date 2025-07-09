<script setup>
const props = defineProps({
  reservations: Array,
  isLoading: Boolean,
  errorMessage: String,
  updatingId: Number | null
});

const emit = defineEmits(['update-status']);

function getStatusClass(status) {
  if (status === 'confirmada') return 'status-confirmed';
  if (status === 'cancelada') return 'status-cancelled';
  return 'status-pending';
}
</script>

<template>
  <div class="card reservations-card">
    <h2>Reservas com Check-in Hoje</h2>
    <div v-if="isLoading" class="feedback-box">Carregando...</div>
    <div v-if="errorMessage" class="feedback-box error">{{ errorMessage }}</div>
    
    <div class="table-container" v-if="!isLoading && reservations && reservations.length > 0">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Hóspede</th>
            <th>Check-out</th>
            <th>Status</th>
            <th class="actions-header">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="res in reservations" :key="res.id">
            <td>#{{ res.id }}</td>
            <td>{{ res.guest_name }} ({{ res.guest_count }} pessoa(s))</td>
            <td>{{ res.check_out_date }}</td>
            <td>
              <span v-if="res.status" :class="['status-badge', getStatusClass(res.status)]">
                {{ res.status }}
              </span>
            </td>
            <td class="actions">
              <div v-if="updatingId === res.id" class="spinner"></div>
              <div v-else>
                <button v-if="res.status === 'pendente'" @click="emit('update-status', res, 'confirmada')" class="btn-action btn-confirm" :disabled="updatingId">Confirmar</button>
                <button v-if="res.status === 'confirmada'" @click="emit('update-status', res, 'pendente')" class="btn-action btn-pending">Reverter</button>
                <button v-if="res.status !== 'cancelada'" @click="emit('update-status', res, 'cancelada')" class="btn-action btn-cancel" :disabled="updatingId">Cancelar</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="!isLoading && (!reservations || reservations.length === 0) && !errorMessage" class="feedback-box">
      Nenhuma reserva com check-in para hoje.
    </div>
  </div>
</template>

<style scoped>
:root {
  --green: #16a34a;
  --red: #dc2626;
  --yellow: #f59e0b;
  --primary-color: #4f46e5;
}
.table-container {
  max-height: 65vh;
  overflow-y: auto;
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 1.25rem;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  vertical-align: middle;
  white-space: nowrap;
}
td {
  font-size: 1.1rem;
}
th {
  font-size: 0.9rem;
  text-transform: uppercase;
  color: #64748b;
  position: sticky;
  top: 0;
  background-color: #ffffff;
}
.actions-header, .actions {
  text-align: center;
}
.actions {
  min-width: 220px;
}
.btn-action {
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  border: none;
  color: white;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  margin: 0 4px;
  transition: opacity 0.2s;
}
.btn-action:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-confirm { background-color: var(--green); }
.btn-cancel { background-color: var(--red); }
.btn-pending { background-color: var(--yellow); }
.status-badge {
  padding: 0.4rem 1rem;
  border-radius: 9999px;
  color: white;
  font-size: 0.9rem;
  font-weight: 700;
  text-transform: capitalize;
}
.status-pending { background-color: var(--yellow); }
.status-confirmed { background-color: var(--green); }
.status-cancelled { background-color: var(--red); }
.feedback-box {
  text-align: center;
  padding: 3rem;
  font-size: 1.2rem;
  font-style: italic;
  color: #64748b;
  background-color: #f1f5f9;
  border-radius: 8px;
}
.feedback-box.error {
  color: var(--red);
  background-color: #fee2e2;
  font-weight: bold;
}
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 24px;
  height: 24px;
  border-radius: 50%;
  border-left-color: var(--primary-color);
  animation: spin 1s ease infinite;
  margin: auto;
  display: block;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>