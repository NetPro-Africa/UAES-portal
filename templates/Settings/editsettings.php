<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Update System Settings</h1>
                        </div>

                        <?= $this->Form->create($setting, ['type' => 'file', 'class' => 'user']) ?>
                        <fieldset>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <?= $this->Form->control('name', ['label' => 'SCHOOL NAME', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $this->Form->control('description', ['label' => 'DESCRIPTION', 'class' => 'form-control form-control-user']) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                
                                <div class="col-sm-6">
                                    <?= $this->Form->control('address', ['label' => 'ADDRESS', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">

                                    <?= $this->Form->control('semester_id', ['options'=>$semesters,'label' => 'Select Semester', 'class' => 'form-control form-control-user2']) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <?= $this->Form->control('email', ['label' => 'EMAIL', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $this->Form->control('phone', ['label' => 'PHONE', 'class' => 'form-control form-control-user']) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <?= $this->Form->control('invoiceprefix', ['label' => 'INVOICE PREFIX', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $this->Form->control('adminprefix', ['label' => 'ADMIN PREFIX', 'class' => 'form-control form-control-user']) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <?= $this->Form->control('logos', ['label' => 'LOGO', 'class' => 'form-control form-control-user', 'type' => 'file']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $this->Form->control('staffprefix', ['label' => 'STAFF PREFIX', 'class' => 'form-control form-control-user']) ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <?= $this->Form->control('regnoformat', ['label' => 'STUDENT REGNO FORMAT', 'class' => 'form-control form-control-user']) ?>
                                </div> 
                               
                                <div class="col-sm-4">
                                    <?= $this->Form->control('application_no_prefix', ['label' => 'APPLICATION NO. PREFIX', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                
                                <div class="col-sm-4">
                                    <?= $this->Form->control('session_id', ['options'=>$sessions,'label' => 'Select Session', 'class' => 'form-control form-control-user2']) ?>
                                </div>
                            </div> 
                            
                             <div class="form-group row">
                                <div class="col-sm-6">
                                    <?= $this->Form->control('rector', ['label' => 'VC', 'class' => 'form-control form-control-user']) ?>
                                </div> 
                               
                                <div class="col-sm-6">
                                    <?= $this->Form->control('rectorcerts', ['label' => 'VC\'s Qualifications', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                </div>
                             <div class="form-group row">
                                <div class="col-sm-6">
                                    <?= $this->Form->control('registrar', ['label' => 'Registrar', 'class' => 'form-control form-control-user']) ?>
                                </div>
                                 <div class="col-sm-6">
                                    <?= $this->Form->control('registrarcerts', ['label' => 'Registrar\'s Qualifications', 'class' => 'form-control form-control-user']) ?>
                                </div>
                            </div> 
                            
                            <div class="form-group row">
                              <div class="col-sm-6">
                                    <?= $this->Form->control('doa', ['label' => 'Director Of Admission', 'class' => 'form-control form-control-user']) ?>
                                </div>   
                            </div>
                            <br /> <br />
                            <?= $this->Form->button('Submit', ['class' => 'btn btn-primary btn-user btn-block']) ?>
                            <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


