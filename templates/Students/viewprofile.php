<?php
$user = $this->request->getSession()->read('usersinfo');
?>

<!-- Page Content -->
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Profile Manager</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><?= $this->Html->link(' Dashboard', ['controller' => 'Lawyers', 'action' => 'dashboard', $this->GenerateUrl('Lawyer dashboard')], ['title' => 'Lawyer dashboard'])
                        ?></li>
                    <li class="breadcrumb-item active">Student Profile Details</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="card mb-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <a href="#">
                                   <?=  $this->Html->image('../student_files/'.$student->passporturl, ['alt' => $student->regno])?>
                                   
<!--                                    <img alt="" src="assets/img/profiles/avatar-02.jpg">-->
                                </a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0">Name: <?=ucwords($student->fname.' '.$student->lname .' '.$student->mname)  ?></h3>
                                        <h6 class="text-muted">Registration  Number: <?=$student->regno  ?></h6>
                                        <small class="text-muted">Department: <?=$student->department->name?></small>
                                        <div class="staff-id text-muted">Programme : <?=$student->programme->name  ?></div>
                                        <div class="staff-id text-muted">Class : <?=$student->level->name  ?></div>
                                      <div class="small doj text-muted">Date of Birth : <?=$student->dob  ?></div>
                                     
                                     
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Phone:</div>
                                            <div class="text"><?=$student->phone?></div>
                                        </li>
                                        <li>
                                            <div class="title">Email:</div>
                                            <div class="text"><?=$student->user->username?></div>
                                        </li>
                                        
                                        <li>
                                            <div class="title">Address:</div>
                                            <div class="text"><?=$student->address?></div>
                                        </li>
                                        <li>
                                            <div class="title">Gender:</div>
                                            <div class="text"><?=$student->gender?></div>
                                        </li>
                                         <li>
                                            <div class="title">State of origin:</div>
                                            <div class="text"><?=$student->state->name?></div>
                                        </li>
                                         <li>
                                            <div class="title">LGA:</div>
                                            <div class="text"><?=$student->lga->name?></div>
                                        </li>
                                        <li>
                                            <div class="title">Hostel/Room:</div>
                                            <div class="text">
<!--                                                <div class="avatar-box">
                                                    <div class="avatar avatar-xs">
                                                        <img src="assets/img/profiles/avatar-16.jpg" alt="">
                                                    </div>
                                                </div>-->
                                                <span>
                                                   <?php 
                                                   foreach($student->hostelrooms as $room ){
                                                       echo $room->hostel->name.' / '. $room->room_number;
                                                   }?>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="pro-edit"><a data-target="#profile_info" data-toggle="modal" class="edit-icon" href="#"><i class="fa fa-pencil"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="card tab-box">
        <div class="row user-tabs">
            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a href="#emp_profile" data-toggle="tab" class="nav-link active">Profile</a></li>
                    <li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link">Projects</a></li>
                    <li class="nav-item"><a href="#bank_statutory" data-toggle="tab" class="nav-link">Bank & Statutory <small class="text-danger">(Admin Only)</small></a></li>
                </ul>
            </div>
        </div>
    </div>-->

    <div class="tab-content">

        <!-- Profile Info Tab -->
        <div id="emp_profile" class="pro-overview tab-pane fade show active">
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title">Academic Information <a href="#" class="edit-icon" data-toggle="modal" data-target="#personal_info_modal"><i class="fa fa-pencil"></i></a></h3>
                            <ul class="personal-info">
                                <li>
                                    <div class="title">Faculty.</div>
                                    <div class="text"><?=$student->faculty->name?></div>
                                </li>
                                <li>
                                    <div class="title">Program</div>
                                    <div class="text"><?=$student->programme->name?></div>
                                </li>
                                <li>
                                    <div class="title">Level</div>
                                    <div class="text"><?=$student->level->name?></div>
                                </li>
                                <li>
                                    <div class="title">Department</div>
                                    <div class="text"><?=$student->department->name?></div>
                                </li>
                               
                               
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title">Payment Information <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal"><i class="fa fa-pencil"></i></a></h3>
                            <div class="table-responsive">
                                 <?php if (!empty($student->invoices)) : ?> 
                                <table class="table table-nowrap">
                                    <thead>
                                         <tr>
          
                 <th >Fee Name</th>
                <th>Amount</th>
              
                <th>Payment Date</th>
                 <th>Session</th>
                <th>Status</th>
              
               
            </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach ($student->invoices as $invoice): ?>
                                        
                                           <tr>
                
                <td><?= h($invoice->fee->name) ?></td>
                <td>₦<?= number_format($invoice->fee->amount) ?></td>
          
                <td><?= h($invoice->payday) ?></td>
               <td><?= h($invoice->session->name) ?></td>
               <td ><?php if($invoice->paystatus=="Unpaid"){
               echo (' <span class="badge badge-warning">'.$invoice->paystatus.'</span>');}
                   
                   else{
                        echo (' <span class="badge badge-success">'.$invoice->paystatus.'</span>');
                   }?>
               </td>
               
        
                
            </tr>
                                          <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="row">
               
                <div class="col-md-6 d-flex">
                    
                </div>
            </div>
            <div class="row">
              
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title">Parental Information<a href="#" class="edit-icon" data-toggle="modal" data-target="#education_info"><i class="fa fa-pencil"></i></a></h3>
                            <div class="experience-box">
                                 
                                <ul class="experience-list">
                                  
                                     
                                    <li>
                                        <div class="experience-user">
                                            <div class="before-circle"></div>
                                        </div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <a href="#/" class="name">Father : <?= h($student->fathersname) ?></a>
                                                <div>Phone : <?= h($student->fatherphone) ?></div>
                                            </div>
                                        </div>
                                    </li>
                                 <li>
                                        <div class="experience-user">
                                            <div class="before-circle"></div>
                                        </div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <a href="#/" class="name">Mother : <?= h($student->mothersname) ?></a>
                                                <div>Phone : <?= h($student->motherphone) ?></div>
<!--                                                <span class="time">₦<?= number_format($subprocesses->amount,2) ?></span>-->
                                            </div>
                                        </div>
                                    </li>
                                   
                                </ul>
                               
                            </div>
                        </div>
                    </div>
                    
                </div>
                 <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title">Results <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal"><i class="fa fa-pencil"></i></a></h3>
                            <div class="table-responsive">
                                <?php if(!empty($student->results)):  ?>
                                <table class="table table-nowrap">
                                    <thead>
                                           <tr>
              
              
               
                <th>Course</th>
                <th >Semester</th>
                <th >Session</th>
                <th>Score</th>
                <th>Grade</th>
                  <th>Class</th>
                <th >Remark</th>
                
                <th >Credit Load</th>
            </tr>
                                    </thead>
                                     <tbody>
            <?php foreach ($student->results as $result): ?>
            <tr>
              
                <td><?= $result->subject->name?></td>
                <td><?= $result->semester->name?></td>
                <td><?= $result->session->name?></td>
                <td><?= $this->Number->format($result->score) ?></td>
                <td><?= h($result->grade) ?></td>
               <td><?= h($result->level->name) ?></td>
                <td><?= h($result->remark) ?></td>
               
                <td><?= $this->Number->format($result->creditload) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                        </div>
                       CGPA : <?= $this->calculateCGPA($student->regno) ?>
                    </div>
             
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title">Courses </h3>
                            <div class="experience-box">
                                <ul class="experience-list">
                                    <?php if(!empty( $courses)){ 
                                        foreach($courses as $course){
                                        ?>
                                    <li>
                                        <div class="experience-user">
                                            <div class="before-circle"></div>
                                        </div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <a href="#/" class="name"><?=$course->session->name ?></a>
                                                <span class="time"><?=$course->level->name ?> (<?=$course->semester->name ?>)
                                                <?= $this->Html->link('', ['controller' => 'Courseregistrations', 'action' => 'viewcourses', $course->id,$this->generateurl($course->semester->name)],
    ['title'=>'view my registered courses for this semester','class'=>'pull-right edit-icon fa fa-eye']) ?></span>
                                            </div>
                                        </div>
                                    </li>
                                        <?php }} ?>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Profile Info Tab -->

   

    </div>
</div>
<!-- /Page Content -->






