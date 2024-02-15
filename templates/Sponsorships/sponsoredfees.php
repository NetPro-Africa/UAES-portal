<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
        <div class="container-fluid">
            <div style="padding-bottom: 10px; margin-bottom: 20px;">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">My Sponsored Invoices</h1></div>
         

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Student Invoices</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                  <thead>
            <tr>
          
                 <th >Fee Name</th>
                <th>Amount</th>
                <th>date Paid</th>
                 <th>Session</th>
                <th>Status</th>
                <th >Action</th>
               
            </tr>
                  </thead>
         <tbody>
            <?php foreach ($sponsored_fees as $invoice): ?>
            <tr>
                
                <td><?= h($invoice->fee->name) ?></td>
                <td>₦<?= number_format($invoice->amount) ?></td>
                <td><?= date('D d M, Y', strtotime($invoice->transdate)) ?></td>
               <td><?= h($invoice->session->name) ?></td>
               <td ><?= $invoice->paystatus ?>
               </td>
               
        
                <td class="actions">
                    
                    <?php if($invoice->paystatus=="completed"){
                         echo $this->Html->link(__(' Get Receipt'), ['controller'=>'Invoices','action' => 'studentreceipt', $invoice->invoice_id,$invoice->student_id],
                            ['class'=>'btn btn-round btn-success fa fa-money','title'=>'print receipt']);  
                        
               
                    }
                    else{
            
                    echo $this->Html->link(__(' Pay Online'), ['controller'=>'Students','action' => 'gotopaystack', $invoice->student_id,$invoice->fee_id,$invoice->id],
                           ['class'=>'btn btn-round btn-info fa fa-money','title'=>'pay online']); 

                    }
                    ?>
                    </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>





