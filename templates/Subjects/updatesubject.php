<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Update Course</h1>
                        </div>
    <?= $this->Form->create($subject) ?>
    <fieldset>
      <div class="form-group row">
          <div class="col-sm-4 mb-3 mb-sm-0">
              <?= $this->Form->control('name', ['label' => 'Course Title', 'placeholder' => 'Course Title', 'required',
                                          'class' => 'form-control form-control-user2']);?>
          </div>
          <div class="col-sm-4 mb-3 mb-sm-0">
              <?=$this->Form->control('subjectcode',['label' => 'Course Code', 'placeholder' => 'Course Code', 'required',
                                          'class' => 'form-control form-control-user2']);?>
          </div>
          <div class="col-sm-4 mb-3 mb-sm-0">
                <?= $this->Form->control('department_id', ['options' => $departments, 'label' => 'Select Department', 'empty' => 'Select Departments', 'class' => 'select2_multiple form-control form-control-user','required']) ?>
                              
          </div>
          
      </div>
        <div class="form-group row">
             <div class="col-sm-4 mb-3 mb-sm-0">
                <?= $this->Form->control('level_id', ['options' => $levels, 'label' => 'Select Level', 'empty' => 'Select Level', 'class' => 'select2_multiple form-control form-control-user','required']) ?>
                              
          </div>
             <div class="col-sm-4 mb-3 mb-sm-0">
        <?php
        
            echo $this->Form->control('semester_id',['label' => 'Select Semester','options' => $semesters, 'placeholder' => 'Select Semester', 'required',
                                          'class' => 'form-control form-control-user2']);?>
      
          </div>
          <div class="col-sm-4 mb-3 mb-sm-0">
        <?php
        
            echo $this->Form->control('creditload',['label' => 'Credit Load', 'placeholder' => 'Credit Load', 'required',
                                          'class' => 'form-control form-control-user2']);?>
      
          </div>
            
        </div>
         <div class="form-group row">
        
        <div class="col-sm-4 mb-3 mb-sm-0">
                <?= $this->Form->control('teachers._ids', ['options' => $teachers, 'label' => 'Select Teacher', 'empty' => 'Select Teacher', 'class' => 'select2_multiple form-control form-control-user']) ?>
                              
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
