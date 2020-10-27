<template>
  <v-container fluid>
    <Estoque :itens="Estoque" :historico="Historico" />
  </v-container>
</template>

<script>
import estoque from '~/components/estoque/Estoque'

export default {
  components: {
    Estoque
  },

  async asyncData({ $axios }) {
    const [estoqueRes, atthisRes] = await Promise.all([
      $axios.get('estoque'),
      $axios.get('atthis')
    ])
    return {
      Estoque: estoqueRes.data,
      Historico: atthisRes.data
    }
  },

  head() {
    return {
      title: 'Estoque'
    }
  }
}
</script>
