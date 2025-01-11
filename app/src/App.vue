<template>
  <div>
    <Navbar :logo ="logo_src"  alt="app_name"/>
    <router-view/> 
    <TheFooter/>
  </div>
</template>
<script>
import Navbar from './components/Navbar.vue'
import TheFooter from './components/TheFooter.vue'
export default {
  components: {
    Navbar,
    TheFooter
  },
  data(){
    return{
      logo_src: "/img/logo_todolist.png",
      app_name: "To Do List"
    }
  }
}
</script>

<style>
  * {
    padding: 0;
    margin: 0;
    border: 0;
    box-sizing: 0;
}
</style>
