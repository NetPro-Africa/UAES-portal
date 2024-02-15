 <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Department : <?= h($department->name) ?></h1>

          <div class="row">

            <div class="col-lg-12">

              <!-- Circle Buttons -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary"> 
            <?= $department->has('faculty') ? $this->Html->link($department->faculty->name, ['controller' => 'Faculties', 'action' => 'viewfaculty', $department->faculty->id,$this->generateurl($department->faculty->name)]) : '' ?></td>
     </h6>
                </div>
                <div class="card-body">
                  <p>List of Programmes  </p>
                  <!-- Circle Buttons (Default) -->
                   <?php if (!empty($department->programes)){
                  foreach ($department->programes as $programes){
          
                  echo h($programes->name.' / '.$programes->programecode).'<br />';
                  
                   }} ?>
                  <hr />
                   <p>Fees Applicable to this Department  </p>
                  <?php if (!empty($department->fees)){
                      
                  foreach ($department->fees as $fee){
          
                  echo h($fee->name.'  -  N'.number_format($fee->amount,2)).'<br />';
                  
                   }} ?>
               
                  <?php if (!empty($department->subjects)){?>
                   <br /> <strong>Courses</strong>
                   <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                       
                       <thead>
            <tr>
          
                <th >Name</th>
                <th >Course Code</th>
                <th>Semester</th>
                <th>Class</th>
                <th>Credit Load</th>
                
            </tr>
                  </thead>
                      <tbody>
                 <?php foreach ($department->subjects as $subject){?>
                          <tr>
                              <td>
                                  <?=$subject->name?>
                              </td>
                              <td>
                                  <?=$subject->subjectcode?>
                              </td>
                               <td>
                                  <?php if(!empty($subject->semester->name)){echo $subject->semester->name;}?>
                              </td>
                               <td>
                                  <?php if(!empty($subject->level->name)){echo $subject->level->name;}?>
                              </td>
                              <td>
                                  <?=$subject->creditload?>
                              </td>
                          </tr>
          
                  <?php }}?>
                      </tbody>
                   </table>
                   <hr />
                </div>
              </div>

            </div>


          </div>

        </div>
        <!-- /.container-fluid -->
