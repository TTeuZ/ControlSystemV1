<template>
  <v-container fluid>
    <div id="estoque-total">
      <span id="estoque-title"> LISTA DE COMPRAS </span>

      <div id="estoque-table">
        <div v-for="item in estoque" id="list-item" :key="item">
          <div v-if="item.flag === 1" id="buy-list">
            <span id="item-title"> {{ item.name.toUpperCase() }} </span>
            <span id="quantidade"> {{ item.quantidade }} </span>
          </div>
        </div>
      </div>
    </div>
  </v-container>
</template>

<script>
export default {
  props: {
    estoque: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
#estoque-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-content: center;
}

#estoque-title {
  font-family: 'Exo Regular';
  font-size: 20px;
  text-align: center;
}

#estoque-table {
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

  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#list-item {
  width: 100%;

  display: flex;
  justify-content: center;
  align-items: center;
}

#item-title {
  font-family: 'Exo Regular';
  font-size: 16px;

  padding-bottom: 5px;
  padding-top: 5px;
}

#buy-list {
  width: 100%;

  display: flex;
  flex-flow: row;
  justify-content: space-around;
  align-items: center;
}

@media screen and (max-width: 1262px) {
  #equip-table {
    max-height: 300px;
  }
}
</style>
