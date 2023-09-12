<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-uppercase">Estudantes Devedores</h4>
                        {{this.estudante_tipo2.designacao}}
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
                            <div class="card-body">
                                <div class="row">
                               
                                      
                            
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Ano Lectivo</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchAnoLectivo"
                                                    class="form-control form-control-sm ">

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
                                                <select @change="search" v-model="searchMes"
                                                    class="form-control form-control-sm ">
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
                                                <select @change="search" v-model="searchFaculdade"
                                                    class="form-control form-control-sm ">
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
                                                <select @change="search" v-model="searchCurso"
                                                    class="form-control form-control-sm ">
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
                                                <select @change="search" v-model="searchTurno"
                                                    class="form-control form-control-sm ">
                                                    <option value="">TODOS</option>
                                                    <option :value="turno.Codigo" v-for="turno in turnos"
                                                        :key="turno.Codigo">{{ turno.Designacao }}</option>
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

                            <div class="card-body">
                                <table id="carregarTabelaEstudantes" style="width: 100%"
                                    class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                                    <thead>
                                        <tr>
                                            <th>Nº Matricula</th>
                                            <th>Tipo de Estudante</th>
                                            <th>Nome</th>
                                            <th>Curso</th>
                                            <th>Turno</th>
                                            <th>Preço</th>
                                            <th width="100px">Ver Perfil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- {{ this.estudante_tipo2.designacao }}{{
                                        estudante_tipo2.designacao -->
                                        <tr v-for="(factura, index) in facturas.data" :key="factura.Codigo">
                                            <td><a :href="route('mf.estudante-visualizar-perfil', factura.Codigo)">{{
                                                factura.Codigo }}</a></td>
                                            <td> 
                                                <div class="col s12 m6" v-if="bolseiro" style="float: right">
                                                    <template v-if="bolseiro.desconto == 100">
                                                        <b>{{ estudante_tipo4.descricao }}</b>
                                                    </template>
                                                    <template
                                                        v-else-if="bolseiro.desconto < 100 && bolseiro.codigo_tipo_bolsa != 32">
                                                        <span>{{ estudante_tipo3.designacao }} {{ estudante_tipo3.descricao
                                                        }} </span>
                                                    </template>
                                                    <template
                                                        v-else-if="bolseiro.desconto < 100 && bolseiro.codigo_tipo_bolsa == 32">
                                                        <span style="font-size: 10">{{ estudante_tipo2.designacao }}{{
                                                            estudante_tipo2.descricao}}</span>
                                                    </template>
                                                </div>
                                                <div class="col s12 m6" v-else style="float: right">
                                                    <span>{{ estudante_tipo1.designacao }} 
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ factura.admissao.preinscricao.Nome_Completo ?? '' }}</td>
                                            <td>{{ factura.admissao.preinscricao.curso.Designacao ?? '' }}</td>
                                            <td>{{ factura.admissao.preinscricao.turno.Designacao ?? '' }}</td>
                                            <td>{{ formatValor(0) }}</td>
                                            <td>
                                                <a :href="route('mf.estudante-visualizar-perfil', factura.Codigo)"
                                                    class="btn-sm btn-primary">
                                                    <i class="fas fa-user-graduate"></i>
                                                </a>
                                            </td>
                                        </tr>




                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                TOTAL REGISTROS: {{ facturas.total }}
                                </Link>
                                <Paginacao :links="facturas.links" :prev="facturas.prev_page_url"
                                    :next="facturas.next_page_url" />
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
        "bolseiro",
        "ano_lectivo_activo",
    ],
    components: {
        Link,
        Paginacao,
    },
    data() {
        return {
            searchAnoLectivo: this.ano_lectivo_activo.Codigo,
            searchMes: "",
            searchFaculdade: "",
            searchCurso: "",
            searchTurno: "",
            estudante_tipo1: {},
            estudante_tipo2: {},
            estudante_tipo3: {},
            estudante_tipo4: {},
            bolseiro:"",
            params: {},
        }
    },


    mounted() {

        this.pegarDescricaoBolseiro();

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
        searchAnoLectivo: function (val) {
            this.params.searchAnoLectivo = val;
            this.updateData();
        },
        searchMes: function (val) {
            this.params.searchMes = val;
            this.updateData();
        },
        searchFaculdade: function (val) {
            this.params.searchFaculdade = val;
            this.updateData();
        },
        searchCurso: function (val) {
            this.params.searchCurso = val;
            this.updateData();
        },
        searchTurno: function (val) {
            this.params.searchTurno = val;
            this.updateData();
        }
    },


    methods: {

        pegarDescricaoBolseiro() {
            axios
                .get(`/estudante/pegar-descricao-bolseiro`)
                .then((response) => {
                    this.estudante_tipo1 = response.data.descricao_tipo1;
                    this.estudante_tipo2 = response.data.descricao_tipo2;
                    this.estudante_tipo3 = response.data.descricao_tipo3;
                    this.estudante_tipo4 = response.data.descricao_tipo4;
                })
                .catch((error) => {
                    console.error("Erro ao buscar dados do bolseiro:", error);
                });
        },



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



        formatValor: function (atual) {
            const valorFormatado = Intl.NumberFormat("pt-br", {
                style: "currency",
                currency: "AOA",
            }).format(atual);

            return valorFormatado;
        },


        getPrecoPropina: function (polo, curso) {
            axios
                .get(`/estudante/pega-preoco-propina/${polo}/${curso}`)
                .then((response) => { return 0 });
        },

        imprimirPDF() {
            window.open("/estudante/devedores/pdf-imprimir?searchAnoLectivo=" + this.searchAnoLectivo + "&searchMes=" + this.searchMes + "&searchFaculdade=" + this.searchFaculdade + "&searchCurso=" + this.searchCurso + "&searchTurno=" + this.searchTurno, "_blank");
        },

        imprimirEXCEL() {
            window.open("/estudante/devedores/excel-imprimir?searchAnoLectivo=" + this.searchAnoLectivo + "&searchMes=" + this.searchMes + "&searchFaculdade=" + this.searchFaculdade + "&searchCurso=" + this.searchCurso + "&searchTurno=" + this.searchTurno, "_blank");
        }


    },


}



</script>
