<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">New Semester</h1>
                        </div>
    <?= $this->Form->create($semester) ?>
    <fieldset>
      
        <?php
            echo $this->Form->control('name',['label'=>'Semester Name','required','placeholder'=>'Semester name'
                                            ,'class'=>'form-control','required']) ?>
       
    </fieldset>
      <br /> <br />
                            <?= $this->Form->button('Submit', ['class' => 'btn btn-primary btn-user btn-block']) ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
