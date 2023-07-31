<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Atribuir crédito educacional </h1>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <form @submit.prevent="atribuirBolsa">
                    <div class="card card-light">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="col-12 col-md-5  mx-auto">
                                <div class="form-group text-center">
                                    <label for="" class="text-secondary">Pesquisar Estudante</label>
                                    <div class="input-group input-group">
                                        <input v-model="form.estudante" class="form-control form-control"
                                            :class="{ 'is-invalid': form.errors.estudante }" type="text" id="estudante"/>

                                            <button class="btn btn-primary" type="button" @click="search"
                                                style="cursor: pointer;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                    </div>
                                    <div v-if="form.errors.estudante" class="text-danger">
                                        {{ form.errors.estudante }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Ano Lectivo</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.anolectivo" disabled
                                                :class="{ 'is-invalid': form.errors.anolectivo }" class="form-control"
                                                id="anolectivo">
                                                <option :value="ano.Codigo" v-for="ano in anoLectivos"
                                                    :key="ano.Codigo"> {{ ano.Designacao }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="form.errors.anolectivo" class="text-danger">
                                            {{ form.errors.anolectivo }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Tipo Instituição</label>
                                        <div class="input-group input-group">
                                            <select disabled v-model="tipo_instituicao" class="form-control" id="tipo_instituicao">
                                                <option :value="tipo_isntitituicao.codigo" v-for="tipo_isntitituicao in tipo_instituicoes" :key="tipo_isntitituicao.codigo">
                                                    {{ tipo_isntitituicao.designacao }}
                                                </option>
                                            </select>
                                        </div>
                                        <!-- <div v-if="form.errors.instituicao" class="text-danger">
                                            {{ form.errors.instituicao }}
                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Instituição</label>
                                        <div class="input-group input-group">
                                            <select @change="carregar_instituicoes" disabled
                                                :class="{ 'is-invalid': form.errors.instituicao }"
                                                v-model="form.instituicao" class="form-control" id="instituicao">
                                                <option :value="instituicao.codigo" v-for="instituicao in instituicoes"
                                                    :key="instituicao.codigo">
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
                                        <label for="" class="text-secondary">Tipo de Bolsa</label>
                                        <div class="input-group input-group">
                                            <select disabled v-model="form.tipo_bolsa" class="form-control"
                                                id="tipoBolsa" :class="{ 'is-invalid': form.errors.tipo_bolsa }">
                                                <option value=""></option>
                                                <option :value="tipoBolsa.codigo" v-for="tipoBolsa in bolsas"
                                                    :key="tipoBolsa.codigo">
                                                    {{ tipoBolsa.designacao }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="form.errors.tipo_bolsa" class="text-danger">
                                            {{ form.errors.tipo_bolsa }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Afectação</label>
                                        <div class="input-group input-group">
                                            <select disabled v-model="form.afectacao" class="form-control"
                                                id="afectacao" :class="{ 'is-invalid': form.errors.afectacao }">
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

                                <div class="col-12 col-md-2" v-show="aplicar_desconto == true">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Desconto</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.desconto"
                                                    class="form-control">
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

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Período</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.semestre" disabled class="form-control form-control"
                                                placeholder="Type your keywords here" id="semestre"
                                                :class="{ 'is-invalid': form.errors.semestre }">
                                                <option value=""></option>
                                                <option :value="semestre.Codigo" v-for="semestre in semestres"
                                                    :key="semestre.Codigo">
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
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                <i class="fas fa-check"></i>
                                Atribuir
                            </button>
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
                        <button type="button" class="close" @click="fecharModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table">
                            <tr>
                                <td width="150px">Nome Completo:</td>
                                <td><strong>{{ item.nome }}</strong></td>
                            </tr>
                            <tr>
                                <td width="150px">Nº Bilhete:</td>
                                <td><strong>{{ item.bilheite }}</strong></td>
                            </tr>
                            <tr>
                                <td width="150px">Curso:</td>
                                <td><strong>{{ item.curso }}</strong></td>
                            </tr>
                            <tr>
                                <td width="150px">Periodo:</td>
                                <td><strong>{{ item.periodo }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" @click="fecharModal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" @click="confirmar_estudante">Confirmar
                            Estudante</button>
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
        "tipo_instituicoes"
    ],
    components: { Link },
    data() {
        return {
            options: {},
            params: {},
            item: {},

            aplicar_desconto: false,

            bolsas: [],

            codigo: "33252356",
            nome: "",
            curso: "",
            bilheite: "",
            periodo: "",

            tipo_instituicao: "",

            form: this.$inertia.form({
                estudante: "",
                anolectivo: 18,
                instituicao: "",
                tipo_bolsa: "",
                afectacao: "",
                desconto: "",
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
        formatValor(atual) {
            const valorFormatado = Intl.NumberFormat("pt-br", {
                style: "currency",
                currency: "AOA",
            }).format(atual);
            return valorFormatado;
        },

        search() {
            axios.get("/consultar/estudante/" + this.form.estudante, {}).then((response) => {


                $('#modal-lg').modal('show')
                sweetSuccess("Estudante Encontrado!");
                if (response.data.errors) {
                    $("#anolectivo").prop("disabled", true);
                    $("#tipoBolsa").prop("disabled", true);
                    $("#desconto").prop("disabled", true);
                    $("#afectacao").prop("disabled", true);
                    $("#semestre").prop("disabled", true);
                    $("#instituicao").prop("disabled", true);

                    sweetError("Número da Matricula não existe!");
                } else {
                    $("#anolectivo").prop("disabled", false);
                    $("#tipoBolsa").prop("disabled", false);
                    $("#desconto").prop("disabled", false);
                    $("#afectacao").prop("disabled", false);
                    $("#semestre").prop("disabled", false);
                    $("#instituicao").prop("disabled", false);

                    $('#modal-lg').modal('show')
                    sweetSuccess("Estudante Encontrado!");
                    this.item = response.data.estudante;
                }
            })
                .catch((error) => {
                    console.log(error)
                    sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
                });
        },

        atribuirBolsa() {
            this.$Progress.start();
            this.form.post(route("mf.atribuir-bolsa-estudante"), {
                preverseScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.$Progress.finish();
                    sweetSuccess("Bolsa de Estudado Adicionado com Sucesso!")
                    $("#estudante").prop("disabled", false)
                    $("#anolectivo").prop("disabled", true)
                    $("#tipoBolsa").prop("disabled", true)
                    $("#desconto").prop("disabled", true)
                    $("#afectacao").prop("disabled", true)
                    $("#semestre").prop("disabled", true)
                    $("#instituicao").prop("disabled", true)
                    $("#tipo_instituicao").prop("disabled", true);
                },
                onError: (errors) => {
                    sweetError("Ocorreu um erro ao tentar adicionar um Bolsa ao Estudante!")
                },
            });
        },

        carregar_instituicoes(e) {
            axios
                .get("/atribuicao/Bolsa-carregar-instituicoes?instituicao=" + this.form.instituicao, {})
                .then((response) => {
                    if (response.data.instituicao.tipo_instituicao == 1) {
                        this.aplicar_desconto = true;
                    } else {
                        this.aplicar_desconto = false;
                        this.form.desconto = 99;
                    }
                    this.bolsas = response.data.tipo_bolsas
                })
                .catch((error) => {
                    sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
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
                    $('#modal-lg').modal('hide')

                    sweetSuccess("Estudante Selecionado com sucesso!");
                    $("#estudante").prop("disabled", true);
                })
                .catch((error) => {
                    sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
                });
        },

    },
};
</script>




<!-- <script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

import { sweetSuccess, sweetError } from "../../components/Alert";

const props = defineProps({
    filtros: Object,
    anolectivoactivo: {
        type: String,
    },
    numero_matricula: {
        type: String
    }
});


const codigo = ref("")
const nome = ref("")
const curso = ref("")
const bilheite = ref("")
const periodo = ref("")
const aplicar_desconto = ref(false)


const form = useForm({
    estudante: props.numero_matricula ?? null,
    anolectivo: props.anolectivoactivo ?? null,
    instituicao: props.filtros.instituicao ?? null,
    tipo_bolsa: null,
    afectacao: null,
    desconto: null,
    semestre: null,
}); -->

<!-- // const search = () => {
//     axios
//         .get("/consultar/estudante/" + form.estudante, {})
//         .then(function (response) {
//             if (response.data.errors) {
//                 $("#anolectivo").prop("disabled", true);
//                 $("#tipoBolsa").prop("disabled", true);
//                 $("#desconto").prop("disabled", true);
//                 $("#afectacao").prop("disabled", true);
//                 $("#semestre").prop("disabled", true);
//                 $("#instituicao").prop("disabled", true);

//                 sweetError("Número da Matricula não existe!");
//             } else {
//                 $("#anolectivo").prop("disabled", false);
//                 $("#tipoBolsa").prop("disabled", false);
//                 $("#desconto").prop("disabled", false);
//                 $("#afectacao").prop("disabled", false);
//                 $("#semestre").prop("disabled", false);
//                 $("#instituicao").prop("disabled", false);

//                 $('#modal-lg').modal('show')

//                 sweetSuccess("Estudante Encontrado!");
//                 codigo.value = response.data.estudante.codigo;
//                 nome.value = response.data.estudante.nome;
//                 curso.value = response.data.estudante.curso;
//                 bilheite.value = response.data.estudante.bilheite;
//                 periodo.value = response.data.estudante.periodo;
//             }
//         })
//         .catch(function (error) {
//             // console.log(error)
//             sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
//         });
// }; -->

<!-- const atribuirBolsa = () => {
    form.post(route("mf.atribuir-bolsa-estudante"), {
        onSuccess: (response) => {
            sweetSuccess("Bolsa de Estudado Adicionado com Sucesso!")
            form.reset()
            form.estudante = null
            $("#estudante").prop("disabled", false)
            $("#anolectivo").prop("disabled", true)
            $("#tipoBolsa").prop("disabled", true)
            $("#desconto").prop("disabled", true)
            $("#afectacao").prop("disabled", true)
            $("#semestre").prop("disabled", true)
            $("#instituicao").prop("disabled", true)
        },
        onError: (errors) => {
            //   console.log(errors);
            sweetError("Ocorreu um erro ao tentar adicionar um Bolsa ao Estudante!")
        },
    });
};

const carregar_instituicoes = (e) => {
    Inertia.get(
        route("mf.atribuicao-bolsa"), { instituicao: form.instituicao },
        {
            onBefore: () => {
                // $(".ajax_load").fadeIn(200).css("display", "block");
            },
            onSuccess: () => {
                // $(".ajax_load").fadeIn(200).css("display", "none");
                $("#estudante").prop("disabled", true);
                $("#anolectivo").prop("disabled", false);
                $("#tipoBolsa").prop("disabled", false);
                $("#desconto").prop("disabled", false);
                $("#afectacao").prop("disabled", false);
                $("#semestre").prop("disabled", false);
                $("#instituicao").prop("disabled", false);
            },
        }, {
            preserveState: true,
            preverseScroll: true,
        }
    );
};

const fecharModal = () => {
    $('#modal-lg').modal('hide')
    form.estudante = null

    $("#anolectivo").prop("disabled", true);
    $("#tipoBolsa").prop("disabled", true);
    $("#desconto").prop("disabled", true);
    $("#afectacao").prop("disabled", true);
    $("#semestre").prop("disabled", true);
    $("#instituicao").prop("disabled", true);
}

const confirmar_estudante = () => {
    axios
        .get("/atribuir-bolsa/confirmar-estudante/" + form.estudante, {})
        .then(function (response) {
            $("#anolectivo").prop("disabled", false);
            $("#tipoBolsa").prop("disabled", false);
            $("#desconto").prop("disabled", false);
            $("#afectacao").prop("disabled", false);
            $("#semestre").prop("disabled", false);
            $("#instituicao").prop("disabled", false);

            $('#modal-lg').modal('hide')

            sweetSuccess("Estudante Selecionado com sucesso!");
            $("#estudante").prop("disabled", true);

        })
        .catch(function (error) {
            // console.log(error)
            sweetError("Ocorreu um erro ao tentar realizar a busca do estudante!");
        });
}
</script> -->
