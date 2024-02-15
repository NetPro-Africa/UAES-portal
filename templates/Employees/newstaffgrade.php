<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
<!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">New Employee Grade</h1>
              </div>
            <?= $this->Form->create($staffgrade) ?>
            <fieldset>
                 <div class="form-group row">
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <?=  $this->Form->control('name', ['label' => 'Name', 'class' => 'form-control form-control-user2',
                        'placeholder'=>'name']);?>
                      </div>
   
                      <div class="col-sm-4 mb-3 mb-sm-0">
                    <?=  $this->Form->control('basicsalary', ['label' => 'Basic salary', 'class' => 'form-control form-control-user2',
                        'placeholder'=>'basic salary']);?>
                      </div>
                     
                      <div class="col-sm-4 mb-3 mb-sm-0">
                    <?=  $this->Form->control('tax', ['label' => 'Tax', 'class' => 'form-control form-control-user2',
                        'placeholder'=>'tax']);?>
                      </div>
                </div>
                
                 <div class="form-group row">
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <?=  $this->Form->control('allowance', ['label' => 'Allowance', 'class' => 'form-control form-control-user2',
                        'placeholder'=>'allowance']);?>
                      </div>
   
                      <div class="col-sm-4 mb-3 mb-sm-0">
                    <?=  $this->Form->control('deduction', ['label' => 'Other Deduction(s)', 'class' => 'form-control form-control-user2',
                        'placeholder'=>'other deductions']);?>
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