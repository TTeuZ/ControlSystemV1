<template>
  <v-container>
    <v-row class="ma-0 pa-0 esp-infos" justify="space-around" align="center">
      <v-col
        class="cabo-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
      >
        <span class="cabos-card-title">
          EM ESTOQUE
        </span>
        <div class="cabos">
          <span class="cabo-text">
            Completo
          </span>
        </div>
        <div class="cabos">
          <span class="cabo-text">
            Grande
          </span>
        </div>
        <div class="cabos">
          <span class="cabo-text">
            Pequeno
          </span>
        </div>
      </v-col>
      <v-col
        class="cabo-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
      >
        <span class="cabos-card-title">
          COM DEFEITOS
        </span>
      </v-col>
      <button id="add-item" @click="openAdd()">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </v-row>
    {{ autos[selectedAutoEletrica][2][0] }}
  </v-container>
</template>

<script>
export default {
  props: {
    autos: {
      type: Array,
      required: true
    },
    selectedAutoEletrica: {
      type: Number,
      required: true
    },
    selectedType: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      caboAzulOK: [],
      caboAzulNOK: []
    }
  },
  mounted() {
    this.getQuantidade()
  },
  methods: {
    getQuantidade() {
      const cabos = this.autos[this.selectedAutoEletrica][2]
      if (this.autos !== [] && cabos !== []) { /*eslint-disable-line*/
        cabos.forEach((item) => {
          const cabo = {
            tipo: item.tipo,
            situacao: item.situacao
          }
          if (item.nome === 'CABO AZUL' && item.situacao === 0) {
            this.caboAzulOK.push(cabo)
          } else {
            this.caboAzulNOK.push(cabo)
          }
        })
      }
    }
  }
}
</script>

<style scoped>
.add-icon {
  color: orange;
}

.border {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

.esp-infos {
  width: 100%;
  margin-left: 15px !important;
  margin-top: 15px !important;
}

.cabo-state-box {
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-flow: column;
}

.cabos-card-title {
  font-family: 'Exo Regular';
  font-size: 21px;
  width: 100%;
  border-bottom: 3px solid black;
  text-align: center;
}

.cabos {
  width: 100%;
  border-bottom: 1px solid black;
  margin-top: 15px;
  display: flex;
  justify-content: space-around;
  cursor: pointer;
}

.cabo-text {
  font-family: 'Exo Regular';
  font-size: 19px;
}

.modal-text {
  font-family: 'Exo Regular';
  font-size: 20px;
}

.modal-ajust {
  margin-left: 30px;
}

#add-item {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 1px solid black;
  background-color: black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
  position: absolute;
  right: 25px;
  bottom: 25px;
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
</style>
