<template>
  <v-container fluid>
    <div id="equip-total">
      <span id="equip-title"> EQUIPAMENTOS NO ESCRITORIO </span>

      <div id="equip-table">
        <nuxt-link to="/manutencao">
          <div v-for="item in equip" id="list-item" :key="item">
            <span id="item-title"> {{ item[0].name.toUpperCase() }} </span>
          </div>
        </nuxt-link>
      </div>
    </div>
  </v-container>
</template>

<script>
export default {
  props: {
    equip: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
#equip-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-content: center;
}

#equip-title {
  font-family: 'Exo Regular';
  font-size: 20px;
  text-align: center;
}

#equip-table {
  border: 1px solid black;

  display: flex;
  flex-flow: column;
  justify-content: center;
  align-content: center;

  margin-top: 30px;

  width: 100%;
  max-width: 550px;
  height: auto;
  max-height: 600px;

  align-self: center;

  overflow: auto;
}

#list-item {
  width: 100%;
  min-height: 40px;

  display: flex;
  justify-content: center;
  align-items: center;

  border-bottom: 1px solid black;
}

#item-title {
  font-family: 'Exo Regular';
  font-size: 16px;
}

a {
  text-decoration: none;
  color: black;
}

@media screen and (max-width: 1262px) {
  #equip-table {
    max-height: 300px;
  }
}
</style>
