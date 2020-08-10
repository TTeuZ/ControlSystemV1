<template>
  <v-container fluid>
    <div id="estoque-total">
      <span id="estoque-title"> EQUIPAMENTOS NO ESCRITORIO </span>

      <div id="estoque-table">
        <div v-for="item in estoque" id="list-item" :key="item">
          <span id="item-title"> {{ item.name.toUpperCase() }} </span>
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

@media screen and (max-width: 1262px) {
  #equip-table {
    max-height: 300px;
  }
}
</style>
