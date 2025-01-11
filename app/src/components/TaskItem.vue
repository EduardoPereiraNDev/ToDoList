<template>
  <div class="org">
    <input 
      type="checkbox" 
      :checked="task.completionDate !== null" 
      @change="taskStatus"
    />
    <div class="tasks" @click="openTask">
      <p>{{ task.title }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "TaskItem",

  props: {
    task: {
      type: Object,
      required: true,
    },
  },

  methods: {
    taskStatus() {
      this.$emit("taskStatus", this.task); 
    },

    openTask() {
      this.$emit("taskClicked", this.task); 
    },
  },
};
</script>

<style scoped>

 
    .org{
        display: flex;
        justify-content: space-around;
        padding: 20px;
        width: max-content;
        border-radius: 10px;
        background-color: #F5F5F5;
        margin-top: 15px;
    }

    .org input[type="checkbox"] {
        width: 15px;
        height: 15px; 
        margin-right: 20px;
        accent-color: #4caf50; 
        border: 2px solid #ccc; 
        border-radius: 4px;
        cursor: pointer; 
    }

    .org input[type="checkbox"]:checked {
        border-color: #4caf50; 
    }

    .org input[type="checkbox"]:hover {
        border-color: #2196f3; 
    }

    .org input[type="checkbox"]:focus {
        outline: 2px solid #ff9800; 
    }

    .tasks p{
        color: #333333;
    }

</style>