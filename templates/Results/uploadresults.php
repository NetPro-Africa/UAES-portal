<!-- Page Wrapper -->

<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Result Management System</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(' Dashboard', ['controller' => 'Lawyers', 'action' => 'dashboard'], ['title' => 'my dashboard']) ?>
                    </li>
                    <li class="breadcrumb-item active">Upload Results</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-header">
                    <h4 class="card-title mb-0">Student Results</h4>
                    <?= $this->Html->link(__(' '), ['action' => 'downloadformat'],
                                    ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'download data format']) ?>
                </div>
                <div class="card-body">
                        <div class="text-center">
                            
                            <h1 class="h4 text-gray-900 mb-4">Bulk Result Upload</h1>
                        </div>
                        <?= $this->Form->create(null,['type'=>'file']) ?>
                    <div class="row">

                            <div class="col-xl-6">
                                 <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Faculty<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                        <?= $this->Form->control('faculty_id', ['label' => false, 'class' => 'form-control', 'required', 'options' => $faculties
                                                ,'onChange'=>'getdepartments(this.value)']) ?>  
                                    </div>
                                </div>
                             
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Department<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                         <?= $this->Form->control('department_id', ['label' => false, 'class' => 'form-control', 'required', 'options' => $departments
                                                 ,'id'=>'dept1']) ?>  

                                    </div>
                                </div>
                                
                                 <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Course<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                        <?= $this->Form->control('subject_id', ['label' => false, 'class' => 'form-control', 'required', 'options' => $subjects]) ?>  
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Semester<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                        <?= $this->Form->control('semester_id', ['label' => false, 'class' => 'form-control', 'required', 'options' => $semesters]) ?>  
                                    </div>
                                </div>
                                 <br /> <br />
                            </div>
                        
                                
                           <div class="col-xl-6">
                               
                            
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Session<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                        <?= $this->Form->control('session_id', ['label' => false, 'class' => 'form-control', 'required', 'options' => $sessions]) ?>  
                                    </div>
                                </div>
                               
                                   <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" title="only pdf or word document is allowed">Upload Document<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                 <?= $this->Form->control('result',['label'=>'Upload File','type'=>'file','required','class'=>'form-control'])?>
                                     </div>
                                </div>
                            
                              <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Select Class<span style="color: red">*</span></label>
                                    <div class="col-lg-9">
                                    <?= $this->Form->control('level_id', ['options' => $levels,'label' => 'Select Class', 'required', 'placeholder' => 'Select Class'
                                        , 'class' => 'form-control'])
                                    ?>
                                </div> 
                              </div>
                           <br /> <br />
                        </div>
                        <br /> <br /> <br /> <br />
<?= $this->Form->button('Upload', ['class' => 'btn btn-primary btn-user btn-block']) ?>
<?= $this->Form->end() ?>
 <br /> <br />
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
