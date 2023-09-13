<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">PERFIL DO ESTUDANTE</h4>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card card-info card-outline">
              <div class="card-body box-profile">
                <div class="row">
                  <div class="col-12 col-md-4">
                    <div class="text-center">
                      <img
                        class="profile-user-img img-fluid img-circle"
                        alt="User profile picture"
                      />
                      <!-- src="http://mutue.co.ao/mutue/resources/img/avatar.png?pfdrid_c=true" -->
                    </div>

                    <h3 class="profile-username text-center">
                      {{ estudante.nome }}
                    </h3>
                    <p class="profile-username text-center">
                      Nº: {{ estudante.codigo }}
                    </p>

                    <p class="text-muted text-center">Situação Actual</p>
                    <p class="text-muted text-center text-uppercase">
                      <span :style="estilo_cor_estado_estudante">{{ designacao_estado_estudante }}</span>
                    <!-- {{ estado_estudante.designacao }} -->
                    </p>
                    <!-- :href="
                          'https://mutue.ao/storage/documentos/' +
                          pagamento.nome_documento
                        " -->
                  </div>

                  <div class="col-12 col-md-8">
                    <ul class="list-group list-group-unbordered mb-3">
                      <li class="list-group-item">
                        <b>Curso:</b>
                        <a class="float-right">{{ estudante.curso }}</a>
                      </li>
                      <li class="list-group-item">
                        <b>Campus:</b>
                        <a class="float-right">{{ estudante.campus }}</a>
                      </li>
                      <li class="list-group-item">
                        <b>Ano Curricular:</b>
                        <a class="float-right">{{ ano_curricular }}</a>
                      </li>

                      <li class="list-group-item">
                        <b>Tipo de Estudante:</b>
                        <a class="float-right">
                            
                          <div class="col s12 m6" v-if="bolseiro" style="float: right">
                            <template v-if="bolseiro.desconto == 100">
                              <b
                                >{{ this.estudante_tipo4.designacao }} ({{
                                  estudante_tipo4.descricao
                                }})</b
                              >
                            </template>
                            <template
                              v-else-if="
                                bolseiro.desconto < 100 &&
                                bolseiro.codigo_tipo_bolsa != 32
                              "
                            >
                              <b
                                >{{ this.estudante_tipo3.designacao }} ({{
                                  estudante_tipo3.descricao
                                }}
                                - {{ bolseiro.desconto }}%)</b
                              >
                            </template>
                            <template
                              v-else-if="
                                bolseiro.desconto < 100 &&
                                bolseiro.codigo_tipo_bolsa == 32
                              "
                            >
                              <b style="font-size: 10"
                                >{{ this.estudante_tipo2.designacao }} ({{
                                  estudante_tipo2.descricao
                                }}
                                - {{ bolseiro.desconto }}%)</b
                              >
                            </template>
                          </div>
                          
                          <div class="col s12 m6" v-else style="float: right">
                            <b
                              >{{ estudante_tipo1.designacao }} ({{
                                estudante_tipo1.descricao
                              }})</b
                            >
                          </div>
                        
                        </a>
                      </li>
                    </ul>

                    <div class="info-box shadow-none">
                      <span class="info-box-icon bg-info"
                        ><i class="fas fa-book"></i
                      ></span>
                      <div class="info-box-content" v-if="bolseiro">
                        <span class="info-box-text text-uppercase"
                          ><strong
                            >Este estudante é um bolseiro(A).</strong
                          ></span
                        >
                        <span class="info-box-text"
                          >Bolsa:
                          <strong class="">{{ bolseiro.designacao }}</strong>
                          que pertence a Instituição:
                          <strong>{{ bolseiro.Instituicao }}</strong> com o
                          Desconto de <strong>{{ bolseiro.desconto }}%.</strong>
                        </span>
                      </div>

                      <div class="info-box-content" v-else>
                        <span class="info-box-text text-uppercase"
                          ><strong
                            >Este estudante não é um bolseiro(A).</strong
                          ></span
                        >
                        <span class="info-box-text"
                          >Estudante
                          <strong>{{ admissao.resultado }}</strong> com a média
                          <strong>{{ admissao.mediaFinal }}</strong
                          >. Data e hora da Admissão :
                          <strong>{{ formatData(admissao.data) }}</strong></span
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>

            <div class="card card-info card-outline">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link active" href="#perfil" data-toggle="tab">
                      <i class="fa fa-user"></i> Perfil</a
                    >
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="#areafinaceira" data-toggle="tab">
                      <i class="fa fa-credit-card"></i> Área Financeira
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="perfil">
                    <h4>Informações Básicas</h4>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-5 col-sm-2">
                          <div
                            class="nav flex-column nav-tabs h-100"
                            id="vert-tabs-tab"
                            role="tablist"
                            aria-orientation="vertical"
                          >
                            <a
                              class="nav-link"
                              id="vert-tabs-profile-tab"
                              data-toggle="pill"
                              href="#ano_lectivos"
                              role="tab"
                              aria-controls="ano_lectivos"
                              aria-selected="false"
                              >Pagamentos/Parcelas</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-profile-tab"
                              data-toggle="pill"
                              href="#dados_telefinicos"
                              role="tab"
                              aria-controls="dados_telefinicos"
                              aria-selected="false"
                              >Contactos</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-messages-tab"
                              data-toggle="pill"
                              href="#dados_pessoais"
                              role="tab"
                              aria-controls="dados_pessoais"
                              aria-selected="false"
                              >Dados Pessoais</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              href="#dados_messagem"
                              role="tab"
                              aria-controls="dados_messagem"
                              aria-selected="false"
                              >Mensagens</a
                            >

                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              href="#dados_inscricao"
                              role="tab"
                              aria-controls="dados_inscricao"
                              aria-selected="false"
                              >Ver Inscrições
                            </a>
                          </div>
                        </div>
                        <div class="col-7 col-sm-10">
                          <div class="tab-content" id="vert-tabs-tabContent">
                            <div
                              class="tab-pane text-left fade show active"
                              id="ano_lectivos"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-home-tab"
                            >
                              <div class="table-responsive">
                                <table
                                  class="table-sm table-hover table-bordered table-striped"
                                  style="width: 100%"
                                >
                                  <thead>
                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        1
                                      "
                                    >
                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-left"
                                          colspan="7"
                                        >
                                          Clica na parcela para ver mais
                                          detalhes
                                        </td>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-right"
                                          colspan="6"
                                        >
                                          Valor da mensalidade actual do
                                          estudante:
                                          {{ formatValor(preco_curso.Preco) }}
                                        </td>
                                      </tr>

                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-center"
                                        >
                                          Ano Lectivo
                                        </td>
                                        <td
                                          v-for="(
                                            mes_temp, index
                                          ) in prestacoes"
                                          :key="mes_temp.id"
                                          style="
                                            background-color: #e3f2fd;
                                            text-align: center;
                                          "
                                        >
                                          {{ ++index }}-{{ prestacoes.length }}
                                        </td>
                                      </tr>
                                    </template>

                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        2
                                      "
                                    >
                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-left"
                                          colspan="6"
                                        >
                                          Clica na parcela para ver mais
                                          detalhes
                                        </td>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-left"
                                          colspan="6"
                                        >
                                          <div class="col-12">
                                            <select
                                              name=""
                                              id=""
                                              class="form-control form-control-sm  form-control-sm"
                                            >
                                              <option value="">
                                                Definir Ciclo
                                              </option>
                                              <option value="">1</option>
                                              <option value="">2</option>
                                            </select>
                                          </div>
                                        </td>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-right"
                                          colspan="13"
                                        >
                                          Valor da mensalidade actual do
                                          estudante:
                                          {{ formatValor(preco_curso.Preco) }}
                                        </td>
                                      </tr>

                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-center"
                                        >
                                          Ciclo Mestrado
                                        </td>
                                        <td
                                          v-for="(
                                            mes_temp, index
                                          ) in prestacoes"
                                          :key="mes_temp.id"
                                          style="
                                            background-color: #e3f2fd;
                                            text-align: center;
                                          "
                                        >
                                          {{ ++index }}-{{ prestacoes.length }}
                                        </td>
                                      </tr>
                                    </template>

                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        3
                                      "
                                    >
                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-left"
                                          colspan="24"
                                        >
                                          Clica na parcela para ver mais
                                          detalhes
                                        </td>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-right"
                                          colspan="24"
                                        >
                                          Valor da mensalidade actual do
                                          estudante:
                                          {{ formatValor(preco_curso.Preco) }}
                                        </td>
                                      </tr>

                                      <tr>
                                        <td
                                          style="background-color: #e3f2fd"
                                          class="text-center"
                                        >
                                          Ciclo Doutoramento
                                        </td>
                                        <td
                                          v-for="(
                                            mes_temp, index
                                          ) in prestacoes"
                                          :key="mes_temp.id"
                                          style="
                                            background-color: #e3f2fd;
                                            text-align: center;
                                          "
                                        >
                                          {{ ++index }}-{{ prestacoes.length }}
                                        </td>
                                      </tr>
                                    </template>
                                  </thead>

                                  <tbody>
                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        1
                                      "
                                    >
                                      <tr>
                                        <td class="text-center py-4">
                                          {{
                                            anolectivosinscritosactual.Designacao
                                          }}
                                        </td>
                                        
                                        <td
                                          v-for="mes_temp in prestacoes"
                                          :key="mes_temp.id"
                                          class="text-center py-4 fs-1"
                                          :class="(mes_temp.bolseiro && (mes_temp.bolseiro.desconto == 100)) ? 'Bolseiro 100%' : (mes_temp.factura_item ? mes_temp.factura_item.valor_pago == 0 ? 'nao_pago' : 'pago' : mes_temp.suspenso ? 'isento' : 'nao_pago')"
                                        >
                                          {{ mes_temp.designacao }} <br />
                                          {{
                                            (mes_temp.bolseiro && (mes_temp.bolseiro.desconto == 100)) ? 'pago' : (mes_temp.factura_item ? formatValor( mes_temp.factura_item .valor_pago ) : mes_temp.suspenso ? "Suspenso" : "0.00")
                                          }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-center py-4">
                                          {{
                                            anolectivosinscritosanterior.Designacao
                                          }}
                                        </td>
                                        <td
                                          v-for="mes_temp_anterior in prestacoes_anterior"
                                          :key="mes_temp_anterior.id" class="text-center py-4 fs-1"
                                          :class="(mes_temp_anterior.bolseiro && (mes_temp_anterior.bolseiro.desconto == 100)) ? 'pago' :  (mes_temp_anterior.factura_item_anterior ? mes_temp_anterior.factura_item_anterior.valor_pago == 0 ? 'nao_pago' : 'pago': mes_temp_anterior.suspenso_anterior? 'isento': 'nao_pago')"
                                        >
                                          {{ mes_temp_anterior.designacao }}
                                          <br />
                                          {{
                                            (mes_temp_anterior.bolseiro && (mes_temp_anterior.bolseiro.desconto == 100)) ? 'Bolseiro 100%' :  (mes_temp_anterior.factura_item_anterior ? formatValor( mes_temp_anterior .factura_item_anterior .valor_pago ) : mes_temp_anterior.suspenso_anterior ? "Isento" : "0.00")
                                          }}
                                        </td>
                                      </tr>
                                    </template>

                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        2
                                      "
                                    >
                                      <tr>
                                        <td class="text-center py-4">
                                          Ciclo Mestrado
                                        </td>
                                        <td
                                          v-for="mes_temp in prestacoes"
                                          :key="mes_temp.id"
                                          class="text-center py-4 fs-1"
                                          :class="
                                            mes_temp.factura_item
                                              ? mes_temp.factura_item
                                                  .valor_pago == 0
                                                ? 'nao_pago'
                                                : 'pago'
                                              : mes_temp.suspenso
                                              ? 'pago'
                                              : 'nao_pago'
                                          "
                                        >
                                          {{ mes_temp.designacao }} <br />
                                          {{
                                            mes_temp.factura_item
                                              ? formatValor(
                                                  mes_temp.factura_item
                                                    .valor_pago
                                                )
                                              : mes_temp.suspenso
                                              ? "Pagamento suspenso"
                                              : "0.00"
                                          }}
                                        </td>
                                      </tr>
                                    </template>

                                    <template
                                      v-if="
                                        preinscricao.codigo_tipo_candidatura ==
                                        3
                                      "
                                    >
                                      <tr>
                                        <td class="text-center py-4">
                                          Ciclo Doutoramento
                                        </td>
                                        <td
                                          v-for="mes_temp in prestacoes"
                                          :key="mes_temp.id"
                                          class="text-center py-4 fs-1"
                                          :class="
                                            mes_temp.factura_item
                                              ? mes_temp.factura_item
                                                  .valor_pago == 0
                                                ? 'nao_pago'
                                                : 'pago'
                                              : mes_temp.suspenso
                                              ? 'pago'
                                              : 'nao_pago'
                                          "
                                        >
                                          {{ mes_temp.designacao }} <br />
                                          {{
                                            mes_temp.factura_item
                                              ? formatValor(
                                                  mes_temp.factura_item
                                                    .valor_pago
                                                )
                                              : mes_temp.suspenso
                                              ? "Pagamento suspenso"
                                              : "0.00"
                                          }}
                                        </td>
                                      </tr>
                                    </template>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div
                              class="tab-pane fade"
                              id="dados_telefinicos"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-profile-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  Contactos Telefonicos
                                </div>
                                <form class="form-horizontal">
                                  <div class="card-body">
                                    <div class="form-group row">
                                      <label
                                        class="col-12 col-sm-3 col-form-label"
                                        >Telefone Principal:</label
                                      >
                                      <div class="col-12 col-sm-5">
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="estudante.contacto_principal"
                                        ></div>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label
                                        class="col-12 col-sm-3 col-form-label"
                                        >Telefone Alternativo:</label
                                      >
                                      <div class="col-sm-5 col-12">
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.contacto_alternativo
                                          "
                                        ></div>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label
                                        class="col-sm-3 col-12 col-form-label"
                                        >Email:</label
                                      >
                                      <div class="col-sm-5 col-12">
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="estudante.email"
                                        ></div>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div
                              class="tab-pane fade"
                              id="dados_pessoais"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-messages-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  Dados Pessoais
                                </div>
                                <form class="form-horizontal">
                                  <div
                                    class="card-body"
                                    style="overflow: scroll; height: 250px"
                                  >
                                    <div class="row">
                                      <div class="form-group col-3">
                                        <label class="col-form-label"
                                          >Nome Completo:</label
                                        >
                                        <div
                                          type="text"
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.nome ?? 'sem-registro'
                                          "
                                          placeholder="Telefone Principal"
                                        ></div>
                                      </div>
                                      <div class="form-group col-2">
                                        <label class="col-form-label"
                                          >Data de Nascimento:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Data_Nascimento ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-2">
                                        <label class="col-form-label"
                                          >Gênero:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Genero ?? 'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-2">
                                        <label class="col-form-label"
                                          >Número BI:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Numero_BI ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-2">
                                        <label class="col-form-label"
                                          >Data de Emissão:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Data_Emissao ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>
                                      <div class="form-group col-2">
                                        <label class="col-form-label"
                                          >Data de Validade:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Data_Validade ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Nacionalidade:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Nacionalidade ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-3">
                                        <label class="col-form-label"
                                          >Nome do Pai:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Nome_Pai ?? 'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-3">
                                        <label class="col-form-label"
                                          >Nome da Mãe:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Nome_Mae ?? 'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Profissão:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Profissao ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Ocupação:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Ocupacao ?? 'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Naturalidade:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Naturalidade ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Província:</label
                                        >
                                        <div class="form-control form-control-sm "></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Município:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.municipio ??
                                            'sem-registro'
                                          "
                                        ></div>
                                      </div>

                                      <div class="form-group col-12 col-md-2">
                                        <label class="col-form-label"
                                          >Morada:</label
                                        >
                                        <div
                                          class="form-control form-control-sm "
                                          v-html="
                                            estudante.Morada ?? 'sem-registro'
                                          "
                                        ></div>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div
                              class="tab-pane fade"
                              id="dados_messagem"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-home-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  Mensagem
                                  <button
                                    type="button"
                                    class="btn-sm btn-light float-right"
                                    data-toggle="modal"
                                    data-target="#modalMessagem"
                                  >
                                    Escrever nova mensagem
                                  </button>
                                </div>
                              </div>
                            </div>
                            <div
                              class="tab-pane fade"
                              id="dados_inscricao"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-settings-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  <h6>Filtros e Outras Opções</h6>
                                </div>

                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-5">
                                      Total Registro: {{ anolectivos.total }}
                                    </div>
                                    <select
                                      v-model="ano"
                                      @change="carregar_inscricoes"
                                      class="form-control form-control-sm  col-12 col-md-5"
                                    >
                                      <option value="">TODOS</option>
                                      <option
                                        :value="anos.Codigo"
                                        v-for="anos in anolectivos"
                                        :key="anos.Codigo"
                                      >
                                        {{ anos.Designacao }}
                                      </option>
                                    </select>

                                    <div class="col-12 mt-4">
                                      <div
                                        class="overflow-auto"
                                        style="height: 300px"
                                      >
                                        <div class="table-responsive">
                                          <table
                                            class="table-sm table-hover table-bordered table-striped"
                                            style="width: 100%"
                                          >
                                            <thead>
                                              <tr>
                                                <th>Ref</th>
                                                <th>Nome</th>
                                                <th>Ano</th>
                                                <th>Duração</th>
                                                <th>Período</th>
                                                <th>Horário</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr
                                                v-for="item in inscricoes"
                                                :key="item"
                                              >
                                                <td>{{ item.codigo ?? '' }}</td>
                                                <td>{{ item.disciplina ?? '' }}</td>
                                                <td>{{ item.classe ?? '' }}</td>
                                                <td>{{ item.duracao ?? '' }}</td>
                                                <td>{{ item.semestre ?? '' }}</td>
                                                <td>
                                                  {{
                                                    item.horario ??
                                                    "--Selecção de horário pendente!--"
                                                  }}
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="areafinaceira">
                    <h4>Área Financeira</h4>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-5 col-sm-3">
                          <div
                            class="nav flex-column nav-tabs h-100"
                            id="vert-tabs-tab"
                            role="tablist"
                            aria-orientation="vertical"
                          >
                            <a
                              class="nav-link active"
                              id="vert-tabs-home-tab"
                              data-toggle="pill"
                              href="#vert-tabs-extratos"
                              role="tab"
                              aria-controls="vert-tabs-home"
                              aria-selected="true"
                              >Extratos</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-home-tab"
                              data-toggle="pill"
                              href="#vert-tabs-actualizarSaldo"
                              role="tab"
                              aria-controls="vert-tabs-home"
                              aria-selected="true"
                              >Actualizar Saldo(Zerar)</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-profile-tab"
                              data-toggle="pill"
                              @click="carregar_historicos_actualizacoes"
                              href="#vert-tabs-historioSaldo"
                              role="tab"
                              aria-controls="vert-tabs-profile"
                              aria-selected="false"
                              >Histórico Actualização De Saldo</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              href="#vert-tabs-facturas"
                              role="tab"
                              aria-controls="vert-tabs-settings"
                              aria-selected="false"
                              >Facturas</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-messages-tab"
                              data-toggle="pill"
                              @click="carregar_lista_isencoes_pagamentos"
                              href="#vert-tabs-isencaoMulta"
                              role="tab"
                              aria-controls="vert-tabs-messages"
                              aria-selected="false"
                              >Isenção de Multa
                            </a>
                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              @click="carregar_lista_isencoes_pagamentos"
                              href="#vert-tabs-isencaoPagemento"
                              role="tab"
                              aria-controls="vert-tabs-settings"
                              aria-selected="false"
                              >Isenção de Pagamento</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              href="#vert-tabs-pagamentos"
                              role="tab"
                              aria-controls="vert-tabs-settings"
                              aria-selected="false"
                              >Pagamentos</a
                            >
                            <a
                              class="nav-link"
                              id="vert-tabs-settings-tab"
                              data-toggle="pill"
                              href="#vert-tabs-servicopago"
                              role="tab"
                              aria-controls="vert-tabs-settings"
                              aria-selected="false"
                              >Serviço Pago(Histórico)</a
                            >
                          </div>
                        </div>
                        <div class="col-7 col-sm-9">
                          <div class="tab-content" id="vert-tabs-tabContent">
                            <div
                              class="tab-pane text-left fade show active"
                              id="vert-tabs-extratos"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-home-tab"
                            >
                              <div class="card shadow-none bg-light">
                                <div class="card-header bg-info">
                                  <span class="float-left"
                                    >LISTA DE MOVIMENTOS
                                    <strong>{{
                                      total_lista_movimentos
                                    }}</strong>
                                  </span>
                                  <span class="float-right"
                                    >Saldo Disponível: AOA 0.00</span
                                  >
                                </div>

                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-3">
                                      <label for="">Operador</label>
                                      <select
                                        v-model="operador_extratos"
                                        class="form-control form-control-sm "
                                        @change="carregar_tipo_movimentos"
                                      >
                                        <option value="">TODOS</option>
                                        <option
                                          :value="
                                            utilizador.utilizadores
                                              .codigo_importado
                                          "
                                          v-for="utilizador in utilizadores"
                                          :key="utilizador.Codigo"
                                        >
                                          {{ utilizador.utilizadores.nome }}
                                        </option>
                                      </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                      <label for="">Tipo de Movimento</label>
                                      <select
                                        v-model="tipo_movimentos_extrato"
                                        class="form-control form-control-sm "
                                        @change="carregar_tipo_movimentos"
                                      >
                                        <option value="">TODOS</option>
                                        <option
                                          :value="movimento.Codigo"
                                          v-for="movimento in tipos_movimentos"
                                          :key="movimento.Codigo"
                                        >
                                          {{ movimento.designacao }}
                                        </option>
                                      </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                      <label for="">Data Inicio</label>
                                      <input
                                        type="date"
                                        @keyup.enter="carregar_tipo_movimentos"
                                        v-model="data_inicio_extrato"
                                        class="form-control form-control-sm "
                                      />
                                    </div>

                                    <div class="col-12 col-md-3">
                                      <label for="">Data Final</label>
                                      <input
                                        type="date"
                                        @keyup.enter="carregar_tipo_movimentos"
                                        v-model="data_final_extrato"
                                        class="form-control form-control-sm "
                                      />
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="card shadow-none">
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="height: 200px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Recibo</th>
                                            <th>Factura</th>
                                            <th>Tipo de Movimento</th>
                                            <th>Data</th>
                                            <th>Motivo</th>
                                            <th>Observação</th>
                                            <th>Saldo da Operação</th>
                                            <th>Credito/Debito</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr
                                            v-for="lista in lista_movimentos"
                                            :key="lista.codigo"
                                          >
                                            <td>
                                              {{
                                                lista.codigo
                                                  ? lista.codigo
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.Factura
                                                  ? lista.Factura
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.tipo_movimento.designacao
                                                  ? lista.tipo_movimento
                                                      .designacao
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.data_movimento
                                                  ? lista.data_movimento
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.codigo
                                                  ? lista.codigo
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.observacao
                                                  ? lista.observacao
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                lista.saldo_operacao
                                                  ? lista.saldo_operacao
                                                  : " --- "
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                formatValor(
                                                  lista.debito
                                                    ? lista.debito
                                                    : "0"
                                                )
                                              }}
                                            </td>
                                          </tr>
                                        </tbody>
                                        <tfoot>
                                          <tr>
                                            <td>Total</td>
                                            <td colspan="8" class="text-right">
                                              {{
                                                formatValor(
                                                  lista_movimentos_soma
                                                )
                                              }}
                                            </td>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                  </div>
                                </div>

                                <div class="card-footer">
                                  <Link href="" class="text-secondary">
                                    TOTAL REGISTROS:
                                    {{ lista_movimentos.total }}
                                  </Link>
                                  <!-- <Paginacao
                                                                  :links="lista_movimentos.links"
                                                                  :prev="lista_movimentos.prev_page_url"
                                                                  :next="lista_movimentos.next_page_url"
                                                              /> -->
                                </div>
                              </div>
                            </div>

                            <div
                              class="tab-pane fade"
                              id="vert-tabs-actualizarSaldo"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-home-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  ACTUALIZAR SALDO
                                </div>
                                <div class="card-body" style="height: 300px">
                                  <div class="row">
                                    <div class="col-12 col-md-12">
                                      <label for="">Saldo Actual</label>
                                      <div class="row">
                                        <div class="col-12 col-md-6">
                                          <input
                                            type="number"
                                            class="form-control form-control-sm  mt-2"
                                            v-model="
                                              form_actualizar_saldo.saldo_actual
                                            "
                                            placeholder="Saldo Actual"
                                          />
                                        </div>
                                        <div class="col-12 col-md-6">
                                          <span
                                            class="text-danger text-middle"
                                            >{{
                                              form_actualizar_saldo.errors
                                                .saldo_actual
                                            }}</span
                                          >
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                      <label for="">Actualizar Para:</label>
                                      <div class="row">
                                        <div class="col-12 col-md-6">
                                          <input
                                            type="number"
                                            class="form-control form-control-sm  mt-2"
                                            v-model="
                                              form_actualizar_saldo.saldo_a_actualizar
                                            "
                                            placeholder="Actualizar para"
                                          />
                                        </div>
                                        <div class="col-12 col-md-6">
                                          <span class="text-danger">{{
                                            form_actualizar_saldo.errors
                                              .saldo_a_actualizar
                                          }}</span>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                      <label for=""
                                        >Motivo da Actualização</label
                                      >
                                      <div class="row">
                                        <div class="col-12 col-md-6">
                                          <textarea
                                            cols="30"
                                            rows="2"
                                            v-model="
                                              form_actualizar_saldo.saldo_motivo
                                            "
                                            class="form-control form-control-sm  mt-2"
                                            placeholder="Actualizar para"
                                          ></textarea>
                                        </div>
                                        <div class="col-12 col-md-6">
                                          <span class="text-danger">{{
                                            form_actualizar_saldo.errors
                                              .saldo_motivo
                                          }}</span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="card-footer">
                                  <button
                                    class="btn btn-info"
                                    @click="actualizar_saldo"
                                  >
                                    <i class="fas fa-check"></i> Actualizar
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- historico do saldo -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-historioSaldo"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-messages-tab"
                            >
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  HISTÓRICOS DE SALDOS
                                  <span class="float-right">
                                    <a
                                      @click="imprimirActualizacaoSaldoEXCEL"
                                      class="btn-sm btn-success"
                                      >EXCEL</a
                                    >
                                    <a
                                      @click="imprimirActualizacaoSaldoPDF"
                                      class="btn-sm btn-danger"
                                      >PDF</a
                                    >
                                  </span>
                                </div>
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="height: 300px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Data Actualização</th>
                                            <th>Saldo Anterior</th>
                                            <th>Saldo Actual</th>
                                            <th>Criado Por</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr
                                            v-for="saldo in lista_historico_saldos"
                                            :key="saldo.id"
                                          >
                                            <td>
                                              {{ saldo.data_actualizacao }}
                                            </td>
                                            <td>
                                              <strong>{{
                                                formatValor(
                                                  saldo.saldo_anterior
                                                )
                                              }}</strong>
                                            </td>
                                            <td>
                                              <strong>{{
                                                formatValor(saldo.saldo_actual)
                                              }}</strong>
                                            </td>
                                            <td>{{ nome(saldo.nome) }}</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- facturas -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-facturas"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-settings-tab"
                            >
                              <!--  facturas  -->
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  TOTAL DE FACTURAS ENCONTRADAS ({{
                                    facturas_estudante.length
                                  }})
                                  <span class="float-right">
                                    <a
                                      @click="imprimirFacturaEstudantePDF"
                                      class="btn-sm btn-danger"
                                      >PDF</a
                                    >
                                    <a
                                      @click="imprimirFacturaEstudanteEXCEL"
                                      class="btn-sm btn-success"
                                      >EXCEL</a
                                    >
                                  </span>
                                </div>

                                <div class="card-body">
                                  <div class="row mb-5">
                                    <div class="col-12 col-md-3">
                                      <label for="">Ano Lectivo</label>
                                      <select
                                        v-model="ano_factura"
                                        class="form-control form-control-sm "
                                        @change="carregar_factura_estudantes"
                                      >
                                        <option
                                          :value="anos.Codigo"
                                          v-for="anos in anolectivos"
                                          :key="anos.Codigo"
                                        >
                                          {{ anos.Designacao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="">Estado Da Factura</label>
                                      <select
                                        v-model="estado_factura"
                                        @change="carregar_factura_estudantes"
                                        class="form-control form-control-sm "
                                      >
                                        <option value="">Todos</option>
                                        <option value="0">
                                          Não Liquidada (Pendentes)
                                        </option>
                                        <option value="2">
                                          Parcialmente Liquidada (Pelo menos 1
                                          Pag.)
                                        </option>
                                        <option value="1">
                                          Totalmente Liquidada
                                        </option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-12">
                                      <div
                                        class="overflow-auto"
                                        style="height: 300px"
                                      >
                                        <div class="table-responsive">
                                          <table
                                            class="table-sm table-hover table-bordered table-striped"
                                            style="width: 100%"
                                          >
                                            <thead>
                                              <tr>
                                                <th>Factura</th>
                                                <th>Estado</th>
                                                <th>Tipo</th>
                                                <th>Preço Total</th>
                                                <th>Valor Entregue</th>
                                                <th>Data</th>
                                                <th>Acções</th>
                                              </tr>
                                            </thead>

                                            <tbody>
                                              <tr
                                                v-for="factura in facturas_estudante"
                                                :key="factura.Codigo"
                                              >
                                                <td>{{ factura.Codigo }}</td>
                                                <td
                                                  v-if="factura.estado == 0"
                                                  class="text-warning"
                                                >
                                                  Não Liquidada (Pendente)
                                                </td>
                                                <td
                                                  v-if="factura.estado == 3"
                                                  class="text-warning"
                                                >
                                                  Não Liquidada (Pendente)
                                                </td>
                                                <td
                                                  v-if="factura.estado == 1"
                                                  class="text-success"
                                                >
                                                  Totalmente Liquidada
                                                </td>
                                                <td
                                                  v-if="factura.estado == 2"
                                                  class="text-danger"
                                                >
                                                  Parcialmente Liquidada
                                                </td>
                                                <td>
                                                  {{
                                                    factura.items.servico
                                                      .Descricao ?? "ss"
                                                  }}
                                                </td>
                                                <td>
                                                  {{
                                                    formatValor(
                                                      factura.ValorAPagar
                                                    )
                                                  }}
                                                </td>
                                                <td>
                                                  {{
                                                    formatValor(
                                                      factura.ValorEntregue
                                                    )
                                                  }}
                                                </td>
                                                <td>
                                                  {{ factura.DataFactura }}
                                                </td>
                                                <td class="d-flex">
                                                  <a
                                                    data-toggle="modal"
                                                    @click="
                                                      carregar_detalhe_factura(
                                                        factura.Codigo
                                                      )
                                                    "
                                                    class="btn-sm btn-info mx-1"
                                                    ><i class="fas fa-eye"></i
                                                  ></a>
                                                  <a
                                                    @click="
                                                      modal_remover_factura_pagamento(
                                                        factura.Codigo
                                                      )
                                                    "
                                                    class="btn-sm btn-danger mx-1"
                                                    ><i class="fas fa-trash"></i
                                                  ></a>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- isenção de Multa  -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-isencaoMulta"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-profile-tab"
                            >
                              <div class="card shadow-none bg-light">
                                <div class="card-header bg-info">
                                  <span class="text-start">
                                    ISENÇÃO DE MULTAS
                                  </span>
                                  <span class="float-right">
                                    {{ isencoes_multas_count }}
                                  </span>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-3">
                                      <label for="">Ano Lectivo</label>
                                      <select
                                        v-model="ano_isencao"
                                        class="form-control form-control-sm "
                                        @change="isencao_pagamentos_search"
                                      >
                                        <option
                                          :value="anos.Codigo"
                                          v-for="anos in anolectivos"
                                          :key="anos.Codigo"
                                        >
                                          {{ anos.Designacao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="">Serviços</label>
                                      <select
                                        v-model="servico_isencao_multa"
                                        class="form-control form-control-sm "
                                      >
                                        <option :value="preco_curso.Codigo">
                                          {{ preco_curso.Descricao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="">Motivo</label>
                                      <select
                                        v-model="motivo_isencao_multa"
                                        class="form-control form-control-sm "
                                      >
                                        <option
                                          :value="isencao.Codigo"
                                          v-for="isencao in motivos"
                                          :key="isencao.Codigo"
                                        >
                                          {{ isencao.Descricao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="recipient">Mês:</label>
                                      <select
                                        v-model="mes_isencao_multa"
                                        class="form-control form-control-sm"
                                        id="recipient"
                                        multiple="multiple"
                                        ref="mySelect"
                                      >
                                        <option
                                          :value="mes.id"
                                          v-for="mes in listar_meses_isencoes"
                                          :key="mes.id"
                                        >
                                          {{ mes.designacao }}
                                        </option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="card-footer">
                                  <button
                                    class="btn btn-info mr-2"
                                    @click="isencao_multa_create"
                                  >
                                    Aplicar
                                  </button>
                                </div>
                              </div>

                              <div class="card shadow-none">
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="height: 200px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Registrado por:</th>
                                            <th>Isento De:</th>
                                            <th>Mes</th>
                                            <th>Data</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr
                                            v-for="items in isencoes_multas"
                                            :key="items.codigo"
                                          >
                                            <td>{{ items.nome ?? "N/A" }}</td>
                                            <td>{{ items.Descricao }}</td>
                                            <td>{{ items.designacao }}</td>
                                            <td>{{ items.data_isencao }}</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- isenção de pagamentos  -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-isencaoPagemento"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-settings-tab"
                            >
                              <!--  Isencao de pagamento -->
                              <div class="card shadow-none">
                                <div class="card-header bg-info">
                                  <span class="text-start">
                                    ISENÇÃO DE PAGAMENTOS
                                  </span>
                                  <span class="float-right">
                                    TOTAL DE ISENSÕES DO ESTUDANTE ({{
                                      isencoes_pagamentos_count
                                    }})
                                  </span>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-3">
                                      <label for="">Ano Lectivo</label>
                                      <select
                                        v-model="ano_isencao"
                                        class="form-control form-control-sm "
                                        @change="isencao_pagamentos_search"
                                      >
                                        <option
                                          :value="anos.Codigo"
                                          v-for="anos in anolectivos"
                                          :key="anos.Codigo"
                                        >
                                          {{ anos.Designacao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="">Serviços</label>
                                      <select
                                        v-model="servico_isencao"
                                        class="form-control form-control-sm "
                                      >
                                        <option :value="preco_curso.Codigo">
                                          {{ preco_curso.Descricao }}
                                        </option>
                                        <!-- <option
                                          :value="servico.Codigo"
                                          v-for="servico in servicos_isencao"
                                          :key="servico.Codigo"
                                        >
                                          {{ servico.Descricao }}
                                        </option> -->
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                      <label for="">Motivo</label>
                                      <select
                                        v-model="motivo_isencao"
                                        class="form-control form-control-sm "
                                      >
                                        <option
                                          :value="isencao.Codigo"
                                          v-for="isencao in motivos"
                                          :key="isencao.Codigo"
                                        >
                                          {{ isencao.Descricao }}
                                        </option>
                                      </select>
                                    </div>
                                    
                                    <!-- <div class="col-12 col-md-3">
                                      <label for="">Mês:</label>
                                      <select
                                        v-model="mes_isencao"
                                        class="form-control form-control-sm "
                                      >
                                        <option
                                          :value="mes.id"
                                          v-for="mes in mes_temps_isencao"
                                          :key="mes.id"
                                        >
                                          {{ mes.designacao }}
                                        </option>
                                      </select>
                                    </div> -->
                                    
                                    <div class="col-12 col-md-3">
                                      <label for="recipient2">Mês:</label>
                                      <select
                                        v-model="mes_isencao"
                                        class="form-control form-control-sm "
                                        id="recipient2"
                                        multiple="multiple"
                                        ref="mySelect2"
                                      >
                                        <template v-for="mes in listar_meses_isencoes" :key="mes.id">
                                            <option :value="mes.id">{{ mes.designacao }}</option>
                                        </template>
                                        <!-- <template v-for="mes_temp in prestacoes" :key="mes_temp.id">
                                            <option :value="mes_temp.id" v-if="!mes_temp.factura_item">{{ mes_temp.designacao }}</option>
                                        </template> -->
                                        <!-- <option
                                          :value="mes_temp.id"
                                          v-for="mes_temp in prestacoes"
                                          :key="mes_temp.id"
                                        >
                                            <template>
                                                {{ mes_temp.designacao }}
                                            </template>
                                            <template v-else >
                                                {{ mes_temp.designacao }}
                                            </template>
                                        </option> -->
                                      </select>
                                    </div>
                                    
                                  </div>
                                </div>
                                <div class="card-footer">
                                  <button
                                    class="btn btn-info mr-2"
                                    @click="isencao_pagamentos_create"
                                  >
                                    Aplicar
                                  </button>
                                </div>
                              </div>

                              <div class="card shadow-none">
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="height: 200px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Registrado por:</th>
                                            <th>Estado</th>
                                            <th>Isento De:</th>
                                            <th>Mes</th>
                                            <th>Data</th>
                                            <th>Remover</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr
                                            v-for="items in isencoes_pagamentos"
                                            :key="items.codigo"
                                          >
                                            <td>{{ items.nome ?? "N/A" }}</td>
                                            <td
                                              class="text-success"
                                              v-if="
                                                items.estado_isensao == 'Activo'
                                              "
                                            >
                                              {{ items.estado_isensao }}
                                            </td>
                                            <td class="text-danger" v-else>
                                              {{ items.estado_isensao }}
                                            </td>
                                            <td>{{ items.Descricao }}</td>
                                            <td>{{ items.designacao }}</td>
                                            <td>{{ items.data_isencao }}</td>
                                            <td
                                              v-if="
                                                items.estado_isensao == 'Activo'
                                              "
                                            >
                                              <button
                                                type="button"
                                                class="btn-sm btn-danger"
                                                @click="
                                                  modal_remover_isencao_pagamento(
                                                    items.codigo
                                                  )
                                                "
                                                id="{{ items.codigo }}"
                                              >
                                                Remover
                                              </button>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Pagamentos -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-pagamentos"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-settings-tab"
                            >
                              <!-- Ppagamento -->
                              <div class="card shadow-none bg-light">
                                <div class="card-header bg-info">
                                  PAGAMENTOS
                                  <span class="float-right">
                                    <a
                                      @click="imprimirCarregarPagamentoEXCEL"
                                      class="btn-sm btn-success"
                                      >EXCEL</a
                                    >
                                    <a
                                      @click="imprimirCarregarPagamentoPDF"
                                      class="btn-sm btn-danger"
                                      >PDF</a
                                    >
                                  </span>
                                </div>

                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-5">
                                      <label for="">Ano Lectivo</label>
                                      <select
                                        v-model="ano_pagamento"
                                        @change="carregar_pagamentos"
                                        class="form-control form-control-sm "
                                      >
                                        <option value="">TODOS</option>
                                        <option
                                          :value="anos.Codigo"
                                          v-for="anos in anolectivos"
                                          :key="anos.Codigo"
                                        >
                                          {{ anos.Designacao }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="col-12 col-md-5">
                                      <label for="">Estado Pagamento</label>
                                      <select
                                        v-model="estado_pagamento"
                                        @change="carregar_pagamentos"
                                        class="form-control form-control-sm "
                                      >
                                        <option value="">TODOS</option>
                                        <option value="1">Validado</option>
                                        <option value="0">Pendentes</option>
                                        <option value="3">Rejeitado</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="card shadow-none">
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="overflow: scroll; height: 250px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Recibo</th>
                                            <th>Serviço</th>
                                            <th>Estado</th>
                                            <th>Data Banco</th>
                                            <th>Data Registro</th>
                                            <th>Factura</th>
                                            <th>Valor</th>
                                          </tr>
                                        </thead>

                                        <tbody>
                                          <tr
                                            v-for="pagamento in lista_pagamentos"
                                            :key="pagamento.Codigo"
                                          >
                                            <td>{{ pagamento.Codigo }}</td>
                                            <td>{{ pagamento.Descricao }}</td>
                                            <td
                                              class="text-success"
                                              v-if="pagamento.estado == 1"
                                            >
                                              Válido
                                            </td>
                                            <td
                                              class="text-warning"
                                              v-if="pagamento.estado == 0"
                                            >
                                              Pendente
                                            </td>
                                            <td
                                              class="text-danger"
                                              v-if="pagamento.estado == 3"
                                            >
                                              Rejeitado
                                            </td>
                                            <td>{{ pagamento.DataBanco }}</td>
                                            <td>{{ pagamento.Data }}</td>
                                            <td>
                                              {{ pagamento.codigo_factura }}
                                            </td>
                                            <td>
                                              {{
                                                formatValor(
                                                  pagamento.valor_depositado
                                                )
                                              }}
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- servicos -->
                            <div
                              class="tab-pane fade"
                              id="vert-tabs-servicopago"
                              role="tabpanel"
                              aria-labelledby="vert-tabs-settings-tab"
                            >
                              <!--  Serviço pago -->
                              <div class="card shadow-none bg-light">
                                <div class="card-header bg-info">
                                  SERVIÇOS PAGOS (HISTORICOS)
                                  <span class="float-right">
                                    <a
                                      @click="imprimirServicoPagosPDF"
                                      class="btn-sm btn-success"
                                      >EXCEL</a
                                    >
                                    <a
                                      @click="imprimirServicoPagosEXCEL"
                                      class="btn-sm btn-danger"
                                      >PDF</a
                                    >
                                  </span>
                                </div>

                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-12 col-md-5">
                                      <label for="">Ano Lectivo</label>
                                      <select
                                        v-model="ano"
                                        @change="carregar_servicos_pagos"
                                        class="form-control form-control-sm "
                                      >
                                        <option value="">TODOS</option>
                                        <option
                                          :value="anos.Codigo"
                                          v-for="anos in anolectivos"
                                          :key="anos.Codigo"
                                        >
                                          {{ anos.Designacao }}
                                        </option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="card shadow-none">
                                <div class="card-body">
                                  <div
                                    class="overflow-auto"
                                    style="overflow: scroll; height: 250px"
                                  >
                                    <div class="table-responsive">
                                      <table
                                        class="table-sm table-hover table-bordered table-striped"
                                        style="width: 100%"
                                      >
                                        <thead>
                                          <tr>
                                            <th>Serviço</th>
                                            <th>Valor</th>
                                            <th>Data Pag.Banco</th>
                                            <th>Data de Validação</th>
                                          </tr>
                                        </thead>

                                        <tbody>
                                          <tr
                                            v-for="factura in facturas"
                                            :key="factura"
                                          >
                                            <td>
                                              {{
                                                factura.items.servico.Descricao
                                                  ? factura.items.servico
                                                      .Descricao
                                                  : factura.observacao
                                              }}
                                            </td>
                                            <td>
                                              {{
                                                formatValor(factura.Totalgeral)
                                              }}
                                            </td>
                                            <td>{{ factura.DataBanco }}</td>
                                            <td>{{ factura.updated_at }}</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalMessagem">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Escrever Nova Mensagem.</h4>
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
            <form id="formulario_messagem">
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Assunto</label>
                  <input
                    type="email"
                    v-model="assunto"
                    class="form-control form-control-sm "
                    id="exampleInputEmail1"
                    placeholder="Assunto:"
                  />
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Mensagem</label>
                  <textarea
                    rows="2"
                    v-model="messagem"
                    class="form-control form-control-sm "
                    id="exampleInputPassword1"
                    placeholder="Mensagem:"
                  ></textarea>
                </div>

                <span
                  >Está mensagem será visualizada pelos serviços
                  académicos</span
                >
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn-sm btn-info"
              form="formulario_messagem"
              data-dismiss="modal"
            >
              Salvar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalRemoverFacturaPagamento">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">CONFIRMAÇÃO DE ELIMINAÇÃO DE FACTURA</h4>
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
            <form id="">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-12 mb-5">
                    <label for="">Motivo</label>
                    <select
                      v-model="motivo_remover_factura_pagamento"
                      class="form-control form-control-sm "
                    >
                      <option
                        :value="motivo.codigo"
                        v-for="motivo in motivos_eliminar_facturas_pagamentos"
                        :key="motivo.codigo"
                      >
                        {{ motivo.descricao }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-md-12">
                    <div class="form-group">
                      <label for="message_motivo_remover_factura_pagamento"
                        >Motivo para remover esta Isenção!</label
                      >
                      <textarea
                        rows="2"
                        v-model="message_motivo_remover_factura_pagamento"
                        class="form-control form-control-sm "
                        id="message_motivo_remover_factura_pagamento"
                        placeholder="Motivo:"
                      ></textarea>
                    </div>
                    <span>Informe o motivo para remover esta factura</span>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn-sm btn-info"
              @click="confirmar_remocao_factura_pagamento"
              data-dismiss="modal"
            >
              Salvar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalRemoverIsencaoPagamento">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Motivo</h4>
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
            <form id="">
              <div class="card-body">
                <div class="form-group">
                  <label for="message_motivo_remover"
                    >Motivo para remover esta Isenção!</label
                  >
                  <textarea
                    rows="2"
                    v-model="message_motivo_remover"
                    class="form-control form-control-sm "
                    id="message_motivo_remover"
                    placeholder="Motivo:"
                  ></textarea>
                </div>
                <span>Informe o motivo para remover esta isenção</span>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn-sm btn-info"
              @click="confirmar_remocao_isencao"
              data-dismiss="modal"
            >
              Salvar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalVisualizarPagamento">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalhes da Factura</h4>
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
              class="table-sm table-hover table-bordered table-striped"
              style="width: 100%"
            >
              <tbody>
                <tr>
                  <td>Matricula :</td>
                  <td>{{ detailsfacturas.codMatriucla }}</td>
                </tr>

                <tr>
                  <td>Nome Completo :</td>
                  <td>{{ detailsfacturas.nome }}</td>
                </tr>

                <tr>
                  <td>Curso :</td>
                  <td>{{ detailsfacturas.curso }}</td>
                </tr>

                <tr>
                  <td>Campus :</td>
                  <td>{{ detailsfacturas.polo }}</td>
                </tr>

                <tr>
                  <td>Valor a Pagar:</td>
                  <td>AO 0.00</td>
                </tr>
              </tbody>
            </table>
            <table
              class="table-sm table-hover table-bordered table-striped"
              style="width: 100%"
            >
              <tbody>
                <tr>
                  <td colspan="4"><strong>Detalhes da Factura</strong></td>
                </tr>

                <tr>
                  <td>Factura</td>
                  <td>Valor Total a Pagar</td>
                  <td>Valor Entregue</td>
                  <td>Data Factura</td>
                </tr>

                <tr>
                  <td>{{ detailsfacturas.Codigo }}</td>
                  <td>{{ formatValor(detailsfacturas.ValorAPagar) }}</td>
                  <td>{{ formatValor(detailsfacturas.ValorEntregue) }}</td>
                  <td>{{ detailsfacturas.DataFactura }}</td>
                </tr>
              </tbody>
            </table>

            <table
              class="table-sm table-hover table-bordered table-striped"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th colspan="5">Itens da Factura</th>
                </tr>
                <tr>
                  <th>Descrição</th>
                  <th>Preço</th>
                  <th>Ano Lectivo</th>
                  <th>Mês</th>
                  <th>Multa</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="items in detailsfacturasItems" :key="items">
                  <td>{{ items.servico }}</td>
                  <td>{{ formatValor(items.servico_preco) }}</td>
                  <td>{{ items.ano }}</td>
                  <td>{{ items.Mes }}</td>
                  <td>{{ formatValor(items.Multa) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button type="button" class="btn btn-info">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
  
  
<script>
import { sweetSuccess, sweetError } from "../../components/Alert";
import Paginacao from "../../Shared/Paginacao.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  props: [
    "estudante",
    "bolseiro",
    "admissao",
    "preinscricao",
    "matricula",
    "anolectivos",
    "motivos",
    "ano_curricular",
    "preco_curso",
    "anolectivosinscritosactual",
    "anolectivosinscritosanterior",
    "utilizadores",
    "tipos_movimentos",
    "lista_movimentos",
    "motivos_eliminar_facturas_pagamentos",
    "prestacoes",
    "prestacoes_anterior",
    "ano_lectivo_activo",
  ],
  components: {
    Link,
    Paginacao,
  },
  
  data() {
    return {
      form_actualizar_saldo: this.$inertia.form({
        saldo_actual: this.preinscricao.saldo,
        saldo_a_actualizar: 0,
        saldo_motivo: null,
        codigo: this.preinscricao.Codigo,
      }),

      form_inseccao_multas: this.$inertia.form({
        saldo_actual: this.preinscricao.saldo,
        saldo_a_actualizar: 0,
        saldo_motivo: null,
        codigo: this.preinscricao.Codigo,
      }),

      /** total das prestações */
      total_prestacao: 0,

      /**
       * facturas listagem
       */
      ano_factura: this.ano_lectivo_activo.Codigo,
      estado_factura: "",
      /**
       * isencao pagamentos
       */
      mes_temps_isencao: [],
      listar_meses_isencoes: [],
      servicos_isencao: [],
      
      listagem_meses_ano_lectivo: [],

      // movimentos das actualizaçoes

      lista_historico_saldos: [],

      isencoes_pagamentos: [],
      isencoes_pagamentos_count: [],

      isencoes_multas: [],
      isencoes_multas_count: [],

      ano_isencao: this.ano_lectivo_activo.Codigo,
      servico_isencao: "",
      motivo_isencao: "",
      mes_isencao: [],

      message_motivo_remover: "",
      id_motivo_remover: "",

      message_motivo_remover_factura_pagamento: "",
      id_motivo_remover_factura_pagamento: "",
      motivo_remover_factura_pagamento: "",

      /**
       * isencao multa
       */
      servico_isencao_multa: this.preco_curso.Codigo,
      motivo_isencao_multa: "",
      mes_isencao_multa: [],

      inscricoes: [],
      facturas: [],
      detailsfacturas: [],
      detailsfacturasItems: [],
      facturas_estudante: [],

      lista_movimentos: [],
      total_lista_movimentos: 0,
      lista_movimentos_soma: 0,
      ano: "",
      codigo: 12121,

      /**pagamentos */
      estado_pagamento: "",
      ano_pagamento: "",
      lista_pagamentos: [],
            
      cor_estado_estudante: "",
      designacao_estado_estudante: "",
      
      estudante_tipo1: {},
      estudante_tipo2: {},
      estudante_tipo3: {},
      estudante_tipo4: {},
      
      bolseiro: {},
      
      params: {},
      
      operador_extratos: "",
      tipo_movimentos_extrato: "",
      data_inicio_extrato: new Date().toISOString().substr(0, 10),
      data_final_extrato: new Date().toISOString().substr(0, 10),

      /**message */
      assunto: "",
      messagem: "",
    };
  },
  
  mounted(){
    this.pegaBolseiro();
    this.pegarDescricaoBolseiro();
    
    this.carregar_historicos_actualizacoes();
  
    axios.get("/estudantes/carregar-estado/" + this.matricula.Codigo).then((response) => {
      this.cor_estado_estudante = response.data.data.cor
      this.designacao_estado_estudante = response.data.data.designacao
    })
    .catch((errors) => {
    });
  },
  
  computed: {
  
    estilo_cor_estado_estudante() {
      return {
        color: this.cor_estado_estudante,
      };
    }
  },
  
  watch: {
    options: function(val) {
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
      ano_isencao: function(val) {
        this.params.ano_isencao = val;
        this.getMeses();
      }
  },

  methods: {
  
    getMeses() {
        this.$Progress.start();
        this.$inertia.get("/carregar/meses-anolectivo", this.params, {
            preserveState: true,
            preverseScroll: true,
            onSuccess: () => {
                this.$Progress.finish();
            }
      });
    },
    
    getStatusEstudante: function(matricula){
    
    },
    
    carregar_lista_isencoes_pagamentos: function () {
      this.$Progress.start();
      axios
        .get(
          "/estudantes/carregar-isencao-pagamento-matricula/" +
            this.matricula.Codigo
        )
        .then((response) => {
          this.isencoes_pagamentos = response.data.isencoes_pagamentos;
          this.isencoes_pagamentos_count = response.data.isencoes_pagamentos_count;

          this.mes_temps_isencao = response.data.meses;
          this.servicos_isencao = response.data.servicos;

          this.isencoes_multas = response.data.isencoes_multas;
          this.isencoes_multas_count = response.data.isencoes_multas_count;
          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log(errors);
          this.$Progress.fail();
        });
    },

    modal_remover_factura_pagamento: function (e) {
      $("#modalRemoverFacturaPagamento").modal("show");

      this.id_motivo_remover_factura_pagamento = e;
      this.message_motivo_remover_factura_pagamento = "";
      this.motivo_remover_factura_pagamento = "";
    },

    confirmar_remocao_factura_pagamento: function () {
      this.$Progress.start();
      axios
        .post("/estudantes/remover-factura-pagamento-post", {
          message: this.message_motivo_remover_factura_pagamento,
          codigo: this.id_motivo_remover_factura_pagamento,
          motivo: this.motivo_remover_factura_pagamento,
        })
        .then((response) => {
          if (response.status == 200) {
            sweetSuccess(response.data.message);
          } else {
            sweetError("Este Pagamento já esta isentado!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Não foi possível Isentar este pagamento");
          this.$Progress.fail();
        });
    },

    confirmar_remocao_isencao: function () {
      this.$Progress.start();
      axios
        .post("/estudantes/remover-isencao-pagamento-post", {
          message: this.message_motivo_remover,
          codigo: this.id_motivo_remover,
          matricula: this.matricula.Codigo,
        })
        .then((response) => {
          if (response.status == 200) {
            this.isencoes_pagamentos = response.data.isencoes_pagamentos;
            sweetSuccess(response.data.message);
          } else {
            sweetError("Este Pagamento já esta isentado!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Não foi possível Isentar este pagamento");
          this.$Progress.fail();
        });
    },

    modal_remover_isencao_pagamento: function (e) {
      $("#modalRemoverIsencaoPagamento").modal("show");

      this.id_motivo_remover = e;
      this.message_motivo_remover = "";
    },

    isencao_pagamentos_search: function () {
      this.$Progress.start();
      axios
        .get(
          "/estudantes/carregar-isencao-pagamento/" +
            this.ano_isencao +
            "/" +
            this.matricula.Codigo
        )
        .then((response) => {
          /**
           * isenção de pagamentos
          */
          
          this.listagem_meses_ano_lectivo = response.data.meses;
          
          this.listar_meses_isencoes = response.data.meses;
          this.servicos_isencao = response.data.servicos;
          this.isencoes_pagamentos = response.data.isencoes_pagamentos;
          this.isencoes_pagamentos_count = response.data.isencoes_pagamentos_count;
          /**
           * isencção de multas
           */
          this.isencoes_multas = response.data.isencoes_multas;
          this.isencoes_multas_count = response.data.isencoes_multas_count;
          

          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log(errors);
          this.$Progress.fail();
        });
    },

    isencao_pagamentos_create: function () {
      this.$Progress.start();
      axios
        .post("/estudantes/carregar-isencao-pagamento-post", {
          ano_isencao: this.ano_isencao,
          servico_isencao: this.servico_isencao,
          motivo_isencao: this.motivo_isencao,
          mes_isencao: this.mes_isencao,
          codigo: this.matricula.Codigo,
        })
        .then((response) => {
          if (response.status == 200) {
            sweetSuccess(response.data.message);
            this.isencoes_pagamentos = response.data.isencoes_pagamentos;
            this.isencoes_pagamentos_count =
              response.data.isencoes_pagamentos_count;
          } else {
            sweetError("Este Pagamento já esta isentado!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Não foi possível Isentar este pagamento");
          this.$Progress.fail();
        });
    },

    isencao_multa_create: function () {
      this.$Progress.start();
      axios
        .post("/estudantes/carregar-isencao-multa-post", {
          ano_isencao: this.ano_isencao,
          servico_isencao: this.servico_isencao_multa,
          motivo_isencao: this.motivo_isencao_multa,
          mes_isencao: this.mes_isencao_multa,
          codigo: this.matricula.Codigo,
        })
        .then((response) => {
          if (response.status == 200) {
            this.isencoes_multas = response.data.isencoes_multas;
            this.isencoes_multas_count = response.data.isencoes_multas_count;
            sweetSuccess(response.data.message);
          } else {
            sweetError("Este Multa já esta isentado!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Não foi possível Isentar este Multa");
          this.$Progress.fail();
        });
    },
    
    

    carregar_inscricoes: function () {
      this.$Progress.start();
      axios
        .get(
          "/estudantes/carregar-inscricoes/" +
            this.estudante.codigo +
            "/" +
            this.ano
        )
        .then((response) => {
          this.inscricoes = response.data.inscricoes;
          if (response.data.inscricoes.length === 0) {
            sweetError("Sem registros encontrado!");
          } else {
            sweetSuccess("Estudante Encontrado com sucesso!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Sem registros encontrado!");
          this.$Progress.fail();
        });
    },

    // #####################################################################################################

    carregar_servicos_pagos: function () {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-servicos-pagos", {
          params: {
            codigo: this.preinscricao.Codigo,
            ano: this.ano,
          },
        })
        .then((response) => {
          this.facturas = response.data.facturas;
          if (response.data.facturas.length === 0) {
            sweetError("Sem registros encontrado!");
          } else {
            sweetSuccess("Estudante Encontrado com sucesso!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Sem registros encontrado!");
          this.$Progress.fail();
        });
    },

    imprimirServicoPagosPDF() {
      window.open(
        `/estudantes/carregar-servicos-pagos-pdf?codigo=${this.preinscricao.Codigo}&ano=${this.ano}`,
        "_blank"
      );
    },

    imprimirServicoPagosEXCEL() {
      window.open(
        `/estudantes/carregar-servicos-pagos-excel?codigo=${this.preinscricao.Codigo}&ano=${this.ano}`,
        "_blank"
      );
    },

    // #####################################################################################################

    carregar_detalhe_factura: function (code) {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-detalhe-facturas/" + code)
        .then((response) => {
          $("#modalVisualizarPagamento").modal("show");

          this.detailsfacturas = response.data.factura;
          this.detailsfacturasItems = response.data.facturas_details;

          if (response.data.factura.length === 0) {
            sweetError("Sem registros encontrado!");
            this.$Progress.fail();
          } else {
            sweetSuccess("Estudante Encontrado com sucesso!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          // sweetError("Sem registros encontrado!")
          this.$Progress.fail();
        });
    },

    // #####################################################################################################

    carregar_factura_estudantes: function () {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-facturas-estudantes", {
          params: {
            codigo: this.estudante.codigo,
            ano: this.ano_factura,
            estado: this.estado_factura,
          },
        })
        .then((response) => {
          this.facturas_estudante = response.data.facturas;
          if (response.data.facturas.length === 0) {
            sweetError("Sem registros encontrado!");
            this.$Progress.fail();
          } else {
            sweetSuccess("Estudante Encontrado com sucesso!");
          }
          this.$Progress.finish();
        })
        .catch((errors) => {
          sweetError("Sem registros encontrado!");
          this.$Progress.fail();
        });
    },

    imprimirFacturaEstudantePDF() {
      window.open(
        `/estudantes/carregar-facturas-estudantes-pdf?codigo=${this.estudante.codigo}&ano=${this.ano_factura}&estado=${this.estado_factura}`,
        "_blank"
      );
    },

    imprimirFacturaEstudanteEXCEL() {
      window.open(
        `/estudantes/carregar-facturas-estudantes-excel?codigo=${this.estudante.codigo}&ano=${this.ano_factura}&estado=${this.estado_factura}`,
        "_blank"
      );
    },

    // #####################################################################################################

    carregar_tipo_movimentos: function () {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-tipo-movimentos", {
          params: {
            codigo: this.estudante.codigo,
            data_inicio: this.data_inicio_extrato,
            data_final: this.data_final_extrato,
            tipo: this.tipo_movimentos_extrato,
            operador: this.operador_extratos,
          },
        })
        .then((response) => {
          this.lista_movimentos = response.data.lista_movimentos.data;
          this.total_lista_movimentos = response.data.total_lista_movimentos;
          this.lista_movimentos_soma = response.data.lista_movimentos_soma;

          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log(errors);
          this.$Progress.fail();
        });
    },

    // #####################################################################################################

    carregar_pagamentos: function () {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-pagamentos", {
          params: {
            codigo: this.preinscricao.Codigo,
            ano: this.ano_pagamento,
            estado: this.estado_pagamento,
          },
        })
        .then((response) => {
          this.lista_pagamentos = response.data.lista_pagamentos;
          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log(errors);
          this.$Progress.fail();
        });
    },

    imprimirCarregarPagamentoPDF() {
      window.open(
        `/estudantes/carregar-pagamentos-pdf?codigo=${this.preinscricao.Codigo}&ano=${this.ano_pagamento}&estado=${this.estado_pagamento}`,
        "_blank"
      );
    },

    imprimirCarregarPagamentoEXCEL() {
      window.open(
        `/estudantes/carregar-pagamentos-excel?codigo=${this.preinscricao.Codigo}&ano=${this.ano_pagamento}&estado=${this.estado_pagamento}`,
        "_blank"
      );
    },

    // #####################################################################################################

    // ACTUALIZAR SALDO
    actualizar_saldo: function () {
      this.$Progress.start();
      this.form_actualizar_saldo.post(route("mf.estudante-actualizar-saldo"), {
        preverseScroll: true,
        onSuccess: () => {
          this.form_actualizar_saldo.reset();
          this.$Progress.finish();
          sweetSuccess("Dados salvos com sucesso");
        },
        onError: (errors) => {
          sweetError("Ocorreu um erro ao actualizar Instituição!");
          this.$Progress.fail();
        },
      });
    },

    // CARREGAR TODOS HISTORICOS DAS ACTUALIZAÇÕES DO SALDO
    carregar_historicos_actualizacoes: function () {
      this.$Progress.start();
      axios
        .get("/estudantes/carregar-actualizacoes-saldos", {
          params: {
            codigo: this.preinscricao.Codigo,
          },
        })
        .then((response) => {
          this.lista_historico_saldos = response.data.lista_historico_saldos;
          this.$Progress.finish();
        })
        .catch((errors) => {
          console.log(errors);
          this.$Progress.fail();
        });
    },

    imprimirActualizacaoSaldoPDF() {
      window.open(
        `/estudantes/carregar-actualizacoes-saldos-pdf?codigo=${this.preinscricao.Codigo}`,
        "_blank"
      );
    },

    imprimirActualizacaoSaldoEXCEL() {
      window.open(
        `/estudantes/carregar-actualizacoes-saldos-excel?codigo=${this.preinscricao.Codigo}`,
        "_blank"
      );
    },

    // ############################################################################################

    nome: function (string) {
      let result = string.replace('"', " ");
      let result2 = result.replace('"', " ");
      return result2;
    },

    formatData(data_input) {
      let data = new Date(data_input);
      let dataFormatada =
        data.getDate() + "-" + (data.getMonth() + 1) + "-" + data.getFullYear();

      return dataFormatada;
    },

    formatValor: function (atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);

      return valorFormatado;
    },
    
    pegaBolseiro: function () {
      this.$Progress.start();
      axios
        .get(`/estudante/pega-bolseiro/${this.matricula.Codigo}`)
        .then((response) => {
          this.bolseiro = response.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    
    
    pegaFinalista: function () {
      //alert(this.anoLectivo.Codigo)
      this.$Progress.start();
      axios
        .get(`/estudante/pega-finalista/${this.codigo_matricula}`, {
          params: { ano_lectivo: this.anoLectivo.Codigo },
        })
        .then((response) => {
          this.cadeiras = response.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    
    pegarDescricaoBolseiro: function () {
      axios
        .get(`/estudante/pegar-descricao-bolseiro`)
        .then((response) => {
          this.estudante_tipo1 = response.data.descricao_tipo1;
          this.estudante_tipo2 = response.data.descricao_tipo2;
          this.estudante_tipo3 = response.data.descricao_tipo3;
          this.estudante_tipo4 = response.data.descricao_tipo4;
        })
        .catch((error) => {
          //console.log(error);
        });
    },
    // ############################EXPORTANDO PDF####################################
    imprimirPDF() {
      window.open("/instituicoes-renuncia-pdf?tipo_instituicao=2", "_blank");
    },

    imprimirEXCEL() {
      window.open("/instituicoes-renuncia-excel?tipo_instituicao=2", "_blank");
    },
  },
};
</script>
  
  
<style>
.pago {
  background-color: #1de9b6;
}

.nao_pago {
  background-color: #f77f7f;
}

.isento {
  background-color: #9e9e9e;
}

</style>