<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>
<!-- Begin Page Content -->
        <div class="container-fluid">

         <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newsubject'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'add new subject']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Courses</h1></div>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Subject Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
               <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                  <thead>
                    <tr>
                        <th> NAME</th>
                       <th>CODE</th>
                        <th>DEPARTMENT</th>  
                       <th>LECTURER</th> 
                      <th>SEMESTER</th>
                     <th>LEVEL</th>
                       <th>CREDIT UNIT</th>
                      
                       <th>ACTIONS</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                       <th> NAME</th>
                       <th>CODE</th>
                       <th>DEPARTMENT</th>  
                         <th>LECTURER</th>  
                        <th>SEMESTER</th>
                     <th>LEVEL</th>
                       <th>CREDIT UNIT</th>
                       
                       <th>ACTIONS</th>
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php foreach ($subjects as $subjects): ?>
                                        <tr>

                                            <td><?= h($subjects->name) ?></td>
                                            <td><?= h($subjects->subjectcode) ?></td>
                                             <td><?=$subjects->department->name?></td>
                                             <td><?php foreach ( $subjects->teachers as $teacher){
                         echo $teacher->firstname.' '.$teacher->middlename.' '.$teacher->lastname.'<br />';
                                             }?></td>
                                              <td><?= h($subjects->semester->name) ?></td>
                                                <td><?= h($subjects->level->name) ?></td>
                                            <td><?= $subjects->creditload ?></td>
                                            


                                            <td class="actions">
                                                <?= $this->Html->link(__('View'), ['action' => 'viewsubject', $subjects->id, $this->GenerateUrl($subjects->name)], ['class'=>'btn btn-round btn-info fa fa-eye']) ?>
                                                <?= $this->Html->link(__('Update'), ['controller'=>'Subjects','action' => 'updatesubject', $subjects->id, $this->GenerateUrl($subjects->name)], ['class'=>'btn btn-round btn-primary fa fa-edit']) ?>
                                              
										
												 
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
               
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->


