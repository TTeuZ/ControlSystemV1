<template>
  <v-conteinaer fluid>
    <Historico :equipamentos="equipamento" :status="status" />
  </v-conteinaer>
</template>

<script>
import historico from '~/components/historico/Historico'

export default {
  components: {
    Historico
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
