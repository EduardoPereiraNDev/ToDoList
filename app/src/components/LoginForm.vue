<template>
  <form class="form" @submit.prevent="login">
    <h2>Login</h2>
    <div class="form-group">
      <label for="email">E-mail</label>
      <input 
        type="email" 
        id="email" 
        v-model="email" 
        placeholder="Digite seu e-mail" 
        required />
    </div>
    <div class="form-group">
      <label for="password">Senha</label>
      <input 
        type="password" 
        id="password" 
        v-model="password" 
        placeholder="Digite sua senha" 
        required />
    </div>
    <button type="submit" class="submit-button">Entrar</button>
  </form>
</template>

<script>
import api from '@/services/api'

export default {
  name: "LoginForm",
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
          await api.get('http://127.0.0.1:8000/sanctum/csrf-cookie', { withCredentials: true });

          const response = await api.post('user/login', {
              email: this.email,
              password: this.password
          }, { withCredentials: true });

          const token = response.data.token; 
          localStorage.setItem('token', token);
          localStorage.setItem('typeAccount', response.data.user.typeAccount); 

          this.$router.push('/home');
          console.log('Login bem-sucedido', response);
      } catch (error) {
          console.error('Erro ao fazer login', error);
          alert('Credenciais inválidas');
      }
    }
  }
};
</script>
  
  <style scoped>
  .form {
    display: flex;
    flex-direction: column;
  }
  
  .form h2 {
    text-align: center;
    color: #333333;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    font-size: 14px;
    color: #333333;
    margin-bottom: 5px;
  }
  
  .form-group input {
    width: 94%;
    padding: 10px;
    border: 1px solid #777777;
    border-radius: 4px;
    font-size: 14px;
  }
  
  .form-group input:focus {
    border-color: #008CBA;
    outline: none;
  }
  
  .submit-button {
    padding: 10px;
    background-color: #008CBA;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
  }
  
  .submit-button:hover {
    background-color: #FF5722;
  }
  </style>
  