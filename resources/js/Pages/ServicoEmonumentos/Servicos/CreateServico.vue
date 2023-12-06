<template>

    <!-- Button trigger modal -->
    <button
      type="button"
      class="btn-sm btn-primary"
      data-toggle="modal"
      data-target="#modealServico"
    >
      Novo Serviços
    </button>

    <div class="modal fade" id="modealServico">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Novo Serviço</h4>
            <button
              type="button"
              class="close"
              @click="closeModel"
            >
              <span aria-hidden="true" >&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <form @submit.prevent="submiter" id="formTipoServico">
              <div class="form-group mb-3">
                <label for="" class="text-secondary">Descrição</label>
                <input v-model="form.descricao" type="text" class="form-control form-control-sm  mb-1" :class="{'is-invalid': errorDescricao }" id="">
                <span class="text-danger d-block" v-if="errorDescricao">{{ errorDescricao }}</span>
              </div>

              <div class="form-group mb-3">
                <label for="" class="text-secondary">Preço</label>
                <input v-model="form.preco" type="text" class="form-control form-control-sm " :class="{'is-invalid': errorPreco }" id="">
                <span class="text-danger d-block" v-if="errorPreco">{{ errorPreco }}</span>
              </div>

              <div class="form-group mb-3">
                <label for="" class="text-secondary">Periodicidade</label>
                <select v-model="form.tipoServico" name="" id="" :class="{'is-invalid': errorTipoServico }" class="form-control form-control-sm ">
                  <option value="Normal">Normal</option>
                  <option value="Semestral">Semestral</option>
                  <option value="Anual">Anual</option>
                </select>
                <span class="text-danger d-block" v-if="errorTipoServico">{{ errorTipoServico }}</span>
              </div>
            </form>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" @click="closeModel" >
              Fechar
            </button>
            <button type="submit" form="formTipoServico" class="btn btn-primary">
              Salvar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </template>

  <script setup>
    import { ref, reactive, onMounted } from 'vue'
    import { Inertia } from '@inertiajs/inertia'
    import { sweetSuccess, sweetError } from '../../../components/Alert.js'

    let createModal = ""

    onMounted(() =>{
      createModal = $('#modealServico')
    })

    const errorDescricao = ref("")
    const errorPreco = ref("")
    const errorTipoServico = ref("")

    const closeModel = () => {
      createModal.modal("hide")
    }

    const form = reactive({
      descricao: null,
      preco: null,
      tipoServico: null,
    })

    const submiter = () =>{

      Inertia.post('/servicos/criar', form, {
        onSuccess: (page)=>{
          closeModel()
          sweetSuccess("Serviço cadastrado com sucesso")
        },
        onError: (errors) => {
          errorDescricao.value = errors.descricao
          errorPreco.value = errors.preco
          errorTipoServico.value = errors.tipoServico
          sweetError("Ocorreu um erro ao tentar cadastrar um serviço")
        }
      })

    }

  </script>
