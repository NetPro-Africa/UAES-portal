<?php
  $userdata = $this->request->getSession()->read('usersinfo');
  $userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <div style="padding-bottom: 10px; margin-bottom: 20px;">
 
        <!-- Page Heading -->
     
        <h1 class="h3 mb-2 text-gray-800">Manage Students Course Registration - <?=$subject->subjectcode ?> (<?=$subject->name ?>)</h1>
    </div>


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Students Course Registration Manager</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                    <thead>
                        <tr>

                            <th >Name</th>
                            <th>RegNo</th>
                            <th >Department</th>
                            <th>Class</th>
                            <th>Programme</th>
                            <th>Session</th>
                            <th>Semester</th>
                            <th >Passport</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Email</th>
                           
                            
                        </tr>
                    </thead>



                    <tbody>
<?php foreach ($courseregistration as $course):?>
                              <tr>

                                  <td>
                                     <?= $this->Html->link(ucfirst($course->courseregistration->student->fname . ' ' . $course->courseregistration->student->lname), ['controller' => 'Students', 'action' => 'viewstudent', $course->courseregistration->student->id,$this->generateurl($course->courseregistration->student->lname)])?>
   </td>



                                  <td><?= h($course->courseregistration->student->regno) ?></td>
                                  <td><?= $course->courseregistration->student->has('department') ? $course->courseregistration->student->department->name : '' ?></td>
                                  <td><?= h($course->courseregistration->student->level->name) ?></td>
                                    <td><?= h($course->courseregistration->student->programme->name) ?></td>
                                    <td><?= h($course->courseregistration->session->name) ?></td>
                                   <td><?= h($course->courseregistration->semester->name) ?></td>
                                  <td> <?= $this->Html->image('../student_files/'.$course->courseregistration->student->passporturl, ['alt' => 'IMG', 'class' => 'img-circle profile_img',
          'style' => 'width:80px;height:80px;'])
      ?>
                                  </td>
                              
                                  <td><?= h($course->courseregistration->student->phone) ?></td>
                                  <td><?= h($course->courseregistration->student->gender) ?></td>
                                  <td><?= h($course->courseregistration->student->user->username) ?></td>
                            
                                  
                                  
                              </tr>
                          <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>






