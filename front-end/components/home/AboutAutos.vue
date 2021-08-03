<template>
  <v-container>
    <div id="auto-total">
      <span id="auto-title"> AUTO ELÉTRICAS </span>
      <div id="auto-table">
        <nuxt-link to="/autoEletricas">
          <div v-for="(auto, a) in autos" id="list-item" :key="auto">
            <span id="item-title"> {{ auto[0].nome }} </span>
            <span class="auto-desc mt-3">Lacres</span>
            <div class="text-box">
              <span v-if="infoLacre(a) <= 2" class="font-error auto-desc">
                Estoque de lacres abaixo/igual de 2
              </span>
              <span v-if="infoLacre(a) > 2" class="font-OK auto-desc">
                Estoque de lacres OK
              </span>
            </div>
            <span class="auto-desc">Cabos</span>
            <div class="text-box">
              <span class="auto-desc">Cabos Azuis: </span>
              <div>
                <span v-if="!infoCabo('azul', a).ok" class="font-error auto-desc"> <!-- eslint-disable-line -->
                  Estoque de cabos azuis abaixo de 2
                </span>
                <span v-if="infoCabo('azul', a).caboType3 <= 2" class="font-error auto-desc"> (Completo) </span> <!-- eslint-disable-line -->
              </div>
              <span v-if="infoCabo('azul', a).ok" class="font-OK auto-desc"> <!-- eslint-disable-line -->
                Estoque de cabos azuis OK
              </span>
            </div>
            <div class="text-box">
              <span class="auto-desc">Cabos de Sensores: </span>
              <div>
                <span v-if="!infoCabo('sensor', a).ok" class="font-error auto-desc"> <!-- eslint-disable-line -->
                  Estoque de cabos de sensores abaixo de 2
                </span>
                <span v-if="infoCabo('sensor', a).caboType1 <= 2" class="font-error auto-desc"> (Novo) </span> <!-- eslint-disable-line -->
                <span v-if="infoCabo('sensor', a).caboType2 <= 2" class="font-error auto-desc"> (Antigo A) </span> <!-- eslint-disable-line -->
              </div>
              <span v-if="infoCabo('sensor', a).ok" class="font-OK auto-desc"> <!-- eslint-disable-line -->
                Estoque de cabos de sensores OK
              </span>
            </div>
            <div class="text-box">
              <span class="auto-desc">Cabos de Alimentação: </span>
              <span v-if="infoCabo('alimentacao', a).quantidade <= 2" class="font-error auto-desc"> <!-- eslint-disable-line -->
                Estoque de cabos de alimentacao abaixo de 2
              </span>
              <span v-if="infoCabo('alimentacao', a).quantidade > 2" class="font-OK auto-desc"> <!-- eslint-disable-line -->
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
        if (item.situacao === '0') quantidade++
      })
      return quantidade
    },
    infoCabo(tipo, autoeletrica) {
      const cabos = this.autos[autoeletrica][2]
      const situation = {
        quantidade: 0,
        caboType1: 0,
        caboType2: 0,
        caboType3: 0,
        ok: false
      }
      switch (tipo) {
        case 'azul': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO AZUL' && cabo.situacao === '0') {
              switch (cabo.tipo) {
                case 'grande': {
                  situation.caboType1++
                  break
                }
                case 'pequeno': {
                  situation.caboType2++
                  break
                }
                case 'completo': {
                  situation.caboType3++
                  break
                }
              }
              situation.quantidade++
            }
          })
          if (situation.quantidade > 2 && situation.caboType3 > 2) situation.ok = true /* eslint-disable-line */
          break
        }
        case 'sensor': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO SENSORES' && cabo.situacao === '0') {
              switch (cabo.tipo) {
                case 'novo': {
                  situation.caboType1++
                  break
                }
                case 'antigo A': {
                  situation.caboType2++
                  break
                }
                case 'antigo P': {
                  situation.caboType3++
                  break
                }
              }
              situation.quantidade++
            }
          })
          if (situation.quantidade > 2 && situation.caboType1 > 2 && situation.caboType2 > 2) situation.ok = true /* eslint-disable-line */
          break
        }
        case 'alimentacao': {
          cabos.forEach((cabo) => {
            if (cabo.nome === 'CABO ALIMENTACAO' && cabo.situacao === '0')
              situation.quantidade++
          })
          break
        }
      }
      return situation
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
