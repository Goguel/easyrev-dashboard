<script setup>
// A seção <script> não precisa de nenhuma alteração na lógica.
// Ela já está correta para buscar, criar e atualizar as reservas.
import { ref, onMounted } from 'vue';
import axios from 'axios';
const API_URL = 'http://localhost:8000/api';
const reservations = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');
const newReservation = ref({
  guest_name: '',
  check_in_date: new Date().toISOString().split('T')[0],
  check_out_date: '',
  guest_count: 1
});
async function fetchReservations() {
  isLoading.value = true;
  errorMessage.value = '';
  try {
    const response = await axios.get(`${API_URL}/reservations/today`);
    reservations.value = response.data;
  } catch (error) {
    errorMessage.value = 'Falha ao carregar as reservas. Verifique o backend e a configuração de CORS.';
    console.error("Erro detalhado ao buscar:", error);
  } finally {
    isLoading.value = false;
  }
}
async function createReservation() {
  errorMessage.value = '';
  try {
    if (!newReservation.value.guest_name || !newReservation.value.check_in_date || !newReservation.value.check_out_date) {
      alert("Por favor, preencha todos os campos da nova reserva.");
      return;
    }
    await axios.post(`${API_URL}/reservations`, newReservation.value);
    newReservation.value = { guest_name: '', check_in_date: new Date().toISOString().split('T')[0], check_out_date: '', guest_count: 1 };
    await fetchReservations();
  } catch (error) {
    errorMessage.value = 'Erro ao criar a reserva. Verifique os dados.';
    console.error("Erro detalhado ao criar:", error);
  }
}
async function updateStatus(reservation, newStatus) {
  try {
    const response = await axios.patch(`${API_URL}/reservations/${reservation.id}`, { status: newStatus });
    // Atualiza o objeto na lista local para reatividade instantânea
    const index = reservations.value.findIndex(r => r.id === reservation.id);
    if (index !== -1) {
      reservations.value[index] = response.data;
    }
  } catch (error) {
    errorMessage.value = `Erro ao atualizar o status da reserva #${reservation.id}.`;
    console.error("Erro detalhado ao atualizar:", error);
  }
}
onMounted(() => { fetchReservations(); });
function getStatusClass(status) {
  if (status === 'confirmada') return 'status-confirmed';
  if (status === 'cancelada') return 'status-cancelled';
  return 'status-pending';
}
</script>

<template>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <h1>Dashboard de Gestão de Reservas</h1>
    </header>
    
    <main class="dashboard-content">

      <div class="card form-card">
        <h2>Criar Nova Reserva</h2>
        <form @submit.prevent="createReservation">
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
          <button type="submit" class="btn btn-primary">Adicionar Reserva</button>
        </form>
      </div>

      <div class="card reservations-card">
        <h2>Reservas com Check-in Hoje</h2>
        <div v-if="isLoading" class="feedback-box">Carregando...</div>
        <div v-if="errorMessage" class="feedback-box error">{{ errorMessage }}</div>
        
        <div class="table-container" v-if="!isLoading && reservations.length > 0">
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
                  <button v-if="res.status === 'pendente'" @click="updateStatus(res, 'confirmada')" class="btn-action btn-confirm">Confirmar</button>
                  <button v-if="res.status === 'confirmada'" @click="updateStatus(res, 'pendente')" class="btn-action btn-pending">Reverter</button>
                  <button v-if="res.status !== 'cancelada'" @click="updateStatus(res, 'cancelada')" class="btn-action btn-cancel">Cancelar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="!isLoading && reservations.length === 0 && !errorMessage" class="feedback-box">
          Nenhuma reserva com check-in para hoje.
        </div>
      </div>
    </main>
  </div>
</template>

<style>
/* MUDANÇA: CSS ajustado para o novo layout e tamanhos */
:root {
  --primary-color: #4f46e5;
  --bg-color: #f1f5f9;
  --card-bg-color: #ffffff;
  --text-color: #1e293b;
  --border-color: #e2e8f0;
  --green: #16a34a;
  --red: #dc2626;
  --yellow: #f59e0b;
}
body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
}
.dashboard-container {
  padding: 2rem 4rem;
}
header h1 {
  font-size: 2.8rem;
  font-weight: 800;
  text-align: center;
  color: var(--primary-color);
  margin-bottom: 3rem;
}
.dashboard-content {
  display: grid;
  /* Proporção ajustada: Formulário com largura mínima de 450px e a tabela pega o resto do espaço */
  grid-template-columns: minmax(450px, 1fr) 2fr;
  gap: 3rem;
  max-width: 1800px;
  margin: auto;
  align-items: start; /* Alinha os cards pelo topo */
}
.card {
  background-color: var(--card-bg-color);
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.05);
  padding: 2.5rem 3rem; /* Mais preenchimento */
}
.card h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-top: 0;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border-color);
}
/* Formulário */
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
}

/* Tabela */
.table-container {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 1.25rem;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
  vertical-align: middle;
}
th {
  font-size: 0.9rem;
  text-transform: uppercase;
  color: #64748b;
}
td {
  font-size: 1.1rem;
}
.actions-header { text-align: center; }
.actions { text-align: center; white-space: nowrap; } /* Evita que os botões quebrem a linha */

/* Botões com texto */
.actions .btn-action {
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
.actions .btn-action:hover { opacity: 0.85; }
.btn-confirm { background-color: var(--green); }
.btn-cancel { background-color: var(--red); }
.btn-pending { background-color: var(--yellow); }

/* Badge de Status */
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
</style>