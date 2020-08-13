<template>
  <v-container id="total" fluid>
    <!-- <div id="search">
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Pesquisar"
        single-line
        hide-details
      ></v-text-field>
    </div> -->

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
            v-for="(item, i) in equipamentos"
            id="list-item"
            :key="item"
            @click=";(showForm = true), (selectedEquip = i), filterStatus()"
          >
            <span v-if="item[0].done === 0" id="item-title">
              {{ item[0].name.toUpperCase() }}
            </span>
            <v-divider v-if="item[0].done === 0" id="divider" />
          </div>
        </div>

        <button id="add-item" @click="modal = !modal">
          <v-icon id="add-icon"> mdi-plus </v-icon>
        </button>
      </v-col>

      <!-- Dialog de criação de equipamento -->
      <v-dialog v-model="modal" max-width="800px" no-click-animation persistent>
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
            <v-btn color="#43A047" text @click="close">
              Cancelar
            </v-btn>
            <v-btn color="#43A047" text @click="criaEquipamento()">
              Salvar
            </v-btn>
          </div>
        </v-card>
      </v-dialog>
      <!-- Dialog de criação de equipamento -->

      <v-col class="ma-0 pa-0" xl="7" lg="7" md="11" sm="11" xs="11" cols="11">
        <v-row id="info-side" class="ma-0 pa-0">
          <div id="title-section">
            <span id="info-title"> INFORMAÇÕES DO EQUIPAMENTO </span>
            <v-divider id="divider" />
          </div>

          <span v-if="showForm" id="equip-title">
            {{ equipamentos[selectedEquip][0].name.toUpperCase() }}
          </span>

          <v-col
            v-if="showForm"
            class="ma-0 pa-0 table-section"
            xl="6"
            lg="6"
            md="6"
            sm="12"
            xs="12"
            cols="12"
          >
            <div class="table-total">
              <span class="table-title">STATUS ATUAL</span>
              <div class="table">
                <div v-for="status in statusFiltered" :key="status">
                  <div v-if="status.flag === 0" class="status">
                    <span class="status-text">
                      {{ status.info }}
                    </span>
                    <span class="status-text-time">
                      {{ status.created_at }}
                    </span>
                    <div class="change-flag">
                      <v-icon
                        class="change-icon"
                        @click=";(flagId = status.id), feito()"
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </v-col>
          <v-col
            v-if="showForm"
            class="ma-0 pa-0 table-section"
            xl="6"
            lg="6"
            md="6"
            sm="12"
            xs="12"
            cols="12"
          >
            <div class="table-total">
              <span class="table-title">FEITO</span>
              <div class="table">
                <div v-for="status in statusFiltered" :key="status">
                  <div v-if="status.flag === 1" class="status">
                    <div class="change-flag">
                      <v-icon
                        class="change-icon"
                        @click=";(flagId = status.id), voltarStatus()"
                      >
                        mdi-arrow-left-bold
                      </v-icon>
                    </div>
                    <span class="status-text-time">
                      {{ status.updated_at }}
                    </span>
                    <span class="status-text">
                      {{ status.info }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </v-col>
          <v-col v-if="showForm" id="buttons" class="ma-o pa-0" cols="12">
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

          <!-- Dialog de criação de status -->
          <v-dialog
            v-model="statusModal"
            max-width="800px"
            no-click-animation
            persistent
          >
            <v-card class="modal-card">
              <div class="modal-title-section">
                <span class="modal-title">
                  ADICIONE O STATUS
                </span>
                <div class="form">
                  <v-text-field
                    v-model="infoStatus"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label="Status"
                  />
                </div>
              </div>
              <div class="btn-section">
                <v-btn color="#43A047" text @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="#43A047" text @click="criaStatus()">
                  Salvar
                </v-btn>
              </div>
            </v-card>
          </v-dialog>
          <!-- Dialog de criação de status -->
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: {
    equipamentos: {
      type: Array,
      required: true
    },
    stat: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      modal: false,
      statusModal: false,
      nome: '',
      infoStatus: '',

      showForm: false,
      selectedEquip: '',

      statusFiltered: [],
      flagId: ''
    }
  },

  // computed: {
  //   filtereditens() {
  //     const listItens = this
  //     return this.itens.filter(function(p) {
  //       return p.label.toLowerCase().includes(listItens.search.toLowerCase())
  //     })
  //   }
  // },

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
          const { mensagem } = !!response && response.data
          this.$toast.error(mensagem, { duration: 5000 })
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
            'equipamento/' + this.equipamentos[this.selectedEquip][0].id,
            sendToHis
          )
          .then(() => {
            this.reload()
          })
      }
    },

    filterStatus() {
      return (this.statusFiltered = this.stat.filter(
        (equip) =>
          equip.equipamento_id === this.equipamentos[this.selectedEquip][0].id
      ))
    },

    feito() {
      const attFlag = {
        flag: 1
      }

      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        this.reload()
      })
    },

    voltarStatus() {
      const attFlag = {
        flag: 0
      }

      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        this.reload()
      })
    },

    criaStatus() {
      const infoStatus = {
        info: this.infoStatus,
        equipamento_id: this.equipamentos[this.selectedEquip][0].id
      }

      this.$axios
        .$post('status', infoStatus)
        .then(() => {
          this.closeAfterOk()
        })
        .catch(({ response }) => {
          const { mensagem } = !!response && response.data
          this.$toast.error(mensagem, { duration: 5000 })
        })
    }
  }
}
</script>

<style scoped>
#search {
  margin-bottom: 30px;
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

#divider {
  background-color: black;
}

#equip-title {
  font-family: 'Exo Regular';
  font-size: 30px;

  width: 100%;
  height: 40px;

  margin-left: 30px;
}

.table-section {
  display: flex;
  justify-content: center;
  align-items: center;
}

.table-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;

  width: 100%;
}

.table-title {
  font-family: 'Exo Regular';
  font-size: 23px;
}

.table {
  border: 1px solid black;

  width: 80%;
  height: 380px;

  overflow: auto;
}

#buttons {
  display: flex;
  flex-flow: row;
  justify-content: flex-end;
}

.form-btns {
  margin-right: 25px;
}

.status {
  width: 100%;
  height: auto;
  min-height: 40px;

  border-bottom: 1px solid black;

  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
}

.status-text {
  padding: 0 10px;

  font-size: 18px;
  font-family: 'Exo Regular';
}

.status-text-time {
  font-size: 14px;
  font-family: 'Exo Regular';
}

.change-flag {
  height: 100%;
  width: 25px;
}

.change-icon {
  color: green;
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
}

@media screen and (max-width: 962px) {
  .table-title {
    margin-top: 15px;
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
}
</style>
