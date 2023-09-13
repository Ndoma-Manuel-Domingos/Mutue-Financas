<template>
  <nav class="mt-2">
    <ul
      class="nav nav-pills nav-sidebar flex-column"
      data-widget="treeview"
      role="menu"
      data-accordion="false"
    >
      <li class="nav-item">
        <Link
          :href="route('mf.dashboard')"
          class="nav-link"
          :class="{ active: $page.component == 'Dashboard' }"
        >
          <i class="nav-icon fas fa-home"></i>
          <p>Dashboard</p>
        </Link>
      </li>

      <li class="nav-item" title="CONTROLO DE PAGAMENTO DE MENSALIDADES">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
            Área Financeira
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
        
          <template v-if='user.type_user == "Validação de Pagamentos" '>
          
            <li class="nav-item" title="CONSULTAR NÚMEROS DE OPERAÇÕES">
                <Link
                  :href="route('mf.consultar-operacao')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/ConsultarOperacao',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Consult. Nº Operação</p>
                </Link>
            </li>

            <li class="nav-item" title="PAGAMENTOS POR VALIDAR">
              <Link
                :href="route('mf.pagamento-por-validar')"
                class="nav-link"
                :class="{
                  active: $page.component == 'AreaFinanceira/PagamentoPorValidar',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Pagamentos por Validar</p>
              </Link>
            </li>
            
            <li class="nav-item">
                <Link
                  :href="route('mf.talao-desuso')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/TalaoEmDesuso',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Talão em Desuso</p>
                </Link>
              </li>
          </template>
          
          <template v-else>
            
              <li class="nav-item" title="ESTUDANTES COM MENSALIDADES A PAGAR">
                <Link
                  :href="route('mf.estudante-propina-paga')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component == 'AreaFinanceira/EstudantePropinasPaga',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Estud. Mensalidades Paga</p>
                </Link>
              </li>
              
              <li class="nav-item" title="ESTUDANTES DEVEDORES">
                <Link
                  :href="route('mf.estudante-devedores')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/EstudanteDevedores',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Estudantes Devedores</p>
                </Link>
              </li>
    
              <li class="nav-item" title="ESTUDANTES INACTIVOS">
                <Link
                  :href="route('mf.estudante-inactivos')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/EstudanteInactivo',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Estudantes Inactivos</p>
                </Link>
              </li>
    
              <li class="nav-item" title="ESTUDANTES FINALISTAS INACTIVOS">
                <Link
                  :href="route('mf.estudante-finalista-inactivos')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'AreaFinanceira/EstudanteFinalistaInactivo',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Estud. Finalistas Inactivos</p>
                </Link>
              </li>
    
              <li class="nav-item" title="CONSULTAR NÚMEROS DE OPERAÇÕES">
                <Link
                  :href="route('mf.consultar-operacao')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/ConsultarOperacao',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Consult. Nº Operação</p>
                </Link>
              </li>
    
              <li class="nav-item" title="CONTROLO DE ACTUALIZAÇÕES DE SALDO">
                <Link
                  :href="route('mf.controle-actualizacao-saldo')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component == 'AreaFinanceira/ControleActualizacaoSaldo',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Controlo Actual. Saldo</p>
                </Link>
              </li>
    
              <li class="nav-item" title="PAGAMENTOS POR VALIDAR">
                <Link
                  :href="route('mf.pagamento-por-validar')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/PagamentoPorValidar',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pagamentos por Validar</p>
                </Link>
              </li>
    
              <li class="nav-item">
                <Link
                  :href="route('mf.talao-desuso')"
                  class="nav-link"
                  :class="{
                    active: $page.component == 'AreaFinanceira/TalaoEmDesuso',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Talão em Desuso</p>
                </Link>
              </li>
    
              <li class="nav-item">
                <Link
                  :href="route('mf.servicos-emonumentos.index')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component == 'AreaFinanceira/ServicoEmonumento/Index',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Serviços e Emolumen.</p>
                </Link>
              </li>
          </template>


        </ul>
      </li>

        <li class="nav-item">
          <a
            href="#"
            class="nav-link"
            title="PAGAMENTOS CORRENTES"
            :class="{
              active: $page.component.startsWith('GestaoCreditoInstituicional/'),
            }"
          >
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
              Gestão de Crédito Educacional
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          
            <template v-if='user.type_user == "Validação de Pagamentos" '>
              <li class="nav-item" title="LISTAR BOLSEIROS">
                <Link
                  :href="route('mf.listar-bolseiros')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'GestaoCreditoInstituicional/ListarBolseiros',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listar Bolseiros</p>
                </Link>
              </li>
            </template>
            
            <template v-else>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-home nav-icon"></i>
                  <p>
                    Instituições
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <Link
                      :href="route('instituicoes-renuncia.index')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'GestaoCreditoInstituicional/Renuncia/Index',
                      }"
                    >
                      <i class="fas fa-building nav-icon"></i>
                      <p>Com Renúncias</p>
                    </Link>
                  </li>
                  <li class="nav-item">
                    <Link
                      :href="route('instituicoes-sem-renuncia.index')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'GestaoCreditoInstituicional/Sem-Renuncia/Index',
                      }"
                    >
                      <i class="far fa-building nav-icon"></i>
                      <p>Sem Renúncias</p>
                    </Link>
                  </li>
    
                  <li class="nav-item">
                    <Link
                      :href="route('todos-instituicoes.index')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'GestaoCreditoInstituicional/TodasInstiuicoes/Index',
                      }"
                    >
                      <i class="far fa-building nav-icon"></i>
                      <p>Todas Instituições</p>
                    </Link>
                  </li>
                </ul>
              </li>
    
              <li class="nav-item">
                <Link
                  :href="route('tipo-bolsas.index')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'GestaoCreditoInstituicional/Tipobolsa/Index',
                  }"
                >
                  <i class="fas fa-folder nav-icon"></i>
                  <p>Tipo Bolsas</p>
                </Link>
              </li>
    
              <li class="nav-item" title="MENSALIDADES (PREÇÁRIO)">
                <Link
                  :href="route('mf.atribuicao-bolsa')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component == 'GestaodeBolsaeDesconto/AtribuicaoBolsa',
                  }"
                >
                  <i class="fas fa-user-graduate nav-icon"></i>
                  <p>Atribuição de Bolsa</p>
                </Link>
              </li>
    
              <!-- <li class="nav-item" title="PERCENTAGEM DE APROVEITAMENTO">
                <Link
                  :href="route('mf.percentagem-aproveitamento')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'GestaoCreditoInstituicional/Percentagem/Index',
                  }"
                >
                  <i class="fas fa-percent nav-icon"></i>
                  <p>Percentagem de Aproveitamento</p>
                </Link>
              </li> -->
    
              <li class="nav-item" title="LISTAR BOLSEIROS">
                <Link
                  :href="route('mf.listar-bolseiros')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'GestaoCreditoInstituicional/ListarBolseiros',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listar Bolseiros</p>
                </Link>
              </li>
            
            </template>
            
          </ul>
        </li>


      <template v-if='user.type_user != "Validação de Pagamentos" '>
        <li class="nav-item">
          <a
            href="#"
            class="nav-link"
            title="GESTAO DE BOLSA E DESCONTO"
            :class="{
              active: $page.component.startsWith('GestaodeBolsaeDesconto'),
            }"
          >
            <i class="nav-icon fa fa-users"></i>
            <p>
              Gestão de Descontos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" title="ATRIBUIR DESCONTO">
              <Link
                :href="route('mf.atribuicao-desconto')" 
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'GestaodeBolsaeDesconto/AtribuicaoDesconto',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Atribuição de Desconto</p>
              </Link>
            </li>
  
            <li class="nav-item" title="LISTAR DESCONTO">
              <Link
                :href="route('mf.Listar-Desconto')"
                class="nav-link"
                :class="{
                  active:
                    $page.component == 'GestaodeBolsaeDesconto/ListarDesconto',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Listar Descontos</p>
              </Link>
            </li>

          </ul>
        </li>
      </template>
      
      
      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="FECHO DE CAIXA"
          :class="{
            active: $page.component.startsWith('RelatoriosPagamentos/Caixa'),
          }"
        >
          <i class="nav-icon fas fa-box"></i>
          <p>
            Fecho de Caixa
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          
          <template v-if='user.type_user == "Validação de Pagamentos" '>
            <li class="nav-item" title="FECHO DO CAIXA POR UTILIZADOR">
              <Link
                :href="route('mf.fecho-caixa-utilizador')"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/Caixa/FechoCaixaUtilizador',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Utilizador</p>
              </Link>
            </li>
          </template>
          
          <template v-else>
            <li class="nav-item" title="FECHO DO CAIXA DIÁRIO">
              <Link
                :href="route('mf.fecho-caixa')"
                class="nav-link"
                :class="{
                  active: $page.component == 'RelatoriosPagamentos/Caixa/FechoCaixa',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Diário</p>
              </Link>
            </li>
  
            <li class="nav-item" title="FECHO DO CAIXA GERAL">
              <Link
                :href="route('mf.fecho-caixa-geral')"
                class="nav-link"
                :class="{
                  active:
                    $page.component == 'RelatoriosPagamentos/Caixa/FechoCaixaGeral',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Geral</p>
              </Link>
            </li>
  
            <li class="nav-item" title="FECHO DO CAIXA POR UTILIZADOR">
              <Link
                :href="route('mf.fecho-caixa-utilizador')"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/Caixa/FechoCaixaUtilizador',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Utilizador</p>
              </Link>
            </li>
          
          </template>
        
        </ul>
      </li>

        <li class="nav-item">
          <a
            href="#"
            class="nav-link"
            title="RELATÓRIOS FINANCEIROS"
            :class="{
              active: $page.component.startsWith('RelatoriosPagamentos/'),
            }"
          >
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
              Relatórios
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <!-- <li class="nav-item" title="FECHO DO CAIXA">
              <Link
                :href="route('mf.fecho-caixa')"
                class="nav-link"
                :class="{
                  active: $page.component == 'RelatoriosPagamentos/FechoCaixa',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa</p>
              </Link>
            </li>
  
            <li class="nav-item" title="FECHO DO CAIXA GERAL">
              <Link
                :href="route('mf.fecho-caixa-geral')"
                class="nav-link"
                :class="{
                  active:
                    $page.component == 'RelatoriosPagamentos/FechoCaixaGeral',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Geral</p>
              </Link>
            </li>
  
  
            <li class="nav-item" title="FECHO DO CAIXA POR UTILIZADOR">
              <Link
                :href="route('mf.fecho-caixa-utilizador')"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/FechoCaixaUtilizador',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Utilizador</p>
              </Link>
            </li> -->
                  
            <template v-if='user.type_user == "Validação de Pagamentos" '>
                
              <li class="nav-item">
                <a
                  href="#"
                  class="nav-link"
                  :class="{
                    active: $page.component.startsWith(
                      'RelatoriosPagamentos/Estudantes'
                    ),
                  }"
                >
                  <i class="fas fa-user-graduate nav-icon"></i>
                  <p>
                    Estudantes Matriculadas
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <Link
                      :href="route('mf.relatorio-listar-estudantes')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'RelatoriosPagamentos/Estudantes/ListarEstudantes',
                      }"
                    >
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listar Todos</p>
                    </Link>
                  </li>
  
                </ul>
              </li>
                
            
            </template>
            
            <template v-else>
              <li class="nav-item">
                <Link
                  :href="route('mf.listar-estudante-isento')"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'RelatoriosPagamentos/ListarEstudanteIsento',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listar Estudantes Isento</p>
                </Link>
              </li>
    
              <li class="nav-item">
                <a
                  href="#"
                  class="nav-link"
                  :class="{
                    active: $page.component.startsWith(
                      'RelatoriosPagamentos/Estudantes'
                    ),
                  }"
                >
                  <i class="fas fa-user-graduate nav-icon"></i>
                  <p>
                    Estudantes Matriculadas
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <Link
                      :href="route('mf.relatorio-listar-estudantes')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'RelatoriosPagamentos/Estudantes/ListarEstudantes',
                      }"
                    >
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listar Todos</p>
                    </Link>
                  </li>
    
                  <li class="nav-item">
                    <Link
                      :href="route('mf.listar-estudante-finalista')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'RelatoriosPagamentos/Estudantes/EstudanteFinalistas',
                      }"
                    >
                      <i class="far fa-circle nav-icon"></i>
                      <p>Estudantes Finalista</p>
                    </Link>
                  </li>
    
                  <li class="nav-item">
                    <Link
                      :href="route('mf.listar-estudantes-credito-instituicao')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'RelatoriosPagamentos/Estudantes/ListarEstudantesCreditoInstituicao',
                      }"
                    >
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listar Estudantes com crédito institucional</p>
                    </Link>
                  </li>
    
                  <li class="nav-item">
                    <Link
                      :href="route('mf.relatorio-listar-estudantes-desconto')"
                      class="nav-link"
                      :class="{
                        active:
                          $page.component ==
                          'RelatoriosPagamentos/Estudantes/ListarEstudantesDesconto',
                      }"
                    >
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listar estudantes com Desconto</p>
                    </Link>
                  </li>
                </ul>
              </li>
            </template>
          </ul>
        </li>
  

      <!-- <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="GESTÃO DE PERMISSÕES"
          :class="{
            active: $page.component.startsWith('GestaoPermissions/'),
          }"
        >
          <i class="nav-icon fas fa-lock"></i>
          <p>
            Gestão Permissões
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="PERFIL">
            <Link
              :href="route('roles.index')"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'GestaoPermissions/roles/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Perfils</p>
            </Link>
          </li>

          <li class="nav-item" title="PERMISSÕES">
            <Link
              :href="route('permissions.index')"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'GestaoPermissions/permissions/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Permissões</p>
            </Link>
          </li>

        </ul>
      </li> -->

      <li class="nav-item" title="AJUDA">
        <Link
          href="/ajuda-sistema"
          class="nav-link"
          :class="{
            active: $page.component == 'Ajuda/Index',
          }"
        >
          <i class="fas fa-question-circle" aria-hidden="true"> </i>
          Ajuda
        </Link>
      </li>

      <div class="ml-auto">
        <ul class="navbar-nav">
          <li class="nav-item text-left">
            <Link
              class="nav-link btn btn-link btn-danger text-white"
              href="/logout"
              method="post"
              as="button"
              type="button"
            >
              <i class="fas fa-sign-out-alt"></i>
              Sair
            </Link>
          </li>
        </ul>
      </div>
    </ul>
  </nav>
</template>

<script setup>
  import { computed } from "vue";
  import { usePage } from "@inertiajs/inertia-vue3";
  import { Link } from "@inertiajs/inertia-vue3";
  const user = computed(() => {
    return usePage().props.value.auth.user;
  });
</script>


<style>
.nav-pills .nav-link.active,
.nav-pills .show > .nav-link {
  color: #fff;
  background-color: #52c7ed;
}
</style>
