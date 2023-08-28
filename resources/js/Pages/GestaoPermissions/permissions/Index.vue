<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Permissões</h1>
          </div>

          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <div class="card-tools">
                  <a @click="create" class="btn-sm btn-primary"
                    >Criar Nova Permissão</a
                  >
                </div>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Designação</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in items.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.updated_at }}</td>
                        <td>
                          <a @click="editItem(item)" class="btn-sm btn-primary mx-1"
                            >Editar</a
                          >
                          <a @click="deleteItem(item)" class="btn-sm btn-danger mx-1"
                            >Eliminar</a
                          >
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

    <!-- Model de nova Instituicao -->
    <div
      class="modal fade"
      id="modalPermissions"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
              <div class="form-group col-12 col-md-12">
                <label for="recipient-name" class="col-form-label"
                  >Permissão <span class="text-danger">*</span></label
                >
                <input
                  type="text"
                  class="form-control form-control-sm  mb-1"
                  id=""
                  v-model="form.name"
                  placeholder="Informe O nome da Instituição"
                />
                <span class="text-danger d-block">{{ form.errors.name }}</span>
              </div>

              <div class="form-group col-12 col-md-12">
                <label for="recipient-name" class="col-form-label"
                  >Perfil <span class="text-danger">*</span></label
                >
                <select
                  name="tipo"
                  class="form-control form-control-sm  mb-1"
                  v-model="form.role_id"
                >
                  <option :value="item.id" v-for="item in roles" :key="item.id">
                    {{ item.name }}
                  </option>
                </select>
                <span class="text-danger d-block">{{
                  form.errors.role_id
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

    <div
      class="modal"
      tabindex="-1"
      role="dialog"
      id="modalDeletePermissao"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Eliminar Permissão</h5>
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
            <p>Tem certeza que pretende eliminar esta Permissão?</p>
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
  </MainLayouts>
</template>

<script>
import Paginacao from "../../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from "../../../components/Alert";

export default {
  props: ["items", "roles"],
  components: { Paginacao },
  data() {
    return {
      itemId: null,
      search: "",
      isUpdate: false,

      form: this.$inertia.form({
        name: null,
        role_id: null,
      }),
    };
  },
  computed: {
    formTitle() {
      return this.isUpdate ? "Editar Permissão" : "Adicionar Permissão";
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
  },

  methods: {
    editItem(item) {
      this.form.clearErrors();
      this.form.name = item.name;
      this.form.role_id = item.role_id;

      this.isUpdate = true;
      this.itemId = item.id;
      $("#modalPermissions").modal("show");
    },

    create() {
      $("#modalPermissions").modal("show");
      this.form.reset();
      this.form.clearErrors();
    },

    updateData() {
      this.$Progress.start();
      this.$inertia.get("/permissions.index", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    deleteItem(item) {
      this.itemId = item.id;
      $("#modalDeletePermissao").modal("show");
    },
    
    destroy() {
        this.$Progress.start();
        this.form.delete(route("permissions.destroy", this.itemId), {
            preverseScroll: true,
            onSuccess: () => {
                this.itemId = null;
                $('#modalDeletePermissao').modal('hide');
                this.$Progress.finish();
            }
        });
    },

    submit() {
      this.$Progress.start();
      if (this.isUpdate) {
        this.form.put(route("permissions.update", this.itemId), {
          preverseScroll: true,
          onSuccess: () => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalPermissions").modal("hide");
            this.$Progress.finish();
          },
          onError: (errors) => {
            console.log(errors);
            sweetError("Ocorreu um erro ao actualizar perfil!");
          },
        });
      } else {
        this.form.post(route("permissions.store"), {
          preverseScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalPermissions").modal("hide");
          },
          onError: (errors) => {
            sweetError("Ocorreu um erro ao actualizar perfil!");
          },
        });
      }
    },
  },
};
</script>
