<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-uppercase">Listar Estudantes Matriculados</h4>
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
                        <label>Ano Lectivo</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="ano_lectivo"
                          >
                            <option value="">Todos</option>
                            <option :value="ano.Codigo" v-for="(ano, index) in ano_lectivos" :key="index">
                              {{ ano.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Curso</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="curso"
                          >
                            <option value="">Todos</option>
                            <option :value="cur.Codigo" v-for="(cur, index) in cursos" :key="index">
                              {{ cur.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Ano Curricular</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="classe"
                          >
                            <option value="">Todos</option>
                            <option :value="clas.Codigo" v-for="(clas, index) in classes" :key="index">
                              {{ clas.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Graduação</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="candidatura"
                          >
                            <option value="">Todos</option>
                            <option :value="cand.id" v-for="(cand, index) in candidaturas" :key="index">
                              {{ cand.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Periodo</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="turno"
                          >
                            <option value="">Todos</option>
                            <option :value="clas.Codigo" v-for="(clas, index) in turnos" :key="index">
                              {{ clas.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                  
                  
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Estudante</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="estado"
                          >
                            <option value="">Todos</option>
                            <option value="estudante_antigo">Estudantes Antigos</option>
                            <option value="estudante_novo">Estudantes Novas</option>
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
                      >TOTAL REGISTROS:  {{ estudantes.total }}</span
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
                        <th class="text-right">Nº Matricula</th>
                        <th>Nome</th>
                        <th>Tipo Aluno</th>
                        <th>Ano Curricular</th>
                        <th>Telefone</th>
                        <th>Genero</th>
                        <th>Curso</th>
                        <th>Ano De Ingresso</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in estudantes.data" :key="item">
                          <td class="text-right">{{ item.codigo_matricula ?? "" }}</td>
                          <td>{{ item.nome ?? "" }}</td>
                          <td>Tipo 1</td>
                          <td>{{ item.classe ?? "" }}</td>
                          <td>{{ item.telefone ?? "--- --- ---" }}</td>
                          <td>{{ item.genero ?? "Personalizado" }}</td>
                          <td>{{ item.curso ?? "" }}</td>
                          <td>{{ item.anoLectivo ?? "" }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
  
                <div class="card-footer">
                  <Link href="" class="text-secondary">
                    TOTAL REGISTROS:  {{ estudantes.total }}
                  </Link>
                  <Paginacao
                    :links="estudantes.links"
                    :prev="estudantes.prev_page_url"
                    :next="estudantes.next_page_url"
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
  import Paginacao from "../../Shared/Paginacao.vue";
  
  export default {
    props: [
      "ano_lectivos",
      "cursos",
      "turnos",
      "classes",
      "estudantes",
      "candidaturas"
    ],
    components: {
        Link,
        Paginacao,
    },
    data() {
      return {
        ano_lectivo: "",
        curso: "",
        classe: "",
        turno: "",
        estado: "",
        candidatura: "",
        
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
  
      ano_lectivo: function (val) {
        this.params.ano_lectivo = val;
        this.updateData();
      },
  
      curso: function (val) {
        this.params.curso = val;
        this.updateData();
      },
  
      classe: function (val) {
        this.params.classe = val;
        this.updateData();
      },
  
      turno: function (val) {
        this.params.turno = val;
        this.updateData();
      },
  
      estado: function (val) {
        this.params.estado = val;
        this.updateData();
      },
      
      candidatura: function (val) {
        this.params.candidatura = val;
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
        this.$inertia.get("/listar-estudantes-matriculados", this.params, {
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
  