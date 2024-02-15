<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">View Hostel Rooms</h1></div>
        

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Hostel Room Manager</h6>
            </div>
            <div class="card-body">
              Hostel : <?= $hostelroom->has('hostel') ? $hostelroom->hostel->name : '' ?><br />
         Floor : 
            <?= h($hostelroom->floor) ?><br />
            Room Number
            <?= h($hostelroom->room_number) ?><br />
      Description :
            <?= h($hostelroom->description) ?><br />
            Available Beds : <?= $this->Number->format($hostelroom->available_beds) ?><br />
            Occupied Beds :  <?= $this->Number->format($hostelroom->occupiedbeds) ?><br />
                
            
            <div class="hostelrooms view large-9 medium-8 columns content">
    

    <div class="related"><br />
        <h4><?= __('Students') ?></h4>
        <?php if (!empty($hostelroom->students)): ?>
        <table cellpadding="5" cellspacing="5">
            <tr>
              
                <th ><?= __('Name') ?></th>
                
                <th><?= __('Class') ?></th>
               
                <th><?= __('Address') ?></th>
                <th><?= __('Phone') ?></th>
                <th>Action</th>
               
            </tr>
            <?php foreach ($hostelroom->students as $students): ?>
            <tr>
               
                <td><?= h($students->fname.' '.$students->lname) ?></td>
               
                <td><?= h($students->department->name) ?></td>
               
                <td><?= h($students->address) ?></td>
                <td><?= h($students->phone) ?></td>
                <td> <?= $this->Html->link(__(' '), ['action' => 'ejectstudent', $students->id,$hostelroom->id,$this->Generateurl($students->fname)],
                            ['class'=>'btn btn-round btn-warning fa fa-edit','title'=>'eject student from room']) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
                 </div>
          </div>
</div>




