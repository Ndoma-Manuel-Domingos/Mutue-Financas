<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-uppercase">Listar Estudantes Personalizados</h4>
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
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Ano</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="ano_inicio"
                          >
                            <option value="">sdfsdf</option>
                          </select>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Ano De Ingresso Fim</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="ano_final"
                          >
                            <option value="">sdfsdf</option>
                          </select>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Grau Académico</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="grau"
                          >
                            <option value="">TODOS</option>
                            <option value="">sdfsdf</option>
                          </select>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Faculdade</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="faculdade"
                          >
                            <option value="">TODAS</option>
                            <option value="">sdfsdf</option>
                          </select>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Cursos</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="curso"
                          >
                            <option value="">TODOS</option>
                            <option value="">sdfsdf</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <div class="row">
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-header bg-light">
                  <h5>
                    <span class="float-left"
                      >TOTAL REGISTROS: 0</span
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
                        <th>Nº Matricula</th>
                        <th>Ano De Ingresso</th>
                        <th>Nome</th>
                        <th>Bilheite</th>
                        <th>Curso</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Divida(AOA)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <a >63463</a >
                        </td>
                        <td>sadasfsa</td>
                        <td>safasfas</td>
                        <td>asfasfaf</td>
                        <td>asfasf</td>
                        <td>afsfafasf</td>
                        <td>afsasfas</td>
                        <td>asfasfasf</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
  
                <div class="card-footer">
                  <Link href="" class="text-secondary">
                    TOTAL REGISTROS: 0
                  </Link>
                  <!-- <Paginacao
                    :links="estudantes.links"
                    :prev="estudantes.prev_page_url"
                    :next="estudantes.next_page_url"
                  /> -->
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
  
  export default {
    props: [
    ],
    components: {
      Link,
    },
    data() {
      return {
        ano_inicio: 21,
        ano_final: 21,
        grau: "",
        faculdade: "",
        curso: "",
        params: {},
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
  
      ano_inicio: function (val) {
        this.params.ano_inicio = val;
        this.updateData();
      },
  
      ano_final: function (val) {
        this.params.ano_final = val;
        this.updateData();
      },
  
      grau: function (val) {
        this.params.grau = val;
        this.updateData();
      },
  
      faculdade: function (val) {
        this.params.faculdade = val;
        this.updateData();
      },
  
      curso: function (val) {
        this.params.curso = val;
        this.updateData();
      },
  
      page: function (val) {
        this.params.curso = val;
        this.updateData();
      },
    },
  
    methods: {
      updateData() {
        this.$Progress.start();
        this.$inertia.get("/estudantes/inactivos", this.params, {
          preserveState: true,
          preverseScroll: true,
          onSuccess: () => {
            this.$Progress.finish();
          },
        });
      },
  
      imprimirPDF() {
        window.open(
          "/estudantes/inactivos/pdf?ano_inicio=" +
            this.ano_inicio +
            "&ano_final=" +
            this.ano_final +
            "&grau=" +
            this.grau +
            "&faculdade=" +
            this.faculdade +
            "&curso=" +
            this.curso,
          "_blank"
        );
      },
  
      imprimirEXCEL() {
        window.open(
          "/estudantes/inactivos/excel?ano_inicio=" +
            this.ano_inicio +
            "&ano_final=" +
            this.ano_final +
            "&grau=" +
            this.grau +
            "&faculdade=" +
            this.faculdade +
            "&curso=" +
            this.curso,
          "_blank"
        );
      },
    },
  };
  </script>
  