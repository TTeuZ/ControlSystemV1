<template>
  <v-container id="total" fluid>
    <v-row class="ma-0 pa-0 search-section">
      <v-col id="search" lg="7" cols="11">
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Pesquisar"
          single-line
          hide-details
          @keyup="sortEquips(), (selectedEquip = '100')"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row id="ajust-itens" class="ma-0 pa-0" justify="center" aling="center">
      <v-col
        id="selector-side"
        class="ma-0 pa-0"
        xl="4"
        lg="4"
        md="11"
        sm="11"
        xs="11"
        cols="11"
      >
        <div id="itens-list">
          <div
            v-for="item in sortEquipamentos"
            id="list-item"
            :key="item"
            @click="
              ;(showForm = true),
                (selectedEquip = item.split(' ')[1]),
                (statusModal = false),
                $refs.status.filterStatus(item.split(' ')[1])
            "
          >
            <span
            <span v-if="filteredEquip[item.split(' ')[1]][0].done === '0'" id="item-title"> <!-- eslint-disable-line -->
              {{ filteredEquip[item.split(' ')[1]][0].name.toUpperCase() }}
            </span>
            <v-divider v-if="filteredEquip[item.split(' ')[1]][0].done === '0'" id="divider" /> <!-- eslint-disable-line -->
          </div>
        </div>

        <button id="add-item" @click="modal = !modal">
          <v-icon id="add-icon"> mdi-plus </v-icon>
        </button>
      </v-col>

      <v-col class="ma-0 pa-0" xl="7" lg="7" md="11" sm="11" xs="11" cols="11">
        <v-row id="info-side" class="ma-0 pa-0">
          <div id="title-section">
            <span id="info-title"> INFORMAÇÕES DO EQUIPAMENTO </span>
            <v-divider id="divider" />
          </div>

          <div v-if="selectedEquip !== '100'" id="info-geral">
            <span v-if="showForm" id="equip-title">
              {{ filteredEquip[selectedEquip][0].name.toUpperCase() }}
            </span>
            <div id="info-geral-2">
              <span v-if="showForm" class="info-text">
                Criado por:
                {{ filteredEquip[selectedEquip][0].user_name_created }}
              </span>
              <span v-if="showForm" class="info-text">
                Retornado por:
                {{ filteredEquip[selectedEquip][0].user_name_updated }}
              </span>
            </div>
          </div>

          <Status
            ref="status"
            :show-form="showForm"
            :selected-equip="selectedEquip"
            :status="stat"
            :status-enum="statusEnum"
            :filtered-equip="filteredEquip"
            :status-modal="statusModal"
          />

          <v-col v-if="showForm && selectedEquip !== '100'" id="buttons" class="ma-o pa-0" cols="12"> <!-- eslint-disable-line -->
            <v-btn
              class="form-btns"
              rounded
              depressed
              color="green"
              @click="statusModal = !statusModal"
            >
              ADICIONAR STATUS
            </v-btn>
            <v-btn
              class="form-btns"
              rounded
              depressed
              color="red"
              @click="sendToHistory()"
            >
              FINALIZAR
            </v-btn>
          </v-col>

          <!-- Dialog de criação de equipamento -->
          <v-dialog
            v-model="modal"
            max-width="800px"
            no-click-animation
            persistent
          >
            <v-card class="modal-card">
              <div class="modal-title-section">
                <span class="modal-title">
                  ADICIONE O EQUIPAMENTO
                </span>
                <div class="form">
                  <v-text-field
                    v-model="nome"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label="Equipamento"
                  />
                </div>
              </div>
              <div class="btn-section">
                <v-btn color="#43A047" text @click="criaEquipamento()">
                  Salvar
                </v-btn>
              </div>
              <div class="modal-title-section">
                <div class="form">
                  <v-select
                    v-model="returned"
                    :items="ids"
                    color="cyan darken-2"
                    label="Equipamento"
                  />
                </div>
              </div>
              <div class="btn-section">
                <v-btn color="#43A047" text @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="#43A047" text @click="catchId(), returnEquip()">
                  Retornar
                </v-btn>
              </div>
            </v-card>
          </v-dialog>
          <!-- Dialog de criação de equipamento -->
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Status from '~/components/manutencao/Status.vue'
export default {
  components: { Status },
  props: {
    equipamentos: {
      type: Array,
      required: true
    },
    stat: {
      type: Array,
      required: true
    },
    statusEnum: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      filteredEquip: this.equipamentos,
      search: '',
      sortEquipamentos: [],
      modal: false,
      statusModal: false,
      nome: '',

      showForm: false,
      selectedEquip: '100',

      statusFiltered: [],

      ids: [],
      returned: '',
      id: ''
    }
  },

  computed: {
    filteredEquips() {
      const self = this
      self.filteredEquip = self.equipamentos.filter((f) =>
        f[0].name.toLowerCase().includes(self.search.toLowerCase())
      )
      return self.filteredEquip
    }
  },

  mounted() {
    this.IDS()
    this.sortEquips()
  },

  methods: {
    close() {
      this.modal = false
      this.statusModal = false
    },
    closeAfterOk() {
      this.modal = false
      this.statusModal = false
      location.reload()
    },
    reload() {
      location.reload()
    },

    sortEquips() {
      if (this.equipamentos.length !== 0) {
        this.filteredEquip = this.equipamentos
        this.sortEquipamentos = []
        const self = this
        let i = 0
        this.filteredEquips.forEach((item) => {
          self.sortEquipamentos.push(item[0].name + ' ' + i.toString())
          i++
        })
        this.sortEquipamentos.sort(function(a, b) {
          return a.split(' ')[0].localeCompare(b.split(' ')[0])
        })
      } else {
        this.filteredEquip = []
      }
    },

    criaEquipamento() {
      const equipamento = {
        name: this.nome
      }
      this.$axios
        .$post('equipamento', equipamento)
        .then(() => {
          this.closeAfterOk()
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
    },

    returnEquip() {
      const retEquip = {
        done: 0
      }
      this.$axios.put('equipamento/' + this.id, retEquip).then(() => {
        this.reload()
      })
    },

    sendToHistory() {
      const ok = window.confirm(
        'Você tem certeza que deseja finalizar esse equipamento?'
      )
      if (ok) {
        const sendToHis = {
          done: 1
        }
        this.$axios
          .put(
            'equipamento/' + this.filteredEquip[this.selectedEquip][0].id,
            sendToHis
          )
          .then(() => {
            this.reload()
          })
      }
    },

    IDS() {
      const self = this
      this.equipamentos.forEach(function(item) {
        if (item[0].done === '1') {
          self.ids.push(item[0].name)
        }
      })
    },

    catchId() {
      const self = this
      this.equipamentos.forEach(function(item) {
        if (self.returned === item[0].name) {
          self.id = item[0].id
        }
      })
    }
  }
}
</script>

<style scoped>
.search-section {
  width: 96%;
  display: flex;
  justify-content: flex-end;
}

#ajust-itens {
  margin-top: 40px !important;
}

#selector-side {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}

#itens-list {
  border: 1px solid black;
  width: 400px;
  height: auto;
  max-height: 680px;
  overflow: auto;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#list-item {
  width: 100%;
  height: auto;
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

#divider {
  background-color: black;
  width: 100%;
}

#item-title {
  font-family: 'Exo Regular';
  font-size: 16px;
  padding-bottom: 15px !important;
  padding-top: 15px !important;
}

#add-item {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 1px solid black;
  margin-top: 25px;
  background-color: black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#add-item:focus {
  outline-style: none;
}

#add-icon {
  color: orange;
}

#info-side {
  border: 1px solid black;
  height: 690px;
  display: flex;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#title-section {
  display: flex;
  flex-flow: column;
  width: 100%;
  height: 55px;
  justify-self: start;
}

#info-title {
  font-family: 'Exo Regular';
  font-size: 36px;
  margin-left: 20px;
}

#info-geral {
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

#info-geral-2 {
  display: flex;
  flex-flow: column;
  justify-content: start;
  align-items: flex-end;
  margin-right: 50px;
  width: 50%;
}

.info-text {
  font-family: 'Exo Regular';
  font-size: 16px;
}

#divider {
  background-color: black;
}

#equip-title {
  font-family: 'Exo Regular';
  font-size: 30px;
  width: 50%;
  height: 40px;
  margin-left: 30px;
}

#buttons {
  display: flex;
  flex-flow: row;
  justify-content: flex-end;
}

.form-btns {
  margin-right: 25px;
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

.form {
  width: 80%;
  padding-top: 20px;
  padding-bottom: 30px;
}

.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.icons {
  color: black;
  font-size: 1em;
}

@media screen and (max-width: 1262px) {
  #itens-list {
    width: 100%;
    max-height: 240px;
  }
  #add-item {
    margin-bottom: 25px;
  }
  #info-side {
    height: auto;
  }
  #buttons {
    margin: 20px 0;
  }
  #equip-title {
    margin-top: 15px;
    margin-bottom: 15px;
  }
  .search-section {
    width: 100%;
    justify-content: center;
  }
}

@media screen and (max-width: 710px) {
  #info-title {
    font-size: 26px;
  }
}

@media screen and (max-width: 564px) {
  #buttons {
    flex-flow: column;
    justify-content: center;
    align-items: center;
  }
  .form-btns {
    margin-right: 0;
    margin-top: 20px;
    width: 200px;
  }
  #info-geral {
    flex-flow: column;
    align-items: flex-start;
  }
  #info-geral-2 {
    align-items: flex-start;
    margin-left: 30px;
    width: 100%;
  }
  #equip-title {
    width: 100%;
  }
}

@media screen and (max-width: 530px) {
  #info-title {
    font-size: 20px;
  }
}

@media screen and (max-width: 430px) {
  #info-title {
    font-size: 15px;
    margin-left: 10px;
  }
  .modal-title {
    font-size: 20px;
  }
}
</style>
