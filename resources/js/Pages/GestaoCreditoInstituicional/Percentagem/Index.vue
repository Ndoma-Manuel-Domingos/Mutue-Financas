<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-uppercase">PERCENTAGEM DE APROVEITAMENTO</h1>
            </div>
            <div class="col-sm-6">
              <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
              <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-header bg-light">
                  <h5>Buscas Avançadas</h5>
                </div>
                <div class="card-body">
                  <div class="row">

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Ano Lectivo</label>
                        <div class="input-group">
                          <select
                            v-model="ano_lectivo"
                            class="form-control"
                          >
                            <option :value="ano.Codigo" v-for="ano in anos_lectivos" :key="ano.Codigo">{{ ano.Designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Graus Academicos</label>
                        <div class="input-group">
                          <select
                            v-model="grau"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option :value="grau.dodigo" v-for="grau in graus" :key="grau.dodigo">{{ grau.designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Curso</label>
                        <div class="input-group">
                          <select
                            v-model="curso"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option :value="curso.Codigo" v-for="curso in cursos" :key="curso.Codigo">{{ curso.Designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Instituições</label>
                        <div class="input-group">
                          <select
                            v-model="instituicao"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option :value="mes.codigo" v-for="mes in instituicoes" :key="mes.codigo">{{ mes.Instituicao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Tipo de Bolsa</label>
                        <div class="input-group">
                          <select
                            v-model="tipo_bolsa"
                            class="form-control"
                          >
                            <option value="">TODAS</option>
                            <option :value="bolsa.codigo" v-for="bolsa in bolsas" :key="bolsa.codigo"> {{ bolsa.designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Periodo</label>
                        <div class="input-group">
                          <select
                            v-model="semestre"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option :value="semestre.Codigo" v-for="semestre in semestres" :key="semestre.Codigo">{{ semestre.Designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Percentagem</label>
                        <div class="input-group">
                          <select
                            v-model="percentagem"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option value="5">5%</option>
                            <option value="10">10%</option>
                            <option value="25">25%</option>
                            <option value="30">30%</option>
                            <option value="35">35%</option>
                            <option value="40">40%</option>
                            <option value="45">45%</option>
                            <option value="50">50%</option>
                            <option value="55">55%</option>
                            <option value="60">60%</option>
                            <option value="65">65%</option>
                            <option value="70">70%</option>
                            <option value="75">75%</option>
                            <option value="80">80%</option>
                            <option value="85">85%</option>
                            <option value="90">90%</option>
                            <option value="95">95%</option>
                            <option value="100">100%</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label>Estados</label>
                        <div class="input-group">
                          <select
                            v-model="estado"
                            class="form-control"
                          >
                            <option value="">TODOS</option>
                            <option :value="turno.Codigo" v-for="turno in turnos" :key="turno.Codigo">{{ turno.Designacao }}</option>
                          </select>
                        </div>
                      </div>
                    </div>


                  </div>
                </div>
                <div class="card-footer">
                  <!-- <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>  Listar a busca
                  </button> -->
                </div>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-12 col-md-12">
              <div class="card mb-4">
                <div class="card-header">

                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Nº Matricula</th>
                          <th>Nome</th>
                          <th>Curso</th>
                          <th>Grau</th>
                          <th>Instituição</th>
                          <th>Período</th>
                          <th>Nº Inscrições</th>
                          <th>Nº Aprovadas</th>
                          <th>Taxa de Aprovação</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in items.data" :key="item.Cod_Matricula">
                          <td><a :href="route('mf.estudante-visualizar-perfil', item.Cod_Matricula)">{{ item.Cod_Matricula }}</a></td>
                          <td><a :href="route('mf.estudante-visualizar-perfil', item.Cod_Matricula)">{{ item.Nome_Completo }}</a></td>
                          <td>{{ item.curso }}</td>
                          <td>{{ item.grau }}</td>
                          <td>{{ item.Instituicao }}</td>
                          <td>{{ item.semestre }}</td>
                          <td></td>
                          <td></td>
                          <td></td>
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

        </div>
      </div>
    </MainLayouts>
  </template>



<script>

import { Link } from '@inertiajs/inertia-vue3'
import Paginacao from '../../../Shared/Paginacao.vue'

export default{
  props: [
    "items",
    "cursos",
    "instituicoes",
    "anos_lectivos",
    "bolsas",
    "semestres",
    "graus",
    "ano_lectivo_activo",
  ],
  components() {
    Link,
    Paginacao
  },
  data() {
    return {
      ano_lectivo: this.ano_lectivo_activo.Codigo,

      params: {},
      grau: "",
      curso: "",
      instituicao: "",
      tipo_bolsa: "",
      semestre: "",
      percentagem: "",
      estado: "",
    }
  },
  watch: {
    options: function(val) {
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
    ano_lectivo: function(val) {
      this.params.ano_lectivo = val;
      this.updateData();
    },
    grau: function(val) {
      this.params.grau = val;
      this.updateData();
    },

    curso: function(val) {
      this.params.curso = val;
      this.updateData();
    },

    instituicao: function(val) {
      this.params.instituicao = val;
      this.updateData();
    },

    tipo_bolsa: function(val) {
      this.params.tipo_bolsa = val;
      this.updateData();
    },

    semestre: function(val) {
      this.params.semestre = val;
      this.updateData();
    },

    percentagem: function(val) {
      this.params.percentagem = val;
      this.updateData();
    },

    estado: function(val) {
      this.params.estado = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/percentagem-aproveitamento", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        }
      });
    },

    imprimirPDF () {
      window.open("/estudante/propina-pagar/pdf-imprimir?searchAnoLectivo="+this.searchAnoLectivo+"&searchInstituicao="+this.searchInstituicao+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
    },

    imprimirEXCEL () {
      window.open("/estudante/propina-pagar/excel-imprimir/?searchAnoLectivo="+this.searchAnoLectivo+"&searchInstituicao="+this.searchInstituicao+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
    }
  }
}
</script>
