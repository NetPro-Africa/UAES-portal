<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                   
                    
                    
                    
                    
                    
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Student's Course Registration</h1>
                        </div>
                        <b> Here is a list of your courses for the semester</b><br />
                    <?php $count = 0; $creditload = 0;
                     // debug(json_encode( $assigned_courses, JSON_PRETTY_PRINT)); exit;
                      foreach($assigned_courses as $subject){
                          $count++; $creditload+= $subject->creditload;
                       echo $count.'  '. $subject->name.' <span class="fa fa-check text-success"> </span><br />'; 
                        
                    }  ?>
                         <br /> <br />
                         
                     <b> Total Credit Load:   <?= $creditload.' </b><br />' ?> 
                      
                    <br />  Note: Your courses for the semester has already been preselected. To add extra
                      courses(eg carryover), select the appropriate level and semester
                      (for the course) and select the course from the "select courses" box.<br />
                      When done, click on add courses to save your selections.
                   
               
                       
    <?= $this->Form->create($courseregistration) ?>
    <br /> <fieldset>
        <div class="form-group row">        
                                <div class="col-sm-4 mb-3 mb-sm-0">
        <?php 
          echo $this->Form->control('department_id', ['options' => $departments,'Label'=>'Department','class' => 'form-control form-control-user','required','default'=>$student->department_id,'id'=>'dept'])?>
           </div>
                  <div class="col-sm-4 mb-3 mb-sm-0">
            <?= $this->Form->control('level_id', ['options' => $levels,'label'=>'Select Level','required','empty'=>'Select Level','class' => 'form-control form-control-user','default'=>$student->level_id
                ,'id'=>'levelid'])?>
            </div>
                                    
                                
            <div class="col-sm-4 mb-3 mb-sm-0">
           
            <?= $this->Form->control('semester_id', ['options' => $semesters,'Label'=>'Select Semester','required','empty'=>'Select Semester','class' => 'form-control form-control-user','onChange'=>'getcourses(this.value)'])?>
            </div>
        </div>
            <div class="form-group row">  
                 
   
                <div class="col-sm-4 mb-3 mb-sm-0">
          
           <?= $this->Form->control('subjects._ids', ['options' => $subjects,'label' => 'Select Courses(Extra courses/carryover only)', 'class' => 'select2_multiple form-control form-control-user','id'=>'extracourses']) ?> 
                </div>
          
                </div>
    </fieldset>
     <br /> <br />
<?= $this->Form->button('Add Courses', ['class' => 'btn btn-primary btn-user btn-block']) ?>
<?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>  
    
       function getcourses(semid){ 
     deptid =     document.getElementById('dept').value; 
     level =     document.getElementById('levelid').value; 
     getcourseswithallparams(semid,deptid,level);

}  

    function getcourseswithallparams(semid,deptid,level){ 
 
    $.ajax({
        url: '../Courseregistrations/getdeptcourses/'+semid +'/'+ deptid +'/'+ level,
        method: 'GET',
        dataType: 'text',
        success: function(response) {
           // console.log(response);
            document.getElementById('extracourses').innerHTML = "";
            document.getElementById('extracourses').innerHTML = response;
            //location.href = redirect;
        }
    });

} 
    
    </script>