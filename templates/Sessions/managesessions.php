<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newsession'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'add new sesion']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Sessions</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Session Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
                
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
              
                <th scope="col"><?= $this->Paginator->sort('create Date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        
        <tfoot>
             <tr>
                
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
              
                <th scope="col"><?= $this->Paginator->sort('create Date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </tfoot>
        
        <tbody>
            <?php foreach ($sessions as $session): ?>
            <tr>
              
                <td><?= h($session->name) ?></td>
                <td><?= h($session->createdate) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__(' Update'), ['action' => 'updatesession', $session->id,$this->Generateurl($session->name)],
                            ['class'=>'btn btn-round btn-primary fa fa-edit','title'=>'update session']) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $session->id], 
                            ['confirm' => __('Are you sure you want to delete # {0}?', $session->name), 'class'=>'btn btn-round btn-danger fa fa-times']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>