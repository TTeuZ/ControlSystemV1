<template>
  <v-container fluid>
    <Manutencao :equipamentos="equipamento" :stat="status" />
  </v-container>
</template>

<script>
import Manutencao from '~/components/manutencao/Equipamento.vue'

export default {
  components: {
    Manutencao
  },

  async asyncData({ $axios }) {
    const [equipamentoRes, statusRes] = await Promise.all([
      $axios.get('equipamento'),
      $axios.get('status')
    ])
    return {
      equipamento: equipamentoRes.data,
      status: statusRes.data
    }
  },

  head() {
    return {
      title: 'Manutenção'
    }
  }
}
</script>
