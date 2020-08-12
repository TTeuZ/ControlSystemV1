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

          <!-- <div id="switches">
            <div v-for="(btn, b) in filters" :key="btn">
              <v-btn
                depressed
                rounded
                color="orange"
                :@click="(filterSelected = btn.filter)"
                :class="{ btnSelected: b === isSelected ? true : false }"
                @click="isSelected = b"
                ><span>{{ btn.label }}</span></v-btn
              >
            </div>
          </div> -->

          <!-- <div id="switches">
            <v-switch v-model="cabos" label="Cabos" />
            <v-switch v-model="lacre" label="Lacre" />
            <v-switch v-model="todos" label="Todos" />
          </div> -->

          <div id="new-item">
            <v-btn rounded depressed color="green" @click="newItemModal = true">
              NOVO ITEM
            </v-btn>
          </div>

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
                    :rules="[(v) => !!v || 'Campo Obrigatório']"
                    color="cyan darken-2"
                    label=" Quantidade"
                  />
                  <v-text-field
                    v-model="newItem.quantidade_min"
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
            </template>
          </v-data-table>
        </div>

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
                  :rules="[(v) => !!v || 'Campo Obrigatório']"
                  color="cyan darken-2"
                  label=" Quantidade"
                />
                <v-text-field
                  v-model="editedItem.quantidade_min"
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
    }
  },

  data() {
    return {
      attModel: false,
      newItemModal: false,
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

      tipes: ['Lacre', 'Cabo']
    }
  },

  computed: {
    estoqueItens() {
      return this.itens.map((e) => ({
        id: e.id,
        name: e.name,
        quantidade: e.quantidade,
        quantidade_min: e.quantidade_min,
        flag: e.flag,
        tipe: e.tipe
      }))
    }
  },
  methods: {
    attModalChange(item) {
      this.attModel = true
      this.editedItem = Object.assign({}, item)
    },

    close() {
      this.attModel = false
      this.newItemModal = false
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
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
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
          this.verificaFlag()
        })
        .catch(({ response }) => {
          const { mensagem } = !!response && response.data
          this.$toast.error(mensagem, { duration: 5000 })
        })
      this.close()
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
        .then(() => {
          this.close()
          window.location.reload()
        })
        .catch(({ response }) => {
          const { mensagem } = !!response && response.data
          this.$toast.error(mensagem, { duration: 5000 })
        })
    },

    verificaFlag() {
      const flag = {
        flag: true
      }
      const noFlag = {
        flag: false
      }
      if (this.editedItem.quantidade_min >= this.editedItem.quantidade) {
        this.$axios.put('estoque/' + this.editedItem.id, flag)
      } else {
        this.$axios.put('estoque/' + this.editedItem.id, noFlag)
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
  max-height: 700px;
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

/* #switches {
  display: flex;
  flex-flow: row;
  justify-content: space-around;
  align-items: center;

  width: 100%;
  height: auto;

  margin-top: 30px;
} */

#new-item {
  align-self: flex-end;

  position: relative;
  top: 580px;

  margin-right: 20px;
}

@media screen and (max-width: 960px) {
  #options-side {
    height: 300px;
  }

  #new-item {
    top: 180px;
  }
}
</style>
