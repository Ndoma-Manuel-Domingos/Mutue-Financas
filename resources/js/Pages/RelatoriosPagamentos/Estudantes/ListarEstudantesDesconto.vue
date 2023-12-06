<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Listar Estudantes Com Desconto</h4>
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
        <div class="row">
          <div class="col-12">
            <form action="">
              <div class="card card-light">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label for="" class="text-secondary"
                          >Tipo Instituição</label
                        >
                        <div class="input-group input-group">
                          <select
                            v-model="tipo_instituicao"
                            class="form-control form-control-sm"
                          >
                            <option value="">TODOS</option>
                            <option
                              :value="item.codigo"
                              v-for="item in tipo_instituicoes"
                              :key="item.codigo"
                            >
                              {{ item.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Instituições</label>
                        <div class="input-group input-group">
                          <select
                            v-model="instituicao"
                            class="form-control form-control-sm"
                          >
                            <option value="">TODAS</option>
                            <option
                              :value="item.codigo"
                              v-for="item in instituicoes"
                              :key="item.codigo"
                            >
                              {{ item.Instituicao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Tipo Desconto</label>
                        <div class="input-group input-group">
                          <select
                            v-model="tipo_desconto"
                            class="form-control form-control-sm"
                          >
                            <option value="">TODAS</option>
                            <option
                              :value="item.Codigo"
                              v-for="item in tipo_descontos"
                              :key="item.Codigo"
                            >
                              {{ item.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ items.total }}</span
                  >
                </h5>
              </div>

              <div class="card-body">
                <table
                  id="carregarTabelaEstudantes"
                  style="width: 100%"
                  class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                >
                  <thead>
                    <tr>
                      <th>Matricula</th>
                      <th>Nome</th>
                      <th>Instituições</th>
                      <th>Tipo de Desconto</th>
                      <th>Estado</th>
                      <th>Período</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in items.data" :key="item.bolseiro">
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.codigo_matricula}`"
                          >{{ item.codigo_matricula }}</a
                        >
                      </td>
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.codigo_matricula}`"
                          >{{ item.nome }}</a
                        >
                      </td>
                      <td>{{ item.instituicao }}</td>
                      <td>{{ item.tipoDesconto }}</td>
                      <td class="text-uppercase">{{ item.Designacao }}</td>
                      <td>{{ item.semestreItem }}</td>
                    </tr>
                  </tbody>
                </table>
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
      </div>
    </div>
  </MainLayouts>
</template>



<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../../Shared/Paginacao.vue";

export default {
  props: ["items", "instituicoes", "tipo_descontos", "tipo_instituicoes"],
  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      params: {},

      tipo_instituicao: "",
      tipo_desconto: "",
      instituicao: "",
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

    tipo_instituicao: function (val) {
      this.params.tipo_instituicao = val;
      this.updateData();
    },
    tipo_desconto: function (val) {
      this.params.tipo_desconto = val;
      this.updateData();
    },
    instituicao: function (val) {
      this.params.instituicao = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/relatorios/listar-estudantes-desconto", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    imprimirPDF() {
      window.open(
        "/relatorios/listar-estudantes-desconto-imprimir-pdf?tipo_instituicao=" +
          this.tipo_instituicao +
          "&desconto=" +
          this.tipo_desconto +
          "&instituicao=" +
          this.instituicao,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/relatorios/listar-estudantes-desconto-imprimir-excel?tipo_instituicao=" +
          this.tipo_instituicao +
          "&desconto=" +
          this.tipo_desconto +
          "&instituicao=" +
          this.instituicao,
        "_blank"
      );
    },
  },
};
</script>
