<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Tipos de Bolsas</h1>
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
              v-if="user.auth.can['CRIAR BOLSAS']"
              @click="create"
              class="btn btn-primary btn-sm float-sm-right mr-2"
              ><i class="fas fa-plus"></i> Novo Tipo de Bolsa</a
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
                  <div class="input-group input-group-md" style="width: 450px">
                    <input
                      type="text"
                      v-model="search"
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
                        <th width="150px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in items.data" :key="item.codigo">
                        <td>{{ item.codigo }}</td>
                        <td>{{ item.designacao }}</td>
                        <td class="text-center">
                          <a
                            v-if="user.auth.can['EDITAR BOLSAS']"
                            class="btn-sm btn-success mr-1"
                            @click="editItem(item)"
                          >
                            <i class="fa fa-edit" title="Editar"> </i>
                          </a>
                          <a
                            class="btn-sm btn-danger mr-1"
                            v-if="user.auth.can['ELIMINAR BOLSAS']"
                            @click="deleteItem(item)"
                          >
                            <i class="fa fa-trash" title="Eliminar"> </i>
                          </a>
                          <a
                            class="btn-sm btn-info mr-1"
                            v-if="user.auth.can['VISUALIZAR BOLSAS']"
                            @click="visualizarInstituicao(item)"
                          >
                            <i
                              class="fas fa-home"
                              title="Visualizar instituições"
                            >
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

    <div class="modal" tabindex="-1" role="dialog" id="modalDeleteTipoBolsa">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Eliminar Tipo Bolsa</h5>
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
            <p>Tem certeza que pretende eliminar este tipo de bolsa?</p>
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
      id="modalTipoBolsa"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
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
            <form id="salvarTipoBolsa" class="row">
              <div class="form-group col-12 col-md-12">
                <label for="recipient-name" class="col-form-label"
                  >Designação</label
                >
                <input
                  type="text"
                  itemid="designacao"
                  class="form-control form-control-sm mb-1"
                  id=""
                  v-model="form.designacao"
                />
                <span class="text-danger d-block">{{
                  form.errors.designacao
                }}</span>
              </div>

              <!-- <div class="form-group col-12 col-md-12" v-show="compo == true">
                                <label for="recipient-name" class="col-form-label">Tipo Instituição</label>
                                <select class="form-control form-control-sm  form-control" id="tipo_instituicao_id" v-model="tipo_instituicao_id">
                                    <option v-for="tipo in tipo_instituicao" :value="tipo.codigo" :key="tipo.codigo">{{ tipo.designacao }}</option>
                                </select>
                                <span class="text-danger d-block">{{ form.errors.tipo_instituicao }}</span>
                            </div> -->

              <!-- <div class="form-group col-12 col-md-12" v-show="compo == true">
                                <label for="recipient-name" class="col-form-label">Instituição</label>
                                <select class="form-control form-control-sm  form-control" id="instituicao_id" v-model="form.instituicao_id">
                                    <option v-for="instituicao in instituicoes" :value="instituicao.codigo" :key="instituicao.codigo">{{ instituicao.Instituicao }}</option>
                                </select>
                                <span class="text-danger d-block">{{ form.errors.instituicao_id }}</span>
                            </div> -->
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="submit"
              form="salvarTipoBolsa"
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

    <div
      class="modal fade"
      id="instituicaoAssociadas"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">
              Instituições Associadas a esta Bolsa
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
                    <th width="150px">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in instituicaoAssociadas" :key="item">
                    <td>{{ item.instituicao.codigo }}</td>
                    <td>{{ item.instituicao.Instituicao }}</td>
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
  props: ["items", "tipo_bolsa", "filtros", "tipo_instituicao", "instituicoes"],
  components: { Paginacao },
  data() {
    return {
      itemId: null,
      instituicaoId: null,
      search: null,
      tipo_instituicao_id: null,
      compo: true,
      isUpdate: false,
      form: this.$inertia.form({
        designacao: null,
        instituicao_id: null,
      }),
      params: {},

      instituicaoAssociadas: [],
    };
  },

  computed: {
    formTitle() {
      return this.isUpdate ? "Editar Tipo de Bolsa" : "Adicionar Tipo de Bolsa";
    },
    user() {
      return this.$page.props.auth.user;
    },
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

    search: function (val) {
      this.params.search = val;
      this.updateData();
    },

    tipo_instituicao_id: function (val) {
      this.params.tipo_instituicao_id = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/tipo-bolsas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    visualizarInstituicao(item) {
      this.instituicaoAssociadas = item.instituicoes;
      $("#instituicaoAssociadas").modal("show");
    },

    editItem(item) {
      this.form.clearErrors();
      this.form.designacao = item.designacao;

      this.isUpdate = true;
      this.itemId = item.codigo;
      $("#modalTipoBolsa").modal("show");

      this.compo = false;

      $("#designacao").prop("disabled", true);
      $("#tipo_instituicao_id").prop("disabled", true);
      $("#instituicao_id").prop("disabled", true);
    },

    deleteItem(item) {
      this.itemId = item.codigo;
      $("#modalDeleteTipoBolsa").modal("show");
    },

    create() {
      $("#modalTipoBolsa").modal("show");
      this.form.reset();
      this.form.clearErrors();
      $("#tipo_instituicao_id").prop("disabled", false);
      $("#instituicao_id").prop("disabled", false);

      this.compo = true;
    },

    destroy() {
      this.$Progress.start();
      this.form.delete(route("tipo-bolsas.destroy", this.itemId), {
        preverseScroll: true,
        onSuccess: () => {
          this.itemId = null;
          $("#modalDeleteTipoBolsa").modal("hide");
          this.$Progress.finish();
        },
      });
    },

    submit() {
      this.$Progress.start();
      if (this.isUpdate) {
        this.form.put(route("tipo-bolsas.update", this.itemId), {
          preverseScroll: true,
          onSuccess: () => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();

            this.$Progress.finish();
            sweetSuccess("Dados salvos com Sucesso!");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao actualizar tipo bolsa!");
          },
        });
      } else {
        this.form.post(route("tipo-bolsas.store"), {
          preverseScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.$Progress.finish();
            $("#modalTipoBolsa").modal("hide");
            sweetSuccess("Dados salvos com Sucesso!");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao cadastrar tipo bolsa!");
          },
        });
      }
    },

    imprimirPDF() {
      window.open("/tipo-bolsas-pdf", "_blank");
    },

    imprimirEXCEL() {
      window.open("/tipo-bolsas-excel", "_blank");
    },
  },
};
</script>
