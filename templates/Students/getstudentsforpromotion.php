<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>

<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;"><?= $this->Html->link(__(' '), ['action' => 'newstudent'],
                            ['class'=>'btn-circle btn-lg fa fa-plus float-right','title'=>'addmit new student']) ?>
          <!-- Page Heading -->
           <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Search Students Base On Departments </h1>
                        </div>
    <?= $this->Form->create(null) ?>
    <fieldset>
        <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
       <?php
            echo $this->Form->control('department_id',['options'=>$departments,'label'=>'Select Department','onChange'=>"getstudents(this.value)",'empty'=>'Select Department','class' => 'select2_multiple form-control form-control-user2']);
        ?>
        </div>
             <div class="col-sm-6 mb-3 mb-sm-0">
        <?php
            echo $this->Form->control('slevel_id',['options'=>$levels,'required','label'=>'Select New Level','empty'=>'Select New Level','class' => 'form-control form-control-user2']);
        ?>
        </div>
             
       
            </div>
    </fieldset>
   <br /> <br />
                    <?= $this->Form->button('Search', ['class' => 'btn btn-primary btn-user btn-block']) ?>   
                        <?= $this->Form->end() ?>
                    </div>
         
          
          <?php if(!empty($students)){ ?>
          
          <h1 class="h3 mb-2 text-gray-800">Students Promotion</h1></div>
          
 <?= $this->Form->create(null,['url'=>['controller' => 'Students','action' => 'promotestudents']]); ?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Promote Students</h6>
            </div>
            <div class="card-body">
                <div class="col-sm-4 mb-3 mb-sm-0">
        <?php
            echo $this->Form->control('slevel_id',['options'=>$levels,'required','label'=>'Select New Level','empty'=>'Select New Level','class' => 'form-control form-control-user2']);
        ?>
        </div>
              <div class="table-responsive">
                <table id="datatable-button" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
		            style="margin-top: 23px;">
                  <thead>
            <tr>
           <th ><input type="checkbox" onclick="toggleAllApplicants(this);" name="parentCheck" /> </th>
           <th>S/N</th>
                 <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
         <th scope="col"><?= $this->Paginator->sort('Department') ?></th>
               
                <th>Level</th>
                <th scope="col"><?= $this->Paginator->sort('Passport') ?></th>
               
                <th scope="col"><?= $this->Paginator->sort('Regno') ?></th>
               
            </tr>
                  </thead>
            
            
             <tfoot>
            <tr>
           <th ><input type="checkbox" onclick="toggleAllApplicants(this);" name="parentCheck" /> </th>
            <th>S/N</th>
                 <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
             
                <th scope="col"><?= $this->Paginator->sort('Department') ?></th>
              
                <th>Level</th>
                <th scope="col"><?= $this->Paginator->sort('Passport') ?></th>
               
                <th scope="col"><?= $this->Paginator->sort('Regno') ?></th>
               
            </tr>
              </tfoot>
            
     
            <tbody>
            <?php $count =0; foreach ($students as $student): $count++; ?>
            <tr>
                 <td><?php 
	    echo $this->Form->checkbox('studentids[]', ['id' => $student->id,'hiddenField' => 'N','value' => $student->id]);
	    
	    ?>
                    
	     </td>
             <td><?=$count?></td>
                <td><?= h($student->fname.' '.$student->lname.' '.$student->mname) ?></td>  
                <td><?= $student->has('department') ? $this->Html->link($student->department->name, ['controller' => 'Departments', 'action' => 'view', $student->department->id]) : '' ?></td>
                <td><?=$student->level->name?></td>
               
                <td> <?= $this->Html->image('../student_files/'.$student->passporturl, ['alt' => 'passport', 'class' => 'img-circle profile_img',
                                    'style' => 'width:80px;height:80px;']) ?>
               </td>
              
                <td><?= h($student->regno) ?></td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
                   <?= $this->Form->button(' Promote ',['type' => 'submit','class'=>'btn btn-large btn-success pull-right','onclick'=>'transferEmails(this)']) ?>
                 
                  <?= $this->Form->end() ?>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
