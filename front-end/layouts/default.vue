<template>
  <v-app>
    <div id="nav-bar">
      <span id="title"> PLK - MANUTENÇÃO </span>
    </div>
    <nuxt></nuxt>
  </v-app>
</template>

<script>
export default {
  data() {
    return {}
  }
}
</script>

<style scoped>
#nav-bar {
  height: 80px;
  width: 100%;

  background-color: orange;

  display: flex;
  justify-content: start;
  align-items: center;
}

#title {
  font-size: 50px;
  color: black;
  font-family: 'Exo Regular';

  margin-left: 90px;
}

@media screen and (max-width: 640px) {
  #title {
    font-size: 40px;
  }
}

@media screen and (max-width: 530px) {
  #title {
    font-size: 30px;
  }
}

@media screen and (max-width: 450px) {
  #title {
    font-size: 20px;
  }
}
</style>
