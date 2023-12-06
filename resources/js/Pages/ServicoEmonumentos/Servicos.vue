<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Serviços (Preçarios)</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <a
                data-toggle="modal"
                data-target="#modealServico"
                class="btn btn-primary btn-sm float-sm-right mr-2"
                ><i class="fas fa-plus"></i> Novo Serviço</a
              >
              <a
                href="/servicos/pdf-imprimir"
                target="_blink"
                class="btn btn-danger btn-sm float-sm-right mr-2"
                ><i class="fas fa-file-pdf"></i> PDF</a
              >
              <a
                href="/servicos/excel-imprimir"
                target="_blink"
                class="btn btn-success btn-sm float-sm-right mr-2"
                ><i class="fas fa-file-excel"></i> Excel</a
              >
            </ol>
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
                    >TOTAL REGISTROS: {{ servicos.total }}</span
                  >
                </h5>

                <div class="card-tools">
                  <div class="input-group input-group-md" style="width: 450px">
                    <input
                      v-model="search"
                      type="text"
                      class="form-control form-control-sm float-right"
                      placeholder="Pesquisar"
                    />
                    <div class="input-group-append">
                      <button type="" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body p-0">
                <table
                  id="carregarTabelaEstudantes"
                  style="width: 100%"
                  class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                >
                  <thead>
                    <tr>
                      <th class="text-left" width="50px">Nº</th>
                      <th class="text-right" width="700px">Descrição</th>
                      <th class="text-left">Preço</th>
                      <th width="100px">Acções</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in servicos.data"
                      :key="item.Codigo"
                    >
                      <td class="text-left">{{ index + 1 }}</td>
                      <td class="text-right">{{ item.Descricao }}</td>
                      <td class="text-left">AOA {{ item.Preco }}</td>
                      <td>
                        <a
                          @click="editItem(item)"
                          class="btn-sm btn-primary"
                          href="#"
                        >
                          <i class="fas fa-edit"></i>
                          Editar
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="card-footer row">
                <div class="col-12 col-md-4">
                  <div class="row">
                    <div class="col-12 col-md-3">Por Pagina:</div>
                    <select
                      class="form-control form-control-sm col-12 col-md-3"
                      v-model="porPagina"
                    >
                      <option value="" selected disabled>Por Pagina</option>
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="20">20</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                  </div>
                </div>

                <div class="col-12 col-md-4 text-center fs-2">
                  <Link>Total Serviços: {{ servicos.total }} </Link>
                </div>

                <div class="col-12 col-md-4">
                  <Paginacao
                    :links="servicos.links"
                    :prev="servicos.prev_page_url"
                    :next="servicos.next_page_url"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modealServico">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ formTitle }}</h4>
            <button type="button" class="close" @click="closeModel">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submit" id="formTipoServico">
              <div class="form-group mb-3">
                <label for="" class="text-secondary">Descrição</label>
                <input
                  v-model="form.descricao"
                  type="text"
                  class="form-control form-control-sm mb-1"
                  :class="{ 'is-invalid': form.errors.descricao }"
                  id=""
                />
                <span class="text-danger d-block">{{
                  form.errors.descricao
                }}</span>
              </div>

              <div class="form-group mb-3">
                <label for="" class="text-secondary">Preço</label>
                <input
                  v-model="form.preco"
                  type="text"
                  class="form-control form-control-sm"
                  :class="{ 'is-invalid': form.errors.preco }"
                  id=""
                />
                <span class="text-danger d-block">{{ form.errors.preco }}</span>
              </div>

              <div class="form-group mb-3">
                <label for="" class="text-secondary">Periodicidade</label>
                <select
                  v-model="form.tipoServico"
                  :class="{ 'is-invalid': form.errors.tipoServico }"
                  class="form-control form-control-sm"
                >
                  <option value="Normal">Normal</option>
                  <option value="Semestral">Semestral</option>
                  <option value="ANUAL">Anual</option>
                </select>
                <span class="text-danger d-block">{{
                  form.errors.tipoServico
                }}</span>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn-sm btn-default"
              @click="closeModel"
            >
              Fechar
            </button>
            <button
              type="submit"
              form="formTipoServico"
              class="btn-sm btn-primary"
            >
              Salvar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  </MainLayouts>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from "../../components/Alert.js";

export default {
  props: ["servicos"],

  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      search: "",
      porPagina: "",

      itemId: null,
      isUpdate: false,
      params: {},
      form: this.$inertia.form({
        id: null,
        descricao: null,
        preco: null,
        tipoServico: 1,
      }),
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
    search: function (val) {
      this.params.search = val;
      this.updateData();
    },
    porPagina: function (val) {
      this.params.porPagina = val;
      this.updateData();
    },
  },
  computed: {
    formTitle() {
      return this.isUpdate ? "Editar Serviços" : "Adicionar Serviços";
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/servicos", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    editItem(item) {
      this.form.clearErrors();
      this.form.descricao = item.Descricao;
      this.form.preco = item.Preco;
      this.form.tipoServico = item.TipoServico;
      this.form.id = item.Codigo;

      this.isUpdate = true;
      this.itemId = item.Codigo;
      $("#modealServico").modal("show");
    },

    submit() {
      this.$Progress.start();
      if (this.isUpdate) {
        this.form.put(route("mf.updateServico", this.itemId), {
          preverseScroll: true,
          onSuccess: () => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();

            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modealServico").modal("hide");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao!");
          },
        });
      } else {
        this.form.post(route("mf.servicos-create"), {
          preverseScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modealServico").modal("hide");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao actualizar!");
          },
        });
      }
    },

    closeModel() {
      $("#modealServico").modal("hide");
    },
  },
};
</script>
  