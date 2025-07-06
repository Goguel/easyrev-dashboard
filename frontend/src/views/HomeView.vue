<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const rooms = ref([]);
const basePrice = ref(450);
const occupancy = ref(85);
const selectedRoomType = ref('');
const isHolidayOrWeekend = ref(true);

const predictedPrice = ref(null);
const errorMessage = ref('');
const isLoading = ref(false);

async function fetchRooms() {
  try {
    const response = await axios.get('http://localhost:8080/api/rooms');
    rooms.value = response.data;
  } catch (error) {
    errorMessage.value = 'Erro ao carregar os quartos. O backend está acessível?';
    console.error(error);
  }
}

async function getPrediction() {
  isLoading.value = true;
  predictedPrice.value = null;
  errorMessage.value = '';

  try {
    const response = await axios.post('http://localhost:8080/api/predict', {
      base_price: basePrice.value,
      occupancy: occupancy.value,
      room_type: selectedRoomType.value,
      is_holiday_or_weekend: isHolidayOrWeekend.value,
    });
    
    predictedPrice.value = response.data.predicted_price;

  } catch (error) {
    errorMessage.value = 'Erro ao calcular a previsão.';
    console.error(error);
  } finally {
    isLoading.value = false;
  }
}

onMounted(() => {
  fetchRooms();
});
</script>

<template>
  <div class="container">
    <h1>Sistema de Previsão de Preços de Hotel</h1>
    
    <form @submit.prevent="getPrediction">
      <h3>Dados para Previsão</h3>

      <div class="form-group">
        <label for="basePrice">Preço Base (R$):</label>
        <input type="number" id="basePrice" v-model="basePrice" class="form-input">
      </div>
      
      <div class="form-group">
        <label for="occupancy">Ocupação (%):</label>
        <input type="number" id="occupancy" v-model="occupancy" class="form-input">
      </div>
      
      <div class="form-group">
        <label for="roomType">Tipo de Quarto:</label>
        <select id="roomType" v-model="selectedRoomType" class="form-input">
          <option disabled value="">Selecione um tipo</option>
          <option v-for="room in rooms" :key="room.id" :value="room.type">
            {{ room.name }}
          </option>
        </select>
      </div>
      
      <div class="form-group-checkbox">
        <input type="checkbox" id="isHolidayOrWeekend" v-model="isHolidayOrWeekend" class="form-checkbox">
        <label for="isHolidayOrWeekend">É Feriado ou Fim de Semana?</label>
      </div>
      
      <button type="submit" :disabled="isLoading" class="submit-button">
        {{ isLoading ? 'Calculando...' : 'Prever Preço' }}
      </button>
    </form>

    <div v-if="predictedPrice" class="result-box">
      <h2>Previsão de Preço para o Próximo Dia:</h2>
      <p class="price-text">R$ {{ predictedPrice }}</p>
    </div>

    <div v-if="errorMessage" class="error-box">
      <h3>Ocorreu um Erro</h3>
      <p>{{ errorMessage }}</p>
    </div>

  </div>
</template>

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');


body {
  font-family: 'Poppins', sans-serif;
  background-color: #eef2ff; 
  color: #333;
  margin: 0;
  padding: 0;
}


.container {
  max-width: 600px;
  margin: 50px auto; 
  padding: 30px;
  background-color: #ffffff; 
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  margin-bottom: 25px;
}

h3 {
  margin-top: 0;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
}

.form-input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-sizing: border-box; 
  font-size: 16px;
}


.form-group-checkbox {
  display: flex;
  align-items: center;
  margin-bottom: 25px;
}
.form-checkbox {
  width: 18px;
  height: 18px;
  margin-right: 10px;
}

.submit-button {
  width: 100%;
  padding: 15px; 
  font-size: 18px; 
  font-weight: bold;
  color: white;
  background-color: #4f46e5; 
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.submit-button:hover {
  background-color: #4338ca; 
}

.submit-button:disabled {
  background-color: #a5b4fc;
  cursor: not-allowed;
}

.result-box {
  margin-top: 30px;
  padding: 20px;
  background-color: #dcfce7;
  border-left: 5px solid #22c55e;
  border-radius: 6px;
}
.price-text {
  font-size: 28px;
  font-weight: bold;
  color: #166534;
  margin: 0;
}

.error-box {
  margin-top: 30px;
  padding: 20px;
  background-color: #fee2e2;
  border-left: 5px solid #ef4444;
  color: #b91c1c;
  border-radius: 6px;
}
</style>