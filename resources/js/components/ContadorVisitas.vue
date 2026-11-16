<template>
<div class="contador">
        <h3>Visitas simuladas</h3>
        <p>Visitas: {{ Visitas }}</p>
        <button @click="aumentar">Simular Visita</button>
</div>
</template>
<script>
export default {
    data() {
        return {
            Visitas: 0
        }
    },
    methods: {
        aumentar() {
            this.Visitas++;
        }
    }
}
</script>
<style>
.contador {
    border: 1px solid #ccc;
    padding: 20px;
    margin: 20px;
}
button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
}
</style>