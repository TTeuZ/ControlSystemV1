<template>
  <v-container>
    <div id="auto-total">
      <span id="auto-title"> AUTO ELÉTRICAS </span>
      <div id="auto-table">
        <nuxt-link to="/autoeletricas">
          <div v-for="(auto, a) in autos" id="list-item" :key="auto">
            <span id="item-title"> {{ auto[0].nome }} </span>
            <span class="auto-desc mt-3">Lacres</span>
            <div class="text-box">
              <span v-if="infoLacre(a) <= 2" class="font-error auto-desc">
                Estoque de lacres abaixo de 2
              </span>
              <span v-if="infoLacre(a) > 2" class="font-OK auto-desc">
                Estoque de lacres OK
              </span>
            </div>
            <span class="auto-desc">Cabos</span>
            <div class="text-box">
              <span class="auto-desc">Cabos Azuis: </span>
              <span
                v-if="infoCabo('azul', a) <= 2"
                class="font-error auto-desc"
              >
                Estoque de cabos azuis abaixo de 2
              </span>
              <span v-if="infoCabo('azul', a) > 2" class="font-OK auto-desc">
                Estoque de cabos azuis OK
              </span>
            </div>
            <div class="text-box">
              <span class="auto-desc">Cabos de Sensores: </span>
              <span
                v-if="infoCabo('sensor', a) <= 2"
                class="font-error auto-desc"
              >
                Estoque de cabos de sensores abaixo de 2
              </span>
              <span v-if="infoCabo('sensor', a) > 2" class="font-OK auto-desc">
                Estoque de cabos de sensores OK
              </span>
            </div>
            <div class="text-box">
              <span class="auto-desc">Cabos de Alimentação: </span>
              <span
                v-if="infoCabo('alimentacao', a) <= 2"
                class="font-error auto-desc"
              >
                Estoque de cabos de alimentacao abaixo de 2
              </span>
              <span
                v-if="infoCabo('alimentacao', a) > 2"
                class="font-OK auto-desc"
              >
                Estoque de cabos de alimentacao OK
              </span>
            </div>
            <v-divider id="divider" />
          </div>
        </nuxt-link>
      </div>
    </div>
  </v-container>
</template>

<script>
export default {
  props: {
    autos: {
      type: Array,
      required: true
    }
  },
  methods: {
    infoLacre(autoeletrica) {
      const equip = this.autos[autoeletrica][1]
      let quantidade = 0
      equip.forEach((item) => {
        if (item.situacao === 0) quantidade++
      })
      return quantidade
    },
    infoCabo(tipo, autoeletrica) {
      const cabos = this.autos[autoeletrica][2]
      let quantidade = 0
      switch (tipo) {
        case 'azul': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO AZUL' && cabo.situacao === 0) quantidade++
          })
          break
        }
        case 'sensor': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO SENSORES' && cabo.situacao === 0)
              quantidade++
          })
          break
        }
        case 'alimentacao': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO ALIMENTACAO' && cabo.situacao === 0)
              quantidade++
          })
          break
        }
      }
      return quantidade
    }
  }
}
</script>

<style scoped>
#auto-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-content: center;
}

#auto-title {
  font-family: 'Exo Regular';
  font-size: 20px;
  text-align: center;
}

#auto-table {
  border: 1px solid black;
  display: flex;
  flex-flow: column;
  justify-content: flex-start;
  align-content: center;
  margin-top: 30px;
  width: 100%;
  max-width: 550px;
  height: auto;
  max-height: 600px;
  align-self: center;
  overflow: auto;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#list-item {
  width: 100%;
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

#item-title {
  font-family: 'Exo Regular';
  font-size: 18px;
  padding-top: 15px !important;
  font-weight: bold;
  border-bottom: 1px solid black;
  width: 100%;
  text-align: center;
}

a {
  text-decoration: none;
  color: black;
}

.text-box {
  width: 100%;
  margin: 10px 0px;
  padding-left: 30px;
}

.auto-desc {
  font-size: 16px;
  font-family: 'Exo Regular';
}

.font-error {
  color: red;
}

.font-OK {
  color: green;
}

#divider {
  background-color: black;
  width: 100%;
}

@media screen and (max-width: 1262px) {
  #auto-table {
    max-height: 300px;
  }
}
</style>
