<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>

<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newdepartment'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'add new department']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Departments</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Department Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
          
                <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
                <th>Programme</th>
              
                <th scope="col"><?= $this->Paginator->sort('Faculty') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Code') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
                  </thead>
            
            
              <tfoot>
            <tr>
          
                <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
               <th>Programme</th>
              
                <th scope="col"><?= $this->Paginator->sort('Faculty') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Deptcode') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
              </tfoot>
            
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
            <tr>
               
                <td><?= h($department->name) ?></td>
                <td><?php foreach ($department->programmes as $programe){
                echo h($programe->name.', ');} ?>
                </td>
                
              
                <td><?= $department->has('faculty') ? $this->Html->link($department->faculty->name, ['controller' => 'Faculties', 'action' => 'viewfaculty', $department->faculty->id]) : '' ?></td>
                <td><?= h($department->deptcode) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__(' View'), ['action' => 'viewdepartment', $department->id,$this->Generateurl($department->name)],
                            ['class'=>'btn btn-round btn-info fa fa-eye','title'=>'view department details']) ?>
                    <?= $this->Html->link(__(' Update'), ['action' => 'updatedepartment', $department->id,$department->name],
                            ['class'=>'btn btn-round btn-primary fa fa-edit','title'=>'update department details']) ?>
                    <?= $this->Form->postLink(__(' Delete'), ['action' => 'delete', $department->id], 
                            ['confirm' => __('Are you sure you want to delete # {0}?', $department->name),
                                'class'=>'btn btn-round btn-danger fa fa-times','title'=>'delete department']) ?>
                
              <?= $this->Html->link(__('Allocated Courses'), ['action' => 'allocatecourses', $department->id,$this->Generateurl($department->name)],
                            ['class'=>'btn btn-round btn-info','title'=>'allocate courses']) ?>  
                
                
                
                </td>
            </tr>
            <?php endforeach; ?>
         </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>


