<template>
  <v-container fluid class="ma-0 pa-0">
    <v-row class="ma-0 pa-0" justify="center" aling="center">
      <v-col class="users-side" xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <!-- <AboutAutos :autos="autos" /> -->
        <Users :usuarios="Users" />
      </v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <EquipNoEsc :equip="equipamento" />
      </v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <BuyList :estoque="estoque" />
      </v-col>
    </v-row>
    <!-- <v-btn class="user-btn" color="orange" @click="usersModal = !usersModal"
      >Usuarios</v-btn
    >
    <v-dialog
      v-model="usersModal"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card>
        <Users :usuarios="Users" />
        <div class="btn-section">
          <v-btn color="#43A047" text @click="usersModal = !usersModal">
            Sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog> -->
  </v-container>
</template>

<script>
import Users from '~/components/home/Users'
import EquipNoEsc from '~/components/home/EquipNoEsc'
import BuyList from '~/components/home/BuyList'
// import AboutAutos from '~/components/home/AboutAutos'

export default {
  components: {
    Users,
    EquipNoEsc,
    BuyList
    // AboutAutos
  },

  data() {
    return {
      usersModal: false
    }
  },

  async asyncData({ $axios }) {
    const [equipamentoRes, estoqueRes, userRes] = await Promise.all([
      $axios.get('equipamento'),
      $axios.get('estoque'),
      $axios.get('usuarios')
    ])
    return {
      equipamento: equipamentoRes.data,
      estoque: estoqueRes.data,
      Users: userRes.data.dados
    }
  },

  head() {
    return {
      title: 'Home'
    }
  },

  middleware: 'auth'
}
</script>

<style scoped>
.user-btn {
  position: absolute;
  bottom: 35px;
  right: 35px;
}
.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

@media screen and (max-width: 880px) {
  .user-btn {
    position: relative;
    margin-top: 200px;
    margin-left: 50px;
  }
}
</style>
