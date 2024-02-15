<div class="container-fluid">
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Assign Course To Staff(Lecturers)</h1>
                        </div>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'Teachers', 'action' => 'assignsubjects']]) ?>
                    <fieldset>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?= $this->Form->control('user_id', ['options' => $users, 'label' => 'Select Teacher', 'empty' => 'Select Teacher', 'class' => 'form-control form-control-user2']) ?>
                                <br />
                            </div>

                            <div class="col-sm-12">
                                <?= $this->Form->control('subjects._ids', ['options' => $subjects, 'label' => 'Assign Subjects', 'class' => 'form-control form-control-user2']) ?>
                            </div>
                        </div>
                    </fieldset>
                    <br /> <br />
                    <?= $this->Form->button('Assign', ['class' => 'btn btn-primary btn-user btn-block']) ?>
                    <?= $this->Form->end() ?> <br /> <br />
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    </div>


