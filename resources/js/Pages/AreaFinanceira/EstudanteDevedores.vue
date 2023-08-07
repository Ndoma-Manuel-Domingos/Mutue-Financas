<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Estudantes Devedores</h1>
                    </div>
                    <div class="col-sm-6">
                        <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-pdf"></i> PDF</a>
                        <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-excel"></i> Excel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header bg-light"></div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Ano Lectivo</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchAnoLectivo" class="form-control">

                                                    <option :value="ano.Codigo" v-for="ano in anolectivos"
                                                        :key="ano.Codigo">{{ ano.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Mês/Parcela</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchMes" class="form-control">
                                                    <option value="">TODAS</option>
                                                    <option :value="mes.id" v-for="mes in mesTemps" :key="mes.id">{{
                                                        mes.designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Faculdade</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchFaculdade" class="form-control">
                                                    <option value="">TODAS</option>
                                                    <option :value="faculdade.codigo" v-for="faculdade in faculdades"
                                                        :key="faculdade.codigo">{{ faculdade.designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Curso</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchCurso" class="form-control">
                                                    <option value="">TODOS</option>
                                                    <option :value="curso.Codigo" v-for="curso in cursos"
                                                        :key="curso.Codigo">{{ curso.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Turno</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchTurno" class="form-control">
                                                    <option value="">TODOS</option>
                                                    <option :value="turno.Codigo" v-for="turno in turnos"
                                                        :key="turno.Codigo">{{ turno.Designacao }}</option>
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
                        <div class="card">
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <!-- <h5>TOTAL REGISTROS: {{ facturas.total }}</h5> -->
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nº Matricula</th>
                                                <th>Nome</th>
                                                <th>Faculdade</th>
                                                <th>Curso</th>
                                                <th>Turno</th>
                                                <th>Mês/Parcela</th>
                                                <th width="100px">Ver Perfil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="factura in facturas.data" :key="factura.matricula">
                                                <td>{{ factura.matricula}}</td>
                                                <td>{{ factura.nome}}</td>
                                                <td>{{ factura.faculdade }}</td>
                                                <td>{{ factura.curso}}</td>
                                                <td>{{ factura.turno}}</td>
                                                <td>{{ factura.parcela}}</td>
                                                <td>
                                                    <a :href="route('mf.estudante-visualizar-perfil',factura.matricula)"
                                                        class="btn-sm btn-primary">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                    TOTAL REGISTROS: {{ facturas.total }}
                                </Link>
                                <!-- <Paginacao :links="facturas.links" :prev="facturas.prev_page_url"
                                    :next="facturas.next_page_url"/> -->
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
    import Paginacao from '../../Shared/Paginacao.vue'

    export default {

        props: [
            "anolectivos",
            "mesTemps",
            "turnos",
            "faculdades",
            "facturas",
            "cursos",
        ],
        components: {
            Link,
            Paginacao,
        },
        data(){
            return {
                 searchAnoLectivo: "18",
                searchMes: "",
                searchFaculdade: "",
                searchCurso: "",
                searchTurno: "",

                params: {},
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
          searchAnoLectivo: function(val) {
            this.params.searchAnoLectivo = val;
            this.updateData();
          },
          searchMes: function(val) {
            this.params.searchMes = val;
            this.updateData();
          },
          searchFaculdade: function(val) {
            this.params.searchFaculdade = val;
            this.updateData();
          },
          searchCurso: function(val) {
            this.params.searchCurso = val;
            this.updateData();
          },
          searchTurno: function(val) {
            this.params.searchTurno = val;
            this.updateData();
          }
        },

        methods: {
            updateData() {
                this.$Progress.start();
                this.$inertia.get("/estudantes/devedores", this.params, {
                  preserveState: true,
                  preverseScroll: true,
                  onSuccess: () => {
                    this.$Progress.finish();
                  }
                });
            },

            imprimirPDF () {
                window.open("/estudante/devedores/pdf-imprimir?searchAnoLectivo="+this.searchAnoLectivo+"&searchMes="+this.searchMes+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
            },

            imprimirEXCEL () {
                window.open("/estudante/devedores/excel-imprimir?searchAnoLectivo="+this.searchAnoLectivo+"&searchMes="+this.searchMes+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
            }
        }
    }

</script>
