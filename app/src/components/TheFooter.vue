<template>
    <footer id="footer">
        <p> Sua Rotina e tarefas Mais Organizadas &copy; 2024 </p>
    </footer>
</template>

<script>
export default {
    name: "TheFooter"
}
</script>

<style scoped>
    #footer{
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #F5F5F5;
        height: 150px;
        width: 100%;
    }

    #footer p{
        color: #333333;
    }
</style>