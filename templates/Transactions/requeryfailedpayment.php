<?php
  $userdata = $this->request->getSession()->read('usersinfo');
  $userrole = $this->request->getSession()->read('usersroles');
?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <div style="padding-bottom: 10px; margin-bottom: 20px;">
        <!-- Page Heading -->
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Requery Failed payment on Paystack </h1>
            </div>
            <?= $this->Form->create(null) ?>
            <fieldset>
                <div class="form-group row">

                    <div class="col-sm-8 mb-3 mb-sm-0">
<?= $this->Form->control('tref', ['label' => 'Transaction Ref', 'placeholder' => 'transaction ref', 'class' => 'form-control form-control-user','required']) ?>
                    </div>

                </div>
            </fieldset>
            <br /> <br />
<?= $this->Form->button('Submit', ['class' => 'btn btn-primary btn-user btn-block']) ?>   
            <?= $this->Form->end() ?>
        </div>
      

</div>
