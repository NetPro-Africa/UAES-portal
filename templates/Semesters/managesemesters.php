<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newsemester'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'add new programe']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Semesters</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Semester Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
              
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tfoot>
           <tr>
               
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr> 
        </tfoot>
        <tbody>
            <?php foreach ($semesters as $semester): ?>
            <tr>
            
                <td><?= h($semester->name) ?></td>
                <td class="actions">
                              <?= $this->Html->link(__(' Update'), ['action' => 'updatesemester', $semester->id,$this->Generateurl($semester->name)],
                             ['class'=>'btn btn-round btn-primary fa fa-edit','title'=>'update semester']) ?>
                    <?= $this->Form->postLink(__(' Delete'), ['action' => 'delete', $semester->id], 
                            ['confirm' => __('Are you sure you want to delete # {0}?', $semester->id),
                              'class'=>'btn btn-round btn-danger fa fa-times'  ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
       </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>