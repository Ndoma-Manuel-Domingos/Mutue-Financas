<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Mensalidades (Preçarios)</h4>
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
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <form action="">
          <div class="card card-light">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Ano Lectivo</label>
                    <div class="input-group input-group">
                      <select
                        v-model="anolectivo"
                        @change="search"
                        class="form-control form-control-sm form-control"
                        placeholder="Type your keywords here"
                      >
                        <option
                          :value="ano.Codigo"
                          v-for="ano in anoLectivos"
                          :key="ano.Codigo"
                        >
                          {{ ano.Designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Polo</label>
                    <div class="input-group input-group">
                      <select
                        v-model="polo"
                        @change="search"
                        class="form-control form-control-sm form-control"
                        placeholder="Type your keywords here"
                      >
                        <option value="">Todos</option>
                        <option
                          :value="ano.id"
                          v-for="ano in polos"
                          :key="ano.id"
                        >
                          {{ ano.designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS:
                    {{ servicos.total }}</span
                  >
                </h5>
              
                <div class="card-tools">
                  <div class="input-group input-group-md" style="width: 450px">
                    <input
                      type="text"
                      name="table_search"
                      class="form-control form-control-sm float-right"
                      placeholder="Search"
                    />
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
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
                        <th width="500px" class="text-center">Descrição</th>
                        <th class="text-center">Polo</th>
                        <th>Preço</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input
                            type="text"
                            v-model="search_descricao"
                            class="form-control form-control-sm"
                          />
                        </td>
                      </tr>

                      <tr v-for="item in servicos.data" :key="item.Codigo">
                        <td class="text-center">{{ item.Descricao }}</td>
                        <td class="text-center">{{ item.polo.designacao }}</td>
                        <td>
                          <span class="tag tag-success"
                            >AOA {{ item.Preco }}</span
                          >
                        </td>
                        <td>
                          <a
                            class="btn-sm btn-success mr-1"
                            @click="editItem(item)"
                          >
                            <i class="fa fa-edit" title="Editar"> </i>
                          </a>
                          <!-- <a @click="editarServicoModal(servico.Codigo)" class="btn-sm btn-primary" href="#"><i class="fas fa-edit"></i> Edtiar</a> -->
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  TOTAL REGISTROS: <strong>{{ servicos.total }}</strong>
                </Link>
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

    <div class="modal fade" id="modalEditarServico">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ formTitle }}</h4>
            <button type="button" class="close" @click="closeModel">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formEditarTipoServico" @submit.prevent="submit">
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
                <label for="" class="text-secondary">Polo</label>
                <select
                  name=""
                  id=""
                  v-model="form.polo_id"
                  type="text"
                  class="form-control form-control-sm mb-1"
                  :class="{ 'is-invalid': form.errors.polo_id }"
                >
                  <option value="">Todos</option>
                  <option :value="ano.id" v-for="ano in polos" :key="ano.id">
                    {{ ano.designacao }}
                  </option>
                </select>
                <span class="text-danger d-block">{{
                  form.errors.polo_id
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
              form="formEditarTipoServico"
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
  props: ["anoLectivos", "polos", "servicos"],
  data() {
    return {
      polo: "",
      anolectivo: "18",

      search_descricao: "",

      itemId: null,
      isUpdate: false,

      params: {},

      form: this.$inertia.form({
        id: null,
        descricao: null,
        preco: null,
        polo_id: 1,
      }),
    };
  },
  components: {
    Link,
    Paginacao,
  },

  computed: {
    formTitle() {
      return this.isUpdate
        ? "Editar Mensalidades (Preçario)"
        : "Adicionar Mensalidades (Preçario)";
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
    anolectivo: function (val) {
      this.params.anolectivo = val;
      this.updateData();
    },
    polo: function (val) {
      this.params.polo = val;
      this.updateData();
    },
    search_descricao: function (val) {
      this.params.search_descricao = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/propinas", this.params, {
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
      this.form.polo_id = item.polo_id;

      this.isUpdate = true;
      this.itemId = item.Codigo;
      $("#modalEditarServico").modal("show");
    },

    submit() {
      this.$Progress.start();
      if (this.isUpdate) {
        this.form.put(route("mf.updatePropinas", this.itemId), {
          preverseScroll: true,
          onSuccess: () => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();

            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalEditarServico").modal("hide");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao!");
          },
        });
      } else {
        // this.form.post(route("instituicoes-sem-renuncia.store"), {
        //     preverseScroll: true,
        //     onSuccess: () => {
        //         this.form.reset();
        //         this.$Progress.finish();
        //         sweetSuccess("Dados salvos com sucesso")
        //         $('#modalInstituicaoRenuncia').modal('hide');
        //     },
        //     onError: (errors) => {
        //         sweetError("Ocorreu um erro ao actualizar Instituição!")
        //     },
        // });
      }
    },

    closeModel() {
      $("#modalEditarServico").modal("hide");
    },

    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },

    imprimirPDF() {
      window.open(
        "/propinas/pdf-imprimir?a" + this.anolectivo + "&p=" + this.polo,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/propinas/pdf-imprimir?a" + this.anolectivo + "&p=" + this.polo,
        "_blank"
      );
    },
  },
};
</script>
