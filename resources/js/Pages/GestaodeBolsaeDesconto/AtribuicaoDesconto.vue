<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Atribuição de Desconto</h4>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <form @submit.prevent="atribuirDesconto">
          <div class="card card-light">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="" class="text-secondary">Pesquisar Estudante</label>
                    <div class="input-group">
                      <input
                        v-model="form.estudante"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.estudante }"
                        :disabled="user.auth.can['ATRIBUIR DESCONTO'] ? false : true"
                        type="text"
                        id="estudante"
                        placeholder="Pesquisar Estudante"
                      />
                      <button
                        v-if="user.auth.can['ATRIBUIR DESCONTO']"
                        class="btn btn-primary"
                        type="button"
                        @click="search_estudante_atribuir_desconto"
                        style="cursor: pointer"
                      >
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div v-if="form.errors.estudante" class="text-danger">
                      {{ form.errors.estudante }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Ano Lectivo</label>
                    <div class="input-group input-group">
                      <select
                        v-model="form.anolectivo"
                        disabled
                        :class="{ 'is-invalid': form.errors.anolectivo }"
                        class="form-control form-control-sm"
                        id="anolectivo"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="ano.Codigo"
                          v-for="ano in anoLectivos"
                          :key="ano.Codigo"
                        >
                          {{ ano.Designacao }}
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.anolectivo" class="text-danger">
                      {{ form.errors.anolectivo }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Instituição</label>
                    <div class="input-group input-group">
                      <select
                        @change="carregar_instituicoes"
                        disabled
                        :class="{ 'is-invalid': form.errors.instituicao }"
                        v-model="form.instituicao_id"
                        class="form-control form-control-sm"
                        id="instituicao"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="instituicao.codigo"
                          v-for="instituicao in instituicoes"
                          :key="instituicao.codigo"
                        >
                          {{ instituicao.Instituicao }}
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.instituicao" class="text-danger">
                      {{ form.errors.instituicao }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary"
                      >Tipo de Desconto</label
                    >
                    <div class="input-group input-group">
                      <select
                        v-model="form.tipoDesconto"
                        disabled
                        class="form-control form-control-sm"
                        id="tipo_Desconto"
                        :class="{ 'is-invalid': form.errors.semestre }"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="tipoDesconto.Codigo"
                          v-for="tipoDesconto in tipoDesconto"
                          :key="tipoDesconto.Codigo"
                        >
                          {{ tipoDesconto.designacao }}
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.tipo_Desconto" class="text-danger">
                      {{ form.errors.tipo_Desconto }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Afectação</label>
                    <div class="input-group input-group">
                      <select
                        disabled
                        v-model="form.afectacao"
                        class="form-control form-control-sm"
                        id="afectacao"
                        :class="{ 'is-invalid': form.errors.afectacao }"
                      >
                        <option value="Pagamento de Propina" selected>
                          Pagamento de Propina
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.afectacao" class="text-danger">
                      {{ form.errors.afectacao }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Período</label>
                    <div class="input-group input-group">
                      <select
                        v-model="form.semestre"
                        disabled
                        class="form-control form-control-sm"
                        id="semestre"
                        :class="{ 'is-invalid': form.errors.semestre }"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="semestre.Codigo"
                          v-for="semestre in semestres"
                          :key="semestre.Codigo"
                        >
                          {{ semestre.Designacao }}
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.semestre" class="text-danger">
                      {{ form.errors.semestre }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button
                v-if="user.auth.can['ATRIBUIR DESCONTO']"
                type="submit"
                :disabled="form.processing"
                class="btn btn-primary"
              >
                <i class="fas fa-check"></i>
                Atribuir
              </button>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ listarAlunoDesconto.total }}</span
                  >
                </h5>
              </div>
              <div class="card-body">
                <table
                  id="carregarTabelaEstudantes"
                  style="width: 100%"
                  class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                >
                  <thead>
                    <tr>
                      <th>Matricula</th>
                      <th>Nome</th>
                      <th>Instituição</th>
                      <th>Tipo de Desconto</th>
                      <th>Afectação</th>
                      <th>Período</th>
                      <th>%.Desconto</th>
                      <th>Ano Lectivo</th>
                      <th>Status</th>
                      <th class="text-center">Acções</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="item in listarAlunoDesconto.data"
                      :key="item.codigo"
                    >
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.matricula}`"
                          >{{ item.matricula }}</a
                        >
                      </td>
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.matricula}`"
                          >{{ item.Nome_Completo }}</a
                        >
                      </td>
                      <td>{{ item.NomeInstituicao }}</td>
                      <td>{{ item.tipoDesconto }}</td>
                      <td>{{ item.afectacao }}</td>
                      <td>{{ item.semestre }}</td>
                      <td>{{ item.valor }}</td>
                      <td>{{ item.Ano }}</td>
                      <td>{{ item.status }}</td>
                      <td>
                        <div class="flex">
                          <a
                            v-if="user.auth.can['EDITAR ATRIBUICAO DESCONTO']"
                            @click="editItem(item)"
                            class="btn-sm btn-success mx-1"
                            ><i class="fas fa-edit"></i
                          ></a>
                          <a
                            v-if="user.auth.can['EDITAR ATRIBUICAO DESCONTO'] && item.estatus_desconto_id == 1"
                            @click="mudarStatusItem(item)"
                            class="btn-sm btn-danger mx-1"
                            ><i class="fas fa-ban"></i
                          ></a>
                          <a
                            @click="mudarStatusItem(item)"
                            v-if="user.auth.can['EDITAR ATRIBUICAO DESCONTO'] && item.estatus_desconto_id == 2"
                            class="btn-sm btn-success mx-1"
                            ><i class="fas fa-check"></i
                          ></a>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="card-footer">
                <Paginacao
                  :links="listarAlunoDesconto.links"
                  :prev="listarAlunoDesconto.prev_page_url"
                  :next="listarAlunoDesconto.next_page_url"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-lg">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Resultado da Pesquisa</h4>
            <button type="button" class="close" @click="fecharModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table
              id="carregarTabelaEstudantes"
              style="width: 100%"
              class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
            >
              <tr>
                <td width="150px">Nome Completo:</td>
                <td>
                  <strong>{{ nome }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Nº Bilhete:</td>
                <td>
                  <strong>{{ bilheite }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Curso:</td>
                <td>
                  <strong>{{ curso }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Periodo:</td>
                <td>
                  <strong>{{ periodo }}</strong>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" @click="fecharModal">
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="confirmar_estudante"
            >
              Confirmar Estudante
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalMudarStatus">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Mudar Estado</h5>
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
            <p>Tem certeza que pretende mudar o estado da bolsa?</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              @click="cancelarStatus"
              class="btn-sm btn-danger"
            >
              Cancelar
            </button>
            <button
              type="button"
              @click="confirmarMudarStatus"
              class="btn-sm btn-primary"
              data-dismiss="modal"
            >
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Model de nova Instituicao -->
    <div
      class="modal fade"
      id="modalEditarBolsa"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #6dd5ed">
            <h5 class="modal-title text-white" id="exampleModalLabel">
              {{ title }}
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
                <label for="recipient-name" class="col-form-label">
                  Ano Lectivo <span class="text-danger">*</span>
                </label>
                <select
                  v-model="form_editar.anolectivo"
                  class="form-control form-control-sm"
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

              <div class="form-group col-12 col-md-6">
                <label for="recipient-name" class="col-form-label"
                  >Estados <span class="text-danger">*</span></label
                >
                <select
                  v-model="form_editar.status"
                  class="form-control form-control-sm"
                >
                  <option
                    :value="item.Codigo"
                    v-for="item in status"
                    :key="item.Codigo"
                  >
                    {{ item.Designacao }}
                  </option>
                </select>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Instituições <span class="text-danger">*</span></label>
                  <div class="input-group input-group">
                    <select
                      v-model="form_editar.instituicao_id"
                      class="form-control form-control-sm"
                    >
                      <option
                        :value="item.codigo"
                        v-for="item in instituicoes"
                        :key="item.codigo"
                      >
                        {{ item.Instituicao }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="" class="text-secondary"
                    >Tipo de Desconto <span class="text-danger">*</span></label
                  >
                  <div class="input-group input-group">
                    <select
                      v-model="form_editar.desconto"
                      @change="recuperar_valor_desconto"
                      class="form-control form-control-sm"
                      :class="{ 'is-invalid': form_editar.errors.semestre }"
                    >
                      <option value="">TODOS</option>
                      <option
                        :value="tipoDesconto.Codigo"
                        v-for="tipoDesconto in tipoDesconto"
                        :key="tipoDesconto.Codigo"
                      >
                        {{ tipoDesconto.designacao }}
                      </option>
                    </select>
                  </div>
                  <div
                    v-if="form_editar.errors.tipo_Desconto"
                    class="text-danger"
                  >
                    {{ form_editar.errors.tipo_Desconto }}
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Semestre <span class="text-danger">*</span></label>
                  <div class="input-group input-group">
                    <select
                      v-model="form_editar.semestre"
                      class="form-control form-control-sm"
                    >
                      <option
                        :value="item.Codigo"
                        v-for="item in semestres"
                        :key="item.Codigo"
                      >
                        {{ item.Designacao }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="" class="text-secondary"
                    >Afectação <span class="text-danger">*</span></label
                  >
                  <div class="input-group input-group">
                    <select
                      v-model="form_editar.afectacao"
                      class="form-control form-control-sm"
                      id="afectacao"
                      :class="{ 'is-invalid': form_editar.errors.afectacao }"
                    >
                      <option value=""></option>
                      <option value="Pagamento de Propina" selected>
                        Pagamento de Propina
                      </option>
                      <!-- <option value="Pagamentos Globais">
                                                Pagamentos Globais
                                            </option> -->
                    </select>
                  </div>
                  <div v-if="form_editar.errors.afectacao" class="text-danger">
                    {{ form_editar.errors.afectacao }}
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="submit"
              form="salvarInstuicao"
              class="btn text-white"
              @click.prevent="editar_desconto"
              style="background-color: #6dd5ed"
            >
              Actualizar
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { sweetSuccess, sweetError } from "../../components/Alert";

export default {
  props: [
    "listarAlunoDesconto",
    "anoLectivos",
    "status",
    "tipoDesconto",
    "instituicoes",
    "Instituicao",
    "semestres",
    "filtros",
  ],
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
  },
  components: { Link },
  data() {
    return {
      options: {},
      params: {},

      title: "",
      codigo: "",
      nome: "",
      curso: "",
      bilheite: "",
      periodo: "",

      form: this.$inertia.form({
        estudante: "",
        anolectivo: "",
        afectacao: "",
        desconto: "",
        semestre: "",
        instituicao_id: 9,
        tipoDesconto: "",
      }),

      form_editar: this.$inertia.form({
        estudante: "",
        anolectivo: "",
        status: "",
        afectacao: "",
        desconto: "",
        semestre: "",
        instituicao_id: 9,

        id: "",
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
    instituicao: function (val) {
      this.params.instituicao = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$inertia.get("/atribuicao/Desconto", this.params, {
        preserveState: true,
        preverseScroll: true,
        onBefore: () => {
          $(".ajax_load").fadeIn(200).css("display", "block");
        },
        onSuccess: () => {
          $(".ajax_load").fadeIn(200).css("display", "none");
        },
      });
    },

    editItem(item) {
      this.title = "Alterar os dados do Desconto do(a) " + item.Nome_Completo;

      this.form_editar.clearErrors();
      this.form_editar.estudante = item.codigo_matricula;
      this.form_editar.anolectivo = item.codigo_anoLectivo;
      this.form_editar.instituicao_id = item.instituicao_id;
      this.form_editar.semestre = item.semestre_id;
      this.form_editar.afectacao = item.afectacao;
      this.form_editar.desconto = item.codigo_tipo_desconto;
      this.form_editar.status = item.estatus_desconto_id;

      this.itemId = item.codigo;

      $("#modalEditarBolsa").modal("show");
    },

    showItem(item) {},

    confirmarMudarStatus() {
      this.$Progress.start();

      axios
        .get(`/mudar-estado-desconto/${this.itemId}`)
        .then((response) => {
          this.$Progress.finish();
          sweetSuccess("Estado da desconto actualizado com sucesso!");
          console.log("Resposta da requisição GET:", response.data);
          window.location.reload();
        })
        .catch((error) => {
          this.$Progress.fail();
          sweetError("Ocorreu um erro ao actualizar estado da desconto!");
          console.error("Erro ao fazer requisição GET:", error);
        });
    },

    mudarStatusItem(item) {
      this.itemId = item.codigo;
      $("#modalMudarStatus").modal("show");
    },

    cancelarStatus() {
      $("#modalMudarStatus").modal("hide");
    },

    atribuirDesconto() {
      this.$Progress.start();
      axios
        .post("/atribuir-desconto/estudante", this.form)
        .then((response) => {
          sweetSuccess("Desconto Adicionado com Sucesso!");
          this.form.reset();
          this.$Progress.finish();
          this.form.estudante = null;
          $("#estudante").prop("disabled", false);
          $("#anolectivo").prop("disabled", true);
          // $("#tipoBolsa").prop("disabled", true)
          $("#desconto").prop("disabled", true);
          $("#afectacao").prop("disabled", true);
          $("#semestre").prop("disabled", true) /
            $("#instituicao").prop("disabled", true);
          $("#tipo_Desconto").prop("disabled", true);
          window.location.reload();
        })
        .catch((error) => {
          console.error("Erro ao fazer requisição POST:", error);
          this.$Progress.fail();
          sweetError(
            "Ocorreu um erro ao tentar adicionar o Desconto ao Estudante!"
          );
        });
    },

    editar_desconto() {
      axios
        .put(`/update-desconto-aluno/${this.itemId}`, this.form_editar)
        .then((response) => {
          this.isUpdate = false;
          this.itemId = null;
          this.form.reset();
          sweetSuccess("Dados salvos com sucesso");
          $("#modalEditarBolsa").modal("hide");
          this.$Progress.finish();
          window.location.reload();
          console.log("Resposta da requisição PUT:", response.data);
        })
        .catch((error) => {
          console.error("Erro ao fazer requisição PUT:", error);
          sweetError("Ocorreu um erro ao actualizar Instituição!");
        });
    },

    search_estudante_atribuir_desconto() {
      axios
        .get("/consultar/estudante/" + this.form.estudante, {})
        .then((response) => {
          if (response.data.errors) {
            $("#anolectivo").prop("disabled", true);
            $("#tipoDesconto").prop("disabled", true);
            $("#desconto").prop("disabled", true);
            $("#afectacao").prop("disabled", true);
            $("#semestre").prop("disabled", true);
            // $("#instituicao").prop("disabled", true);
            $("#tipo_Desconto").prop("disabled", true);

            sweetError("Número da Matricula não existe!");
          } else {
            $("#anolectivo").prop("disabled", false);
            $("#tipoDesconto").prop("disabled", false);
            $("#desconto").prop("disabled", false);
            $("#afectacao").prop("disabled", false);
            $("#semestre").prop("disabled", false);
            $("#instituicao").prop("disabled", false);
            $("#tipo_Desconto").prop("disabled", false);

            $("#modal-lg").modal("show");

            sweetSuccess("Estudante Encontrado!");
            this.codigo = response.data.estudante.codigo;
            this.nome = response.data.estudante.nome;
            this.curso = response.data.estudante.curso;
            this.bilheite = response.data.estudante.bilheite;
            this.periodo = response.data.estudante.periodo;
          }
        })
        .catch((error) => {
          console.log(error);
          sweetError(
            "Ocorreu um erro ao tentar realizar a desconto do estudante!"
          );
        });
    },

    confirmar_estudante() {
      axios
        .get(
          "/atribuir-desconto/confirmar-estudante/" + this.form.estudante,
          {}
        )
        .then((response) => {
          $("#anolectivo").prop("disabled", false);
          // $("#tipoBolsa").prop("disabled", false);
          $("#desconto").prop("disabled", false);
          $("#afectacao").prop("disabled", false);
          $("#semestre").prop("disabled", false);
          $("#instituicao").prop("disabled", true);
          $("#tipo_Desconto").prop("disabled", false);

          $("#modal-lg").modal("hide");

          sweetSuccess("Estudante Selecionado com sucesso!");
          $("#estudante").prop("disabled", true);
        })
        .catch((error) => {
          // console.log(error)
          sweetError(
            "Ocorreu um erro ao tentar realizar a busca do estudante!"
          );
        });
    },

    fecharModal() {
      $("#modal-lg").modal("hide");
      this.form.estudante = null;

      $("#anolectivo").prop("disabled", true);
      // $("#tipoBolsa").prop("disabled", true);
      $("#desconto").prop("disabled", true);
      $("#afectacao").prop("disabled", true);
      $("#semestre").prop("disabled", true);
      // $("#instituicao").prop("disabled", true);
      $("#tipo_Desconto").prop("disabled", true);
    },

    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },
  },
};
</script>

