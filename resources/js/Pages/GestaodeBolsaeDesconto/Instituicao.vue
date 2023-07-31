<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Instituções</h1>
                    </div>

                    <div class="col-sm-6">
                        <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-pdf"></i> PDF</a>
                        <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-excel"></i> Excel</a>
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
                                <button type="button" class="btn-sm btn-primary" data-toggle="modal"
                                    data-target="#exampleModal" data-whatever="@mdo">Nova Instituicão
                                </button>
                                <div class="card-tools">
                                    <div class="input-group input-group-md" style="width: 300px">
                                        <input type="text" v-model="instituicao_busca" @keyup.enter="search"
                                            class="form-control float-right" placeholder="Search" />
                                    </div>
                                </div>
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
                                                <th>Contacto</th>
                                                <th>Endereço</th>
                                                <th width="100px">Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="instituicacao in props.instituicacao.data"
                                                :key="instituicacao.codigo">
                                                <td>{{ instituicacao.codigo }}</td>
                                                <td>{{ instituicacao.Instituicao ?? '****' }}</td>
                                                <td>{{ instituicacao.sigla ?? '****' }}</td>
                                                <td>{{ instituicacao.nif ?? '****' }}</td>
                                                <td>{{ instituicacao.contacto ?? '****' }}</td>
                                                <td>{{ instituicacao.Endereco ?? '****' }}</td>
                                                <td class="text-center">

                                                    <a class="btn-sm btn-success mr-1" data-toggle="modal" @click.prevent="buscarInstuicao(instituicacao)" data-target="#editarModal">
                                                        <i class="fa fa-edit" title="Editar"> </i>
                                                    </a>
                                                    <a class="btn-sm btn-primary mr-1" data-toggle="modal" @click.prevent="buscarInstuicao(instituicacao)" data-target="#BolsaModal">
                                                        <i class="fa fa-link" title="Associar tipo de bolsa a Instituições"> </i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary"> Total Registro: {{ props.instituicacao.total }}</Link>
                                <Paginacao
                                    :links="props.instituicacao.links"
                                    :prev="props.instituicacao.prev_page_url"
                                    :next="props.instituicacao.next_page_url"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Model de nova Instituicao -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nova Instituição</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form @submit.prevent="submiter" id="salvarInstuicao">
                                    <div class="form-group ">

                                        <label for="recipient-name" class="col-form-label">Instituição</label>
                                        <input v-model="form.Instituicao" type="text" class="form-control mb-1" :class="{ 'is-invalid': errorInstituicao }" id="">
                                        <span class="text-danger d-block" v-if="errorInstituicao">{{ errorInstituicao }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Sigla</label>
                                        <input v-model="form.sigla" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorsigla }" id="">
                                        <span class="text-danger d-block" v-if="errorsigla">{{
                                            errorsigla
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">NIF</label>
                                        <input v-model="form.nif" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errornif }" id="">
                                        <span class="text-danger d-block" v-if="errornif">{{
                                            errornif
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Contacto</label>
                                        <input v-model="form.contacto" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorcontacto }" id="">
                                        <span class="text-danger d-block" v-if="errorcontacto">{{
                                            errorcontacto
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Endereço</label>
                                        <input v-model="form.Endereco" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorEndereco }" id="">
                                        <span class="text-danger d-block" v-if="errorEndereco">{{
                                            errorEndereco
                                        }}</span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">

                                <button type="submit" form="salvarInstuicao" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Inicio Model de Editar Instituicao -->
               <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Instituição</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form @submit.prevent="updateInstituicao" id="updateInstuicao">
                                    <div class="form-group ">

                                        <label for="recipient-name" class="col-form-label">Instituição</label>
                                        <input v-model="formEdit.Instituicao" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorInstituicao }" id="">
                                        <span class="text-danger d-block" v-if="errorInstituicao">{{
                                            errorInstituicao
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Sigla</label>
                                        <input v-model="formEdit.sigla" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorsigla }" id="">
                                        <span class="text-danger d-block" v-if="errorsigla">{{
                                            errorsigla
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">NIF</label>
                                        <input v-model="formEdit.nif" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errornif }" id="">
                                        <span class="text-danger d-block" v-if="errornif">{{
                                            errornif
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Contacto</label>
                                        <input v-model="formEdit.contacto" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorcontacto }" id="">
                                        <span class="text-danger d-block" v-if="errorcontacto">{{
                                            errorcontacto
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Endereço</label>
                                        <input v-model="formEdit.Endereco" type="text" class="form-control mb-1"
                                            :class="{ 'is-invalid': errorEndereco }" id="">
                                        <span class="text-danger d-block" v-if="errorEndereco">{{
                                            errorEndereco
                                        }}</span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">

                                <button type="submit" form="updateInstuicao" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fim Model de Editar Instituicao -->

                <!-- Inicio Modal tipo de bolsa -->

                <div class="modal fade" id="BolsaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tipo de Bolsa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form @submit.prevent="artribuirbolsaInst" id="artribuirbolsaInst">
                                    <div class="col-12 col-md-12">
                                        <div class="input-group input-group">

                                            <select class="form-control form-control" v-model="valorSelecionado">
                                                <option v-for="TipoBolsa in TipoBolsa" :value="TipoBolsa">{{
                                                    TipoBolsa.designacao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="errortipo_bolsa">{{
                                            errortipo_bolsa
                                        }}</span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">

                                <button type="submit" form="artribuirbolsaInst" class="btn btn-primary"> Salvar </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </MainLayouts>
</template>


<script setup>

import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";
import { Inertia } from '@inertiajs/inertia'
import { ref, reactive, defineProps, getCurrentInstance } from 'vue'
import { sweetSuccess, sweetError } from '../../components/Alert'

const props = defineProps({
    instituicacao: Array,
    filtros: Object,
    instituicao: Object,
    bolsa: Array,
    TipoBolsa: Array
});


const internalInstance = getCurrentInstance();
const instituicao_busca = ref(props.filtros.instituicao ?? "")

const errorInstituicao = ref("")
const errornif = ref("")
const errorcontacto = ref("")
const errorEndereco = ref("")
const errorsigla = ref("")
const instituicao = ref("")
const valorSelecionado = ref("")

const form = reactive({
    Instituicao: null,
    nif: null,
    contacto: null,
    Endereco: null,
    sigla: null,
})

const formEdit = reactive({
    Instituicao: null,
    nif: null,
    contacto: null,
    Endereco: null,
    sigla: null,
})


const buscarInstuicao = (instituicao1) => {
    formEdit.Instituicao = instituicao1.Instituicao
    formEdit.nif = instituicao1.nif
    formEdit.contacto = instituicao1.contacto
    formEdit.Endereco = instituicao1.Endereco
    formEdit.sigla = instituicao1.sigla
    formEdit.codigo = instituicao1.codigo
}


const artribuirbolsaInst = () => {

const formInstuiBosla = {
    tipo_bolsa: valorSelecionado.value.codigo,
    instituicao: formEdit.Instituicao

}

Inertia.post(route('mf.tipo-bolsa-instuicao'), formInstuiBosla, {


    onSuccess: (page) => {
        $('#BolsaModal').modal('hide')
        sweetSuccess("Instituição  Actualizado  com sucesso")
    },
    onError: (errors) => {
        errortipo_bolsa.value = errors.tipo_bolsa
        errorinstituicao.value = errors.instituicao

        sweetError("Ocorreu um erro ao tentar Actualizar")
    }

})
}
const submiter = () => {
    Inertia.post(route('mf.intituicao-criar'), form, {
        onSuccess: (page) => {
            $('#exampleModal').modal('hide')
            sweetSuccess("Instituição  cadastrado com sucesso")
        },
        onError: (errors) => {
            errorInstituicao.value = errors.instituicao
            errornif.value = errors.nif
            errorcontacto.value = errors.contacto
            errorEndereco.value = errors.Endereco
            errorsigla.value = errors.sigla
            sweetError("Ocorreu um erro ao tentar cadastrar a Instituição")
        }
    })
}

const updateInstituicao = () => {

    Inertia.put(`/instituicoes/update/${formEdit.codigo}`, formEdit, {
        onSuccess: (page) => {
            $('#editarModal').modal('hide')
            sweetSuccess("Instituição  Actualizado  com sucesso")
        },
        onError: (errors) => {
            errorInstituicao.value = errors.instituicao
            errornif.value = errors.nif
            errorcontacto.value = errors.contacto
            errorEndereco.value = errors.Endereco
            errorsigla.value = errors.sigla
            sweetError("Ocorreu um erro ao tentar Actualizar")
        }
    })
}

const search = _.throttle(function () {
    Inertia.get(route('mf.instituicoes'),
        {
            instituicao: instituicao_busca.value
        },
        {
            onBefore: () => {
                internalInstance.appContext.config.globalProperties.$Progress.start();
            },
            onSuccess: () => {
                internalInstance.appContext.config.globalProperties.$Progress.finish();
            }
        },
        {
            replace: true,
            preserveState: false
        }
    )
}, 500)

const imprimirPDF = () => {
    window.open("/instituicoes/pdf/" + instituicao_busca.value, "_blank");
}

const imprimirEXCEL = () => {
    window.open("/instituicoes/excel/" + instituicao_busca.value, "_blank");
}
</script>
