<template>
  <div class="main-container">
    <div class="row">
      <div class="data-Group">
        <h1>Suas Tarefas Pendentes:</h1>
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
        <h1>Suas Tarefas Concluídas:</h1>
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
    <TaskView
      v-if="showUpdatePopup"
      :taskClicked="selectedTask"
      @closePopup="closeUpdatePopup"
    />
  </div>
</template>

<script>
import TaskForm from "@/components/TaskForm.vue";
import TaskItem from "@/components/TaskItem.vue";
import TaskSearch from "@/components/TaskSearch.vue";
import TaskView from "@/components/TaskView.vue";
import api from '@/services/api'

export default {
  name: "Home",
  components: {
    TaskItem,
    TaskSearch,
    TaskForm,
    TaskView,
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
    };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {

    async fetchTasks() {
      try {
        const response = await api.get("tasks");
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

    openUpdatePopup(task) {
      this.selectedTask = task;
      this.showUpdatePopup = true;
    },
    closeUpdatePopup() {
      this.showUpdatePopup = false;
      this.selectedTask = null;
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
  width: 100%;
}

</style>
