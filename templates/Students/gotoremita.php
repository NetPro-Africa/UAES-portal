<div class="container" id="printableArea">
<?php $settings =  $this->request->getSession()->read('settings') ?>
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <br />
             
                <div class="col-lg-12">
                    <div class="p-5">
                      
                       <div class="text-center">
                        <div class="col-md-3">
                <?=$this->Html->image($settings->logo, ['alt' => 'LOGO', 'class' => 'img-responsive float-left','height'=>100,'width'=>130])?>
                </div>    
                            <h1 class="h4 text-gray-900 mb-4"><strong style="font-size: 28px;"><?= $settings->name?></strong><br />
                                <b style="font-size: 19px;"> <?= $settings->address?></b><br />
                               
                                <b style="font-size: 21px;"> Fee Payment Invoice<br />(<?=$fee->name?>) </b></h1>
                        
                        </div>
                       
Name : <?=$student->fname.' '.$student->lname  ?><br />
Registration Number : <?=$student->regno?><br />
 Address :  <?=$student->address  ?>      <br /> 
 Amount : ₦<?= number_format($transaction->amount)  ?><br />
 RRR Code : <?=$transaction->payref  ?><br />
 Date : <?= date('D d M, Y')?>
                        <br /><br />
                        <form action="<?=GATEWAYRRRPAYMENTURL?>" method="POST">
<input id="merchantId" name="merchantId" value="<?=MERCHANTID?>" type="hidden"/>
<input id="rrr" name="rrr" value="<?=$transaction->payref?>" type="hidden"/>
<input id="responseurl" name="responseurl" value="<?=$responseurl?>" type="hidden"/>
<input id="hash" name="hash" value="<?=$new_hash?>" type="hidden"/>

 <div class="form-group">
	<div class="col-sm-4 float-left">
		<input type="submit" class="btn btn-success" name="submit" value="Pay Online" />
	</div>
     <div class="col-sm-4 float-right">
		<input class="btn btn-primary float-right" type="button" onclick="printDiv('printableArea')" value="Print Slip" />
          
	</div>
</div>
<br /><br /><br />
Note : To pay through the bank, please print this page and take it to any commercial bank and pay then come back to the system
and confirm your payment using the RRR code.
<!--copy the InterSwitch Reference code(<?=$transaction->payref ?>) go to any bank of your choice and
make an InterSwitch payment using the RRR code, after which you shall return here and verify the payment using same PAYEE ID.-->
	      
                         <br /> <br />
                        </form>
                    </div>
                  </div>
                  </div>
                  </div>
     
            </div>
            </div>
<script>

    function printDiv(divName) { //alert('am called');
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

</script>
       