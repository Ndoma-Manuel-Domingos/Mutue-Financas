te<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4 class="m-0 text-uppercase">Listagem Geral Estudantes</h4>
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
                  
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Ano Lectivo</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="ano_lectivo"
                          >
                            <option value="">Tados</option>
                            <option :value="ano.Codigo" v-for="(ano, index) in ano_lectivos" :key="index">
                              {{ ano.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Faculdade</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="faculdade"
                          >
                            <option value="">Tados</option>
                            <option :value="facul.codigo" v-for="(facul, index) in faculdades" :key="index">
                              {{ facul.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Grau Academico</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="grau_academico"
                          >
                            <option value="">Tados</option>
                            <option :value="grau.id" v-for="(grau, index) in grau_academicos" :key="index">
                              {{ grau.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Curso</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="curso"
                          >
                            <option value="">Tados</option>
                            <option :value="cur.Codigo" v-for="(cur, index) in cursos" :key="index">
                              {{ cur.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Ano Curricular</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="classe"
                          >
                            <option value="">Tados</option>
                            <option :value="clas.Codigo" v-for="(clas, index) in classes" :key="index">
                              {{ clas.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Periodo</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="turno"
                          >
                            <option value="">Tados</option>
                            <option :value="clas.Codigo" v-for="(clas, index) in turnos" :key="index">
                              {{ clas.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Nacionalidade</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="nacionalidade"
                          >
                            <option value="">Tados</option>
                            <option :value="nacionalidad.Codigo" v-for="(nacionalidad, index) in nacionalidades" :key="index">
                              {{ nacionalidad.Designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Nessecidade Especial</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="necessidades_especial"
                          >
                            <option value="">Tados</option>
                            <option :value="item.id" v-for="(item, index) in necessidades_especiais" :key="index">
                              {{ item.designacao }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label>Sexo</label>
                        <div class="input-group">
                          <select
                            class="form-control form-control-sm"
                            v-model="sexo"
                          >
                            <option value="">Tados</option>
                            <option :value="sex.Designacao" v-for="(sex, index) in sexos" :key="index">
                              {{ sex.Designacao }}
                            </option>
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
                        <th>Nº Matricula</th>
                        <th>Nome</th>
                        <th>Sexo</th>
                        <th>Faculdade</th>
                        <th>Curso</th>
                        <th>Necessidade</th>
                        <th>Nacionalidade</th>
                        <th>Periodo</th>
                        <th>Ano Lectivo</th>
                        <th>Tipo Aluno</th>
                        <th>Ano Curricular</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input type="text" v-model="input_listagem_geral_matricula_estudante" class="form-control" placeholder="Informe o número da matricula"></td>
                      </tr>
                      <tr v-for="(item) in estudantes.data" :key="item">
                        <td>{{ item.codigo ?? '' }}</td>
                        <td>{{ item.nome ?? '' }}</td>
                        <!-- <td>{{ item.admissao ? (item.admissao.preinscricao ? item.admissao.preinscricao.Nome_Completo : '') : '' }}</td>
                        <td>{{ item.admissao ? (item.admissao.preinscricao ? item.admissao.preinscricao.Sexo : '') : '' }}</td>
                        <td>{{ item.curso ? (item.curso.faculdade ? item.curso.faculdade.designacao : '') : '' }}</td>
                        <td>{{ item.curso ?  item.curso.Designacao : '' }}</td>
                        <td>{{ item.admissao ? (item.admissao.preinscricao ? (item.admissao.preinscricao.necessidade ? item.admissao.preinscricao.necessidade.designacao : '') : '') : '' }}</td>
                        <td>{{ item.admissao ? (item.admissao.preinscricao ? (item.admissao.preinscricao.nacionalidade ? item.admissao.preinscricao.nacionalidade.Designacao : '') : '') : '' }}</td>
                        <td>{{ item.admissao ? (item.admissao.preinscricao ? (item.admissao.preinscricao.turno ? item.admissao.preinscricao.turno.Designacao : '') : '') : '' }}</td>
                        <td>{{ item.confirmacao ? (item.confirmacao.ano_lectivo ? item.confirmacao.ano_lectivo.Designacao : '') : '' }}</td>
                        <td>Tipo 1</td>
                        <td>{{ item.grade_curricular_aluno ? (item.grade_curricular_aluno.grade_curricular ? (item.grade_curricular_aluno.grade_curricular.classe ? item.grade_curricular_aluno.grade_curricular.classe.Designacao : '') : '') : '' }}</td>
                         -->
                        <td>{{ item.sexo }}</td>
                        <td>{{ item.faculdade }}</td>
                        <td>{{ item.curso }}</td>
                        <td>{{ item.nessecidade }}</td>
                        <td>{{ item.nacionalidade }}</td>
                        <td>{{ item.periodo }}</td>
                        <td>{{ item.anolectivo }}</td>
                        <td>Tipo 1</td>
                        <td>{{ item.classe }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
  
                <div class="card-footer">
                  <Link href="" class="text-secondary">
                    TOTAL REGISTROS: {{ estudantes.total }}
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
      "faculdades",
      "grau_academicos",
      "cursos",
      "turnos",
      "nacionalidades",
      "classes",
      "necessidades_especiais",
      "sexos",
      "estudantes"
    ],
    components: {
      Link,
      Paginacao
    },
    data() {
      return {
        ano_lectivo: "",
        faculdade: "",
        grau_academico: "",
        turno: "",
        curso: "",
        classe: "",
        nacionalidade: "",
        necessidades_especial: "",
        sexo: "",
        
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
      
      turno: function (val) {
        this.params.turno = val;
        this.updateData();
      },
  
      faculdade: function (val) {
        this.params.faculdade = val;
        this.updateData();
      },
  
      grau_academico: function (val) {
        this.params.grau_academico = val;
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
  
      nacionalidade: function (val) {
        this.params.nacionalidade = val;
        this.updateData();
      },
      
      necessidades_especial: function (val) {
        this.params.necessidades_especial = val;
        this.updateData();
      },
  
      sexo: function (val) {
        this.params.sexo = val;
        this.updateData();
      },
    },
  
    methods: {
      updateData() {
        this.$Progress.start();
        this.$inertia.get("/listagem-geral-estudantes", this.params, {
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
  