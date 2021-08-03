<template>
  <v-container fluid>
    <AllEquip :equipamentos="all_equip" />
  </v-container>
</template>

<script>
import AllEquip from '~/components/centralEquip/AllEquip.vue'

export default {
  components: {
    AllEquip
  },

  async asyncData({ $axios }) {
    const [allEquipRes] = await Promise.all([$axios.get('all_equip')])
    return {
      all_equip: allEquipRes.data
    }
  },

  head() {
    return {
      title: 'Central de Equipamentos'
    }
  }
}
</script>
