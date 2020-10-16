<template>
  <v-container fluid>
    <estoque :itens="Estoque" :historico="Historico" />
  </v-container>
</template>

<script>
import estoque from '~/components/estoque/estoque'

export default {
  components: {
    estoque
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
