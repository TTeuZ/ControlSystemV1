<template>
  <v-container fluid>
    <Manutencao
      :equipamentos="equipamento"
      :stat="status"
      :status-enum="statusEnum"
    />
  </v-container>
</template>

<script>
import Manutencao from '~/components/manutencao/Equipamento.vue'

export default {
  components: {
    Manutencao
  },

  async asyncData({ $axios }) {
    const [equipamentoRes, statusRes, statusEnumRes] = await Promise.all([
      $axios.get('equipamento'),
      $axios.get('status'),
      $axios.get('statusEnum')
    ])
    return {
      equipamento: equipamentoRes.data,
      status: statusRes.data,
      statusEnum: statusEnumRes.data
    }
  },

  head() {
    return {
      title: 'Manutenção'
    }
  }
}
</script>
