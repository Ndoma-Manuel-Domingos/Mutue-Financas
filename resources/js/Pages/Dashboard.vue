<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="TOTAL DE ESTUDANTES ACTIVOS"
            >
              <span class="info-box-icon"
                ><i class="fas fa-users"></i
              ></span>
              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break">Estudantes ACTIVOS</span>
                <span class="info-box-number">{{ total_estudantes }}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="TOTAL DE ESTUDANTES BOLSEIROS"
            >
              <span class="info-box-icon"
                ><i class="fas fa-users"></i
              ></span>
              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break">BOLSEIROS</span>
                <span class="info-box-number">{{ bolseiros }}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col --> 
          
          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="TOTAL DE ESTUDANTES BOLSEIROS COM RENUNCIA"
            >
              <span class="info-box-icon"><i class="fas fa-users"></i> </span>
              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break"
                  >BOLSEIROS COM RENUNCIA</span
                >
                <!-- <span class="info-box-number">{{ estudante_propinas_pagas }}</span> -->
                <span class="info-box-number">{{ bolseiros_com_renuncia }}</span>
                
                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="TOTAL DE ESTUDANTES BOLSEIROS SEM RENUNCIA"
            >
              <span class="info-box-icon"><i class="fas fa-users"></i> </span>
              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break"
                  >BOLSEIROS SEM RENUNCIA</span
                >
                <!-- <span class="info-box-number">{{ estudante_propinas_pagas }}</span> -->
                
                <span class="info-box-number">{{ bolseiros_sem_renuncia }}</span>
                
                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          
          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="TOTAL ESTUDANTES COM MENSALIDADES PAGAS"
            >
              <span class="info-box-icon"><i class="fas fa-users"></i> </span>
              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break"
                  >ESTUDANTES MENSAL. PAGAS</span
                >
                <!-- <span class="info-box-number">{{ estudante_propinas_pagas }}</span> -->
                
                <span class="info-box-number">{{ estudante_propinas_pagas }}</span>
                
                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
            >
              <span class="info-box-icon"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text text-uppercase word-break">Estudantes Devedores</span>
                <!-- <span class="info-box-number">{{ total_estudantes_devedores }}</span> -->
                <span class="info-box-number">{{ total_estudantes_devedores }}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description"> Total </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
          
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <h5 class="col-12 col-md-4">
                    Estudantes com pagamento de Mensalidades <!-- {{ ano_lectivo }}-->.
                  </h5>
                  <div class="col-12 col-md-2">
                    <div class="row">
                      <label
                        for=""
                        class="col-12 col-md-6 text-end col-form-label"
                      >
                        <span class="float-right">Anos Lectivos</span>
                      </label>

                      <select
                        type="text"
                        class="form-control form-control-sm  col-12 col-md-6"
                        v-model="ano_lectivo_selecionado"
                        @change="search"
                      >
                        <option
                          :value="ano.Codigo"
                          v-for="ano in anos_lectivos"
                          :key="ano.Codigo"
                        >
                          {{ ano.Designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-12 col-md-3">
                    <div class="row">
                      <label
                        for=""
                        class="col-12 col-md-6 col-form-label text-end"
                      >
                        <span class="float-right">Meses</span>
                      </label>
                      <select
                        type="text"
                        class="form-control form-control-sm  col-12 col-md-6"
                        v-model="mes_temp_id"
                        @change="search_meses"
                      >
                        <option value="" disabled>Selecione</option>
                        <option :value="mes.id" v-for="mes in mesTemps" :key="mes.id">{{ mes.designacao }}</option>
                      </select>
                    </div>
                  </div>
                  
                  
                  <!-- <div class="col-12 col-md-3">
                    <div class="row">
                      <label
                        for=""
                        class="col-12 col-md-6 col-form-label text-end"
                      >
                        <span class="float-right">Estado Pagamentos</span>
                      </label>
                      <select
                        type="text"
                        class="form-control form-control-sm  col-12 col-md-6"
                        v-model="estado_pagamentos"
                        @change="search"
                      >
                        <option value="0">Todos</option>
                        <option value="1">Pendentes</option>
                        <option value="2">Validados</option>
                        <option value="3">Rejeitados</option>
                      </select>
                    </div>
                  </div> -->
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        
      
        <div class="row mt-2">
          <div class="col-12 col-md-8">
            <div class="card">
              <div class="card-body">
                <column-chart
                :colors="color"
                  :data="charts_meses"
                  :stacked="true"
                  prefix=""
                  suffix=" Kzs"
                  :round="2"
                  :zeros="true"
                  title="Total de pagamentos por Parcela"
                  thousands="."
                  loading="carregamdo dados..."
                  empty="carregamdo dados..."
                  decimal=","
                  :download="true"
                ></column-chart>
              </div>
              <div class="card-footer">
                
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="card">
              <div class="card-body">
                <column-chart
                  :colors="['#50c6ea']"
                  :data="charts_turno"
                  :stacked="false"
                  prefix=""
                  suffix=" Kzs"
                  :round="2"
                  :zeros="true"
                  title="Total de pagamento por período"
                  thousands="."
                  loading="carregamdo dados..."
                  empty="carregamdo dados..."
                  decimal=","
                  :download="true"
                ></column-chart>
              </div>
              <div class="card-footer">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>

export default {
  props: ["anos_lectivos", "ano"],
  data() {
    return {

      utilizadores: "",
      estudante_propinas_pagas: "",
      
      bolseiros: "",
      bolseiros_com_renuncia: "",
      bolseiros_sem_renuncia: "",
      
      mesTemps: [],
      mes_temp_id: "",
      
      total_estudantes: "",
      total_estudantes_devedores: "",
      ano_lectivo_selecionado: this.ano.Codigo,

      quantidade_estudantes: "100",
      estado_pagamentos: "0",
      
      color:['#17a08c'],

      charts_turno: [],
      charts_meses: [],

      ano_lectivo: "",
      
      selected: null,
      options: [
        { value: null, text: 'Please select some item' },
        { value: 'a', text: 'This is option a' },
        { value: 'b', text: 'Default Selected Option b' },
        { value: 'c', text: 'This is option c' },
        { value: 'd', text: 'This one is disabled', disabled: true },
        { value: 'e', text: 'This is option e' },
        { value: 'e', text: 'This is option f' }
      ]
    };
  },

  mounted() {
    this.carregar_grafico_estudantes_devedores(this.ano.Codigo);
    this.carregar_grafico_estudantes_propinas_pagas(this.ano.Codigo);
    this.carregar_grafico_estudantes_bolseiros(this.ano.Codigo);
    this.carregar_grafico_pagamentos_por_turnos(this.ano.Codigo);
    this.carregar_grafico_pagamentos_por_meses(this.ano.Codigo);
  },
  methods: {
    search(value) {

         if(value.target.value  ==0)
         {
            this.color =['#17a08c']; //todos cor verde

         }

         else  if(value.target.value  ==1)
         {
            this.color = ['#d68204']; // pedente cor verde
         }

         else  if(value.target.value  ==2)
         {
            this.color =['#03aa27']; // validado cor pedente

         }

         else  if(value.target.value  ==3)
         {
            this.color =['#ce0000'];   // Rejeitado cor vermelho

         }
         else
         {
            this.color =['#17a08c']; // Defaoult Todos status
         }


      this.carregar_grafico_pagamentos_por_meses(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
      this.carregar_grafico_pagamentos_por_turnos(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
      this.carregar_grafico_estudantes_bolseiros(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
      // ************************************************************/  
      this.carregar_grafico_estudantes_devedores(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
      this.carregar_grafico_estudantes_propinas_pagas(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
    },
    
    search_meses(){
      this.carregar_grafico_estudantes_devedores(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
      this.carregar_grafico_estudantes_propinas_pagas(
        this.ano_lectivo_selecionado,
        this.mes_temp_id,
        this.estado_pagamentos
      );
    },

    carregar_grafico_pagamentos_por_turnos(index, mes_temp_id, estado) {
      this.$Progress.start();
      axios
        .get("/carregar-graficos-pagamentos-por-turnos", {
          params: {
            ano_lectivo: index,
            mes_temp_id: mes_temp_id,
            estado: estado,
          },
        })
        .then((response) => {
          this.utilizadores = response.data.utilizadores;
          
          this.estudante_propinas_pagas = response.data.estudante_propinas_pagas;
          this.total_estudantes = response.data.total_estudantes;
          this.total_estudantes_devedores = response.data.total_estudantes_devedores;

          this.ano_lectivo = response.data.ano_lectivo;
          this.charts_turno = response.data.charts_turno;

          this.$Progress.finish();
        })
        .catch((errors) => {
          this.$Progress.fail();
        });
    },

    carregar_grafico_pagamentos_por_meses(index, mes_temp_id, estado) {
      this.$Progress.start();
      axios
        .get("/carregar-graficos-pagamentos-por-meses", {
          params: {
            ano_lectivo: index,
            mes_temp_id: mes_temp_id,
            estado: estado,
          },
        })
        .then((response) => {
          this.charts_meses = response.data.charts_meses;
          this.mesTemps = response.data.mesTemps;
          
          this.$Progress.finish();
        })
        .catch((errors) => {
          this.$Progress.fail();
        });
    },
        
    carregar_grafico_estudantes_devedores(index, mes_temp_id, estado) {
      this.$Progress.start();
      axios
        .get("/carregar-graficos-estudantes-devedores", {
          params: {
            ano_lectivo: index,
            mes_temp_id: mes_temp_id,
            estado: estado,
          },
        })
        .then((response) => {
          this.total_estudantes_devedores = response.data.total_estudantes_devedores;
          
          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log("Erro ao carregar o graficos estudantes devedores");
          this.$Progress.fail();
        });
    },

    carregar_grafico_estudantes_propinas_pagas(index, mes_temp_id, estado) {
      this.$Progress.start();
      axios
        .get("/carregar-graficos-estudantes-propinas-pagas", {
          params: {
            ano_lectivo: index,
            mes_temp_id: mes_temp_id,
            estado: estado,
          },
        })
        .then((response) => {
          this.estudante_propinas_pagas = response.data.estudante_propinas_pagas;
          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log("Erro ao carregar o graficos estudantes com mensalidade pagas");
          this.$Progress.fail();
        });
    },
    
    carregar_grafico_estudantes_bolseiros(index, mes_temp_id, estado) {
      this.$Progress.start();
      axios
        .get("/carregar-graficos-estudantes-bolseiros", {
          params: {
            ano_lectivo: index,
            mes_temp_id: mes_temp_id,
            estado: estado,
          },
        })
        .then((response) => {
          
          this.bolseiros = response.data.bolseiros;
          this.bolseiros_com_renuncia = response.data.bolseiros_com_renuncia;
          this.bolseiros_sem_renuncia = response.data.bolseiros_sem_renuncia;

          this.$Progress.finish();
        })
        .catch((errors) => {
          this.$Progress.fail();
        });
    },

  },


};
</script>


