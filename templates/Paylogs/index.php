<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;">
          <!-- Page Heading -->
          
          <h1 class="h3 mb-2 text-gray-800">Manage Payment Logs</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Payment Logs</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                  <thead>
            <tr>
          
                 <th scope="col">Student</th>
               <th scope="col">Date</th>
                <th scope="col">Ref Code</th>
                 <th>Gateway Response</th>

                <th scope="col">Amount</th>
                <th scope="col">Type</th>
            </tr>
                  </thead>
            
            
           
         <tbody>
            <?php foreach ($paylogs as $log): ?>
            <tr>
                
                <td><?= h($log->student->fname.' '.$log->student->lname) ?></td>
             
             
               
                <td><?= h(date('D, d M Y h:i A', strtotime($log->transdate))) ?></td>
                <td><?= $log->tref?></td>
                <td><?= h($log->responsecode) ?></td>

                <td><?= ($log->amount )?></td>
        <td><?= h($log->paymethod) ?></td>
            
            </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
