<template>
  <nav class="navbar-style">
    <div class="navbar-navigation">
      <img :src="logo" :alt="alt" id="logo">
      <router-link v-if="!isLoginPage" to="/home">Home</router-link>
      <router-link v-if="!isLoginPage" to="/tasks">Tasks</router-link> 
    </div>
    <LogoutButton v-if="!isLoginPage"/>
  </nav>
</template>
<script>
  import LogoutButton from "@/components/LogoutButton.vue";
  import { computed } from 'vue';
  import { useRoute } from 'vue-router';
    export default {
      name: "Navbar",
      components: {
        LogoutButton,
        isAdmin: false,
      },
      props: ["logo","alt"],
      setup() {
        const route = useRoute();
        const isLoginPage = computed(() => {
          return route.path === '/';
        });
        return {
          isLoginPage,
        };
      },
    }
</script>
<style scoped>

 .navbar-style{
  background-color: #F5F5F5;
  padding: 15px 50px;
  display: flex;
  justify-content: space-between ;
  align-items: center;
 }

 .navbar-style #logo{
  width: 80px;
 }

 .navbar-style a{
  color: #333333;
  text-decoration:  none;
  margin: 15px;
  transition: .5s;
  font-size: 22px;
 }

 .navbar-style a:hover{
  color: #FF5722;
 }

 .navbar-style .navbar-navigation{
  display: flex;
  align-items: center;
 }

</style>