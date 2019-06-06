<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12 pb-4">
                    <h2 class="title-1 text-center">informações da organização </h2>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Identificação da Organização</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="organizacao_pk" class="form-control-label"><strong>Domínio*</strong></label>
                                <input type="text" id="organizacao_pk" name="organizacao_pk" class="form-control" required="true" disabled="true" maxlength="10" minlength="3">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="organizacao_nome" class="form-control-label"><strong>Nome*</strong></label>
                                <input type="text" id="organizacao_nome" name="organizacao_nome" class="form-control" required="true" maxlength="60" minlength="3">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="organizacao_cnpj" class="form-control-label"><strong>CNPJ*</strong></label>
                                <input type="text" id="organizacao_cnpj" name="organizacao_cnpj" class="form-control cnpj-input" required="true" minlength="18" maxlength="18">
                                <small class="form-text text-muted"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Localização</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="row form-group">
                                <!-- <div class="col-12 col-md-3">
                                    <label for="uf-input" class=" form-control-label"><strong>Estado*</strong></label>
                                    <select class="form-control loading" id="uf-input" name="estado_pk" required="true" data-value="<?= 'SP' ?>"></select>
                                </div> -->
                                <!-- <div class="col-12 col-md-9">
                                    <label for="cidade-input" class=" form-control-label"><strong>Cidade*</strong></label>
                                    <select class="form-control loading" id="cidade-input" name="municipio_pk" data-value="<?= '$organizacao->municipio_pk' ?>" required="true"></select>
                                </div> -->
                                <div class="col-12 col-md-9">
                                    <select class="form-control" name="localizacao_municipio" id="localizacao_municipio" required="true">

                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-12 col-md-9">
                                    <label for="localizacao_rua" class=" form-control-label"><strong>Logradouro*</strong></label>
                                    <input type="text" name="localizacao_rua" id="localizacao_rua" class="form-control">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="localizacao_num" class=" form-control-label"><strong>N°*</strong></label>
                                    <input type="number" id="localizacao_num" name="localizacao_num" class="form-control " min="0" required="true">
                                </div>
                            </div>
                            <div class="row form-group">
            
                                <div class="col-12 col-md-6">
                                    <label for="localizacao_bairro" class=" form-control-label"><strong>Bairro*</strong></label>
                                    <input type="text" name="localizacao_bairro" id="localizacao_bairro" class="form-control">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Área de Atuação</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="add_city"> Adicione Cidades </label> <br>
                                <input type="text" name="add_city" id="add_city" class="form-control col-md-8">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row div-btn-lower">
                <div class="col-md-12 d-flex justify-content-center">
                    <div class="overview-wrap">
                        <button id="btn-edit" class="au-btn au-btn-icon au-btn--blue"><i class="fa fa-dot-circle-o"></i>
                            confirmar edição
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_edit" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title">Confirmar Edição</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                           <input type="password" class="form-control" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-save" minlength="8">
                        </div>
                        <div class="form-group">
                           <button type="button" class="btn btn-confirmar-senha" id="btn-confirmar-edicao" name="post" value=""><i class="fa fa-dot-circle-o"></i> Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>