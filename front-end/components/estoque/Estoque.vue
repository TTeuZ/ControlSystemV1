<template>
  <v-container fluid>
    <v-row
      id="estoque-total"
      class="ma-0 pa-0"
      justify="space-around"
      aling="center"
    >
      <v-col
        class="ma-0 pa-0 tables"
        xl="3"
        lg="3"
        md="3"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="table-title"> FILTROS </span>
        <div id="options-side">
          <div id="search">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Pesquisar"
              single-line
              hide-details
            ></v-text-field>
          </div>

          <div id="new-item">
            <v-btn rounded depressed color="blue" @click="logModal = true">
              LOG
            </v-btn>

            <v-btn rounded depressed color="green" @click="newItemModal = true">
              NOVO ITEM
            </v-btn>
          </div>

          <!-- Dialog de log -->
          <v-dialog
            v-model="logModal"
            max-width="1600px"
            no-click-animation
            persistent
          >
            <v-card class="modal-card">
              <div class="modal-title-section">
                <span class="modal-title">
                  LOG DO ESTOQUE
                </span>
              </div>
              <v-row
                id="log-total"
                class="ma-0 pa-0"
                justify="center"
                align="center"
              >
                <v-col
                  class="log-table-total"
                  xl="6"
                  lg="6"
                  md="6"
                  sm="11"
                  xs="11"
                  cols="11"
                >
                  <span class="log-title"> CRIADOS </span>
                  <div class="log-table">
                    <div v-for="item in itens" :key="item">
                      <div class="log-item">
                        <span class="log-text"> Nome: {{ item.name }} </span>
                        <span class="log-text">
                          {{
                            item.created_at
                              .split(' ')[0]
                              .split('-')
                              .reverse()
                              .join('/') +
                              ' às ' +
                              item.created_at.split(' ')[1]
                          }}
                        </span>
                        <span class="log-text">
                          {{ item.user_name_created }}
                        </span>
                      </div>
                      <v-divider class="divider" />
                    </div>
                  </div>
                </v-col>
                <v-col
                  class="log-table-total"
                  xl="6"
                  lg="6"
                  md="6"
                  sm="11"
                  xs="11"
                  cols="11"
                >
                  <span class="log-title"> ATUALIZAÇÕES </span>
                  <div class="log-table">
                    <div v-for="item in historico" :key="item">
                      <div class="log-item">
                        <span class="log-text"> Nome: {{ item.name }} </span>
                        <span class="log-text">
                          {{
                            item.updated_at
                              .split(' ')[0]
                              .split('-')
                              .reverse()
                              .join('/') +
                              ' às ' +
                              item.updated_at.split(' ')[1]
                          }}
                        </span>
                        <span class="log-text">
                          {{ item.user_name_updated }}
                        </span>
                        <span class="log-text">
                          quant: {{ item.quantidade }}
                        </span>
                      </div>
                      <v-divider class="divider" />
                    </div>
                  </div>
                </v-col>
              </v-row>
              <div class="btn-section">
                <v-btn color="blue" text @click="close">
                  SAIR
                </v-btn>
              </div>
            </v-card>
          </v-dialog>
          <!-- Dialog de log -->
          <!-- dialog para criar -->
          <v-dialog
            v-model="newItemModal"
            max-width="800px"
            no-click-animation
            persistent
          >
            <v-card class="modal-card">
              <div class="modal-title-section">
                <span class="modal-title">
                  ADICIONE UM ITEM
                </span>
                <div class="form">
                  <v-text-field
                    v-model="newItem.name"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Nome"
                  />
                  <v-text-field
                    v-model="newItem.quantidade"
                    v-maska="'#*'"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Quantidade"
                  />
                  <v-text-field
                    v-model="newItem.quantidade_min"
                    v-maska="'#*'"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Minimo"
                  />
                  <v-select
                    v-model="newItem.tipe"
                    :items="tipes"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Tipo"
                  />
                </div>
              </div>
              <div class="btn-section">
                <v-btn color="#43A047" text @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="#43A047" text @click="criaItem()">
                  Salvar
                </v-btn>
              </div>
            </v-card>
          </v-dialog>
          <!-- dialog para criar -->
          <!-- dialog para atualizar -->
          <v-dialog
            v-model="attModel"
            max-width="800px"
            no-click-animation
            persistent
          >
            <v-card class="modal-card">
              <div class="modal-title-section">
                <span class="modal-title">
                  ATUALIZE O ITEM
                </span>
                <div class="form">
                  <v-text-field
                    v-model="editedItem.name"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Nome"
                  />
                  <v-text-field
                    v-model="editedItem.quantidade"
                    v-maska="'#*'"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Quantidade"
                  />
                  <v-text-field
                    v-if="admin"
                    v-model="editedItem.quantidade_min"
                    v-maska="'#*'"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Minimo"
                  />
                  <v-select
                    v-model="editedItem.tipe"
                    :items="tipes"
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Tipo"
                  />
                </div>
              </div>
              <div class="btn-section">
                <v-btn color="#43A047" text @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="#43A047" text @click="atualizaItem()">
                  Salvar
                </v-btn>
              </div>
            </v-card>
          </v-dialog>
          <!-- dialog para atualizar -->
        </div>
      </v-col>
      <v-col
        class="ma-0 pa-0 tables"
        xl="7"
        lg="7"
        md="7"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="table-title"> ESTOQUE </span>
        <div id="list-side">
          <v-data-table
            id="data-table"
            :headers="headers"
            :items="estoqueItens"
            :search="search"
          >
            <template v-slot:item.action="{ item }">
              <v-icon
                medium
                class="mr-2"
                color="primary"
                @click="attModalChange(item)"
              >
                mdi-circle-edit-outline
              </v-icon>
              <v-icon medium color="error" @click="deletaItem(item)">
                mdi-delete
              </v-icon>
              <v-menu right close-on-click>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    depressed
                    height="25"
                    width="25"
                    fab
                    :class="item.color"
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <v-list>
                  <v-list-item v-for="color in colors" :key="color">
                    <v-list-item-title @click="updatePriorit(color, item.id)">{{
                      color
                    }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: {
    itens: {
      type: Array,
      required: true
    },
    historico: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      attModel: false,
      admin: false,
      newItemModal: false,
      logModal: false,

      search: '',
      caboFil: false,
      lacreFil: false,

      cabos: false,
      lacre: false,
      todos: true,

      editedItem: {
        id: 0,
        name: '',
        quantidade: '',
        quantidade_min: '',
        tipe: ''
      },

      newItem: {
        id: 0,
        name: '',
        quantidade: '',
        quantidade_min: '',
        tipe: ''
      },

      headers: [
        { text: 'Nome', value: 'name' },
        { text: 'Quantidade', value: 'quantidade' },
        { text: 'Tipo', value: 'tipe' },
        { text: '', value: 'action', align: 'right', sortable: false }
      ],

      tipes: ['Lacre', 'Cabo'],
      colors: ['Verde', 'Amarelo', 'Vermelho']
    }
  },

  computed: {
    estoqueItens() {
      return this.itens.map((e) => ({
        id: e.id,
        name: e.name,
        quantidade: e.quantidade,
        quantidade_min: e.quantidade_min,
        color: e.color,
        flag: e.flag,
        tipe: e.tipe
      }))
    }
  },
  methods: {
    attModalChange(item) {
      this.attModel = true
      this.editedItem = Object.assign({}, item)
      if (
        this.$auth.$state.user.name === 'pauloalves' ||
        this.$auth.$state.user.name === 'joao'
      ) {
        this.admin = true
      } else {
        this.admin = false
      }
    },

    close() {
      this.attModel = false
      this.newItemModal = false
      this.logModal = false
    },

    deletaItem(item) {
      const ok = window.confirm(
        'Você tem certeza que deseja deletar esse item?'
      )
      if (ok) {
        this.$axios
          .delete('estoque/' + item.id)
          .then((res) => {
            this.itens = this.itens.filter((e) => e.id !== item.id)
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    atualizaItem() {
      const attItem = {
        name: this.editedItem.name,
        quantidade: this.editedItem.quantidade,
        quantidade_min: this.editedItem.quantidade_min,
        tipe: this.editedItem.tipe
      }
      this.$axios
        .put('estoque/' + this.editedItem.id, attItem)
        .then((res) => {
          this.itens = this.itens.map((e) => {
            if (e.id === this.editedItem.id) {
              return Object.assign(e, attItem)
            }
            return e
          })
          this.verificaFlag(
            this.editedItem.quantidade_min,
            this.editedItem.quantidade,
            this.editedItem.id
          )
          this.close()
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
    },

    criaItem() {
      const newItem = {
        name: this.newItem.name,
        quantidade: this.newItem.quantidade,
        quantidade_min: this.newItem.quantidade_min,
        tipe: this.newItem.tipe
      }
      this.$axios
        .post('estoque', newItem)
        .then((res) => {
          this.itens.push(res.data)
          this.verificaFlag(
            this.newItem.quantidade_min,
            this.newItem.quantidade,
            res.data.id
          )
          this.newItem = {}
          this.close()
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
    },

    updatePriorit(color, id) {
      const newPriorit = {}
      switch (color) {
        case 'Verde': {
          newPriorit.color = 'green'
          this.$axios.put('estoque/' + id, newPriorit).then((res) => {
            this.itens = this.itens.map((e) => {
              if (e.id === id) {
                return Object.assign(e, newPriorit)
              }
              return e
            })
          })
          break
        }
        case 'Amarelo': {
          newPriorit.color = 'yellow'
          this.$axios.put('estoque/' + id, newPriorit).then((res) => {
            this.itens = this.itens.map((e) => {
              if (e.id === id) {
                return Object.assign(e, newPriorit)
              }
              return e
            })
          })
          break
        }
        case 'Vermelho': {
          newPriorit.color = 'red'
          this.$axios.put('estoque/' + id, newPriorit).then((res) => {
            this.itens = this.itens.map((e) => {
              if (e.id === id) {
                return Object.assign(e, newPriorit)
              }
              return e
            })
          })
          break
        }
      }
    },

    verificaFlag(quantMin, quant, id) {
      const flag = {}
      if (parseInt(quantMin, 10) >= parseInt(quant, 10)) {
        flag.flag = true
        this.$axios.put('estoque/' + id, flag)
      } else {
        flag.flag = false
        this.$axios.put('estoque/' + id, flag)
      }
    }
  }
}
</script>

<style scoped>
#list-side {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
  height: auto;
  width: 100%;
  margin-top: 10px !important;
}

#options-side {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
  height: 700px;
  width: 100%;
  margin-top: 10px !important;
  display: flex;
  flex-flow: column;
  align-items: center;
}

.tables {
  display: flex;
  flex-flow: column;
  align-items: center;
}

.table-title {
  font-size: 40px;
  font-family: 'Exo Regular';
  margin-top: 30px;
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

#search {
  width: 88%;
  margin-top: 15px;
}

#new-item {
  align-self: flex-end;
  position: relative;
  top: 580px;
  margin-right: 20px;
}

.log-table-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

.log-table {
  border: 1px solid black;
  width: 97%;
  height: auto;
  max-height: 600px;
  overflow: auto;
}

.log-title {
  font-family: 'Exo Regular';
  font-size: 20px;
}

.log-item {
  width: 100%;
  display: flex;
  flex-flow: row;
  justify-content: space-around;
  align-items: center;
}

.log-text {
  font-family: 'Exo Regular';
  font-size: 14px;
  padding-top: 5px;
  padding-bottom: 5px;
}

.divider {
  background-color: black;
}

.green {
  background-color: lightgreen !important;
}
.red {
  background-color: rgb(235, 57, 57) !important;
}
.yellow {
  background-color: rgb(240, 240, 50) !important;
}

@media screen and (max-width: 960px) {
  #options-side {
    height: 300px;
  }

  #new-item {
    top: 180px;
  }
}

@media screen and (max-width: 680px) {
  .log-item {
    flex-flow: column;
    justify-content: start;
    align-items: flex-start;
  }

  .log-text {
    margin-left: 5px;
  }
}
</style>
