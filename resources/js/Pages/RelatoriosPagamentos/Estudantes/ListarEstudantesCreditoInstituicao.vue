<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-uppercase">Listar Estudantes Com Creditos Educacional</h1>
            </div>
            <div class="col-sm-6">
              <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
              <a  @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i class="fas fa-file-excel"></i> Excel</a>
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
                        <div class="card-header">Buscas Avançadas</div>
                        <div class="card-body">
                          <div class="row">

                            <div class="col-12 col-md-3">
                              <div class="form-group">
                                <label for="" class="text-secondary">Tipo Instituição</label>
                                <div class="input-group input-group">
                                    <select
                                        v-model="tipo_instituicao"
                                        class="form-control"
                                      >
                                        <option value="">TODOS</option>
                                        <option :value="item.codigo" v-for="item in tipo_instituicoes"  :key="item.codigo">{{ item.designacao }}</option>
                                    </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>Instituições</label>
                                    <div class="input-group input-group">
                                        <select v-model="instituicao"
                                            class="form-control">
                                            <option value="">TODAS</option>
                                            <option :value="item.codigo"  v-for="item in instituicoes"  :key="item.codigo">
                                                {{ item.Instituicao }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Bolsa</label>
                                    <div class="input-group input-group">
                                        <select v-model="bolsa"
                                            class="form-control">
                                            <option value="">TODAS</option>
                                            <option :value="item.codigo" v-for="item in bolsas"
                                                :key="item.codigo">
                                                {{ item.designacao }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>Percetagem</label>
                                    <div class="input-group input-group">
                                        <select v-model="desconto"
                                            class="form-control">
                                            <option value="">TODAS</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="15">15%</option>
                                            <option value="20">20%</option>
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

                          </div>
                        </div>
                        <div class="card-footer"></div>
                      </div>
                    </form>
                </div>
            </div>

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header bg-light">
                  <h3 class="card-title">
                    <strong>LISTAGEM</strong>
                  </h3>
                  <div class="card-tools">

                  </div>
                </div>

                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <!-- <th>Codigo</th> -->
                          <th>Matricula</th>
                          <th>Nome</th>
                          <th>Instituições</th>
                          <!-- <th>Curso</th> -->
                          <th>Tipo de Bolsa</th>
                          <th>Desconto</th>
                          <th>Estado</th>
                          <th>Período</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in items.data" :key="item.bolseiro">
                          <td><a :href="route('mf.estudante-visualizar-perfil', item.codigo_matricula)">{{ item.codigo_matricula }}</a></td>
                          <td><a :href="route('mf.estudante-visualizar-perfil', item.codigo_matricula)">{{ item.nome }}</a></td>
                          <td>{{ item.instituicao }}</td>
                          <td>{{ item.tipobolsa }}</td>
                          <td>{{ item.desconto }}</td>
                          <td v-if="item.status ==0" class="">
                              <span class="text-success">ACTIVO</span>
                          </td>
                          <td v-if="item.status ==1" class="">
                              <span class="text-danger">INACTIVO</span>
                          </td>
                          <td>{{ item.semestreItem }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="card-footer">
                  <Link href="" class="text-secondary">
                  TOTAL REGISTROS: {{ items.total }}
                  </Link>
                  <Paginacao :links="items.links"
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
import { Link } from "@inertiajs/inertia-vue3"
import Paginacao from '../../../Shared/Paginacao.vue';


export default{
  props: [
    "items",
    "instituicoes",
    "bolsas",
    "tipo_instituicoes",
  ],
  components: {
    Link,
    Paginacao
  },
  data(){

    return {
      params: {},

      tipo_instituicao: "",
      instituicao: "",
      bolsa: "",
      desconto: "",
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

    tipo_instituicao: function(val) {
      this.params.tipo_instituicao = val;
      this.updateData();
    },
    instituicao: function(val) {
      this.params.instituicao = val;
      this.updateData();
    },

    desconto: function(val) {
      this.params.desconto = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/relatorios/listar-estudantes-credito-instituicao", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        }
      });
    },
    imprimirPDF () {
      window.open("/relatorios/listar-estudantes-credito-instituicao-imprimir-pdf?tipo_instituicao="+this.tipo_instituicao+"&instituicao="+this.instituicao+"&bolsa="+this.bolsa+"&desconto="+this.desconto, "_blank");
    },

    imprimirEXCEL () {
      window.open("/relatorios/listar-estudantes-credito-instituicao-imprimir-excel?tipo_instituicao="+this.tipo_instituicao+"&instituicao="+this.instituicao+"&bolsa="+this.bolsa+"&desconto="+this.desconto, "_blank");
    }
  }

}

</script>
