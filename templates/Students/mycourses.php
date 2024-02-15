<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">My Registered Courses(Current Semester)</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Registered Courses</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
          
                 <th >Course Name</th>
                <th>Course Code</th>
                
                <th >Action</th>
               
            </tr>
                  </thead>
            
            
              <tfoot>
            <tr>
          
                 <th >Course Name</th>
                <th>Course Code</th>
                
                <th >Action</th>
            </tr>
              </tfoot>
            
        
         <tbody>
            <?php 
              foreach ($registered_courses as $courses){
                 // debug(json_encode( $courses->subjects, JSON_PRETTY_PRINT));exit;
                  foreach ($courses->subjects as $subject){
                  
                  ?>
            <tr>
                
                <td><?= h($subject->name) ?></td>
                <td><?= h($subject->subjectcode) ?></td>
              
                <td class="actions">
                    
                    <?= $this->Html->link(__(' '), ['controller'=>'Topics','action' => 'viewcontents', $subject->id,$this->generateurl($subject->name)],
                            ['class'=>'btn btn-round btn-primary fa fa-eye','title'=>'view course contents']) ?>
                    </td>
            </tr>
                  <?php } } ?>
            <!--  for any assigned course like carry over 
             <?php //debug(json_encode( $mycourses->department->subjects, JSON_PRETTY_PRINT));exit;
              foreach ($mycourses->subjects as $subject): ?>
            <tr>
                
                <td><?= h($subject->name) ?></td>
                <td><?= h($subject->subjectcode) ?></td>
              
                <td class="actions">
                    
                    <?= $this->Html->link(__(' '), ['controller'=>'Topics','action' => 'viewcontents', $subject->id,$this->generateurl($subject->name)],
                            ['class'=>'btn btn-round btn-primary fa fa-eye','title'=>'view course contents']) ?>
                    </td>
            </tr>
            <?php endforeach; ?>
             -->
        </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>





