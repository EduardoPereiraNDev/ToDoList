<template>
  <div class="task-form">
    <h2>Criar Nova Tarefa</h2>
    <form @submit.prevent="submitTask">
      <div class="form-group">
        <label for="titulo">Título</label>
        <input
          v-model="task.title"
          type="text"
          id="titulo"
          placeholder="Digite o título da tarefa"
          required
        />
      </div>

      <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea
          v-model="task.description"
          id="descricao"
          placeholder="Descreva a tarefa"
          required
        ></textarea>
      </div>

      <div class="form-group">
        <label for="dataVencimento">Data de Vencimento</label>
        <input
          v-model="task.expiryDate"
          type="datetime-local"
          id="dataVencimento"
          required
        />
      </div>

      <div class="form-group">
        <label for="userSelect">Selecione um Usuário:</label>
        <select id="userSelect" v-model="selectedUser" required>
          <option value=null disabled selected>Escolha o usuário</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.id+"-"+user.name+"-"+user.email }}
          </option>
        </select>
      </div>
      
      <div class="form-group">
        <button type="submit" class="btn">Criar Tarefa</button>
      </div>
    </form>
  </div>
</template>

<script>
import api from '@/services/api'

export default {
  name: "TaskForm",
  data() {
    return {
      users: [], 
      selectedUser: null, 
      task: {
        title: "",
        description: "",
        expiryDate: "",
        user_id: "",
      },
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers(){
      try{
        const response = await api.get('/users', { withCredentials: true }); 
        this.users = response.data;
      }catch(error){
        console.error("Erro ao buscar usuarios:", error);
      }
    },

    async submitTask() {
      try {
        const date = new Date(this.task.expiryDate);
        const formattedDate = date.getFullYear() + '-' +
          String(date.getMonth() + 1).padStart(2, '0') + '-' +
          String(date.getDate()).padStart(2, '0') + ' ' +
          String(date.getHours()).padStart(2, '0') + ':' +
          String(date.getMinutes()).padStart(2, '0');
        this.task.expiryDate = formattedDate;
        this.task.user_id = this.selectedUser ;
        const response = await api.post("tasks", this.task);
        console.log("Tarefa criada com sucesso:", response.data);

        this.task.title = "";
        this.task.description = "";
        this.task.expiryDate = "";
        this.selectedUser  = null;

        this.$emit("taskCreated", response.data);
      } catch (error) {
        console.error("Erro ao criar a tarefa:", error);
      }
    },
  },
};
</script>

<style scoped>
.task-form {
  margin: 20px;
  padding: 20px;
  background-color: #f5f5f5;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.task-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.task-form .form-group {
  margin-bottom: 15px;
}

.task-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.task-form input[type="text"],
textarea,
input[type="date"], select {
  width: 90%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.task-form textarea {
  resize: vertical;
  min-height: 100px;
}

.task-form button.btn {
  width: 100%;
  padding: 10px;
  background-color: #008cba;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

.task-form button.btn:hover {
  background-color: #005f7a;
}
</style>
