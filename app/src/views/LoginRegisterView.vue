<template>
  <div class="main-container">
    <div class="auth-card">
      <div class="auth-header">
        <button 
          :class="{'active': isLogin}" 
          @click="showLogin">
          Login
        </button>
        <button 
          :class="{'active': !isLogin}" 
          @click="showRegister">
          Cadastro
        </button>
      </div>
      <div class="auth-content">
        <LoginForm v-if="isLogin" />
        <RegisterForm v-else />
      </div>
    </div>
  </div>
</template>

<script>
import LoginForm from '@/components/LoginForm.vue';
import RegisterForm from '@/components/RegisterForm.vue';

export default {
  name: "LoginRegister",
  components: {
    LoginForm,
    RegisterForm,
  },
  data() {
    return {
      isLogin: true,
    };
  },
  methods: {
    showLogin() {
      this.isLogin = true;
    },
    showRegister() {
      this.isLogin = false;
    },
  },
};
</script>

<style scoped>
.main-container {
  display: flex;
  height: 80vh;
  justify-content: center;
  align-items: center;
  background-color: #F5F5F5;
}

.auth-card {
  width: 400px;
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.auth-header {
  display: flex;
  justify-content: space-around;
  margin-bottom: 20px;
}

.auth-header button {
  flex: 1;
  padding: 10px;
  border: none;
  background: none;
  font-size: 16px;
  font-weight: bold;
  color: #777777;
  border-bottom: 2px solid transparent;
  cursor: pointer;
}

.auth-header button.active {
  color: #333333;
  border-bottom: 2px solid #008CBA;
}

.auth-header button:hover {
  color: #FF5722;
}

.auth-content {
  margin-top: 20px;
}


</style>