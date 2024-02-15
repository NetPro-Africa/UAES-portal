<?php $settings = $this->request->getSession()->read('settings') ?>
<!-- Page Content -->
<div class="container">
<div class="content container-fluid">

    <div class="row" id="printableArea">

        <div class="col-md-12">
            <?php if(!empty($student->id)) { ?>
            <div class="card"><br /><br />
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-sm-3 m-b-20">
                            <?= $this->Html->image("android-chrome-512x512.png", ['alt' => 'LOGO', 'class' => 'inv-logo']) ?>

                            <br /><br /><br />


                        </div>
                        <div class="col-sm-6 m-b-20 text-center">


                            <h1 class="h4 text-gray-900 mb-4"><strong style="font-size: 28px;"><?= $settings->name ?></strong><br />
                                <b style="font-size: 23px;">  <?= $settings->address ?><br />
                                    <?= $settings->email ?><br /><?= $settings->phone ?><br /></b>

                                <b style="font-size: 21px;"> Fee Payment Invoice- <?= $transaction->fee->name ?> </b></h1>

                            <br />    </div>
                        <div class="col-sm-3 m-b-20">
                            <?= $this->Html->image('../student_files/'.$student->passporturl, ['alt' => 'passport', 'class' => 'inv-logo float-right', 'height' => 130, 'width' => 160]) ?>

                            <br /><br /><br />


                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-7 col-xl-8 m-b-20">
                            <h5>Invoice to:</h5>
                            <ul class="list-unstyled">
                                <li><h5><strong>Name: <?= $student->fname . ' ' . $student->lname ?></strong></h5></li>
                               <li><span>Application No: <?= $student->application_no ?></span></li>
                                <li><span>Address: <?= $student->address ?></span></li>
                                <li>JAMB Reg No: <?= $student->jambregno ?></li>
                                <li>JAMB Score: <?= $student->jamb ?></li>
                                <li>State: <?= $student->state->name ?></li>
                                <li>LGA: <?= $student->lga->name ?></li>
                                <li>Country: <?= $student->country->name ?></li>
                                <li>Phone: <?= $student->phone ?></li>
                                <li><a href="#">Email: <?= $student->user->username ?></a></li>
                                <li>Faculty: <?= $student->faculty->name ?></li>
                                <li>Department: <?= $student->department->name ?></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-lg-5 col-xl-4 m-b-20">
                            <span class="text-muted">Payment Details:</span>
                            <ul class="list-unstyled invoice-payment-details">
                                <li> Transaction Ref :<b> <?= $transaction->payref ?> </b></li>
                                <li> Transaction Date : <?= date('D d M, Y h:i', strtotime($transaction->transdate)) ?> </li>
                                <li><h5>Total Due: <span class="text-right">₦<?= number_format($transaction->amount, 2) ?></span></h5></li>
                                <li>Fee: <span><?= $transaction->fee->name ?></span></li>
                                <li>Pay Status: <span><B><?php
                                            if ($transaction->paystatus == 'completed') {
                                                echo '<span class="badge badge-success">' . h($transaction->paystatus) . '</span>';
                                            } else {
                                                echo $transaction->paystatus;
                                            }
                                            ?></B></span></li>
<!--                                 <li>City: <span>London E1 8BF</span></li>
                              <li>Address: <span>3 Goodman Street</span></li>
                              <li>IBAN: <span>KFH37784028476740</span></li>
                              <li>SWIFT code: <span>BPT4E</span></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ITEM</th>
                                    <th class="d-none d-sm-table-cell">TRANSACTION REF</th>
                                    <th>AMOUNT</th>
                                    <th>STATUS</th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><?= $transaction->fee->name ?></td>
                                    <td class="d-none d-sm-table-cell"><?= $transaction->payref ?></td>
                                    <td>₦<?= number_format($transaction->amount, 2) ?></td>
                                    <td><?= $transaction->paystatus?></td>

                                </tr>




                            </tbody>
                        </table>
                    </div>
                    <span style="margin-right: 20px; float: right;"> <?=
                                            $this->Qr->text($transaction->payref . ' ' .
                                                    $student->fname . ' ' . $student->lname .
                                                    ' ' . $transaction->transdate . ', ' . number_format($transaction->amount, 2) . ', ' . $transaction->paystatus . ', '
                                                    . $student->phone . ', ' . $student->user->username);
                                            ?></span> 
                    <div>
                        <br />  <br />  <br />
                        <div class="invoice-info">
                            <h5><b>Information</b></h5>
                            <p class="text-muted">Please click on the yellow button below to make payment. All applications without application fee payment will not be processed<br />
<?php if ($transaction->gresponse != 'success') { ?>
                                    For assistance please call/WhatsApp: <?=$settings->phone?> or Mail: <?=$settings->email?>
                <?php } ?>

                            </p>
                        </div>
                    </div>
                </div>
                
                                   <?php 
// interswitch webpay setup
 $amount_to_pay = round($transaction->amount); //$transaction->amount-$transaction->charge;
 $split_amount = "";
 $total = round($amount_to_pay,2); 
 $amount_to_pass =  $total*100;
 //check if this is for utme or direct entry
 if($transaction->amount==5500){
  $split_amount =    $transaction->amount-300;
 $netpro_split = round((0.2307*$split_amount),2);
 $cun_split = round((0.7693*$split_amount),2); 
 }
 else{
   $split_amount =    $transaction->amount-300;
   $netpro_split = round((0.117*$split_amount),2);
 $cun_split = round((0.883*$split_amount),2);   
 }
                             
                            
                $name = $student->fname . ' ' . $student->lname . ' ' . $student->mname;
                $terminalId = "0000000001";
                $secret_key = "DEMO_KEY";
                 $responseurl = $baseUrl."invoices/verifyetransact";
                //  $str=$transaction->amount.$terminalId.$transaction->payref.$responseurl.$secret_key;
//$checksum=hash("sha256" ,$str);     
                $transId = $transaction->payref;
                $str = $transaction->amount . $terminalId . $transId . $responseurl . $secret_key;
                $checksum = md5($str);
                $cheksum = md5($transaction->amount . $terminalId . $transId . $responseurl . $secret_key);
                ?>

                <form method='POST' action='https://demo.etranzact.com/webconnect/v3/caller.jsp'>
                    <input name="TERMINAL_ID" type="hidden" value="<?=$terminalId?>" />
                    <input name="TRANSACTION_ID" type="hidden" value="<?= $transaction->payref ?>" />
                    <input name="AMOUNT" type="hidden" value="<?= $transaction->amount ?>" />
                    <input name="DESCRIPTION" type="hidden" value="<?= 'UAES Fee Payment ' . $transaction->fee->name ?>" />
                    <input name="RESPONSE_URL" type="hidden" value="<?= $responseurl ?>" />
                    <input name="NOTIFICATION_URL" type="hidden" value="AB-12385_TT" />
                    <input type='hidden' name = 'EMAIL' value="<?= $student->user->username ?>">
                    <input type='hidden' name = 'FULL_NAME' value="<?= $name ?>">
                    <input type='hidden' name = 'LOGO_URL' value='http://localhost:81/projects/Webconnect/images/elogo.fw.png'>
                    <input type='hidden' name = 'CURRENCY_CODE' value='NGN'>
                    <input type='hidden' name = 'PHONENO' value="<?= $student->phone ?>">
                    <input name="CHECKSUM" type="hidden" value="<?= $cheksum ?>" />

<!--                    <div class="form-group">
                        <div class="col-sm-12 float-left">
                             link to pay stack online payment 
<!--?=
$this->Html->link(__(' Pay Online (Paystack)'), ['controller' => 'Transactions', 'action' => 'gotopaystack', $student->user->username,
    $student->phone, $name, $transaction->amount, $student->id, $transaction->fee_id,
    $transaction->payref,$transaction->id], ['class' => 'btn btn-success float-right', 'title' => 'pay online with your ATM card'])
?>
                        </div>     link to pay with etransact 
                        <div class="col-sm-4 float-left">
                            <input type="submit" class="btn btn-success" name="submit"  value="Pay Online(eTransact)" />
                        </div>

                    </div>-->
                </form>
                <br /><br /><br />
                
                 <!-- interswitch webpay form  -->

<form name="form1" action="<?=GATEWAYURL_LIVE?>" method="post">
    <input name="product_id" type="hidden" value="<?=PRODUCT_ID_LIVE?>" />
    <input name="pay_item_id" type="hidden" value="<?=PAYMENT_ITEM_ID_LIVE ?>" />
<input name="amount" type="hidden" value='<?= $amount_to_pass?>' />
<input name="currency" type="hidden" value="566" />
<input name="site_name" type="hidden" value="https://portal.uaes.edu.ng" />
<input name="site_redirect_url" type="hidden" value="<?=$baseUrl.'transactions/getwebpayinterswitch/'?>"/>
<input name="txn_ref" type="hidden" value="<?=$transaction->payref?>" />
<input name="cust_id" type="hidden" value="<?=$student->id?>" >
<input name="cust_name" type="hidden" value="<?= ucfirst($student->fname . ' ' . $student->lname) ?>" />
<?php
$fluterWave_return_url = $baseUrl.'transactions/getpaymentstatusfluter/';
$return_url = $baseUrl.'transactions/getwebpayinterswitch/';
$payref = $transaction->payref;
//$amount = $total*100;
$product_id = PRODUCT_ID_LIVE;
$payment_item = PAYMENT_ITEM_ID_LIVE;
$string1  = $payref. PRODUCT_ID_LIVE.PAYMENT_ITEM_ID_LIVE. $amount_to_pass.$return_url.MACKEY_LIVE; 
  $hashed = hash("sha512", $string1 );
  $xml = Cake\Utility\Xml::build('<payment_item_detail>
        <item_details detail_ref="' . $payref . '" college="UAES" department="ITServices" faculty="Engineering"> 
            <item_detail item_id="1" item_name="Application fees1" item_amt="' . $netpro_split*100 . '" bank_id="7" acct_num="1019186840" /> 
            <item_detail item_id="2" item_name="Application fees2" item_amt="' . $cun_split*100 . '" bank_id="8" acct_num="2031912457" />
        </item_details> 
    </payment_item_detail>');
  //echo $xml->asXML(); 
//  
//  
//  $xml2 = Cake\Utility\Xml::build("<payment_item_detail>
//        <item_details detail_ref='$payref' institution='NetproInternationalLTD' sub_location='ITServices' location='Abuja'> 
//            <item_detail item_id='1' item_name='Court fees1' item_amt='$netpro_split' bank_id='7' acct_num='1019186840' /> 
//            <item_detail item_id='2' item_name='Items fees2' item_amt='$judiciary_split' bank_id='7' acct_num='1021435611' />
//        </item_details> 
//    </payment_item_detail>");
  
?>
<input name="hash" type="hidden" value="<?=$hashed?>" />
<input name="payment_params" type="hidden" value="college_split" />
<input name="xml_data" type="hidden" value='<?= $xml->asXML() ?>' />

                                         <?php if($transaction->paystatus=='completed'){?>
<!--                                     <button class="btn btn-white" onclick="printDiv('printableArea')" ><i class="fa fa-print fa-lg"></i> Print</button>     -->
                                         <?php } else{  
                                             echo $this->Form->button('Pay Online(Interswitch)',['class'=>'btn btn-primary pull-right','title'=>'pay online with InterSwitch','style'=>'margin-right: 20px;']) ;
                                         } 
                                         
                                         ?>
                                  
                                   </form>
                                   <div class="col-sm-12">
                    &nbsp;  
<?php if($transaction->paystatus !='completed'){
    
echo $this->Html->image('interswitch.png', ['alt' => 'InterSwitch logo', 'class' => 'inv-logo pull-right']);

} ?>
     <?php if($transaction->paystatus !='completed'){?>
                                                <!-- link to pay stack online payment -->
<?=
$this->Html->link(__(' Pay Online (Paystack)'), ['controller' => 'Transactions', 'action' => 'gotopaystack', $student->user->username,
    $student->phone, $student->fname.' '.$student->lname, $transaction->amount, $student->id, $transaction->fee_id,
    $transaction->payref,$transaction->id], ['class' => 'btn btn-success float-left', 'title' => 'pay online with your ATM card'])
?>
<!--<form>
  <script src="https://checkout.flutterwave.com/v3.js"></script>
  <button class="btn btn-success pull-left" title="Pay online with fluter wave" type="button" onClick='makePayment()'> Pay Online(Fluter Wave)</button>
</form>-->
     <?php } ?><br />                               
               
 <br /><button class="btn btn-primary float-left" onclick="printDiv('printableArea')" ><i class="fa fa-print fa-lg"></i> Print Slip</button>
                    

                </div>

                <br />  <br />  <br />
            </div>
            <?php  }
            
            else{?>
            
            <div class="card" style="padding-left: 20px;"><br /><br /><br />
                <div class="row">
                        
                        <div class="col-sm-3 m-b-20">
                            <?= $this->Html->image("android-chrome-512x512.png", ['alt' => 'LOGO', 'class' => 'inv-logo', 'height' => 100, 'width' => 130]) ?>

                            <br /><br /><br />


                        </div>
                        <div class="col-sm-6 m-b-20 text-center">


                            <h1 class="h4 text-gray-900 mb-4"><strong style="font-size: 28px;"><?= $settings->name ?></strong><br />
                                <b style="font-size: 23px;">  <?= $settings->address ?><br />
                                    <?= $settings->email ?><br /></b>

                            <br />   
                       
                        </div>
                        

                    </div>
                <div class="card-body">
                    <div >
         <?= $this->Form->create(null,['url'=>['controller'=>'Students','action'=>'getincompleteapplicant']]) ?>
          <div class="form-group row">
                                 <div class="col-md-12 mb-3 mb-sm-0">
<?= $this->Form->control('application_no', ['label' => false, 'placeholder' => ' Enter your application number to retrieve your application and payment details',
      'class' => 'form-control form-control-user2', 'required','id'=>'application_id'])
?>
                                </div>
  </div>
          
       <br />
          <?= $this->Form->button('Submit', ['class' => 'btn btn-primary btn-lg']) ?>
<?= $this->Form->end() ?> <br /> <br />
      </div>   </div>   </div>
 <br /> <br />
            <?php  }?>
        </div>
    </div> 
</div>
    </div>
<!-- /Page Content -->
<script>

    function printDiv(divName) { //alert('am called');
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

    }

</script>

