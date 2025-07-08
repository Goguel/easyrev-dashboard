<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const API_URL = 'http://localhost/api'; // Usando a porta 80 padrão do Sail
const reservations = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');

const newReservation = ref({
  guest_name: '',
  check_in_date: '',
  check_out_date: '',
  guest_count: 1
});

async function fetchReservations() {
  isLoading.value = true;
  errorMessage.value = '';
  try {
    // Usando a data de hoje no formato YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];
    const response = await axios.get(`${API_URL}/reservations/today?date=${today}`);
    reservations.value = response.data;
  } catch (error) {
    errorMessage.value = 'Falha ao carregar as reservas. O backend está acessível e o CORS configurado?';
    console.error("Erro detalhado:", error);
  } finally {
    isLoading.value = false;
  }
}

async function createReservation() {
  errorMessage.value = '';
  if (!newReservation.value.guest_name || !newReservation.value.check_in_date || !newReservation.value.check_out_date) {
    errorMessage.value = "Por favor, preencha todos os campos da nova reserva.";
    return;
  }

  try {
    await axios.post(`${API_URL}/reservations`, newReservation.value);
    newReservation.value = { guest_name: '', check_in_date: '', check_out_date: '', guest_count: 1 };
    await fetchReservations();
  } catch (error) {
    errorMessage.value = 'Erro ao criar a reserva.';
    console.error("Erro detalhado:", error);
  }
}

async function updateStatus(id, newStatus) {
  try {
    await axios.patch(`${API_URL}/reservations/${id}`, { status: newStatus });
    await fetchReservations();
  } catch (error) {
    errorMessage.value = `Erro ao atualizar o status da reserva ${id}.`;
    console.error("Erro detalhado:", error);
  }
}

onMounted(() => {
  // Ajuste para o método today do backend aceitar a data como parâmetro
  // Isso requer uma pequena mudança no ReservationController
  fetchReservations();
});

function getStatusClass(status) {
  if (status === 'confirmada') return 'status-confirmed';
  if (status === 'cancelada') return 'status-cancelled';
  return 'status-pending';
}
</script>

<template>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <h1>Dashboard de Reservas</h1>
    </header>
    
    <main class="dashboard-content">
      <div class="card form-card">
        <h2><i class="fas fa-plus-circle"></i> Criar Nova Reserva</h2>
        <form @submit.prevent="createReservation">
          <div class="form-group">
            <label for="guest_name">Nome do Hóspede</label>
            <input id="guest_name" type="text" v-model="newReservation.guest_name" placeholder="Ex: João da Silva">
          </div>
          <div class="form-group">
            <label for="guest_count">Nº de Hóspedes</label>
            <input id="guest_count" type="number" v-model="newReservation.guest_count" min="1">
          </div>
          <div class="form-group">
            <label for="check_in">Data de Check-in</label>
            <input id="check_in" type="date" v-model="newReservation.check_in_date">
          </div>
          <div class="form-group">
            <label for="check_out">Data de Check-out</label>
            <input id="check_out" type="date" v-model="newReservation.check_out_date">
          </div>
          <button type="submit" class="btn btn-primary">Adicionar Reserva</button>
        </form>
      </div>

      <div class="card reservations-card">
        <h2><i class="fas fa-calendar-day"></i> Reservas com Check-in Hoje</h2>
        <div v-if="isLoading" class="loading">Carregando...</div>
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
        
        <div class="table-container" v-if="!isLoading && reservations.length > 0">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Hóspede</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="res in reservations" :key="res.id">
                <td>#{{ res.id }}</td>
                <td>{{ res.guest_name }} ({{ res.guest_count }}pax)</td>
                <td>{{ res.check_out_date }}</td>
                <td><span :class="['status-badge', getStatusClass(res.status)]">{{ res.status }}</span></td>
                <td class="actions">
                  <button title="Confirmar Reserva" v-if="res.status === 'pendente'" @click="updateStatus(res.id, 'confirmada')" class="btn-icon btn-confirm">✔</button>
                  <button title="Cancelar Reserva" v-if="res.status !== 'cancelada'" @click="updateStatus(res.id, 'cancelada')" class="btn-icon btn-cancel">✖</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="!isLoading && reservations.length === 0 && !errorMessage" class="no-reservations">
          Nenhuma reserva com check-in para hoje.
        </div>
      </div>
    </main>
  </div>
</template>

<style>
/* Estilo geral inspirado no projeto anterior, mais limpo e espaçado */
:root {
  --primary-color: #4f46e5;
  --primary-hover-color: #4338ca;
  --green-color: #16a34a;
  --red-color: #dc2626;
  --yellow-color: #f59e0b;
  --bg-color: #f8fafc;
  --card-bg-color: #ffffff;
  --text-color: #334155;
  --border-color: #e2e8f0;
}

body {
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
}

.dashboard-container {
  padding: 2rem;
}

.dashboard-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  text-align: center;
  color: var(--primary-color);
  margin-bottom: 2rem;
}

.dashboard-content {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 2rem;
  max-width: 1400px;
  margin: auto;
}

.card {
  background-color: var(--card-bg-color);
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  padding: 1.5rem;
}

.card h2 {
  font-size: 1.5rem;
  margin-top: 0;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--border-color);
}

/* Formulário */
.form-group {
  margin-bottom: 1rem;
}
.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
}
.form-group input {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-sizing: border-box;
}
.btn {
  width: 100%;
  padding: 0.8rem 1rem;
  font-size: 1.1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-primary {
  background-color: var(--primary-color);
  color: white;
}
.btn-primary:hover {
  background-color: var(--primary-hover-color);
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
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}
th {
  font-weight: 700;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Badge de Status */
.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  color: white;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: capitalize;
}
.status-pending { background-color: var(--yellow-color); }
.status-confirmed { background-color: var(--green-color); }
.status-cancelled { background-color: var(--red-color); }

/* Botões de Ação */
.actions .btn-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  color: white;
  font-size: 1rem;
  cursor: pointer;
  margin: 0 4px;
  transition: transform 0.2s;
}
.actions .btn-icon:hover {
  transform: scale(1.1);
}
.btn-confirm { background-color: var(--green-color); }
.btn-cancel { background-color: var(--red-color); }

.loading, .no-reservations, .error-message {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #64748b;
}
.error-message {
  color: var(--red-color);
}
</style>