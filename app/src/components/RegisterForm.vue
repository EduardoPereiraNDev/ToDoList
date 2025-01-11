<template>
  <form class="form" @submit.prevent="register">
    <h2>Cadastro</h2>
    <div class="form-group">
      <label for="name">Nome</label>
      <input 
        type="text" 
        id="name" 
        v-model="name"
        placeholder="Digite seu nome" 
        required />
    </div>
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
        placeholder="Digite sua senha-Min 8 caracteres" 
        required />
    </div>

    <div class="form-group">
      <label for="typeAccount">Tipo de Conta</label>
      <select id="typeAccount" v-model="typeAccount">
        <option value="user">user</option>
        <option value="admin">admin</option>
      </select>
    </div>
    <button type="submit" class="submit-button">Cadastrar</button>
  </form>
</template>

<script>
import api from '@/services/api';

export default {
  name: "RegisterForm",
  data() {
    return {
      name: '',
      email: '',
      password: '',
      typeAccount: 'user'
    };
  },
  methods: {
    async register() {
      if (!this.name || !this.email || !this.password) {
        alert('Por favor, preencha todos os campos!');
        return;
      }

      console.log({
        name: this.name,
        email: this.email,
        password: this.password,
        typeAccount: this.typeAccount, 
      }); 

      try {
        await api.get('http://127.0.0.1:8000/sanctum/csrf-cookie', { withCredentials: true });
        const response = await api.post('/user/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          typeAccount: this.typeAccount, 
        }, { withCredentials: true });

        const token = response.data.token; 
        localStorage.setItem('token', token); 

        this.$router.push('/home');

        console.log('Registro bem-sucedido', response.data);

      } catch (error) {
        console.error('Erro ao fazer cadastro', error);
        alert('Erro ao fazer cadastro. Verifique os dados e tente novamente.');
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

  .form-group input,
  .form-group select {
    width: 94%;
    padding: 10px;
    border: 1px solid #777777;
    border-radius: 4px;
    font-size: 14px;
  }

  .form-group input:focus,
  .form-group select:focus {
    border-color: #008CBA;
    outline: none;
  }

  .form-group select {
    background-color: #F5F5F5;
    color: #333333;
    font-weight: 300;
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
