<template>
  <v-container fluid class="ma-0 pa-0">
    <v-row class="ma-0 pa-0" justify="center" aling="center">
      <v-col class="users-side" xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <AboutAutos :autos="autos" />
      </v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <EquipNoEsc :equip="equipamento" />
      </v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <BuyList :estoque="estoque" />
      </v-col>
    </v-row>
    <v-btn class="user-btn" color="orange" @click="usersModal = !usersModal"
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
    </v-dialog>
  </v-container>
</template>

<script>
import users from '~/components/home/Users'
import equipNoEsc from '~/components/home/EquipNoEsc'
import buyList from '~/components/home/BuyList'
import AboutAutos from '~/components/home/AboutAutos'

export default {
  components: {
    Users,
    EquipNoEsc,
    BuyList,
    AboutAutos
  },

  data() {
    return {
      usersModal: false
    }
  },

  async asyncData({ $axios }) {
    const [
      equipamentoRes,
      statusRes,
      estoqueRes,
      userRes,
      autosRes
    ] = await Promise.all([
      $axios.get('equipamento'),
      $axios.get('status'),
      $axios.get('estoque'),
      $axios.get('usuarios'),
      $axios.get('autoeletrica')
    ])
    return {
      equipamento: equipamentoRes.data,
      status: statusRes.data,
      estoque: estoqueRes.data,
      Users: userRes.data.dados,
      autos: autosRes.data
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
