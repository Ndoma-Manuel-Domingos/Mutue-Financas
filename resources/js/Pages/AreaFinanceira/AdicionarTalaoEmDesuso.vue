<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Adicionar Novo Talão</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <form @submit.prevent="save" id="form_talao">
              <div class="form-group">
                <label for="">Matricula</label>
                <input type="text" v-model="form.matricula" class="form-control form-control-sm " :class="{'active': form.errors.matricula}">
                <span v-if="form.errors.matricula" class="text-danger error">{{ form.errors.matricula }}</span>
              </div>

              <div class="form-group">
                <label for="">Nº Operação 1</label>
                <input type="text" class="form-control form-control-sm " v-model="form.operacao1" :class="{'active': form.errors.operacao1}">
                <span v-if="form.errors.operacao1" class="text-danger error">{{ form.errors.operacao1 }}</span>
              </div>

              <div class="form-group">
                <label for="">Nº Operação 2</label>
                <input type="text" class="form-control form-control-sm " v-model="form.operacao2" :class="{'active': form.errors.operacao2}">
                <span v-if="form.errors.operacao2" class="text-danger error">{{ form.errors.operacao2 }}</span>
              </div>

              <div class="form-group">
                <label for="">Valor Depositado</label>
                <input type="text" class="form-control form-control-sm " v-model="form.valor" :class="{'active': form.errors.valor}">
                <span v-if="form.errors.valor" class="text-danger error">{{ form.errors.valor }}</span>
              </div>

              <div class="form-group">
                <label for="">Descrição</label>
                <textarea type="text" class="form-control form-control-sm " v-model="form.descricao" :class="{'active': form.errors.descricao}"></textarea>
                <span v-if="form.errors.descricao" class="text-danger error">{{ form.errors.descricao }}</span>
              </div>

              <div class="row">
                <div class="col-12 col md 6">
                  <div class="form-group">
                    <label for="">Comprovativo</label>
                    <input type="file" accept="image/*" class="form-control form-control-sm " @input="previewImage($event)" >
                  </div>
                </div>

                <div class="col-12 col md 6">
                  <img src="" alt="" id="image-preview" style="height: 120px;width: 120px;display: none;">
                </div>
              </div>
              

            </form>            
          </div>

          <div class="card-footer">
            <button type="submit" form="form_talao" class="btn btn-primary">
              Salvar
            </button>
          </div>

        </div>
      </div>
    </div>
  </MainLayouts>
</template>
  
<script setup>
  import { useForm } from '@inertiajs/inertia-vue3'
  import { sweetSuccess, sweetError } from "../../components/Alert"

  const form = useForm({
    matricula: null,
    operacao1: null,
    operacao2: null,
    valor: null,
    descricao: null,
    image: null
  })

  const previewImage = (event) =>{
    if(event.target.files.length > 0){
      form.image = event.target.files[0]

      var src = URL.createObjectURL(event.target.files[0])
      var image_preview = document.getElementById("image-preview")

      image_preview.src = src
      image_preview.style.display = "block"

    }
  }

  const save = () =>{
    form.post(route("mf.store-talao-desuso"), {
      preverseScroll: true,
      onSuccess: () => {
        sweetSuccess("Talão Adicionado com Sucesso!")
        form.reset();
      },
      onError: (erros) =>{
        if(erros.warning){
          sweetError("Matricula Não Existe Registrada! ")
        }else{

          sweetError("Atenção Ocorreu um erro ao tentar adicionar um talão! ")
        }
      }
    });
  }

</script>


<script>



// export default {
//   data() {
//     return {
//       formTalao: this.$inertia.form({
//         matricula: null,
//         operacao1: null,
//         operacao2: null,
//         valor: null,
//         descricao: null,
//         image: null
//       }),
//     }
//   },

//   methods: {
//     save(){
//       this.formTalao.post(route("mf.store-talao-desuso"), {
//         preverseScroll: true,
//         onSuccess: () => {
//           sweetSuccess("Talão Adicionado com Sucesso!")
//           this.formTalao.reset();
//         },
//         onError: () =>{
//           sweetError("Atenção Ocorreu um erro ao tentar adicionar um talão! ")
//         }
//       });
//     },

//     previewImage(event){
//       if(event.target.files.length > 0){
//         formTalao.image = event.target.files[0]

//         var src = URL.createObjectURL(event.target.files[0])
//         var image_preview = document.getElementById("image-preview")

//         image_preview.src = src
//         image_preview.style.display = "block"

//       }
//     }
//   }
// }
// </script>