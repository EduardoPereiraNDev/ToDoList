<template>
  <div>
    <div class="popup-overlay" @click="cancelUpdate"></div>

    <div class="task-form popup">
      <div class="card-user">
        <h2>Proprietário da tarefa:</h2>
        <p>{{"Id:" + this.taskData.user.id }}</p>
        <p>{{"Nome:" + this.taskData.user.name }}</p>
        <p>{{"Email:" + this.taskData.user.email }}</p>
      </div>
      <div class="org">
        <h2>Tarefa:</h2>
        <img
          id="apagarIMG"
          src="../../public/img/lixeira.png"
          alt="Apagar Task"
          @click="deleteTask"
        />
      </div>
      <form @submit.prevent="updateTask">
        <div class="form-group">
          <label for="titulo">Título</label>
          <input
            v-model="taskData.title"
            type="text"
            id="titulo"
            placeholder="Digite o título da tarefa"
            required
          />
        </div>

        <div class="form-group">
          <label for="descricao">Descrição</label>
          <textarea
            v-model="taskData.description"
            id="descricao"
            placeholder="Descreva a tarefa"
            required
          ></textarea>
        </div>

        <div class="form-group">
          <label for="dataVencimento">Data de Vencimento</label>
          <input
            v-model="taskData.expiryDate"
            type="datetime-local"
            id="dataVencimento"
            required
          />
        </div>

        <div class="form-group">
          <button type="button" class="btn-cancel" @click="cancelUpdate">
            Cancelar
          </button>
        </div>

        <div class="form-group">
          <button type="submit" class="btn">Atualizar Tarefa</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from '@/services/api'

export default {
  name: "TaskUpdate",
  props: {
    taskClicked: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      taskData: { ...this.taskClicked },
    };
  },
  mounted(){
    console.log(this.taskData);
  },
  methods: {

    formatDate(date) {
      const d = new Date(date);
      return (
        d.getFullYear() +
        "-" +
        String(d.getMonth() + 1).padStart(2, "0") +
        "-" +
        String(d.getDate()).padStart(2, "0") +
        " " +
        String(d.getHours()).padStart(2, "0") +
        ":" +
        String(d.getMinutes()).padStart(2, "0")
      );
    },

    async updateTask() {
      try {

        this.taskData.expiryDate = this.formatDate(this.taskData.expiryDate);

        if(this.taskData.completionDate){
        this.taskData.completionDate = this.formatDate(this.taskData.completionDate);
        }

        console.log(this.taskData);

        const response = await api.put(`tasks/${this.taskData.id}`,this.taskData);
        this.$emit("taskChange", response.data);
      } catch (error) {
        console.error("Erro ao atualizar a tarefa:", error);
      }
    },
    async deleteTask() {
      try {
        const response = await api.delete(
          `tasks/${this.taskData.id}`,
          this.taskData
        );
        this.$emit("taskChange", response.data);
      } catch (error) {
        console.error("Erro ao deletar a tarefa:", error);
      }
    },
    cancelUpdate() {
      this.$emit("closePopup");
    },
  },
};
</script>

<style scoped>

.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 998;
}

.popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 999;
  background-color: #ffffff;
  padding: 25px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 90%;
}

.popup .card-user{
  background-color: #F5F5F5;
  border-radius: 8px;
  padding: 15px;
}

.popup .org {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}

.popup #apagarIMG {
  width: 25px;
  height: 25px;
}

.popup #apagarIMG:hover {
  cursor: pointer;
}

.popup h2 {
  margin: 0;
}

.popup .form-group {
  margin-bottom: 15px;
}

.popup label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.popup input,
.popup textarea {
  width:  94%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.popup textarea {
  resize: vertical;
  min-height: 100px;
}

.popup button {
  width: 100%;
  padding: 10px;
  background-color: #008cba;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

.popup button.btn-cancel {
  background-color: rgb(197, 51, 51);
}

.popup button.btn:hover {
  background-color: #005f73;
}
</style>
