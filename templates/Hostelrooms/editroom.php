<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Admit New Hostel Room</h1>
                        </div>
    <?= $this->Form->create($hostelroom) ?>
    <fieldset>
           <div class="form-group row">
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                    <?= $this->Form->control('hostel_id', ['label'=>'Select Hostel','options' => $hostels,'required','class' => 'form-control form-control-user2'])?>
                                  
                                </div>
               <div class="col-sm-4 mb-3 mb-sm-0">
                   <?= $this->Form->control('floor', ['label'=>'Hostel Floor','required','class' => 'form-control form-control-user2'])?>
           
               </div>
               
                <div class="col-sm-4 mb-3 mb-sm-0">
                   <?= $this->Form->control('room_number', ['label'=>'Room Number','required','class' => 'form-control form-control-user2'])?>
           
               </div>
           </div>
        <div class="form-group row">
            <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('available_beds', ['label'=>'Available Beds','required','class' => 'form-control form-control-user2'])?>
              
            </div>
             <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('occupiedbeds', ['label'=>'Ocuupied Beds','required','class' => 'form-control form-control-user2'])?>
              
            </div>
             <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('description', ['label'=>'Description','required','class' => 'form-control form-control-user2'])?>
              
            </div>
      </div>
        
        <div class="form-group row">
            <div class="col-sm-4 mb-3 mb-sm-0">
                  <?php
            echo $this->Form->control('students._ids', ['options' => $students,'label'=>'Assign Students','class'=>'select2_multiple form-control form-control-user']);
        ?>
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