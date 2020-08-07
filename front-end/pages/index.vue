<template>
  <v-container fluid>
    <manutencao :equipamentos="equipamento" :stat="status" />
  </v-container>
</template>

<script>
import manutencao from '~/components/manutencao.vue'

export default {
  components: {
    manutencao
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
  }
}
</script>
