
<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Tipo de Descontos</h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="" class="btn btn-success btn-sm float-sm-right"><i class="fas fa-file-excel"></i>
                            Excel</a>
                        <a @click="imprimirPDF" href="" class="btn btn-danger btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-pdf"></i> PDF</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#modalTipoDesconto">Novo Tipo de Desconto </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap">

                                    <thead>
                                        <tr>
                                            <th style="width: 100px;">codigo</th>
                                            <th style="width: 300px;">Designacao</th>
                                            <th style="width: 300px;">Valor de Desconto</th>
                                            <th>Utilizador</th>
                                            <th>status</th>
                                            <th class="text-center">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td><input type="text" class="form-control" placeholder="Codigo" v-model="codigo_busca"></td>
                                            <td><input type="text" class="form-control" placeholder="Designação" v-model="designcao_busca"></td>
                                            <td><input type="text" class="form-control" placeholder="Valor do desconto" v-model="valor_busca"></td>
                                        </tr>
                                    
                                        <tr v-for="item in listarDescontos.data"
                                            :key="item.Codigo">
                                            <td class="text-center">{{ item.Codigo }}</td>
                                            <td>{{ item.designacao }}</td>
                                            <td>{{ item.valor_desconto }}</td>
                                            <td>{{ item.usuario }}</td>
                                            <td>{{ item.estado }}</td>
                                            <td class="text-center">
                                                <a class="btn-sm btn-success mr-1" data-toggle="modal" @click.prevent="editarItem(item)">
                                                    <i class="fa fa-edit" title="Editar"> </i>
                                                </a>

                                                <a class="btn-sm btn-danger mr-1" data-toggle="modal" @click.prevent="deleteItem(item)">
                                                    <i class="fa fa-trash" title="Eliminar"> </i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <p>Total de Registros: {{ listarDescontos.total }}</p>
                                <Paginacao 
                                    :links="listarDescontos.links" 
                                    :prev="listarDescontos.prev_page_url"
                                    :next="listarDescontos.next_page_url" 
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model de nova tipo de desconto -->
        <div class="modal fade" id="modalTipoDesconto" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <div class="form-group col-12 col-md-4">
                                <label for="recipient-name" class="col-form-label">Designação</label>
                                <input v-model="form.designacao" type="text" class="form-control" placeholder="Informe a Designação" :class="{ 'is-invalid': form.errors.designacao }">
                                <span class="text-danger d-block">{{ form.errors.designacao }}</span>
                            </div>

                            <div class="form-group col-12 col-md-4">
                                <label for="recipient-name" class="col-form-label">Valor do Desconto</label>
                                <input v-model="form.valor_desconto" type="text" class="form-control" :class="{ 'is-invalid': form.errors.valor_desconto }">
                                <span class="text-danger d-block">{{ form.errors.valor_desconto }}</span>
                            </div>


                            <div class="form-group col-12 col-md-4">
                                <label for="" class="col-form-label">Estado</label>
                                <div class="input-group input-group">
                                    <select v-model="form.codigo_status" class="form-control" :class="{ 'is-invalid': form.errors.codigo_status }">
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="" class="btn text-white" @click.prevent="submiter" style="background-color: #6dd5ed;">Salvar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="modalDelete">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Eliminar Tipo Desconto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Tem certeza que pretende eliminar esta Tipo Desconto?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-sm btn-danger">Cancelar</button>
                <button type="button" @click="destroy" class="btn-sm btn-primary" data-dismiss="modal">Eliminar</button>
              </div>
            </div>
          </div>
        </div>
    </MainLayouts>
</template>


<script>

    
    import { Link } from "@inertiajs/inertia-vue3";
    import Paginacao from "../../Shared/Paginacao.vue";
    import { sweetSuccess, sweetError } from '../../components/Alert'
    
    
    export default {
        props: [ "listarDescontos", "status"],
        components: { Link, Paginacao },
        data() {
            return {
                options: {},
                params: {},
                
                codigo_busca: "",
                designcao_busca: "",
                valor_busca: "",
                                
                itemId: null,
                
                isUpdate: false,
                
                form: this.$inertia.form({
                    designacao: "",
                    valor_desconto: "",
                    estado: 1,
                }),
                
            };
        },
        computed: {
            formTitle() {
                return this.isUpdate ? "Editar Tipo Desconto" : "Adicionar Tipo Desconto";
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
            codigo_busca: function(val) {
              this.params.codigo_busca = val;
              this.updateData();
            },
            designcao_busca: function(val) {
              this.params.designcao_busca = val;
              this.updateData();
            },
            valor_busca: function(val) {
              this.params.valor_busca = val;
              this.updateData();
            }
        },
        
        methods: {
            updateData() {
                this.$Progress.start();
                this.$inertia.get("/listar-desconto", this.params, {
                    preserveState: true,
                    preverseScroll: true,
                    onSuccess: () => {
                        this.$Progress.finish();
                    },
                    onError: () => {
                        this.$Progress.fail();
                    }
              });
            },
 
            editarItem(item){
                console.log(item)
                
                this.form.designacao = item.designacao
                this.form.valor_desconto = item.valor_desconto
                this.form.estado = item.statusid
                
                this.itemId = item.Codigo
                this.isUpdate = true
                
                $('#modalTipoDesconto').modal('show')
                
            },
            
            deleteItem(item) {
               this.itemId = item.Codigo;
               $('#modalDelete').modal('show');
            },
            
            destroy(){
                this.$Progress.start();
                this.form.delete(route("mf.delete-desconto", this.itemId), {
                    preverseScroll: true,
                    onSuccess: () => {
                        this.itemId = null;
                        $('#modalDelete').modal('hide');
                        this.$Progress.finish();
                    }
                });
            },
            
            submiter () {
                if (this.isUpdate) {
                    this.$Progress.start();
                    this.form.put(route('mf.update-Desconto', this.itemId),   {
                        onSuccess: () => {
                            this.form.reset();
                            this.$Progress.finish();
                            this.isUpdate = false
                            $('#modalTipoDesconto').modal('hide')
                            sweetSuccess("Tipo de Desconto  Actualizado  com sucesso")
                        },
                        onError: () => {
                            this.$Progress.fail();
                            sweetError("Ocorreu um erro ao tentar Actualizar")
                        }
                    })
                } else {
                    this.$Progress.start();
                    this.form.post(route('mf.store-tipo-desconto'), {
                        onSuccess: () => {
                            this.form.reset();
                            this.$Progress.finish(); 
                            $('#modalTipoDesconto').modal('hide')
                            sweetSuccess("Desconto cadastrado com sucesso")
                        },
                        onError: () => {
                            this.$Progress.fail();
                            sweetError("Ocorreu um erro ao tentar cadastrar o Desconto")
                        }
                    })
                }
            },
        },
    }
    
</script>
