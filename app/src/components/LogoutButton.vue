<template>
    <button class="button-logout" @click="logout">Logout</button>
  </template>
  
  <script>
  import api from '@/services/api'; // Certifique-se de que o caminho está correto
  
  export default {
    name: 'LogoutButton',
    methods: {
      async logout() {
        try {
          await api.post('/user/logout', {}, { withCredentials: true });
          localStorage.removeItem('token'); 
          this.$router.push('/'); 
          console.log('Logout realizado com sucesso');
        } catch (error) {
          console.error('Erro ao fazer logout', error);
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .button-logout {
    background-color: #ff4d4d; 
    color: white; 
    border: none; 
    border-radius: 5px;
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
    transition: background-color 0.3s; 
  }
  
  .button-logout:hover {
    background-color: #ff1a1a; 
  }
  </style>