
<template>


    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-uppercase">Listar Bolseiros</h4>
                    </div>
                    <div class="col-sm-6">
                        <a @click="imprimirExcel" href="" class="btn btn-success btn-sm float-sm-right"><i class="fas fa-file-excel"></i> Excel</a>
                        <a @click="imprimirPDF" href="" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
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
                                            <div class="input-group input-group">
                                                <select v-model="AnoLectivo"
                                                    class="form-control form-control-sm  form-control select2">
                                                    <option value=""> TODAS</option>
                                                    <option :value="ano.Codigo" v-for="ano in anolectivos"
                                                        :key="ano.Codigo">
                                                        {{ ano.Designacao }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Curso</label>
                                            <div class="input-group input-group">
                                                <select v-model="Curso"
                                                    class="form-control form-control-sm  form-control select2"
                                                    placeholder="Type your keywords here">
                                                    <option value="">TODOS</option>
                                                    <option :value="curso.Codigo" v-for="curso in cursos" :key="curso.Codigo"> {{ curso.Designacao }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Instituições</label>
                                            <div class="input-group input-group">
                                                <select v-model="Instituicao"
                                                    class="form-control form-control-sm  form-control select2"
                                                    placeholder="Type your keywords here">
                                                    <option value="">TODAS</option>
                                                    <option :value="instituicao.codigo"  v-for="instituicao in instituicao"  :key="instituicao.codigo">
                                                        {{ instituicao.Instituicao }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Tipo de Bolsa</label>
                                            <div class="input-group input-group">
                                                <select v-model="TipoBolsa"
                                                    class="form-control form-control-sm ">
                                                    <option value="">TODAS</option>
                                                    <option :value="bolsa.codigo" v-for="bolsa in bolsa"
                                                        :key="bolsa.codigo">
                                                        {{ bolsa.designacao }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Percetagem</label>
                                            <div class="input-group input-group">
                                                <select v-model="Desconto"
                                                    class="form-control form-control-sm ">
                                                    <option value="">TODAS</option>
                                                    <option value="0">0%</option>
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
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Estado</label>
                                            <div class="input-group input-group">
                                                <select v-model="Estado" class="form-control form-control-sm " >
                                                    <option value="">TODOS</option>
                                                    <option value="0">ACTIVO</option>
                                                    <option value="1">INACTIVO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Período</label>
                                            <div class="input-group input-group">
                                                <select v-model="Semestre" class="form-control form-control-sm ">
                                                    <option value="">TODOS</option>
                                                    <option :value="semestre.Codigo" v-for="semestre in semestres" :key="semestre.Codigo">{{ semestre.Designacao }}</option>
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
                                <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">

                                    <thead>
                                        <tr>
                                            <th>codigo</th>
                                            <th class="text-center" style="width: 150px">Matricula</th>
                                            <th class="text-center">Nome</th>
                                            <th>Curso</th>
                                            <th>Tipo de Bolsa</th>
                                            <th>Desconto</th>
                                            <th>Estado</th>
                                            <th>Semestre</th>
                                            <th style="width: 150px">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td class="text-center">
                                                <input type="text" v-model="busca_matricula" class="form-control form-control-sm " placeholder="Informe a matricula">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_nome" name="" class="form-control form-control-sm " placeholder="Informe o Nome">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_curso" name="" class="form-control form-control-sm " placeholder="Informe o curso">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_tipo_bolsa" name="" class="form-control form-control-sm " placeholder="Informe o tipo de bolsa">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_desconto" name="" class="form-control form-control-sm " placeholder="Informe o desconto">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_estado" name="" class="form-control form-control-sm " placeholder="Informe o estado">
                                            </td>
                                            <td>
                                                <input type="text" v-model="busca_semestre" name="" class="form-control form-control-sm " placeholder="Informe o semestre">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr v-for="item in listarbolseiro.data"
                                            :key="item.bolseiro">
                                            <td>{{ item.codigo }}</td>
                                            <td class="text-center"><a :href="route('mf.estudante-visualizar-perfil', item.codigo_matricula)">{{ item.codigo_matricula }}</a></td>
                                            <td class="text-center"><a :href="route('mf.estudante-visualizar-perfil', item.codigo_matricula)">{{ item.nome }}</a></td>
                                            <td class="text-center">{{ item.curso }}</td>
                                            <td class="text-center">{{ item.tipobolsa }}</td>
                                            <td class="text-center">{{ item.desconto }}</td>
                                            <td v-if="item.status ==0" class="text-center">
                                                <span class="text-success">ACTIVO</span>
                                            </td>
                                            <td v-if="item.status ==1" class="text-center">
                                                <span class="text-danger">INACTIVO</span>
                                            </td>
                                            <td class="text-center">{{ item.semestreItem }}</td>
                                            <td>
                                                <div class="flex">
                                                    <template v-if='user.type_user == "Administrador"'>
                                                        <a @click="editItem(item)" class="btn-sm btn-success mx-1"><i class="fas fa-edit"></i></a>
                                                        <a @click="showItem(item)" class="btn-sm btn-info mx-1"><i class="fas fa-eye"></i></a>
                                                        <a @click="mudarStatusItem(item)" v-if="item.status == 0" class="btn-sm btn-danger mx-1"><i class="fas fa-ban"></i></a>
                                                        <a @click="mudarStatusItem(item)" v-if="item.status == 1" class="btn-sm btn-success mx-1"><i class="fas fa-check"></i></a>
                                                    </template>

                                                    <template>
                                                        <a @click="showItem(item)" class="btn-sm btn-info mx-1"><i class="fas fa-eye"></i></a>
                                                        <a @click="mudarStatusItem(item)" v-if="item.status == 0" class="btn-sm btn-danger mx-1"><i class="fas fa-ban"></i></a>
                                                        <a @click="mudarStatusItem(item)" v-if="item.status == 1" class="btn-sm btn-success mx-1"><i class="fas fa-check"></i></a>
                                                    </template>

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                TOTAL REGISTROS: {{ listarbolseiro.total }}
                                </Link>
                                <Paginacao :links="listarbolseiro.links"
                                    :prev="listarbolseiro.prev_page_url"
                                    :next="listarbolseiro.next_page_url" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="modalMudarStatus">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Mudar Estado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Tem certeza que pretende mudar o estado da bolsa?</p>
              </div>
              <div class="modal-footer">
                <button type="button" @click="cancelarStatus" class="btn-sm btn-danger">Cancelar</button>
                <button type="button" @click="confirmarMudarStatus" class="btn-sm btn-primary" data-dismiss="modal">Confirmar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="modalShowBolsa">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Detalhes da Bolsa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <table class="table table-sm table-hover table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="2">Número Matricula: <strong>{{ matricula.Codigo }}</strong></td>
                            <td colspan="2">B.I|Passaport: <strong>{{ aluno.Bilhete_Identidade }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">Nome Completo: <strong>{{ aluno.Nome_Completo }}</strong></td>
                            <td colspan="2">Data de Emissão: <strong>{{ aluno.data_emissao_bi }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">Data de Nascimento: <strong>{{ aluno.Data_Nascimento }}</strong></td>
                            <td colspan="2">Data de Validade: <strong>{{ aluno.data_validade_bi }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">Género: <strong>{{ aluno.Sexo }}</strong></td>
                            <td colspan="2">Telefone: <strong>{{ aluno.Contactos_Telefonicos }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">Estado Civil: <strong>{{ aluno.Estado_Civil }}</strong></td>
                            <td colspan="2">Telefone Alternativo: <strong>{{ aluno.contacto_de_emergencia }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">País de Nacionalidade: <strong>{{ aluno.Naturalidade }}</strong></td>
                            <td colspan="2">Email: <strong>{{ aluno.Email }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2">Morada: <strong>{{ aluno.Morada_Completa }}</strong></td>
                            <td colspan="2">Atribuida Por: <strong>{{ bolsa.nome }}</strong></td>
                        </tr>

                        <tr>
                            <td colspan="4">DADOS DA INSTITUIÇÃO</td>
                        </tr>

                        <tr>
                            <th>Instituição</th>
                            <th>Endereço</th>
                            <th>NIF</th>
                            <th>Contacto</th>
                        </tr>

                        <tr>
                            <td>{{ bolsa.Instituicao }}</td>
                            <td>{{ bolsa.Endereco }}</td>
                            <td>{{ bolsa.nif }}</td>
                            <td>{{ bolsa.contacto }}</td>
                        </tr>

                        <tr>
                            <th>Tipo De Bolsa</th>
                            <th>Desconto</th>
                            <th>Data de inicio</th>
                            <th>Data De Fim</th>
                        </tr>

                        <tr>
                            <td>{{ bolsa.designacao }}</td>
                            <td>{{ bolsa.desconto }}</td>
                            <td>{{ bolsa.data_inicio_bolsa }}</td>
                            <td>{{ bolsa.data_fim_bolsa }}</td>
                        </tr>
                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" @click="cancelarStatus" class="btn-sm btn-danger">Cancelar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Model de nova Instituicao -->
        <div class="modal fade" id="modalEditarBolsa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6dd5ed;">
                        <h5 class="modal-title text-white" id="exampleModalLabel">{{ title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="salvarInstuicao" class="row">

                            <div class="form-group col-12 col-md-12">
                                <label for="recipient-name" class="col-form-label">Ano Lectivo</label>
                                <select v-model="form.anolectivo" class="form-control form-control-sm ">
                                    <option :value="ano.Codigo" v-for="ano in anolectivos" :key="ano.Codigo">
                                        {{ ano.Designacao }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>Instituições</label>
                                    <div class="input-group input-group">
                                        <select v-model="form.instituicao"
                                            class="form-control form-control-sm ">
                                            <option :value="item.codigo"  v-for="item in instituicao"  :key="item.codigo">
                                                {{ item.Instituicao }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>Tipo de Bolsa</label>
                                    <div class="input-group input-group">
                                        <select v-model="form.tipo_bolsa"
                                            class="form-control form-control-sm ">
                                            <option :value="item.codigo"  v-for="item in bolsa"  :key="item.codigo">
                                                {{ item.designacao }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Período</label>
                                    <div class="input-group input-group">
                                        <select v-model="form.semestre"
                                            class="form-control form-control-sm ">
                                            <option :value="item.Codigo"  v-for="item in semestres"  :key="item.Codigo">
                                                {{ item.Designacao }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="text-secondary">Afectação</label>
                                    <div class="input-group input-group">
                                        <select v-model="form.afectacao" class="form-control form-control-sm " id="afectacao" :class="{ 'is-invalid': form.errors.afectacao }">
                                            <option value=""></option>
                                            <option value="Pagamento de Propina">
                                                Pagamento de Propina
                                            </option>
                                            <option value="Pagamentos Globais">
                                                Pagamentos Globais
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="form.errors.afectacao" class="text-danger">
                                        {{ form.errors.afectacao }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>Percetagem</label>
                                    <div class="input-group input-group">
                                        <select v-model="form.desconto"
                                            class="form-control form-control-sm ">
                                            <option value="">TODAS</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="15">15%</option>
                                            <option value="20">20%</option>
                                            <option value="30">30%</option>
                                            <option value="35">35%</option>
                                            <option value="40">40%</option>
                                            <option value="45">45%</option>
                                            <option value="50">50%</option>
                                            <option value="70">70%</option>
                                            <option value="100">100%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="salvarInstuicao" class="btn text-white"  @click.prevent="submit" style="background-color: #6dd5ed;">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </MainLayouts>

</template>

<script setup>
  import { computed } from "vue";
  import { usePage } from "@inertiajs/inertia-vue3";
  const user = computed(() => {
    return usePage().props.value.auth.user;
  });
</script>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from '../../components/Alert'


export default {
    props: [
        "anolectivos",
        "semestres",
        "mesTemps",
        "turnos",
        "bolsa",
        "bolseiro",
        "cursos",
        "instituicao",
        "listarbolseiro",
    ],
    components: {
        Link,
        Paginacao
    },
    data() {
        return {
            AnoLectivo: "",
            Curso: "",
            Instituicao: "",
            TipoBolsa: "",
            Desconto: "",
            Estado: "",
            Semestre: "",
            title: "",

            // BUSCAS START
            busca_matricula: "",
            busca_nome: "",
            busca_curso: "",
            busca_tipo_bolsa: "",
            busca_desconto: "",
            busca_estado: "",
            busca_semestre: "",
            // BUSCAS END


            matricula: {},
            admissao: {},
            bolsa: {},

            params: {},
            aluno: {},

            form: this.$inertia.form({
                anolectivo: null,
                instituicao: null,
                tipo_bolsa: null,
                tipo: null,
                semestre: null,
                afectacao: null,
                desconto: null,
            }),
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
        AnoLectivo: function(val) {
          this.params.AnoLectivo = val;
          this.updateData();
        },
        Curso: function(val) {
          this.params.Curso = val;
          this.updateData();
        },

        Instituicao: function(val) {
          this.params.Instituicao = val;
          this.updateData();
        },

        Desconto: function(val) {
          this.params.Desconto = val;
          this.updateData();
        },

        Estado: function(val) {
          this.params.Estado = val;
          this.updateData();
        },

        Semestre: function(val) {
          this.params.Semestre = val;
          this.updateData();
        },

        busca_matricula: function(val) {
          this.params.busca_matricula = val;
          this.updateData();
        },

        busca_nome: function(val) {
          this.params.busca_nome = val;
          this.updateData();
        },

        busca_curso: function(val) {
          this.params.busca_curso = val;
          this.updateData();
        },

        busca_tipo_bolsa: function(val) {
          this.params.busca_tipo_bolsa = val;
          this.updateData();
        },

        busca_desconto: function(val) {
          this.params.busca_desconto = val;
          this.updateData();
        },

        busca_estado: function(val) {
          this.params.busca_estado = val;
          this.updateData();
        },

        busca_semestre: function(val) {
          this.params.busca_semestre = val;
          this.updateData();
        },
    },

    methods: {
        updateData() {
          this.$Progress.start();
          this.$inertia.get("/listar-bolseiros", this.params, {
            preserveState: true,
            preverseScroll: true,
            onSuccess: () => {
              this.$Progress.finish();
            }
        });
    },

    editItem(item){
        this.title = "Alterar os dados do bolseiro " + item.nome;

        this.form.clearErrors();
        this.form.anolectivo = item.codigo_anoLectivo;
        this.form.instituicao = item.codigo_Instituicao;
        this.form.tipo_bolsa = item.codigo_tipo_bolsa;
        this.form.semestre = item.semestre;
        this.form.afectacao = item.afectacao;
        this.form.desconto = item.desconto;

        this.itemId = item.codigo;

       $('#modalEditarBolsa').modal('show');
    },

    mudarStatusItem(item) {
       this.itemId = item.codigo;
       $('#modalMudarStatus').modal('show');
    },

    showItem(item){
        axios.get("/visualizar-bolseiros/" + item.matricula + "?codigo="+item.codigo+"&codigo_matriula="+ item.matricula, {}).then( (response) => {
            $('#modalShowBolsa').modal('show')
            this.aluno = response.data.aluno;
            this.matricula = response.data.matricula;
            this.admissao = response.data.admissao;
            this.bolsa = response.data.bolsa;
        })
        .catch( (error) => {
            sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
        });
    },

    confirmarMudarStatus(){
        this.$Progress.start();
          this.$inertia.get("/mudar-estado-bolseiros/"+this.itemId, {}, {
            preserveState: true,
            preverseScroll: true,
            onSuccess: () => {
              this.$Progress.finish();
              sweetSuccess("Estado da bolsa actualizado com sucesso!");
            },
            onError: (errors) => {
                sweetError("Ocorreu um erro ao actualizar estado da bolsa!")
            },
        });
    },

    cancelarStatus(){
        $('#modalMudarStatus').modal('hide');
    },

    submit(){
        this.form.put(route("mf.update-bolseiros", this.itemId), {
          preverseScroll: true,
          onSuccess: () => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();
            sweetSuccess("Dados salvos com sucesso")
            $('#modalEditarBolsa').modal('hide');
            this.$Progress.finish();
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao actualizar Instituição!")
          },
        });
    },

    imprimirPDF () {
      window.open("/listagem/bolserios/pdf-imprimir?AnoLectivo="+this.AnoLectivo+"&Curso="+this.Curso+"&Instituicao="+this.Instituicao+"&Desconto="+this.Desconto+"&Estado="+this.Estado+"&Semestre="+this.Semestre, "_blank");
    },


    imprimirExcel() {
      window.open("/listagem/bolserios/excel-imprimir?AnoLectivo="+this.AnoLectivo+"&Curso="+this.Curso+"&Instituicao="+this.Instituicao+"&Desconto="+this.Desconto+"&Estado="+this.Estado+"&Semestre="+this.Semestre, "_blank");
    },
 }
}

</script>
