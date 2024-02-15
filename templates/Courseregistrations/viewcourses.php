<?php
$userdata = $this->request->getSession()->read('usersinfo');
$settings = $this->request->getSession()->read('settings');
?>
<!-- Begin Page Content -->
        <div class="container-fluid"><br /><br />
            <div class="card shadow mb-4" id="printableArea">
       <br /><br />
                 <div class="row"><br /><br />
                     <div class="col-sm-2 m-b-20" style="margin-left: 30px;">
                      <?=$this->Html->image($settings->logo, ['alt' => 'LOGO', 'class' => 'img-responsive float-left','height'=>100,'width'=>140])?>
                         
                            <br /><br /><br />

                            
                        </div>
                        <div class="col-sm-6 m-b-20 text-center">
                            
                          
                            <h1 class="h4 text-gray-900 mb-4"><strong style="font-size: 26px;"><?=$settings->name?></strong><br />
                                <b style="font-size: 21px;">  <?=$settings->address?><br />
                               <?=$settings->email?><br /></b>
                               
                                <b style="font-size: 21px;"> Course Registration Form </b></h1>
                        
                    <br />    </div>
                        <div class="col-sm-3 m-b-20">
                    <?=$this->Html->image('../student_files/'.$student->passporturl, ['alt' => 'Passport', 'class' => 'img-responsive float-right','height'=>100,'width'=>135,'style'=>'margin-right: 15px;'])?>
    
                        </div>
                        
                    </div>
            
             <div class="col-md-12 float-right" style="margin-left: 25px; font-size: 19px;">    
                 Name : <?=  ucfirst($student->fname. ' '.$student->lname.' '.$student->mname) ?><br />
             Regno : <?=$student->regno?><br />
             Department : <?=$student->department->name?><br />
             Session : <?= $courseregistration->session->name ?><br />
             Semester : <?= $courseregistration->semester->name ?><br />
              Class : <?= $courseregistration->level->name ?><br />
              Registration Date : <?= date('D d M, Y', strtotime($courseregistration->date_created)) ?>
             </div>
            
          
            <center>   <br /> Student Course Registration Form<br /></center>
          <!-- DataTales Example --><br />
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Registered Courses</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr><th> #</th>
                        <th> COURSE</th>
                       <th>CODE</th>
                       <th> UNIT</th>
                      
                      
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                         <tr><th> #</th>
                       <th> COURSE</th>
                       <th>CODE</th>
                       <th>UNIT </th>
                       
                    
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php $subject_count = 0; $unit = 0; foreach ($courseregistration->subjects as $subjects): 
                          $unit+= $subjects->creditload;
                      $subject_count++;
                          ?>
                                        <tr>
                                            <td><?=$subject_count?></td>

                                            <td><?= h($subjects->name) ?></td>
                                            <td><?= h($subjects->subjectcode) ?></td>
                                            <td><?= $subjects->creditload ?></td>
                                            

                                        </tr>
                                    <?php endforeach; ?>
               
                  </tbody>
              
                </table>
                      Total Unit : <?=$unit?>
              </div>
            </div>
          </div>
<br />
  <div class="col-md-6 float-right" >
                <input class="btn btn-success float-left" type="button" onclick="printDiv('printableArea')" value="Print Course Form" />
  </div>
<div class="col-md-6 float-left" >
                <?= $this->Html->link(__('Update Courses'), ['action' => 'edit', $courseregistration->id,'my-courses'],['class'=>'btn btn-info float-right','title'=>'update registered courses']) ?>
 </div>
           <br /><br />
        </div>
            </div>
        <!-- /.container-fluid -->



<script>
    
    function printDiv(divName) { //alert('am called');
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
 }

    </script>