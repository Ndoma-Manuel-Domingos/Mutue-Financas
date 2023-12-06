<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Atribuir crédito educacional</h4>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">

      <div class="container-fluid">
        <form @submit.prevent="atribuirBolsa">
          <div class="card">
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
                      type="text"
                      :disabled="user.auth.can['ATRIBUIR BOLSAS'] ? false : true"
                      placeholder="Introduz o número de matricula do estudante: "
                      id="estudante"
                    />

                    <button
                        v-if="user.auth.can['ATRIBUIR BOLSAS']"
                        class="btn btn-primary"
                        type="button"
                        @click="search"
                        @keyup.enter="search"
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
                    <label for="" class="text-secondary"
                      >Tipo Instituição</label
                    >
                    <div class="input-group input-group">
                      <select
                        disabled
                        v-model="tipo_instituicao"
                        @change="mostrar_tipo_instituica"
                        class="form-control form-control-sm"
                        id="tipo_instituicao"
                      >
                        <option
                          :value="tipo_isntitituicao.codigo"
                          v-for="tipo_isntitituicao in tipo_instituicoes"
                          :key="tipo_isntitituicao.codigo"
                        >
                          {{ tipo_isntitituicao.designacao }}
                        </option>
                      </select>
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
                        v-model="form.instituicao"
                        class="form-control form-control-sm"
                        id="instituicao"
                      >
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

                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Tipo de Bolsa</label>
                    <div class="input-group input-group">
                      <select
                        disabled
                        v-model="form.tipo_bolsa"
                        class="form-control form-control-sm"
                        id="tipoBolsa"
                        :class="{ 'is-invalid': form.errors.tipo_bolsa }"
                      >
                        <option value=""></option>
                        <option
                          :value="tipoBolsa.codigo"
                          v-for="tipoBolsa in bolsas"
                          :key="tipoBolsa.codigo"
                        >
                          {{ tipoBolsa.designacao }}
                        </option>
                      </select>
                    </div>
                    <div v-if="form.errors.tipo_bolsa" class="text-danger">
                      {{ form.errors.tipo_bolsa }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-3">
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
                <!-- v-show="aplicar_desconto == true" -->
                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Desconto</label>
                    <div class="input-group input-group">
                      <select
                        v-model="form.desconto"
                        class="form-control form-control-sm"
                      >
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
                    <div v-if="form.errors.desconto" class="text-danger">
                      {{ form.errors.desconto }}
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary"
                      >Período de Bolsa</label
                    >
                    <div class="input-group input-group">
                      <select
                        v-model="form.semestre"
                        disabled
                        class="form-control form-control-sm form-control"
                        placeholder="Type your keywords here"
                        id="semestre"
                        :class="{ 'is-invalid': form.errors.semestre }"
                      >
                        <option value=""></option>
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
                v-if="user.auth.can['ATRIBUIR BOLSAS']"
                type="submit"
                :disabled="form.processing"
                class="btn btn-primary"
              >
                <i class="fas fa-check"></i>
                Atribuir
              </button>

              <a class="btn btn-primary mx-1" @click="cancelar">
                <i class="fas fa-ban"></i>
                Cancelar
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="modal-lg">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Resultado da Pesquisa</h4>
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
              <tr>
                <td width="150px">Nome Completo:</td>
                <td>
                  <strong>{{ item.nome }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Nº Bilhete:</td>
                <td>
                  <strong>{{ item.bilheite }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Curso:</td>
                <td>
                  <strong>{{ item.curso }}</strong>
                </td>
              </tr>
              <tr>
                <td width="150px">Periodo:</td>
                <td>
                  <strong>{{ item.periodo }}</strong>
                </td>
              </tr>
              <tr v-if="bolseiro">
                <td width="150px">Bolseiro:</td>
                <td>
                  <strong class="text-danger"
                    >Este estudante já é um bolseiro</strong
                  >
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Fechar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="!bolseiro"
              @click="confirmar_estudante"
            >
              Confirmar Estudante
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
    "anoLectivos",
    "tipoBolsas",
    "instituicoes",
    "semestres",
    "tipo_instituicoes",
    "anolectivoactivo",
  ],
  components: { Link },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
  },
  data() {
    return {
      options: {},
      params: {},
      item: {},
      bolseiro: {},

      aplicar_desconto: false,
      tipo_instituicao: "",

      bolsas: [],

      codigo: "",
      nome: "",
      curso: "",
      bilheite: "",
      periodo: "",

      tipo_instituicao: "",

      form: this.$inertia.form({
        estudante: "",
        anolectivo: this.anolectivoactivo,
        instituicao: "",
        tipo_bolsa: "",
        afectacao: "",
        desconto: 5,
        semestre: "",
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

    tipo_instituicao: function (val) {
      this.params.tipo_instituicao = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$inertia.get("/atribuicao/Bolsa", this.params, {
        preserveState: true,
        preverseScroll: true,
        onBefore: () => {
          this.$Progress.start();
        },
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    cancelar() {
      $("#anolectivo").prop("disabled", true);
      $("#tipoBolsa").prop("disabled", true);
      $("#desconto").prop("disabled", true);
      $("#afectacao").prop("disabled", true);
      $("#semestre").prop("disabled", true);
      $("#instituicao").prop("disabled", true);
      $("#tipo_instituicao").prop("disabled", true);

      $("#estudante").prop("disabled", false);
    },

    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },

    search() {
      axios
        .get("/consultar/estudante/" + this.form.estudante, {})
        .then((response) => {
          this.bolseiro = null;
          $("#modal-lg").modal("show");
          sweetSuccess("Estudante Encontrado!");
          if (response.data.errors) {
            $("#anolectivo").prop("disabled", true);
            $("#tipoBolsa").prop("disabled", true);
            $("#desconto").prop("disabled", true);
            $("#afectacao").prop("disabled", true);
            $("#semestre").prop("disabled", true);
            $("#instituicao").prop("disabled", true);

            this.item = null;

            sweetError("Número da Matricula não existe!");
          } else {
            if (response.data.bolseiro) {
              $("#anolectivo").prop("disabled", true);
              $("#tipoBolsa").prop("disabled", true);
              $("#desconto").prop("disabled", true);
              $("#afectacao").prop("disabled", true);
              $("#semestre").prop("disabled", true);
              $("#instituicao").prop("disabled", true);

              $("#modal-lg").modal("show");
              sweetSuccess("Estudante Encontrado!");
              this.item = response.data.estudante;
              this.bolseiro = response.data.bolseiro;
            } else {
              $("#anolectivo").prop("disabled", false);
              $("#tipoBolsa").prop("disabled", false);
              $("#desconto").prop("disabled", false);
              $("#afectacao").prop("disabled", false);
              $("#semestre").prop("disabled", false);
              $("#instituicao").prop("disabled", false);

              $("#modal-lg").modal("show");
              sweetSuccess("Estudante Encontrado!");
              this.item = response.data.estudante;
              // this.bolseiro = response.data.bolseiro;
            }
          }
        })
        .catch((error) => {
          console.log(error);
          this.bolseiro = null;
          sweetError(
            "Ocorreu um erro ao tentar realizar a busca do estudante!"
          );
        });
    },

    atribuirBolsa() {
      this.$Progress.start();
      this.form.post(route("mf.atribuir-bolsa-estudante"), {
        preverseScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.$Progress.finish();
          sweetSuccess("Bolsa de Estudado Adicionado com Sucesso!");
          $("#estudante").prop("disabled", false);
          $("#anolectivo").prop("disabled", true);
          $("#tipoBolsa").prop("disabled", true);
          $("#desconto").prop("disabled", true);
          $("#afectacao").prop("disabled", true);
          $("#semestre").prop("disabled", true);
          $("#instituicao").prop("disabled", true);
          $("#tipo_instituicao").prop("disabled", true);
        },
        onError: (errors) => {
          sweetError(
            "Ocorreu um erro ao tentar adicionar um Bolsa ao Estudante!"
          );
        },
      });
    },

    mostrar_tipo_instituica() {
      if (this.tipo_instituicao == 1) {
        // alert('1')
        this.aplicar_desconto = true;
        this.form.desconto = "100";
      } else {
        // alert('2')
        this.aplicar_desconto = true;
        this.form.desconto = "90";
      }
      // this.bolsas = response.data.tipo_bolsas
    },

    carregar_instituicoes(e) {
      axios
        .get(
          "/atribuicao/Bolsa-carregar-instituicoes?instituicao=" +
            this.form.instituicao,
          {}
        )
        .then((response) => {
          if (response.data.instituicao.tipo_instituicao == 1) {
            // alert('1')
            this.aplicar_desconto = true;
          } else {
            // alert('2')
            this.aplicar_desconto = true;
            this.form.desconto = "90";
          }
          this.bolsas = response.data.tipo_bolsas;
        })
        .catch((error) => {
          sweetError(
            "Ocorreu um erro ao tentar realizar a busca do estudante!"
          );
        });
    },

    confirmar_estudante() {
      axios
        .get("/atribuir-bolsa/confirmar-estudante/" + this.form.estudante, {})
        .then((response) => {
          $("#anolectivo").prop("disabled", false);
          $("#tipoBolsa").prop("disabled", false);
          $("#desconto").prop("disabled", false);
          $("#afectacao").prop("disabled", false);
          $("#semestre").prop("disabled", false);
          $("#instituicao").prop("disabled", false);
          $("#tipo_instituicao").prop("disabled", false);
          $("#modal-lg").modal("hide");

          sweetSuccess("Estudante Selecionado com sucesso!");
          $("#estudante").prop("disabled", true);
        })
        .catch((error) => {
          sweetError(
            "Ocorreu um erro ao tentar realizar a busca do estudante!"
          );
        });
    },
  },
};
</script>
