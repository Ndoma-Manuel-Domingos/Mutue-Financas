<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Instituções Sem Renúncias</h4>
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
            <a
              @click="create"
              class="btn btn-primary btn-sm float-sm-right mr-2"
               v-if="user.auth.can['CRIAR INSTITUICOES']"
              ><i class="fas fa-plus"></i> Nova Instituicão</a
            >
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ items.total }}</span
                  >
                </h5>
                <div class="card-tools">
                  <div class="input-group input-group-md" style="width: 300px">
                    <input
                      type="text"
                      v-model="search_instituicao_sem_renuncia"
                      class="form-control form-control-sm float-right"
                      placeholder="Search"
                    />
                  </div>
                </div>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table
                    id="carregarTabelaEstudantes"
                    style="width: 100%"
                    class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                  >
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Designação</th>
                        <th>Sigla</th>
                        <th>NIF</th>
                        <!-- <th>Tipo</th> -->
                        <th>Contacto</th>
                        <th>Endereço</th>
                        <th width="150px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td>
                          <input type="text" class="form-control" v-model="nome_instituicao_search" placeholder="Nome da instituição...">
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="sigla_instituicao_search" placeholder="Informe a Sigla...">
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="nif_instituicao_search" placeholder="Informe o NIF...">
                        </td>
                      </tr>
                  
                      <tr v-for="item in items.data" :key="item.codigo">
                        <td>{{ item.codigo }}</td>
                        <td>{{ item.Instituicao }}</td>
                        <td>{{ item.sigla }}</td>
                        <td>{{ item.nif }}</td>
                        <!-- <td>{{ item.tipo }}</td> -->
                        <td>{{ item.contacto }}</td>
                        <td>{{ item.Endereco }}</td>
                        <td class="text-center">
                          <a
                            class="btn-sm btn-success mr-1"
                            @click="editItem(item)"
                            v-if="user.auth.can['EDITAR INSTITUICOES']"
                          >
                            <i class="fa fa-edit" title="Editar"> </i>
                          </a>
                          <a
                            class="btn-sm btn-primary mr-1"
                            @click="atribuirItem(item)"
                            v-if="user.auth.can['ASSOCIAR BOLSA INSTITUICOES']"
                          >
                            <i
                              class="fa fa-link"
                              title="Associar tipo de bolsa a Instituições"
                            >
                            </i>
                          </a>
                          <a
                            class="btn-sm btn-success mr-1"
                            @click="visualizarBolsas(item)"
                            v-if="user.auth.can['VISUALIZAR INSTITUICOES']"
                          >
                            <i class="fas fa-folder" title="VISUALIZAR BOLSAS">
                            </i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ items.total }}</Link
                >
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

    <div
      class="modal"
      tabindex="-1"
      role="dialog"
      id="modalDeleteInstituicaoRenuncia"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Eliminar Instituição</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Tem certeza que pretende eliminar esta Instituição?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-sm btn-danger">Cancelar</button>
            <button
              type="button"
              @click="destroy"
              class="btn-sm btn-primary"
              data-dismiss="modal"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Model de nova Instituicao -->
    <div
      class="modal fade"
      id="modalInstituicaoRenuncia"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #6dd5ed">
            <h5 class="modal-title text-white" id="exampleModalLabel">
              {{ formTitle }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="salvarInstuicao" class="row">
              <div class="form-group col-12 col-md-6">
                <label for="recipient-name" class="col-form-label"
                  >Instituição <span class="text-danger">*</span></label
                >
                <input
                  type="text"
                  class="form-control form-control-sm mb-1"
                  id=""
                  v-model="form.instituicao"
                  placeholder="Informe a instituição"
                />
                <span class="text-danger d-block">{{
                  form.errors.instituicao
                }}</span>
              </div>
              <div class="form-group col-12 col-md-6">
                <label for="recipient-name" class="col-form-label"
                  >NIF <span class="text-danger">*</span></label
                >
                <input
                  type="text"
                  class="form-control form-control-sm mb-1"
                  maxlength="15"
                  max="15"
                  v-model="form.nif"
                  placeholder="Informe o Número de Identificação Fiscal"
                />
                <span class="text-danger d-block">{{ form.errors.nif }}</span>
              </div>
              <div class="form-group col-12 col-md-6">
                <label for="recipient-name" class="col-form-label">Sigla</label>
                <input
                  type="text"
                  class="form-control form-control-sm mb-1"
                  v-model="form.sigla"
                  placeholder="Informe a Sigla"
                />
                <span class="text-danger d-block">{{ form.errors.sigla }}</span>
              </div>

              <div class="form-group col-12 col-md-6">
                <label for="tipo_institicoes" class="col-form-label"
                  >Tipo de Instituição</label
                >
                <select
                  name="tipo"
                  id="tipo_institicoes"
                  class="form-control form-control-sm mb-1"
                  v-model="form.tipo"
                >
                  <option
                    :value="item.codigo"
                    v-for="item in tipo_instituicao"
                    :key="item.codigo"
                  >
                    {{ item.designacao }}
                  </option>
                </select>
                <span class="text-danger d-block">{{ form.errors.tipo }}</span>
              </div>

              <div class="form-group col-12 col-md-3">
                <label for="recipient-name" class="col-form-label"
                  >Contacto</label
                >
                <input
                  type="text"
                  class="form-control form-control-sm mb-1"
                  id=""
                  v-model="form.contacto"
                  placeholder="Informe o contacto da Instituição"
                />
                <span class="text-danger d-block">{{
                  form.errors.contacto
                }}</span>
              </div>

              <div class="form-group col-12 col-md-3">
                <label for="recipient-name" class="col-form-label"
                  >Endereço</label
                >
                <input
                  type="text"
                  class="form-control form-control-sm mb-1"
                  id=""
                  v-model="form.endereco"
                  placeholder="Informe o Endereço da Instituição"
                />
                <span class="text-danger d-block">{{
                  form.errors.endereco
                }}</span>
              </div>

              <div class="form-group col-12 col-md-6">
                <label for="recipient" class="col-form-label"
                  >Tipo de Bolsas <span class="text-danger">*</span></label
                >
                <select
                  class="form-control form-control-sm"
                  id="recipient"
                  ref="mySelect"
                  v-model="form.tipos_bolsas"
                  multiple="multiple"
                >
                  <option
                    v-for="bolsa in tipo_bolsa"
                    :value="bolsa.codigo"
                    :key="bolsa.codigo"
                  >
                    {{ bolsa.designacao }}
                  </option>
                </select>
                <span class="text-danger d-block">{{
                  form.errors.tipos_bolsas
                }}</span>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="submit"
              form="salvarInstuicao"
              class="btn text-white"
              @click.prevent="submit"
              style="background-color: #6dd5ed"
            >
              Salvar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Inicio Modal tipo de bolsa -->

    <div
      class="modal fade"
      id="modalAtribuirBolsa"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Associar Bolsa a instituição
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form id="artribuirbolsaInst">
              <div class="col-12 col-md-12">
                <div class="input-group input-group">
                  <select
                    class="form-control form-control-sm form-control"
                    v-model="form_atribuir.tipo_bolsa"
                  >
                    <option
                      v-for="bolsa in tipo_bolsa"
                      :value="bolsa.codigo"
                      :key="bolsa.codigo"
                    >
                      {{ bolsa.designacao }}
                    </option>
                  </select>
                </div>
                <span class="text-danger d-block">{{
                  form_atribuir.errors.tipo_bolsa
                }}</span>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="submit"
              form="artribuirbolsaInst"
              @click.prevent="atribuir"
              class="btn btn-primary"
            >
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="bolsasAssociadas"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">
              Bolsas Associadas a Instituição
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <table
              id="carregarTabelaEstudantes"
              style="width: 100%"
              class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
            >
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
                    <a
                      class="btn-sm btn-danger mr-1"
                      @click="elimanar_bolsa_instituica(item.codigo)"
                    >
                      <i class="fas fa-trash" title="VISUALIZAR BOLSAS"> </i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import Paginacao from "../../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from "../../../components/Alert";

export default {
  props: ["items", "tipo_bolsa", "tipo_instituicao"],
  components: { Paginacao },
  data() {
    return {
      itemId: null,
      instituicaoId: null,
      
      nome_instituicao_search: "",
      sigla_instituicao_search: "",
      nif_instituicao_search: "",
      
      search_instituicao_sem_renuncia: "",
      isUpdate: false,
      form: this.$inertia.form({
        instituicao: null,
        nif: null,
        contacto: null,
        tipo: 1,
        endereco: null,
        sigla: null,
        tipos_bolsas: [],
      }),
      form_atribuir: this.$inertia.form({
        tipo_bolsa: null,
        instituicao: null,
      }),
      params: {},
      bolsaAssociadas: [],
    };
  },

  computed: {
    formTitle() {
      return this.isUpdate
        ? "Editar Instituição Sem Renúncia"
        : "Adicionar Instituição Sem Renúncia";
    },
        
    user() {
      return this.$page.props.auth.user;
    },
  },

  mounted() {
    this.getBolsasEstudos();
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
    search_instituicao_sem_renuncia: function (val) {
      this.params.search_instituicao_sem_renuncia = val;
      this.updateData();
    },
    
    nome_instituicao_search: function (val) {
      this.params.nome_instituicao_search = val;
      this.updateData();
    },
    
    sigla_instituicao_search: function (val) {
      this.params.sigla_instituicao_search = val;
      this.updateData();
    },
    
    nif_instituicao_search: function (val) {
      this.params.nif_instituicao_search = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/instituicoes-sem-renuncia", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    visualizarBolsas(item) {
      this.bolsaAssociadas = item.bolsas;
      $("#bolsasAssociadas").modal("show");
    },

    elimanar_bolsa_instituica(item) {
      this.$Progress.start();
      this.form_atribuir.delete(
        route("associar-bolsas-instituicao.destroy", item),
        {
          preverseScroll: true,
          onSuccess: () => {
            $("#bolsasAssociadas").modal("hide");
            this.$Progress.finish();
            sweetSuccess("Instituição  removida  com sucesso");
            this.form_atribuir.reset();
          },
        }
      );
    },

    getBolsasEstudos() {
      axios.get("listar-todas-bolsas-estudos").then((response) => {
        console.log(response);
      });
    },

    editItem(item) {
      this.form.clearErrors();
      this.form.instituicao = item.Instituicao;
      this.form.nif = item.nif;
      this.form.contacto = item.contacto;
      this.form.tipo = item.tipo;
      this.form.endereco = item.Endereco;
      this.form.sigla = item.sigla;

      this.isUpdate = true;
      this.itemId = item.codigo;
      $("#modalInstituicaoRenuncia").modal("show");
    },

    deleteItem(item) {
      this.itemId = item.codigo;
      $("#modalDeleteInstituicaoRenuncia").modal("show");
    },

    atribuirItem(item) {
      this.form_atribuir.clearErrors();
      this.form_atribuir.instituicao = item.codigo;
      $("#modalAtribuirBolsa").modal("show");
    },

    create() {
      $("#modalInstituicaoRenuncia").modal("show");
      this.form.reset();
      this.form.clearErrors();
    },

    destroy() {
      this.$Progress.start();
      this.form.delete(
        route("instituicoes-sem-renuncia.destroy", this.itemId),
        {
          preverseScroll: true,
          onSuccess: () => {
            this.itemId = null;
            $("#modalDeleteInstituicaoRenuncia").modal("hide");
            this.$Progress.finish();
          },
        }
      );
    },

    atribuir() {

      this.$Progress.start();      
      axios.post('/associar-bolsas-instituicao', this.form_atribuir)
      .then(response => {
          sweetSuccess("Instituição  Actualizado  com sucesso");
          this.form_atribuir.reset();
          this.$Progress.finish();
          $("#modalAtribuirBolsa").modal("hide");
          window.location.reload()
        console.log('Resposta da requisição POST:', response.data);
      })
      .catch(error => {
        this.$Progress.fail();
        console.error('Erro ao fazer requisição POST:', error);
      });
        
    },

    submit() {
      this.$Progress.start();

      if (this.isUpdate) {
      
        axios.put(`/instituicoes-sem-renuncia/${this.itemId}`, this.form)
        .then(response => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();

            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalInstituicaoRenuncia").modal("hide");
        })
        .catch(error => {
          sweetError("Ocorreu um erro ao actualizar Instituição!");
          console.error('Erro ao fazer requisição PUT:', error);
        });
            
      } else {
        
        axios.post('/instituicoes-sem-renuncia', this.form)
        .then(response => {
            this.form.reset();
            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalInstituicaoRenuncia").modal("hide");
            window.location.reload()
          console.log('Resposta da requisição POST:', response.data);
        })
        .catch(error => {
          console.error('Erro ao fazer requisição POST:', error);
          sweetError("Ocorreu um erro ao actualizar Instituição!");
          this.$Progress.fail();
        });

      }
    },

    imprimirPDF() {
      window.open(
        "/instituicoes-sem-renuncia-pdf?tipo_instituicao=1",
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/instituicoes-sem-renuncia-excel?tipo_instituicao=1",
        "_blank"
      );
    },
  },
};
</script>
