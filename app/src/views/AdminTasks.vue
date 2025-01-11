<template>
  <div class="main-container">
    <div class="row">
      <TaskForm @taskCreated="fetchTasks" />
      <div class="second-container">
        <div class="navbar-card">
          <label for="userSelect">Filtre por Usuario:</label>
          <select id="userSelect" v-model="selectedUser " required @change="filterTasks">
            <option value=null disabled selected>Escolha o usuário</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.id + "-" + user.name + "-" + user.email }}
            </option>
          </select>
          <button v-if="selectedUser" @click="clearFilter" id="clearButton">Limpar</button>
        </div>
        <div class="group-list">
          <div class="data-Group">
            <h1>Tarefas Pendentes:</h1>
            <div class="list">
              <TaskSearch
                listType="pending"
                @search="handleSearch"
              />
              <div v-if="filteredPendingTasks.length">
                <TaskItem
                  v-for="task in filteredPendingTasks"
                  :key="task.id"
                  :task="task"
                  @taskClicked="openUpdatePopup"
                  @taskStatus="taskStatus"
                />
              </div>
              <p v-else>Nenhuma tarefa pendente encontrada.</p>
            </div>
          </div>
          <div class="data-Group">
            <h1>Tarefas Concluídas:</h1>
            <div class="list">
              <TaskSearch
                listType="completed"
                @search="handleSearch"
              />
              <div v-if="filteredCompletedTasks.length">
                <TaskItem
                  v-for="task in filteredCompletedTasks"
                  :key="task.id"
                  :task="task"
                  @taskClicked="openUpdatePopup"
                  @taskStatus="taskStatus"
                />
              </div>
              <p v-else>Nenhuma tarefa concluída encontrada.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <TaskUpdate
      v-if="showUpdatePopup"
      :taskClicked="selectedTask"
      @closePopup="closeUpdatePopup"
      @taskChange="changeTaskList"
    />
  </div>
</template>
  
<script>
import TaskForm from "@/components/TaskForm.vue";
import TaskItem from "@/components/TaskItem.vue";
import TaskSearch from "@/components/TaskSearch.vue";
import TaskUpdate from "@/components/TaskUpdate.vue";
import api from '@/services/api'

export default {
  name: "AdminsTasks",
  components: {
    TaskItem,
    TaskSearch,
    TaskForm,
    TaskUpdate,
  },
  data() {
    return {
      tasks: [],
      completedTasks: [],
      pendingTasks: [],
      filteredCompletedTasks: [],
      filteredPendingTasks: [],
      showUpdatePopup: false,
      selectedTask: null,
      users: [], 
      selectedUser: null,
    };
  },
  mounted() {
    this.fetchTasks();
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

    async fetchTasks() {
      try {
        const response = await api.get("tasks/all");
        this.tasks = response.data;
        this.completedTasks = this.tasks.filter((task) => task.completionDate !== null);
        this.pendingTasks = this.tasks.filter((task) => task.completionDate === null);
        this.filteredPendingTasks = [...this.pendingTasks];
        this.filteredCompletedTasks = [...this.completedTasks];
      } catch (error) {
        console.error("Erro ao buscar tarefas:", error);
        if (error.response.status === 401) {
        alert('Você não está autenticado.');
        this.$router.push('/');
        }
        if (error.response.status === 403) {
        alert('Você não possui autorização para acessar está pagina.');
        this.$router.push('/home');
        }
      }
    },
    async taskStatus(task) {
      try {
        const response = await api.put(`tasks/${task.id}/toggle-completion`);
        const updatedTask = response.data;

        if (updatedTask.completionDate) {
          const index = this.pendingTasks.findIndex((list) => list.id === updatedTask.id);
          if (index !== -1) {
            this.pendingTasks.splice(index, 1);
            this.completedTasks.push(updatedTask);
          }
        } else {
          const index = this.completedTasks.findIndex((list) => list.id === updatedTask.id);
          if (index !== -1) {
            this.completedTasks.splice(index, 1);
            this.pendingTasks.push(updatedTask);
          }
        }

        this.filteredPendingTasks = [...this.pendingTasks];
        this.filteredCompletedTasks = [...this.completedTasks];
      } catch (error) {
        console.error("Erro ao concluir a tarefa:", error);
      }
    },

    handleSearch({ listType, searchTerm }) {
      if (listType === "pending") {
        this.filteredPendingTasks = this.pendingTasks.filter(
          (task) =>
            task.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            task.description.toLowerCase().includes(searchTerm.toLowerCase())
        );
      } else if (listType === "completed") {
        this.filteredCompletedTasks = this.completedTasks.filter(
          (task) =>
            task.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            task.description.toLowerCase().includes(searchTerm.toLowerCase())
        );
      }
    },

    filterTasks() {
      if (this.selectedUser ) {
          this.filteredPendingTasks = this.pendingTasks.filter(task => task.user_id === this.selectedUser );
          this.filteredCompletedTasks = this.completedTasks.filter(task => task.user_id === this.selectedUser );
      } else {
          this.filteredPendingTasks = [...this.pendingTasks];
          this.filteredCompletedTasks = [...this.completedTasks];
      }
    },

    clearFilter() {
      this.selectedUser  = null; 
      this.filteredPendingTasks = [...this.pendingTasks]; 
      this.filteredCompletedTasks = [...this.completedTasks]; 
    },
    openUpdatePopup(task) {
      this.selectedTask = task;
      this.showUpdatePopup = true;
    },
    closeUpdatePopup() {
      this.showUpdatePopup = false;
      this.selectedTask = null;
    },

    changeTaskList() {
      this.fetchTasks();
      this.closeUpdatePopup();
    },
  },
};
</script>
  
  <style scoped>
  
  .main-container {
    display: flex;
    min-height: 600px;
    margin: 50px;
    align-items: flex-start;
    flex-direction: column;
  }

  .row{
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    width: 90%;
  }

  .second-container{
    display: flex;
    background-color: #F5F5F5;
    width: 55%;
    border-radius: 10px;
    align-items: center;
    flex-direction: column;
  }

  .group-list{
    display: flex;
    margin: 25px;
    flex-direction: row;
    width: 100%;
    justify-content: space-around;
  }

  .navbar-card{
    display: flex;
    align-items: center;
    box-sizing: border-box; 
    width: 100%;
    padding: 25px; 
    border-radius: 10px 10px 0px 0px;
    background-color: #333333;
  }

  .navbar-card label{
    color: white;
    font-size: 18px;
    margin-right: 25px;
  }

  .navbar-card select {
    width: 150px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
  }

  .navbar-card #clearButton{
    color: #008cba;
    font-size: 14px;
    font-weight: 400; 
    outline: none; 
    background-color: transparent;
    border: none; 
    cursor: pointer; 
    padding: 0.5rem 1rem; 
    border-radius: 0.25rem; 
    transition: background-color 0.2s;
  }

  </style>
  