<template>
  <v-conteinaer fluid>
    <historico :equipamentos="equipamento" :status="status" />
  </v-conteinaer>
</template>

<script>
import historico from '~/components/historico/historico'

export default {
  components: {
    historico
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
      title: 'Historico'
    }
  }
}
</script>

<style scoped></style>
