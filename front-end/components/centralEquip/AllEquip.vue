<template>
  <v-container fluid>
    <v-row class="ma-0 pa-0" justify="center" aling="center">
      <v-col
        class="border filters-box"
        xl="3"
        lg="3"
        md="3"
        sm="12"
        xs="12"
        cols="12"
      >
        <div style="width:100%">
          <div class="ajust">
            <v-select
              v-model="filterSelected"
              :items="selectors"
              color="cyan darken-2"
              label="Tipo"
            />
          </div>
          <div class="ajust">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Pesquisar"
              single-line
              hide-details
            ></v-text-field>
          </div>
        </div>
        <v-btn
          class="add-btn"
          rounded
          color="orange"
          @click="addModal = !addModal"
        >
          Adicionar
        </v-btn>
      </v-col>
      <v-col class="itens-box" xl="9" lg="9" md="9" sm="12" xs="12" cols="12">
        <div class="header-section">
          <span class="text"> Nome </span>
          <span class="text"> Situação </span>
          <span class="text"> Alterado por </span>
          <span class="text"> Data </span>
          <span class="text"> Data de criação </span>
        </div>
        <v-divider class="divider" />
        <div class="all-itens">
          <div v-for="item in filterItens" :key="item">
            <div class="infos-box">
              <span class="text"> {{ item.name.toUpperCase() }} </span>
              <span class="text"> {{ item.situacao }} </span>
              <span class="text"> {{ item.user_name }} </span>
              <span class="text"> {{ ajustTime(item.updated_at) }} </span>
              <span class="text"> {{ ajustTime(item.created_at) }} </span>
            </div>
          </div>
          <!-- Menu -->
          <!-- Menu -->
        </div>
      </v-col>
    </v-row>

    <!-- Modal de adição -->
    <v-dialog
      v-model="addModal"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            ADICIONE UM EQUIPAMENTO
          </span>
          <div class="form">
            <v-text-field
              v-model="name"
              label="Equipamento"
              single-line
              hide-details
            ></v-text-field>
          </div>
        </div>
        <div class="btn-section">
            <v-btn color="#43A047" text @click="addEquip()"> <!-- eslint-disable-line -->
            Adicionar
          </v-btn>
          <v-btn color="#43A047" text @click="addModal = !addModal"> <!-- eslint-disable-line -->
            Sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Modal de adição -->
  </v-container>
</template>

<script>
export default {
  props: {
    equipamentos: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      addModal: false,
      name: '',

      search: '',
      filterSelected: 'Nome',
      selectors : ['Nome', 'Situação', 'Alterado por', 'Data', 'Data de criação'], /*eslint-disable-line*/

      allEquipsAtt: this.equipamentos,
      allEquipFiltered: []
    }
  },

  computed: {
    filterItens() {
      const self = this
      switch (this.filterSelected) {
        case 'Nome': {
          self.allEquipFiltered = self.allEquipsAtt.filter((f) =>
            f.name.toLowerCase().includes(self.search.toLowerCase())
          )
          break
        }
        case 'Situação': {
          self.allEquipFiltered = self.allEquipsAtt.filter((f) =>
            f.situacao.toLowerCase().includes(self.search.toLowerCase())
          )
          break
        }
        case 'Alterado por': {
          self.allEquipFiltered = self.allEquipsAtt.filter((f) =>
            f.user_name.toLowerCase().includes(self.search.toLowerCase())
          )
          break
        }
        case 'Data': {
          self.allEquipFiltered = self.allEquipsAtt.filter(
            (f) =>
            this.ajustTime(f.updated_at.toLowerCase()).includes(self.search.toLowerCase()) /*eslint-disable-line*/
          )
          break
        }
        case 'Data de criação': {
          self.allEquipFiltered = self.allEquipsAtt.filter(
            (f) =>
            this.ajustTime(f.created_at.toLowerCase()).includes(self.search.toLowerCase()) /*eslint-disable-line*/
          )
          break
        }
      }
      return self.allEquipFiltered
    }
  },

  methods: {
    ajustTime(time) {
      let newTime = ''
      newTime = time.split(' ')[0].split('-').reverse().join('/') + ' às ' + time.split(' ')[1] /*eslint-disable-line*/
      return newTime
    },
    addEquip() {
      const newEquip = {
        name: this.name
      }
      this.$axios.post('all_equip', newEquip).then(() => {
        this.$axios.get('all_equip').then((res) => {
          this.allEquipsAtt = res.data
        })
        this.addModal = false
        this.name = ''
      })
    }
  }
}
</script>

<style scoped>
.border {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}
.text {
  font-family: 'Exo Regular';
  font-size: 14px;
  width: 20%;
  text-align: center;
}
.divider {
  background-color: black;
}
.filters-box {
  height: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-flow: column;
}
.add-btn {
  width: 100%;
}
.ajust {
  width: 99%;
}
.itens-box {
  padding-left: 45px;
}
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
}
.infos-box {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 5px 0px;
}
.all-itens {
  max-height: 750px;
  overflow: auto;
}
.modal-title-section {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}
.modal-title {
  font-family: 'Exo Regular';
  font-size: 30px;
  margin-top: 15px;
}
.modal-text {
  font-family: 'Exo Regular';
  font-size: 20px;
}
.form {
  width: 90%;
  display: flex;
  justify-content: center;
  align-items: center;
  padding-bottom: 15px;
}
.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
</style>
