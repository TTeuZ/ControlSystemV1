<template>
  <v-container fluid>
    <v-row class="ma-0 pa-0 search-section">
      <v-col id="search" lg="7" cols="11">
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Pesquisar"
          single-line
          hide-details
          @keyup="sortEquips(), (selectedEquip = 100)"
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
                filterStatus()
            "
          >
            <span v-if="filteredEquip[item.split(' ')[1]][0].done === '1'" id="item-title"> <!-- eslint-disable-line -->
              {{ filteredEquip[item.split(' ')[1]][0].name.toUpperCase() }}
            </span>
            <v-divider v-if="filteredEquip[item.split(' ')[1]][0].done === '1'" id="divider" /> <!-- eslint-disable-line -->
          </div>
        </div>
      </v-col>

      <v-col class="ma-0 pa-0" xl="7" lg="7" md="11" sm="11" xs="11" cols="11">
        <v-row id="info-side" class="ma-0 pa-0">
          <div id="title-section">
            <span id="info-title"> INFORMAÇÕES DO EQUIPAMENTO </span>
            <v-divider id="divider" />
          </div>

          <div v-if="selectedEquip !== 100" id="history-info">
            <span v-if="showForm" id="equip-title">
              {{ filteredEquip[selectedEquip][0].name.toUpperCase() }}
            </span>

            <div id="part-info">
              <span v-if="showForm" class="history-text"
                >criado:
                {{
                  filteredEquip[selectedEquip][0].created_at
                    .split(' ')[0]
                    .split('-')
                    .reverse()
                    .join('/') +
                    ' ' +
                    'às' +
                    ' ' +
                    filteredEquip[selectedEquip][0].created_at.split(' ')[1]
                }}</span
              >
              <span v-if="showForm" class="history-text">
                Por: {{ filteredEquip[selectedEquip][0].user_name_created }}
              </span>
              <span v-if="showForm" class="history-text">
                finalizado:
                {{
                  filteredEquip[selectedEquip][0].updated_at
                    .split(' ')[0]
                    .split('-')
                    .reverse()
                    .join('/') +
                    ' ' +
                    'às' +
                    ' ' +
                    filteredEquip[selectedEquip][0].updated_at.split(' ')[1]
                }}
              </span>
              <span v-if="showForm" class="history-text">
                Por: {{ filteredEquip[selectedEquip][0].user_name_updated }}
              </span>
            </div>
          </div>

          <div v-if="selectedEquip !== 100" id="all-info">
            <div id="part-info-two">
              <span v-if="showForm" id="status-title">
                Status
              </span>
              <div v-if="showForm" id="table">
                <div
                  v-for="status in statusFiltered"
                  :key="status"
                  class="status-geral"
                >
                  <div class="status">
                    <span class="status-text">
                      {{ status.info }}
                    </span>
                    <div class="ajust-info">
                      <span class="status-text-time">
                        {{
                          status.created_at
                            .split(' ')[0]
                            .split('-')
                            .reverse()
                            .join('/') +
                            ' às ' +
                            status.created_at.split(' ')[1]
                        }}
                      </span>

                      <v-icon
                        class="icons"
                        @mouseover="created = true"
                        @mouseleave="created = false"
                      >
                        mdi-comment-question-outline
                      </v-icon>

                      <div v-if="created" class="hover-info">
                        <span class="hover-text"> criado em </span>
                      </div>
                    </div>

                    <div class="ajust-info">
                      <span class="status-text-time">
                        {{
                          status.updated_at
                            .split(' ')[0]
                            .split('-')
                            .reverse()
                            .join('/') +
                            ' às ' +
                            status.updated_at.split(' ')[1]
                        }}
                      </span>

                      <v-icon
                        class="icons"
                        @mouseover="updated = true"
                        @mouseleave="updated = false"
                      >
                        mdi-comment-question-outline
                      </v-icon>

                      <div v-if="updated" class="hover-info">
                        <span class="hover-text"> finalizado em </span>
                      </div>
                    </div>
                  </div>
                  <div class="created_info">
                    <span class="status-text">
                      Criado por: {{ status.user_name_created }}
                    </span>
                    <span class="status-text">
                      Finalizado por: {{ status.user_name_updated }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <v-col
            v-if="showForm && selectedEquip !== 100"
            id="buttons"
            class="ma-o pa-0"
            cols="12"
          >
            <v-btn
              class="form-btns"
              rounded
              depressed
              color="red"
              @click="excluirEquip()"
            >
              EXCLUIR EQUIPAMENTO
            </v-btn>
          </v-col>
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
    status: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      filteredEquip: this.equipamentos,
      sortEquipamentos: [],
      search: '',

      showForm: false,
      selectedEquip: '',
      statusFiltered: [],

      created: false,
      updated: false
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
    console.log(this.filteredEquip)
    this.sortEquips()
  },

  methods: {
    excluirEquip() {
      const ok = window.confirm(
        'Você tem certeza que deseja excluir esse equipamento?'
      )

      if (ok) {
        this.$axios
          .delete('equipamento/' + this.filteredEquip[this.selectedEquip][0].id)
          .then(() => {
            this.reload()
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    filterStatus() {
      return (this.statusFiltered = this.status.filter(
        (equip) =>
          equip.equipamento_id ===
          this.filteredEquip[this.selectedEquip][0].id.toString()
      ))
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

    reload() {
      location.reload()
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
  width: 30%;
  height: 40px;
  margin-left: 30px;
}

#history-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-flow: row;
  width: 100%;
}

#all-info {
  display: flex;
  flex-flow: row;
  justify-content: center;
  align-items: center;
  margin-left: 30px;
  margin-right: 30px;
  width: 100%;
  height: 400px;
}

#part-info {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: flex-end;
  width: 70%;
  margin-right: 30px;
}

#part-info-two {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

#table {
  border: 1px solid black;
  width: 100%;
  height: 100%;
  overflow: auto;
}

#status-title {
  font-family: 'Exo Regular';
  font-size: 24px;
}

.history-text {
  font-family: 'Exo Regular';
  font-size: 16px;
}

.status-geral {
  display: flex;
  flex-flow: column;
  border-bottom: 1px solid black;
}

.status {
  width: 100%;
  height: auto;
  min-height: 40px;
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
}

.status-text {
  padding: 0 10px;
  font-size: 16px;
  font-family: 'Exo Regular';
}

.status-text-time {
  font-size: 14px;
  font-family: 'Exo Regular';
  padding-right: 10px !important;
}

.created_info {
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  width: 100%;
}

#buttons {
  display: flex;
  flex-flow: row;
  justify-content: flex-end;
}

.form-btns {
  margin-right: 25px;
}

.ajust-info {
  display: flex;
  flex-flow: row;
}

.hover-info {
  position: absolute;
  height: 20px;
  width: 90px;
  background-color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 70px;
}

.hover-text {
  font-family: 'Exo Regular';
  font-size: 12px;
}

.icons {
  color: black;
  font-size: 1em;
  margin-right: 5px;
}

@media screen and (max-width: 1262px) {
  #itens-list {
    width: 100%;
    max-height: 240px;
  }
  #info-side {
    height: auto;
    margin-top: 40px !important;
  }
  #buttons {
    margin: 20px 0;
  }
  #equip-title {
    margin-top: 15px;
    margin-bottom: 15px;
  }
  #part-info {
    margin-top: 10px;
  }
  .search-section {
    width: 100%;
    justify-content: center;
  }
}

@media screen and (max-width: 730px) {
  #info-title {
    font-size: 26px;
  }
  #all-info {
    flex-flow: column;
  }
  #part-info {
    width: 100%;
  }
  #part-info-two {
    margin-top: 20px !important;
    width: 100%;
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
  #history-info {
    flex-flow: column;
    justify-content: flex-start;
  }
  #equip-title {
    width: 100%;
  }
  #part-info {
    align-items: flex-start;
    margin-left: 65px;
  }
  .status {
    flex-flow: column;
    align-items: flex-start;
  }
  .status-text-time {
    padding-bottom: 5px;
    padding-left: 10px;
  }
  .created_info {
    flex-flow: column;
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
  .history-text {
    font-size: 15px;
  }
}

@media screen and (max-width: 355px) {
  .history-text {
    font-size: 13px;
  }
  .status-text {
    font-size: 13px;
  }
  .status-text-time {
    font-size: 13px;
  }
}
</style>
