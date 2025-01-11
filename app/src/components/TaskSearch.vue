<template>
  <form class="forms-search">
    <input
      v-model="searchTerm"
      @input="searchTasks"
      placeholder="Busque sua Tarefa ..."
      type="text"
      class="campo-text"
    />

  </form>
</template>

<script>
export default {
  name: "TaskSearch",
  props: {
    listType: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      searchTerm: "",
    };
  },
  methods: {
    searchTasks() {
      this.$emit("search", { listType: this.listType, searchTerm: this.searchTerm });
    },
  },
};
</script>

<style scoped>
.forms-search {
  display: flex;
  align-items: center;
  padding: 16px;
  background-color: #F5F5F5;
  height: 30px;
  border-radius: 4px;
  border-left: 4px solid #008CBA;
  margin-bottom: 12px;
}

.campo-text {
  flex: 1;
  background-color: #F5F5F5;
  color: #777777;
  font-weight: 300;
  border: none;
  outline: none;
  padding: 12px;
  margin-right: 8px;
  font-size: 14px;
}

.campo-text::placeholder {
  color: #777777;
}

</style>
