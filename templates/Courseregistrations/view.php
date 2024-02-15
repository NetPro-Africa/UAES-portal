<?php
$userdata = $this->request->getSession()->read('usersinfo');
$settings = $this->request->getSession()->read('settings');
?>
<!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="card shadow mb-4" id="printableArea">
        <div class="card-header py-3">
                <div class="col-md-3">
                <?=$this->Html->image($settings->logo, ['alt' => 'LOGO', 'class' => 'img-responsive float-left','height'=>100,'width'=>140])?>
                </div>
                <center >
                    <h6 class="m-0 font-weight-bold">
                        <br /><b style="font-size: 14px;"><?= strtoupper($settings->name)?></b><br />
              <?=$settings->address?><br /><?=$settings->email?><br /><?=$settings->phone?><br />
                  Course Registration Form</h6></center>
                 <?=$this->Html->image('../student_files/'.$courseregistration->student->passporturl, ['alt' => 'Passport', 'class' => 'img-responsive float-right','height'=>100])?>
               
           <br />
             
            </div>
             <br /><br /><br />
             <div class="col-md-12 float-right" style="margin-left: 25px; font-size: 19px;">    
             Name : <?=ucfirst($courseregistration->student->fname. ' '.$courseregistration->student->lname.' '.$courseregistration->student->mname) ?><br />
             Regno : <?=$courseregistration->student->regno?><br />
             Department : <?=$courseregistration->student->department->name?><br />
             Session : <?= $courseregistration->session->name ?><br />
             Semester : <?= $courseregistration->semester->name ?><br />
              Class : <?= $courseregistration->level->name ?><br />
              Registration Date : <?= date('D d M, Y', strtotime($courseregistration->date_created)) ?>
             </div>
            
          
             <center>   <br /> <strong>Student Course Form</strong><br /></center>
          <!-- DataTales Example --><br />
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold">Registered Courses</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th> COURSE</th>
                       <th>CODE</th>
                       <th> UNIT</th>
                      
                      
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                       <th> COURSE</th>
                       <th>CODE</th>
                       <th>UNIT </th>
                       
                    
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php $unit = 0; foreach ($courseregistration->subjects as $subjects): 
                          $unit+= $subjects->creditload;
                          ?>
                                        <tr>

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
<div col-md-3>
    &nbsp;&nbsp;&nbsp; <input class="btn btn-custom" type="button" onclick="printDiv('printableArea')" value="Print Course Form" />
                <br /><br /></div>    <br /><br />
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