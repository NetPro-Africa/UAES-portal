<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Update Fee</h1>
                        </div>
                        <?= $this->Form->create($fee) ?>
                        <fieldset>
                            <div class="form-group row">
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                        <?= $this->Form->control('name', [ 'label' => false,'placeholder' => 'Fee Name', 'required',
                                            'class' => 'form-control form-control-user2'])
                                        ?>
                                </div>
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                    <?= $this->Form->control('amount', ['label' => false, 'placeholder' => 'Amount', 'required',
                                        'class' => 'form-control form-control-user'])
                                    ?>
                                </div>
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                <?= $this->Form->control('departments._ids', ['options' => $departments,'label'=>false,'empty'=>'Select Departments','class'=>'select2_multiple form-control form-control-user'])?>
                                    </div>
                            </div>
                             <div class="form-group row">
                                <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('startdate', ['label' => false, 'class' => 'form-control form-control-user2', 'type' => 'text', 'id' => 'datepicker','placeholder'=>'start date'])?>
                    </div>
                                       <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('enddate', ['label' => false, 'class' => 'form-control form-control-user2', 'type' => 'text', 'id' => 'datepicker2','placeholder'=>'end date'])?>
                    </div>
                                  <div class="col-sm-4 mb-3 mb-sm-0">
              <?php $feetypes = ['enrolled'=>'Enrolled','none_enrolled'=>'None Enrolled'];
                echo $this->Form->control('feetype', ['options'=>$feetypes,'label' => false, 'class' => 'form-control form-control-user2', 'placeholder'=>'fee type'])?>
                
</div>
                                 </div>
                            <div class="form-group row">
                                <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('itemcode', ['label' => false, 'class' => 'form-control form-control-user2',  'placeholder'=>'item code'])?>
                    </div>
                            </div>

                            
                        </fieldset>
                        <br /> <br />
                        <?= $this->Form->button('Submit', ['class' => 'btn btn-primary btn-user btn-block']) ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

