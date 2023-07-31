<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Instituções</h1>
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
                            <div class="card-body">
                                <form action="">
                                    <div class="row">
                                        <div class="from-group col-12 col-md-3">
                                            <label class="form-label">Tipo de Instituição</label>
                                            <select v-model="instituicao_tipo" class="form-control">
                                                <option value="">TODOS</option>
                                                <option :value="item.codigo" v-for="item in tipo_instituicao" :key="item.codigo">{{ item.designacao }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Designação</th>
                                                <th>Sigla</th>
                                                <th>NIF</th>
                                                <th>Tipo</th>
                                                <th>Contacto</th>
                                                <th>Endereço</th>
                                                <th width="150px">Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items.data" :key="item.codigo">
                                                <td>{{ item.codigo }}</td>
                                                <td>{{ item.Instituicao }}</td>
                                                <td>{{ item.sigla ?? '----' }}</td>
                                                <td>{{ item.nif }}</td>
                                                <td>{{ item.tipo_instituicao_descricao.designacao }}</td>
                                                <td>{{ item.contacto }}</td>
                                                <td>{{ item.Endereco }}</td>
                                                <td class="text-center">
                                                    <a class="btn-sm btn-success mr-1" @click="editItem(item)">
                                                        <i class="fa fa-edit" title="Editar"> </i>
                                                    </a>
                                                    <a class="btn-sm btn-info mr-1" @click="visualizarBolsas(item)">
                                                        <i class="fas fa-folder" title="VISUALIZAR BOLSAS"> </i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary"> Total Registro: {{ items.total}}</Link>
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

        <!-- Model de nova Instituicao -->
        <div class="modal fade" id="modalInstituicaoRenuncia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6dd5ed;">
                        <h5 class="modal-title text-white" id="exampleModalLabel">{{ formTitle }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="salvarInstuicao" class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="recipient-name" class="col-form-label">Instituição <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-1" id="" v-model="form.instituicao" placeholder="Informe O nome da Instituição">
                                <span class="text-danger d-block">{{ form.errors.instituicao }}</span>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="recipient-name" class="col-form-label">NIF <span class="text-danger">*</span></label>
                                <input  type="text" class="form-control mb-1" id="" v-model="form.nif" maxlength="15" max="15" placeholder="Informe o Número de Identificação Fiscal">
                                <span class="text-danger d-block">{{ form.errors.nif }}</span>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="recipient-name" class="col-form-label">Sigla</label>
                                <input type="text" class="form-control mb-1" v-model="form.sigla" placeholder="Informe a Sigla">
                                <span class="text-danger d-block">{{ form.errors.sigla }}</span>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="recipient-name" class="col-form-label">Tipo de Instituição <span class="text-danger">*</span></label>
                                <select name="tipo" class="form-control mb-1" v-model="form.tipo">
                                    <option :value="item.codigo" v-for="item in tipo_instituicao" :key="item.codigo">{{ item.designacao }}</option>
                                </select>
                                <span class="text-danger d-block">{{ form.errors.tipo }}</span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="recipient-name" class="col-form-label">Contacto</label>
                                <input type="text" class="form-control mb-1" id="" v-model="form.contacto" placeholder="Informe o contacto da Instituição">
                                <span class="text-danger d-block">{{ form.errors.contacto }}</span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="recipient-name" class="col-form-label">Endereço</label>
                                <input type="text" class="form-control mb-1" id="" v-model="form.endereco" placeholder="Informe o Endereço da Instituição">
                                <span class="text-danger d-block">{{ form.errors.endereco }}</span>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="recipient-name" class="col-form-label">Tipo de Bolsas<span class="text-danger">*</span></label>
                                <select class="form-control" ref="mySelect" multiple="multiple">
                                    <option v-for="bolsa in listae_tipos_bolsas" :value="bolsa.id" :key="bolsa.id">{{ bolsa.text }}</option>
                                </select>
                                <span class="text-danger d-block">{{ form.errors.tipos_bolsas }}</span>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="salvarInstuicao" class="btn text-white"  @click.prevent="submit" style="background-color: #6dd5ed;">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inicio Modal tipo de bolsa -->
        <div class="modal fade" id="bolsasAssociadas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">Bolsas Associadas a Instituição</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Designação</th>
                                        <th width="150px">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in bolsaAssociadas" :key="item">
                                        <td>{{ item.bolsa.codigo }}</td>
                                        <td>{{ item.bolsa.designacao }}</td>
                                        <td class="text-center">
                                            <a class="btn-sm btn-danger mr-1" @click="elimanar_bolsa_instituica(item.codigo)">
                                                <i class="fas fa-trash" title="VISUALIZAR BOLSAS"> </i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    </MainLayouts>
</template>

<script>
    import Paginacao from "../../../Shared/Paginacao.vue";
    import { sweetSuccess, sweetError } from '../../../components/Alert'
    import { Link } from '@inertiajs/inertia-vue3'
    import axios from 'axios';
    // import Select2Component
    import Select2 from 'vue3-select2-component';

    export default {
        props: ["items", "total", "listae_tipos_bolsas", 'tipo_instituicao'],
        components: { Paginacao, Select2, Link },
        data() {
            return {
                itemId: null,
                instituicaoId: null,
                isUpdate: false,
                form: this.$inertia.form({
                    instituicao: null,
                    nif: null,
                    contacto: null,
                    tipo: 2,
                    endereco: null,
                    sigla: null,
                    tipos_bolsas: [],
                }),
                instituicao_tipo: "",
                form_atribuir: this.$inertia.form({
                    tipo_bolsa: null,
                    instituicao: null,
                }),
                params: {},
                bolsaAssociadas: [],
                lista_tipos_bolsas: {},
            };
        },
        computed: {
            formTitle() {
                return this.isUpdate ? "Editar Instituição Com Renúncia" : "Adicionar Instituição Com Renúncia";
            }
        },

        mounted(){
            this.getBolsasEstudos();
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
                        
            instituicao_tipo: function(val) {
              this.params.instituicao_tipo = val;
              this.updateData();
            }
        },

        methods: {

            updateData() {
                this.$Progress.start();
                this.$inertia.get("/todos-instituicoes", this.params, {
                    preserveState: true,
                    preverseScroll: true,
                    onSuccess: () => {
                        this.$Progress.finish();
                    }
              });
            },

            visualizarBolsas(item) {
                this.bolsaAssociadas = item.bolsas;
                $('#bolsasAssociadas').modal('show');
            },

            elimanar_bolsa_instituica(item){
                this.$Progress.start();
                this.form_atribuir.delete(route("associar-bolsas-instituicao.destroy", item), {
                    preverseScroll: true,
                    onSuccess: () => {
                        sweetSuccess("Bolsa removida com sucesso desta instituicão.");
                        this.form_atribuir.reset();
                        this.$Progress.finish();
                        $('#modalAtribuirBolsa').modal('hide');
                    }
                });
            },

            getBolsasEstudos() {
                axios.get('listar-todas-bolsas-estudos').then(response => {
                    console.log(response);
                })
            },

            editItem(item) {

              this.form.clearErrors();
              this.form.instituicao = item.Instituicao;
              this.form.nif = item.nif;
              this.form.contacto = item.contacto;
              this.form.tipo= item.tipo_instituicao;
              this.form.endereco = item.Endereco;
              this.form.sigla = item.sigla;

              // this.form.tipos_bolsas = item.bolsas;
              this.form.tipos_bolsas = [],

              this.isUpdate = true;
              this.itemId = item.codigo;
              $('#modalInstituicaoRenuncia').modal('show');
            },

            deleteItem(item) {
               this.itemId = item.codigo;
               $('#modalDeleteInstituicaoRenuncia').modal('show');
            },

            create() {
                $('#modalInstituicaoRenuncia').modal('show');
                this.form.reset();
                this.form.clearErrors();
            },

            destroy() {
                this.$Progress.start();
                this.form.delete(route("instituicoes-renuncia.destroy", this.itemId), {
                    preverseScroll: true,
                    onSuccess: () => {
                        this.itemId = null;
                        $('#modalDeleteInstituicaoRenuncia').modal('hide');
                        this.$Progress.finish();
                    }
                });
            },
            
            submit() {

                this.$Progress.start();
                if (this.isUpdate) {

                    this.form.put(route("instituicoes-renuncia.update", this.itemId), {
                      preverseScroll: true,
                      onSuccess: () => {
                        this.isUpdate = false;
                        this.itemId = null;
                        this.form.reset();
                        sweetSuccess("Dados salvos com sucesso")
                        $('#modalInstituicaoRenuncia').modal('hide');
                        this.$Progress.finish();
                      },
                      onError: (errors) => {
                        console.log(errors)
                        sweetError("Ocorreu um erro ao actualizar Instituição!")
                      },
                    });
                } else {
                    this.form.post(route("instituicoes-renuncia.store"), {
                        preverseScroll: true,
                        onSuccess: () => {
                            this.form.reset();
                            this.$Progress.finish();
                            sweetSuccess("Dados salvos com sucesso")
                            $('#modalInstituicaoRenuncia').modal('hide');
                        },
                        onError: (errors) => {
                            sweetError("Ocorreu um erro ao actualizar Instituição!")
                        },
                    });
                }
            },

            imprimirPDF() {
                window.open(`/instituicoes-renuncia-pdf?tipo_instituicao=${this.instituicao_tipo}`, "_blank");
            },

            imprimirEXCEL() {
                window.open(`/instituicoes-renuncia-excel?tipo_instituicao=${this.instituicao_tipo}`, "_blank");
            }
        },
    }


</script>
