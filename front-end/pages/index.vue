<template>
  <v-container fluid class="ma-0 pa-0">
    <div id="footer">
      <span id="footer-title">PLK - Feito por Paulo Mateus Luza Alves</span>
    </div>
  </v-container>
</template>

<script>
export default {
  components: {},

  middleware: 'auth'
}
</script>

<style scoped>
#footer {
  height: 80px;
  width: 100%;

  background-color: orange;

  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;

  position: absolute;
  bottom: 0%;
}

#footer-title {
  font-family: 'Exo Regular';
  font-size: 18px;
}

@media screen and (max-width: 350px) {
  #footer-title {
    font-size: 16px;
  }
}
</style>
