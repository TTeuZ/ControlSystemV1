<template>
  <v-container fluid>
    <div class="fornecedores-total">
      <span id="header-title"> FORNECEDORES </span>
      <div class="panels-section">
        <div class="serch-section">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Pesquisar (Item)"
            single-line
            hide-details
            @keyup="filterFornecedores()"
          ></v-text-field>
        </div>
        <v-expansion-panels v-model="panel" accordion flat>
          <v-expansion-panel
            v-for="(f, index) in fornecedoresUti"
            :key="f"
            class="expansion-panel"
          >
            <v-expansion-panel-header
              class="expansion-panel"
              @click="getItens(f[0].id, index)"
            >
              <div
                v-if="!(isEdit === f[0].id)"
                class="fornecedor-info"
                @click="isEdit = ''"
              >
                <span class="text"> Nome: {{ f[0].nome }} </span>
                <span class="text"> email: {{ f[0].email }} </span>
                <span class="text"> Telefone: {{ f[0].telefone }} </span>
              </div>
              <div v-if="isEdit === f[0].id" class="att-form">
                <v-text-field
                  v-model="attFornecedorObj.nome"
                  :rules="[(v) => !!v || 'Nome Obrigatório']"
                  color="cyan darken-2"
                  label=" Nome"
                  class="inputs"
                ></v-text-field>
                <v-text-field
                  v-model="attFornecedorObj.email"
                  :rules="[(v) => !!v || 'Email Obrigatório']"
                  color="cyan darken-2"
                  label=" Email"
                  class="inputs"
                ></v-text-field>
                <v-text-field
                  v-model="attFornecedorObj.telefone"
                  v-maska="'(##) #####-####'"
                  :rules="[(v) => !!v || 'Telefone Obrigatório']"
                  color="cyan darken-2"
                  label=" Telefone"
                  class="inputs"
                ></v-text-field>
              </div>
              <div class="edit-icon">
                <v-icon
                  v-if="canEdit === f[0].id"
                  color="primary"
                  @click="attFornecedor(f[0].id)"
                  >mdi-circle-edit-outline</v-icon
                >
              </div>
            </v-expansion-panel-header>
            <v-expansion-panel-content class="expansion-panel pa-0">
              <div v-if="f[1].length != 0">
                <div
                  v-for="item in fornecedorItens"
                  :key="item"
                  class="fornecedor-itens-section"
                >
                  <div
                    v-if="!(isEditItem === item.id)"
                    class="item-info"
                    @click="showOb(item.id, '0')"
                  >
                    <span class="text"> Item: {{ item.nome }} </span>
                    <span class="text">
                      Quantidade: {{ item.quantidade }}
                    </span>
                    <span class="text"> Valor: {{ item.valor }} </span>
                    <span class="text"> Data: {{ item.data }} </span>
                  </div>
                  <div v-if="isEditItem === item.id" style="width:100%;">
                    <div class="att-form">
                      <v-select
                        v-model="attItemObj.nome"
                        :rules="[(v) => !!v || 'Item Obrigatório']"
                        color="cyan darken-2"
                        label=" Item"
                        :items="estoqueItems"
                        class="inputs"
                      ></v-select>
                      <v-text-field
                        v-model="attItemObj.quantidade"
                        v-maska="'#*'"
                        :rules="[(v) => !!v || 'Quantidade Obrigatório']"
                        color="cyan darken-2"
                        label=" Quantidade"
                        class="inputs"
                      ></v-text-field>
                      <v-text-field
                        v-model="attItemObj.valor"
                        v-maska="'R$ #*,#*'"
                        :rules="[(v) => !!v || 'Valor Obrigatório']"
                        color="cyan darken-2"
                        label=" Valor"
                        class="inputs"
                      ></v-text-field>
                      <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="attItemObj.data"
                            label="Data da compra"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="date" no-title scrollable>
                          <v-spacer></v-spacer>
                          <v-btn text color="primary" @click="setData(date)">
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-menu>
                    </div>
                    <v-text-field label="Observação" dense v-model="attItemObj.observacao"></v-text-field> <!-- eslint-disable-line -->
                  </div>
                  <div class="edit-icon-item">
                    <v-icon color="primary" @click="attItem(item.id)"
                      >mdi-circle-edit-outline</v-icon
                    >
                  </div>
                </div>
                <div v-if="f[1].length > 3" class="show-more-section">
                  <span class="show-text" @click="showAll(f[0].id, index)"
                    >Mostrar mais</span
                  >
                </div>
              </div>
              <div v-else class="else-text">
                <span class="text">
                  Não foi efetuada nenhuma compra com este fornecedor
                </span>
              </div>
              <div style="display:flex; justify-content:flex-end; align-items:center"> <!-- eslint-disable-line -->
                <div v-if="isAdd" style="width:100%;"> <!-- eslint-disable-line -->
                  <div class="create-form">
                    <v-select
                      v-model="newItem.nome"
                      :rules="[(v) => !!v || 'Item Obrigatório']"
                      color="cyan darken-2"
                      label=" Item"
                      :items="estoqueItems"
                      class="inputs"
                    ></v-select>
                    <v-text-field
                      v-model="newItem.quantidade"
                      v-maska="'#*'"
                      :rules="[(v) => !!v || 'Quantidade Obrigatório']"
                      color="cyan darken-2"
                      label=" Quantidade"
                      class="inputs"
                    ></v-text-field>
                    <v-text-field
                      v-model="newItem.valor"
                      v-maska="'R$ #*,#*'"
                      :rules="[(v) => !!v || 'Valor Obrigatório']"
                      color="cyan darken-2"
                      label=" Valor"
                      class="inputs"
                    ></v-text-field>
                    <v-menu
                      ref="menu"
                      v-model="menu"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="newItem.data"
                          label="Data da compra"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="date" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="setData(date), menu = false"> <!-- eslint-disable-line -->
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-menu>
                  </div>
                  <v-text-field label="Observação" dense v-model="newItem.observacao"></v-text-field> <!-- eslint-disable-line -->
                </div>
                <v-btn
                  class="add-btn"
                  small
                  fab
                  depressed
                  color="#fafafa"
                  @click="addItem(f[0].id)"
                >
                  <v-icon class="add-icon">mdi-plus</v-icon>
                </v-btn>
              </div>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <div class="create-fornecedor-section">
            <div v-if="isCreate" class="create-form">
              <v-text-field
                v-model="newFornecedor.nome"
                :rules="[(v) => !!v || 'Nome Obrigatório']"
                color="cyan darken-2"
                label=" Nome"
                class="inputs"
              ></v-text-field>
              <v-text-field
                v-model="newFornecedor.email"
                :rules="[(v) => !!v || 'Email Obrigatório']"
                color="cyan darken-2"
                label=" Email"
                class="inputs"
              ></v-text-field>
              <v-text-field
                v-model="newFornecedor.telefone"
                v-maska="'(##) #####-####'"
                :rules="[(v) => !!v || 'Telefone Obrigatório']"
                color="cyan darken-2"
                label=" Telefone"
                class="inputs"
              ></v-text-field>
            </div>
            <v-btn
              class="add-btn"
              small
              plain
              fab
              depressed
              color="#fafafa"
              @click="createFornecedor()"
            >
              <v-icon class="add-icon">mdi-plus</v-icon>
            </v-btn>
          </div>
        </v-expansion-panels>
      </div>
    </div>
    <!-- dialog show all -->
    <v-dialog
      v-model="showAllDialog"
      max-width="1600px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            TODOS OS ITENS
          </span>
          <div class="serch-section">
            <v-text-field
              v-model="searchItem"
              append-icon="mdi-magnify"
              label="Pesquisar (Item)"
              single-line
              hide-details
              @keyup="filterItens()"
            ></v-text-field>
          </div>
          <div
            v-for="item in fornecedorItensAll"
            :key="item"
            class="fornecedor-itens-all"
            @click="showOb(item.id, '1')"
          >
            <span class="text"> Item: {{ item.nome }} </span>
            <span class="text"> Quantidade: {{ item.quantidade }} </span>
            <span class="text"> Valor: {{ item.valor }} </span>
            <span class="text"> Data {{ item.data }} </span>
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="showAllDialog = false">
            sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- dialog show all -->
    <!-- dialog de observacao -->
    <v-dialog
      v-model="obDialog"
      max-width="600px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            OBSERVAÇÃO
          </span>
          <div v-if="obItem.length === 1" class="ob-info">
            <div style="display:flex; justify-content:space-between;">
              <span class="text"> Item: {{ obItem[0].nome }} </span>
              <span class="text"> Quantidade: {{ obItem[0].quantidade }} </span>
              <span class="text"> Valor: {{ obItem[0].valor }} </span>
              <span class="text"> Data: {{ obItem[0].data }} </span>
            </div>
            <span class="text"> Observação: {{ obItem[0].observacao }} </span>
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="obDialog = false">
            sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- dialog de observacao -->
  </v-container>
</template>

<script>
export default {
  props: {
    fornecedores: {
      type: Array,
      required: true
    },
    estoque: {
      type: Array,
      required: true
    },
    buyedItems: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      search: '',
      searchItem: '',
      fornecedoresUti: this.fornecedores,
      fornecedorItens: [],
      fornecedorItensAll: [],
      fornecedorItensAllRes: [],
      estoqueItems: [],
      panel: [],
      menu: false,

      canEdit: '',
      isEdit: '',
      isEditItem: '',
      isCreate: false,
      isAdd: false,
      showAllDialog: false,
      obDialog: false,
      obItem: {},
      newFornecedor: {
        nome: '',
        email: '',
        telfone: ''
      },
      attFornecedorObj: {
        nome: '',
        email: '',
        telfone: ''
      },
      date: new Date().toISOString().substr(0, 10),
      newItem: {
        nome: '',
        quantidade: '',
        valor: '',
        data: '',
        observacao: ''
      },
      attItemObj: {
        nome: '',
        quantidade: '',
        valor: '',
        data: '',
        observacao: ''
      }
    }
  },
  mounted() {
    this.estoque.forEach((item) => {
      this.estoqueItems.push(item.name)
    })
  },
  methods: {
    filterFornecedores() {
      let count = 0
      let fornecedoresId = []
      const filteredFornecedores = []
      this.fornecedoresUti = this.fornecedores
      const items = this.buyedItems.filter((i) =>
        i.nome.toLowerCase().includes(this.search.toLowerCase())
      )
      items.forEach((i) => {
        fornecedoresId.push(i.fornecedor_id)
      })
      // função que elimina os repitidos
      fornecedoresId = fornecedoresId.filter((atual, posterior) => {
        return fornecedoresId.indexOf(atual) === posterior
      })
      while (count <= fornecedoresId.length - 1) {
        this.fornecedoresUti.forEach((f) => {
          if (parseInt(fornecedoresId[count], 10) === f[0].id) {
            filteredFornecedores.push(f)
          }
        })
        count += 1
      }
      this.fornecedoresUti = filteredFornecedores
    },
    filterItens() {
      this.fornecedorItensAll = this.fornecedorItensAllRes
      this.fornecedorItensAll = this.fornecedorItensAll.filter((i) =>
        i.nome.toLowerCase().includes(this.searchItem.toLowerCase())
      )
    },
    createFornecedor() {
      if (!this.isCreate) {
        this.isCreate = true
      } else {
        const fornecedor = {}
        fornecedor.nome = this.newFornecedor.nome
        fornecedor.email = this.newFornecedor.email
        fornecedor.telefone = this.newFornecedor.telefone
        this.$axios
          .post('fornecedor', fornecedor)
          .then(() => {
            this.atualizaFornecedores()
            this.newFornecedor = {}
            this.isCreate = false
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },
    setData(data) {
      this.newItem.data = data.split(' ')[0].split('-').reverse().join('/') /*eslint-disable-line*/
      this.attItemObj.data = data.split(' ')[0].split('-').reverse().join('/') /*eslint-disable-line*/
    },
    addItem(id) {
      if (!this.isAdd) {
        this.isAdd = true
      } else {
        const item = {}
        item.fornecedor_id = id
        item.nome = this.newItem.nome
        item.quantidade = this.newItem.quantidade
        item.valor = this.newItem.valor
        item.data = this.newItem.data
        item.observacao = this.newItem.observacao
        this.$axios
          .post('fornecedorItem', item)
          .then(() => {
            this.atualizaFornecedores()
            this.newItem = {}
            this.isAdd = false
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },
    attFornecedor(id) {
      if (this.isEdit !== id) {
        this.isEdit = id
        this.fornecedoresUti.forEach((f) => {
          if (f[0].id === id) {
            this.attFornecedorObj.nome = f[0].nome
            this.attFornecedorObj.telefone = f[0].telefone
            this.attFornecedorObj.email = f[0].email
          }
        })
      } else {
        const update = {}
        update.nome = this.attFornecedorObj.nome
        update.telefone = this.attFornecedorObj.telefone
        update.email = this.attFornecedorObj.email
        this.$axios
          .put('fornecedor/' + id, update)
          .then(() => {
            this.atualizaFornecedores()
            this.attFornecedorObj = {}
            this.isEdit = ''
            this.canEdit = ''
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },
    attItem(id) {
      if (this.isEditItem !== id) {
        this.isEditItem = id
        this.fornecedorItens.forEach((f) => {
          if (f.id === id) {
            this.attItemObj.nome = f.nome
            this.attItemObj.quantidade = f.quantidade
            this.attItemObj.valor = f.valor
            this.attItemObj.data = f.data
            this.attItemObj.observacao = f.observacao
          }
        })
      } else {
        const update = {}
        update.nome = this.attItemObj.nome
        update.quantidade = this.attItemObj.quantidade
        update.valor = this.attItemObj.valor
        update.data = this.attItemObj.data
        update.observacao = this.attItemObj.observacao
        this.$axios
          .put('fornecedorItem/' + id, update)
          .then(() => {
            this.atualizaFornecedores()
            this.attItemObjt = {}
            this.isEditItem = ''
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },
    getItens(id, index) {
      let max = this.fornecedoresUti[index][1].length
      const item = this.fornecedoresUti[index][1]
      let count = 1
      this.canEdit = id
      const items = []
      while (count <= 4) {
        if (!(item[max] === undefined)) {
          items.push(item[max])
        }
        max = max - 1
        count = count + 1
      }
      this.fornecedorItens = items
    },
    showAll(id, index) {
      this.fornecedorItensAll = []
      const item = this.fornecedoresUti[index][1]
      item.forEach((i) => {
        this.fornecedorItensAll.push(i)
      })
      this.fornecedorItensAll.sort(function(a, b) {
        if (a.nome > b.nome) return 1
        else return -1
      })
      this.fornecedorItensAllRes = this.fornecedorItensAll
      this.showAllDialog = true
    },
    showOb(id, location) {
      this.obDialog = true
      if (location === '1') {
        this.obItem = this.fornecedorItensAll.filter((f) => f.id === id)
      } else {
        this.obItem = this.fornecedorItens.filter((f) => f.id === id)
      }
    },
    atualizaFornecedores() {
      this.$axios.get('fornecedor').then((res) => {
        this.fornecedoresUti = res.data
      })
    }
  }
}
</script>

<style scoped>
.serch-section {
  margin-bottom: 35px;
  width: 500px;
  margin-right: 20px;
  align-self: flex-end;
}
.text {
  font-family: 'Exo Regular';
}
.edit-icon {
  width: 30px;
  display: flex;
  justify-content: flex-end;
  padding-right: 10px;
}
.edit-icon-item {
  width: 30px;
  display: flex;
  justify-content: flex-end;
}
.fornecedores-total {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
  width: 100%;
}
.fornecedor-info {
  display: flex;
  justify-content: space-between;
}
.item-info {
  width: 70%;
  display: flex;
  justify-content: space-between;
}
#header-title {
  font-family: 'Exo Regular';
  font-size: 20px;
  text-align: center;
}
.panels-section {
  width: 100%;
  margin-top: 20px;
  display: flex;
  flex-flow: column;
  align-items: flex-end;
}
.expansion-panel >>> .v-expansion-panel-header {
  background-color: #fafafa;
  border: 0px none transparent !important;
  -webkit-box-shadow: 0px 0px 0px -30px rgba(0, 0, 0, 0);
  box-shadow: 0px 0px 0px -30px rgba(0, 0, 0, 0);
}
.expansion-panel >>> .v-expansion-panel-content {
  background-color: #fafafa;
  border: 0px none transparent !important;
  -webkit-box-shadow: 0px 0px 0px -30px rgba(0, 0, 0, 0);
  box-shadow: 0px 0px 0px -30px rgba(0, 0, 0, 0);
}
.v-expansion-panel::before {
  box-shadow: none !important;
}
.fornecedor-itens-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-right: 25px;
  margin-bottom: 6px;
}
.show-more-section {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}
.show-text {
  font-family: 'Exo Regular';
  color: #777;
  font-size: 16px;
}
.else-text {
  display: flex;
  justify-content: center;
  align-items: center;
}
.create-fornecedor-section {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
.create-form {
  width: 100%;
  display: flex;
  flex-flow: row;
  justify-content: space-between;
}
.att-form {
  width: 100%;
  display: flex;
  flex-flow: row;
  justify-content: space-between;
}
.inputs {
  padding-left: 10px;
  padding-right: 10px;
}
.add-btn {
  margin-right: 16px;
}
.add-icon {
  color: #777 !important;
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
.fornecedor-itens-all {
  width: 80%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 10px;
}
.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
.ob-info {
  display: flex;
  flex-flow: column;
  width: 90%;
}
</style>
