<template>
  <div class="log-compontent">
    <v-btn color="orange" @click=";(modalLog = !modalLog), getInfo()">
      LOG
    </v-btn>
    <v-dialog
      v-model="modalLog"
      max-width="1600px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            LOG
          </span>
          <div class="filters">
            <div class="ajust">
              <v-select
                v-model="actionSelected"
                :items="actionItens"
                color="cyan darken-2"
                label="Ação"
                @change="filterForAction()"
              />
            </div>
            <div class="ajust">
              <v-select
                v-model="typeSelected"
                :items="typeItens"
                color="cyan darken-2"
                label="Tipo"
                @change="filterFortype()"
              />
            </div>
            <div class="ajust">
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Pesquisar"
                single-line
                hide-details
                @keyup="getInfo()"
              ></v-text-field>
            </div>
          </div>
          <div v-for="log in filterItens" :key="log" class="content">
            <div class="log">
              <span class="log-text">Item: {{ log.nome }}</span>
              <span class="log-text">Tipo: {{ log.tipo }}</span>
              <span class="log-text">Feito por: {{ log.user_name }}</span>
              <span class="log-text">Ação: {{ log.acao }}</span>
              <span class="log-text">Data: {{ ajustTime(log.created_at) }}</span> <!-- eslint-disable-line -->
            </div>
            <v-divider class="divider" />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalLog = !modalLog"> <!-- eslint-disable-line -->
            Sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    whichItem: {
      type: String,
      required: true
    },
    cabosLog: {
      type: Array
    },
    autoEletricaId: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      cabosLogAtt: this.cabosLog,
      cabosLogFiltered: [],
      currentInfoOfAction: [],
      finalInfo: [],
      modalLog: false,
      currentInfo: [],

      search: '',
      actionSelected: 'Todos',
      actionItens: ['Todos', 'Criado', 'Para Defeito', 'Para Estoque'],

      typeSelected: 'Todos',
      typeItens: ['Todos']
    }
  },

  computed: {
    filterItens() {
      const self = this
      self.cabosLogFiltered = self.finalInfo.filter((f) =>
        f.nome.toLowerCase().includes(self.search.toLowerCase())
      )
      return self.cabosLogFiltered
    }
  },

  methods: {
    getInfo() {
      this.$axios.get('log_cabos').then((res) => {
        this.cabosLogAtt = res.data
      })
      this.currentInfo = this.cabosLogAtt.filter(
        (item) => this.autoEletricaId.toString() === item.auto_eletrica_id && item.nome === this.whichItem /*eslint-disable-line*/
      )
      this.getTypes()
      this.filterForAction()
    },

    filterForAction() {
      if (this.actionSelected.toLowerCase() === 'todos') {
        this.currentInfoOfAction = this.currentInfo
      } else {
        this.currentInfoOfAction = this.currentInfo.filter((f) => f.acao.toLowerCase() === this.actionSelected.toLowerCase()) /*eslint-disable-line*/
      }
      this.filterFortype()
    },

    filterFortype() {
      if (this.typeSelected.toLowerCase() === 'todos') {
        this.finalInfo = this.currentInfoOfAction
      } else {
        this.finalInfo = this.currentInfoOfAction.filter((f) => f.tipo.toLowerCase() === this.typeSelected.toLowerCase()) /*eslint-disable-line*/
      }
    },

    getTypes() {
      switch (this.whichItem.toLowerCase()) {
        case 'cabo azul': {
          this.typeItens.push('Completo')
          this.typeItens.push('Grande')
          this.typeItens.push('Pequeno')
          break
        }
        case 'cabo sensores': {
          this.typeItens.push('Novo')
          this.typeItens.push('Antigo a')
          this.typeItens.push('Antigo p')
          break
        }
        case 'cabo alimentacao': {
          this.typeItens.push('Geral')
          break
        }
      }
    },

    ajustTime(time) {
      let newTime = ''
      newTime = time.split(' ')[0].split('-').reverse().join('/') + ' às ' + time.split(' ')[1] /*eslint-disable-line*/
      return newTime
    }
  }
}
</script>

<style scoped>
.log-compontent {
  padding: 5px 5px;
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

.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.content {
  width: 90%;
  padding-top: 5px;
  padding-bottom: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}

.filters {
  display: flex;
  flex-flow: row;
  justify-content: space-around;
  align-items: flex-start;
  width: 90%;
}
.ajust {
  width: 30%;
  display: flex;
  flex-flow: row;
}

.log {
  width: 100%;
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
}

.log-text {
  font-family: 'Exo Regular';
  font-size: 14px;
}

.divider {
  width: 100%;
  background-color: black;
}

@media screen and (max-width: 900px) {
  .log {
    flex-flow: column;
  }
}

@media screen and (max-width: 600px) {
  .filters {
    flex-flow: column;
    justify-content: center;
    align-items: center;
    padding-bottom: 15px;
  }
  .ajust {
    width: 100%;
  }
}
</style>
