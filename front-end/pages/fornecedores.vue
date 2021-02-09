<template>
  <v-container fluid>
    <v-row justify="center" align="flex-start">
      <v-col class="buy-list-section" sm="12" md="12" lg="4" cols="12">
        <BuyList :estoque="estoque" />
      </v-col>
      <v-col class="fornecedores-section" sm="12" md="12" lg="8" cols="12">
        <Fornecedores
          :fornecedores="fornecedor"
          :estoque="estoque"
          :buyed-items="fornItens"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Fornecedores from '../components/estoque/Fornecedores.vue'
import BuyList from '../components/home/BuyList.vue'
export default {
  components: { Fornecedores, BuyList },

  async asyncData({ $axios }) {
    const [estoqueRes, fornecedorRes, fornItemRes] = await Promise.all([
      $axios.get('estoque'),
      $axios.get('fornecedor'),
      $axios.get('fornecedorItem')
    ])
    return {
      estoque: estoqueRes.data,
      fornecedor: fornecedorRes.data,
      fornItens: fornItemRes.data
    }
  }
}
</script>

<style scoped>
.buy-list-section {
  height: 600px;
}
</style>
