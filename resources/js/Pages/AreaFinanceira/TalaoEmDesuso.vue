<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Talão Em Desuso</h1>
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
          <div class="card-header">
            <Link
              :href="route('mf.adicionar-talao-desuso')"
              class="btn btn-primary btn-sm"
            >
              Adicionar Talão
            </Link>
          </div>
          <div class="card-body bg-light">
            <div class="row">
              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Operador</label>
                  <select name="" id="" v-model="operador" class="form-control">
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

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Data Inicio:</label>
                  <input
                    v-model="data_inicio"
                    type="date"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Data Final:</label>
                  <input
                    v-model="data_final"
                    type="date"
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
                    <th>Nº da Operação Bancaria-1</th>
                    <th>Nº da Operação Bancaria-2</th>
                    <th>Valor Depositado</th>
                    <th>Utilizador</th>
                    <th>Data</th>
                    <th>Anexo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="pagamento in items.data" :key="pagamento.Codigo">
                    <td>{{ pagamento.preinscricao.Nome_Completo ?? "===" }}</td>
                    <td>{{ pagamento.N_Operacao_Bancaria ?? "===" }}</td>
                    <td>{{ pagamento.N_Operacao_Bancaria2 ?? "===" }}</td>
                    <td>{{ formatValor(pagamento.valor_depositado ?? 0) }}</td>
                    <td>
                      {{
                        pagamento.utilizadores
                          ? pagamento.utilizadores.nome
                          : ""
                      }}
                    </td>
                    <td>{{ pagamento.Data ?? "===" }}</td>
                    <td colspan="2">
                      <a
                        target="_blink"
                        :href="
                          'https://mutue.ao/storage/documentos/' +
                          pagamento.nome_documento
                        "
                        class="btn btn-primary btn-sm"
                      >
                        Visualizar
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer">
            <Link href="" class="text-secondary">
              TOTAL REGISTROS: {{ items.total }}
            </Link>
            <Paginacao
              :links="items.links"
              :prev="items.prev_page_url"
              :next="items.next_page_url"
            />
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";

export default {
  props: ["items"],

  components: {
    Link,
    Paginacao,
  },

  data() {
    return {
      operador: "",
      data_inicio: new Date().toISOString().substr(0, 10),
      data_final: new Date().toISOString().substr(0, 10),
      params: {},
      dados_pagamentos: {},
    };
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
      this.$inertia.get("/talao/desuso", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },

    imprimirPDF() {
      window.open(
        "/talao/desuso-pdf?o=" +
          this.operador +
          "&di=" +
          this.data_inicio +
          "&df=" +
          this.data_final,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/talao/desuso-excel?o=" +
          this.operador +
          "&di=" +
          this.data_inicio +
          "&df=" +
          this.data_final,
        "_blank"
      );
    },
  },
};
</script>
