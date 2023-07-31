<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Controle Actualização de Saldo</h1>
          </div>
          <div class="col-sm-6">
            <a
              @click="imprimirPDF"
              class="btn btn-danger btn-sm float-sm-right mr-2"
              ><i class="fas fa-file-pdf"></i> PDF</a
            >
            <a
              @click="imprimirEXCEL"
              class="btn btn-success btn-sm float-sm-right mr-2"
              ><i class="fas fa-file-excel"></i> Excel</a
            >
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-body bg-light">
            <div class="row">
              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Operador</label>
                  <div
                    class="input-group date"
                    id="reservationdate"
                    data-target-input="nearest"
                  >
                    <select
                      v-model="operador"
                      class="form-control"
                    >
                      <option value="">Todos Operadores</option>
                      <option
                        :value="utilizador.utilizadores.pk_utilizador"
                        v-for="utilizador in utilizadores"
                        :key="utilizador"
                      >
                        {{ utilizador.utilizadores.nome }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label>Data Inicio:</label>
                  <input
                    type="date"
                    v-model="data_inicio"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label>Data Final:</label>
                  <input
                    type="date"
                    v-model="data_final"
                    class="form-control"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Estudante</th>
                    <th>Data Actualização</th>
                    <th>Saldo Anterior</th>
                    <th>Saldo Actual</th>
                    <th>Criado Por</th>
                    <th>Ver Mais</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="saldo in saldos.data" :key="saldo.id">
                    <td>{{ saldo.aluno }}</td>
                    <td>{{ formatData(saldo.data_actualizacao) }}</td>
                    <td>{{ formatValor(saldo.saldo_anterior) }}</td>
                    <td>{{ formatValor(saldo.saldo_actual) }}</td>
                    <td>{{ nome(saldo.nome) }}</td>
                    <td>
                      <button
                        @click="mostrar_detalhe(saldo.id)"
                        class="btn-sm btn-primary"
                      >
                        Ver Mais
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer">
            <Paginacao
              :links="saldos.links"
              :prev="saldos.prev_page_url"
              :next="saldos.next_page_url"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-xl">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalhes</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <th colspan="6" class="bg-light">Dados</th>
                  </tr>
                  <tr>
                    <td colspan="1" class="text-left">Observação:</td>
                    <td colspan="5" class="text-left">
                      <strong> {{ detalhe.obs }}</strong>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="1" class="text-left">Anexo:</td>
                    <td colspan="5"><strong>{{ detalhe.url_anexo ?? 'sem anexo' }}</strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Fechar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  </MainLayouts>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";

export default {
  props: ["saldos", "utilizadores"],
  components: {
    Link,
    Paginacao
  },
  data() {
    return {
      operador: null,
      data_inicio: new Date().toISOString().substr(0, 10),
      data_final: new Date().toISOString().substr(0, 10),

      params: {},
      detalhe: [],
    }
  },
  watch: {
    options: function (val) {
      this.params.page = val.page;
      this.params.page_size = val.itemsPerPage;
      if (val.sortBy.length != 0) {
        this.params.sort_by = val.sortBy[0];
        this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
      } else {
        this.params.sort_by = null;
        this.params.order_by = null;
      }
      this.updateData();
    },
    operador: function (val) {
      this.params.operador = val;
      this.updateData();
    },
    data_inicio: function (val) {
      this.params.data_inicio = val;
      this.updateData();
    },

    data_final: function (val) {
      this.params.data_final = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/consultar/actualizacao-saldo", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    mostrar_detalhe(value) {
    
      this.$Progress.start();
      axios
        .get(
          "/consultar/actualizacao-saldo/detalhes/" +
          value
        )
        .then((response) => {
          this.detalhe = response.data;
          $("#modal-xl").modal('show');
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Sem registros encontrado!");
          this.$Progress.fail();
      });

    },

    formatData(data_input) {
      let data = new Date(data_input);
      let dataFormatada =
        data.getDate() + "-" + (data.getMonth() + 1) + "-" + data.getFullYear();

      return dataFormatada;
    },

    nome(string) {
      let result = string.replace('"', " ");
      let result2 = result.replace('"', " ");
      return result2;
    },

    formatValor: function (atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);

      return valorFormatado;
    },

    imprimirPDF() {
      window.open(
        "/consultar/actualizacao-saldo-pdf/" +
          this.operador +
          "/" +
          this.data_inicio +
          "/" +
          this.data_final,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/consultar/actualizacao-saldo-excel/" +
          this.operador +
          "/" +
          this.data_inicio +
          "/" +
          this.data_final,
        "_blank"
      );
    },
  },
};
</script>
