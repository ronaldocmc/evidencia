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
                                <input type="hidden" id="local_pk" name="local_pk" class="form-control" value="<?= $organizacao->localizacao_fk ?>">

                                <label for="dominio-input" class="form-control-label"><strong>Domínio*</strong></label>
                                <input type="text" id="dominio-input" name="dominio" class="form-control" required="true" disabled="true" maxlength="10" minlength="3" value="<?= $organizacao->organizacao_pk ?>">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="nome-input" class="form-control-label"><strong>Nome*</strong></label>
                                <input type="text" id="nome-input" name="organizacao_nome" class="form-control" required="true" maxlength="60" minlength="3" value="<?= $organizacao->organizacao_nome ?>">
                                <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                <label for="cnpj-input" class="form-control-label"><strong>CNPJ*</strong></label>
                                <input type="text" id="cnpj-input" name="organizacao_cnpj" class="form-control cnpj-input" required="true" minlength="18" maxlength="18" value="<?= $organizacao->organizacao_cnpj ?>">
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
                                        <?php foreach($municipios as $m): ?>
                                            <?php 
                                                $checked = ($m->municipio_pk == $organizacao->localizacao_municipio) ? 'checked': '';
                                            ?>
                                            <option $checked value="<?= $m->municipio_pk ?>"><?= $m->municipio_nome ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-12 col-md-9">
                                    <label for="logradouro_pk" class=" form-control-label"><strong>Logradouro*</strong></label>
                                    <input type="text" name="logradouro-input" id="logradouro-input" class="form-control" value="<?= $organizacao->localizacao_rua ?>">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="numero-input" class=" form-control-label"><strong>N°*</strong></label>
                                    <input type="number" id="numero-input" name="local_num" class="form-control numero-input" min="0" required="true" value="<?= $organizacao->localizacao_num ?>">
                                </div>
                            </div>
                            <div class="row form-group">
            
                                <div class="col-12 col-md-6">
                                    <label for="bairro-input" class=" form-control-label"><strong>Bairro*</strong></label>
                                    <input type="text" name="bairro-input" id="bairro-input" class="form-control" value="<?= $organizacao->localizacao_bairro ?>">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row div-btn-lower">
                <div class="col-md-12 d-flex justify-content-center">
                    <div class="overview-wrap">
                        <button id="btn-edit" class="au-btn au-btn-icon au-btn--blue" value="<?= $this->session->user['is_superusuario'] ?>"><i class="fa fa-dot-circle-o"></i>
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
                           <input type="password" class="form-control" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-editar" minlength="8">
                        </div>
                        <div class="form-group">
                           <button type="button" class="btn btn-confirmar-senha" id="btn-confirmar-edicao" name="post" value=""><i class="fa fa-dot-circle-o"></i> Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>