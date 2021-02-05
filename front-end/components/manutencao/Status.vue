<template>
  <v-container>
    <v-row class="ma-0 pa-0" justify="center" align="center">
      <v-col
        v-if="selectedEquip !== '100'"
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
            <div v-for="(status, s) in statusFiltered" :key="status"> <!-- eslint-disable-line -->
              <div v-if="status.flag === '0'" class="status">
                <span class="status-text" @click="openInfo(status.id, s)">
                  {{ status.status_enum.title }}
                </span>
                <div>
                  <span
                    class="status-text-time"
                    @click="openInfo(status.id, s)"
                  >
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
                </div>
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
        v-if="selectedEquip !== '100'"
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
            <div v-for="(status, s) in statusFiltered" :key="status"> <!-- eslint-disable-line -->
              <div v-if="status.flag === '1'" class="status">
                <div class="change-flag">
                  <v-icon
                    class="change-icon"
                    @click=";(flagId = status.id), voltarStatus()"
                  >
                    mdi-arrow-left-bold
                  </v-icon>
                </div>
                <div>
                  <span
                    class="status-text-time"
                    @click="openInfo(status.id, s)"
                  >
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
                </div>
                <span class="status-text" @click="openInfo(status.id, s)">
                  {{ status.status_enum.title }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <!-- Dialog de info do status -->
    <v-dialog
      v-if="selectedEquip !== '100' && statusId !== '0'"
      v-model="statusInfoModal"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            STATUS
          </span>
          <div class="form">
            <div class="status-infos">
              <span class="status-text">Tipo: {{ statusFiltered[statusSelected].status_enum.title }} </span> <!-- eslint-disable-line -->
              <span class="status-text">Descrição: {{ statusFiltered[statusSelected].info }} </span> <!-- eslint-disable-line -->
            </div>
            <div class="created-infos">
              <span class="status-text"> Criado por: {{ statusFiltered[statusSelected].user_name_created }} </span> <!-- eslint-disable-line -->
              <span class="status-text"> Finalizado por: {{ statusFiltered[statusSelected].user_name_updated }} </span> <!-- eslint-disable-line -->
            </div>
          </div>
          <div v-if="statusEditableMode" class="form">
            <v-select
              v-model="newStatus.type"
              :items="getStatusPadraoTitles()"
              color="cyan darken-2"
              label="Tipo"
            />
            <v-text-field
              v-model="newStatus.info"
              :rules="[(v) => !!v || 'Campo Obrigatório']"
              color="cyan darken-2"
              label="Status"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="delStatus(statusId)">
            excluir
          </v-btn>
          <v-btn color="#43A047" text @click="attStatus(statusId)">
            editar
          </v-btn>
          <v-btn color="#43A047" text @click="statusInfoModal = false">
            sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de info do status -->
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
            <v-select
              v-model="newStatus.type"
              :items="getStatusPadraoTitles()"
              color="cyan darken-2"
              label="Tipo"
            />
            <v-text-field
              v-model="newStatus.info"
              :rules="[(v) => !!v || 'Campo Obrigatório']"
              color="cyan darken-2"
              label="Status"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="statusPadraoModal = true"> <!-- eslint-disable-line -->
            Status Padrão
          </v-btn>
          <v-btn color="#43A047" text @click="statusModal = false">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="criaStatus()">
            Salvar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de status -->
    <!-- Dialog de status status padrao -->
    <v-dialog
      v-model="statusPadraoModal"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            STATUS PADRÕES
          </span>
          <div class="form">
            <v-text-field
              v-model="searchStatusPadrao"
              append-icon="mdi-magnify"
              label="Pesquisar"
              single-line
              hide-details
            />
          </div>
          <div class="form over">
            <div
              v-for="(padrao, p) in filteredStatusPadrao"
              :key="padrao"
              class="status-padrao-item"
              @click="statusPadraoId = padrao.id"
            >
              <span
                class="status-text"
                :class="{
                  statusPadraoSelecionado:
                    statusPadraoId === padrao.id ? true : false
                }"
              >
                {{ padrao.title }}
              </span>
              <v-divider v-if="p + '1' !== filteredStatusPadrao.length" class="status-padrao-divider" /> <!-- eslint-disable-line -->
            </div>
          </div>
          <div v-if="openCreateStatusPadrao" class="form pt-1">
            <v-text-field
              v-model="statusPadraoTitle"
              :rules="[(v) => !!v || 'Nome Obrigatório']"
              color="cyan darken-2"
              label="Status Padrão"
            />
            <v-text-field
              v-model="statusPadraoTime"
              :rules="[(v) => !!v || 'Tempo Obrigatório']"
              color="cyan darken-2"
              label="Tempo"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn v-if="statusPadraoId !== ''" color="#43A047" text @click="delStatusPadrao(statusPadraoId)"> <!-- eslint-disable-line -->
            Excluir
          </v-btn>
          <v-btn v-if="statusPadraoId !== ''" color="#43A047" text @click="editaStatusPadrao(statusPadraoId)"> <!-- eslint-disable-line -->
            Editar
          </v-btn>
          <v-btn v-if="!statusPEditableMode" color="#43A047" text @click="criaStatusPadrao()"> <!-- eslint-disable-line -->
            Adicionar
          </v-btn>
          <v-btn color="#43A047" text @click="statusPadraoModal = false">
            Sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de status status padrao -->
  </v-container>
</template>

<script>
export default {
  props: {
    showForm: {
      type: Boolean,
      required: true
    },
    selectedEquip: {
      type: String,
      required: true
    },
    status: {
      type: Array,
      required: true
    },
    statusEnum: {
      type: Array,
      required: true
    },
    filteredEquip: {
      type: Array,
      required: true
    },
    statusModal: {
      type: Boolean,
      required: true
    }
  },

  data() {
    return {
      statusPadraoModal: false,
      searchStatusPadrao: '',
      statusEnumAtt: this.statusEnum,
      statusPadraoFiltered: [],
      openCreateStatusPadrao: false,
      statusPadraoTitle: '',
      statusPadraoId: '',
      statusPEditableMode: false,

      statusInfoModal: false,
      statusEditableMode: false,
      equipamentoId: '0',
      statusSelected: '0',
      statusId: '0',

      statusAtt: this.status,
      statusFiltered: [],
      newStatus: {
        type: '',
        info: ''
      },
      flagId: ''
    }
  },

  computed: {
    filteredStatusPadrao() {
      const self = this
      self.statusPadraoFiltered = self.statusEnumAtt.filter((f) =>
        f.title.toLowerCase().includes(self.searchStatusPadrao.toLowerCase())
      )
      return self.statusPadraoFiltered
    }
  },

  methods: {
    filterStatus(equipId) {
      this.equipamentoId = equipId
      return (this.statusFiltered = this.statusAtt.filter(
        (equip) =>
          equip.equipamento_id === this.filteredEquip[equipId][0].id.toString()
      ))
    },

    openInfo(id, selected) {
      this.statusId = id
      this.statusSelected = selected
      this.statusInfoModal = true
    },

    feito() {
      const attFlag = {
        flag: 1
      }
      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        this.$axios.get('status').then((res) => {
          this.statusAtt = res.data
          this.filterStatus(this.equipamentoId)
        })
      })
    },

    voltarStatus() {
      const attFlag = {
        flag: 0
      }
      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        this.$axios.get('status').then((res) => {
          this.statusAtt = res.data
          this.filterStatus(this.equipamentoId)
        })
      })
    },

    criaStatusPadrao() {
      if (this.openCreateStatusPadrao === false) {
        this.openCreateStatusPadrao = true
      } else {
        const statusPadrao = {
          title: this.statusPadraoTitle,
          time: this.statusPadraoTime
        }
        this.$axios
          .post('statusEnum', statusPadrao)
          .then(() => {
            this.$axios.get('statusEnum').then((res) => {
              this.statusEnumAtt = res.data
              this.openCreateStatusPadrao = false
              this.statusPadraoTitle = ''
              this.statusPadraoTime = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    editaStatusPadrao(id) {
      let statusEnumSelected = {}
      statusEnumSelected = this.statusEnumAtt.filter((item) => item.id === id)
      if (this.openCreateStatusPadrao === false) {
        this.openCreateStatusPadrao = true
        this.statusPEditableMode = true
        this.statusPadraoTitle = statusEnumSelected[0].title
        this.statusPadraoTime = statusEnumSelected[0].time
      } else {
        const statusPadraoAtt = {
          title: this.statusPadraoTitle,
          time: this.statusPadraoTime
        }
        this.$axios
          .put('statusEnum/' + id, statusPadraoAtt)
          .then(() => {
            this.$axios.get('statusEnum').then((res) => {
              this.statusEnumAtt = res.data
              this.openCreateStatusPadrao = false
              this.statusPEditableMode = false
              this.statusPadraoTitle = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    delStatusPadrao(id) {
      const ok = window.confirm(
        'Você tem certeza que deseja excluir esse status padrão?'
      )
      if (ok) {
        this.$axios
          .delete('statusEnum/' + id)
          .then(() => {
            this.$axios.get('statusEnum').then((res) => {
              this.statusEnumAtt = res.data
              this.openCreateStatusPadrao = false
              this.statusPEditableMode = false
              this.statusPadraoId = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    getStatusPadraoTitles() {
      const statusTitles = []
      this.statusEnumAtt.forEach((item) => {
        statusTitles.push(item.title)
      })
      return statusTitles
    },

    criaStatus() {
      let enumId = 0
      this.statusEnumAtt.forEach((s) => {
        if (s.title === this.newStatus.type) {
          enumId = s.id
        }
      })
      const newStatus = {
        info: this.newStatus.info,
        status_enum_id: enumId,
        equipamento_id: this.filteredEquip[this.selectedEquip][0].id
      }
      this.$axios
        .$post('status', newStatus)
        .then(() => {
          this.$axios.get('status').then((res) => {
            this.statusAtt = res.data
            this.filterStatus(this.equipamentoId)
            this.statusModal = false
            this.newStatus.info = ''
            this.enumId = 0
          })
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
    },

    attStatus(id) {
      let statusSelected = {}
      statusSelected = this.statusAtt.filter((item) => item.id === id)
      if (this.statusEditableMode === false) {
        this.statusEditableMode = true
        this.newStatus.type = statusSelected[0].status_enum.title
        this.newStatus.info = statusSelected[0].info
      } else {
        let enumId = 0
        this.statusEnumAtt.forEach((s) => {
          if (s.title === this.newStatus.type) {
            enumId = s.id
          }
        })
        const attStatus = {
          info: this.newStatus.info,
          status_enum_id: enumId
        }
        this.$axios
          .put('status/' + id, attStatus)
          .then(() => {
            this.$axios.get('status').then((res) => {
              this.statusAtt = res.data
              this.filterStatus(this.equipamentoId)
              this.statusInfoModal = false
              this.statusEditableMode = false
              this.newStatus.info = ''
              this.newStatus.type = ''
              this.enumId = 0
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    delStatus(id) {
      const ok = window.confirm(
        'Você tem certeza que deseja excluir esse status?'
      )
      if (ok) {
        this.$axios
          .delete('status/' + id)
          .then(() => {
            this.$axios.get('status').then((res) => {
              this.statusAtt = res.data
              this.filterStatus(this.equipamentoId)
              this.statusInfoModal = false
              this.statusEditableMode = false
              this.statusId = '0'
              this.statusSelected = '0'
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    }
  }
}
</script>

<style scoped>
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
  width: 95%;
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

.over {
  height: 300px;
  overflow: auto;
}

.status-padrao-item {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
  height: 35px;
}

.status-padrao-divider {
  background-color: #777;
  width: 100%;
}

.statusPadraoSelecionado {
  color: orange;
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

.status-infos {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-flow: row;
}

.created-infos {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-flow: row;
  padding-top: 15px;
}

@media screen and (max-width: 1262px) {
  #buttons {
    margin: 20px 0;
  }
}

@media screen and (max-width: 962px) {
  .table-title {
    margin-top: 15px;
  }
}

@media screen and (max-width: 700px) {
  .status-infos {
    flex-flow: column;
  }
  .created-infos {
    flex-flow: column;
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

@media screen and (max-width: 430px) {
  .status {
    flex-flow: column;
  }
  .modal-title {
    font-size: 20px;
  }
  .status-text {
    font-size: 14px;
  }
  .btn-section {
    flex-flow: column;
    padding-right: 0px !important;
  }
}
</style>
