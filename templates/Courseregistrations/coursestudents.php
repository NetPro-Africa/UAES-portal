<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Manage Students Course Registration</h1></div>
     <!-- DataTales Example -->
          <div class="card shadow mb-4" id="students">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Student Course Registration</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                    <thead>
            <tr>
          
                <th >#</th>
        
                <th >Course</th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
                  </thead>
            
            
              <tfoot>
            <tr>
          
               <th >#</th>
       
                <th >Course</th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
              </tfoot>
    
         <tbody>
            <?php 
            $subjects = 0; 
            foreach ($courseregistrations as $subject):
               $subjects++;
                
                ?>
            <tr>
                <td><?=$subjects ?></td>
              
               <td><?= $subject->subject->name?></td>
                
              
               
                <td class="actions">
                    
                    <?php echo $this->Html->link(__(' View Students'), ['action' => 'viewregisteredstudents',$subject->subject_id,$this->generateurl($subject->subject->name)],
                            ['class'=>'btn btn-round btn-primary fa fa-eye','title'=>'view students for this course'])?>
                    
            
                   
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>


