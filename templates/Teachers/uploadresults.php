
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <?= $this->Html->link(__(' '), ['controller'=>'Results','action' => 'downloadformat'],
                                    ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'download data format']) ?>
                            <h1 class="h4 text-gray-900 mb-4">Bulk Result Upload</h1>
                        </div>
                        <?= $this->Form->create(null,['type'=>'file']) ?>
                        <fieldset>

                            <div class="form-group row">
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                <?= $this->Form->control('faculty_id', ['options' => $faculties, 'required', 'label' => 'Select Faculty',
                                        'placeholder' => 'Faculty', 'class' => 'form-control','onChange'=>'getdepartments(this.value)'])
                                    ?>     
                                </div>
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                    <?= $this->Form->control('department_id', ['options' => $departments, 'required', 'label' =>'Select Department',
                                        'placeholder' => 'Select Department', 'class' => 'form-control','id'=>'dept1'])
                                    ?>
                                </div>
                               <div class="col-sm-4 mb-3 mb-sm-0">
                                    <?= $this->Form->control('level_id', ['options' => $levels, 'required', 'label' =>'Select Class',
                                        'placeholder' => 'Select Class', 'class' => 'form-control','id'=>'dept1'])
                                    ?>
                                </div>

                               
                            </div>
                                
                            <div class="form-group row">
                                 <div class="col-sm-4">
                                    <?= $this->Form->control('subject_id', ['options' => $subjects,'label' => 'Select Course', 'required', 'placeholder' => 'Select Course'
                                        , 'class' => 'form-control'])
                                    ?>
                                </div>
                           <div class="col-sm-4">
                                    <?= $this->Form->control('semester_id', ['options' => $semesters,'label' => 'Select Semester', 'required', 'placeholder' => 'Select Semester'
                                        , 'class' => 'form-control'])
                                    ?>
                                </div>  
                                <div class="col-sm-4">
                                    <?= $this->Form->control('session_id', ['options' => $sessions,'label' => 'Select Session', 'required', 'placeholder' => 'Select Session'
                                        , 'class' => 'form-control'])
                                    ?>
                                </div>
                                             
                                </div>
                            <div class="form-group row">
                               <div class="col-sm-4">
                                 <?= $this->Form->control('result',['label'=>'Upload File','type'=>'file','required','class'=>'form-control'])?>
                                   </div>    
                            </div>
                          
                        </fieldset>
                        <br /> <br />
<?= $this->Form->button('Upload', ['class' => 'btn btn-primary btn-user btn-block']) ?>
<?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    
        function getdepartments(facultyid){ 

    $.ajax({
        url: '../Results/getdepartments/'+facultyid,
        method: 'GET',
        dataType: 'text',
        success: function(response) {
           // console.log(response);
            document.getElementById('dept1').innerHTML = "";
            document.getElementById('dept1').innerHTML = response;
            //location.href = redirect;
        }
    });

}
    </script>
