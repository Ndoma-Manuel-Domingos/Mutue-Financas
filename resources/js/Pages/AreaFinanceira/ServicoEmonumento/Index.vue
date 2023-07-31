<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Serviços e Emonumentos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right"></ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
              <div class="inner text-center">
                <Link :href="route('mf.propinas')" class="small-box-footer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="height: 120px;width: 120px;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                    <h4>Mensalidades</h4>
                </Link>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a :href="route('mf.propinas')" class="small-box-footer"
                >Mais informações <i class="fas fa-arrow-circle-right"></i
              ></a>
            </div>
          </div>


          <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
              <div class="inner text-center">
                <Link :href="route('mf.servicos')" class="small-box-footer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="height: 120px;width: 120px;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z" />
                    </svg>
                    <h4>Serviços</h4>
                </Link>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <Link :href="route('mf.servicos')" class="small-box-footer"
                >Mais informações <i class="fas fa-arrow-circle-right"></i
              ></Link>
            </div>
          </div>



        </div>
      </div>
    </div>
  </MainLayouts>

  <!-- <Edit
      :identificador="editando"
    /> -->
</template>


<script>
  import { Link } from "@inertiajs/inertia-vue3";
  export default {
    components:{
      Link
    },
  };
</script>

