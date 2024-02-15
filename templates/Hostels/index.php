<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'createhostel'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'add new hostel']) ?>
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Hostels</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Hostel Manager</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
                
                <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hostels as $hostel): ?>
            <tr>
              
                <td><?= h($hostel->name) ?></td>
                <td><?= h($hostel->type) ?></td>
                <td><?= h($hostel->address) ?></td>
                <td><?= h($hostel->phone) ?></td>
                <td class="actions">
                   
                    <?= $this->Html->link(__(' '), ['action' => 'updatehostel', $hostel->id,$this->generateurl($hostel->name)],['class'=>'fa fa-edit btn btn-primary','title'=>'update hostel']) ?>
                    <?= $this->Form->postLink(__(' Delete'), ['action' => 'delete', $hostel->id], 
                            ['confirm' => __('Are you sure you want to delete # {0}?', $hostel->name),'class'=>'btn btn-danger','title'=>'delete hostel']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
              </div>
            </div>
          </div>
</div>
