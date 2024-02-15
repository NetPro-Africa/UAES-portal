<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
               
                   <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-12">
                    
                    <div class="p-5">
                        <strong class="text-center"> Continents and Their Respective Cost of Transcript Delivery</strong>
                <?php foreach ($continent_costs  as $continent) { 
                    
                echo '<br />'.$continent->name.'  - ₦'    .number_format($continent->cost);
                    
                    
                }?>
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Transcript Request</h1>
                        </div>
<?= $this->Form->create($trequest) ?>
    <fieldset>
       
        <div class="form-group row">        
                                <div class="col-sm-8 mb-3 mb-sm-0">
        <?php
            //echo $this->Form->control('student_id', ['options' => $students]);
          //  echo $this->Form->control('orderdate');
            echo $this->Form->control('institution', ['label' => 'Name of Institution', 'placeholder' => 'institution',
      'class' => 'form-control form-control-user2', 'required'])
                   //  echo $this->Form->control('status');
?>
           </div>
             <div class="col-sm-4 mb-3 mb-sm-0">
           <?= $this->Form->control('continent_id', ['options' => $continents,'label' => 'Select Continent', 'empty' => 'Select Continent', 'class' => 'form-control form-control-user']) ?>
                           
             </div>
        </div>
            <div class="form-group row"> 
            
             <div class="col-sm-6 mb-3 mb-sm-0">
            <?= $this->Form->control('country_id', ['options' => $countries,'label' => 'Select Country', 'empty' => 'Select Country', 'class' => 'select2_multiple form-control form-control-user','onChange'=>'getstates(this.value)']) ?>
                            
             </div>
        
               
                                <div class="col-sm-6 mb-3 mb-sm-0">
           <?= $this->Form->control('state_id', ['options' => $states,'label' => 'Select State', 'empty' => 'Select State', 'class' => 'select2_multiple form-control form-control-user','id'=>'userstates']) ?>
                            
                                </div>
            </div>
          <div class="form-group row"> 
              <div class="col-sm-8 mb-3 mb-sm-0">
           <?= $this->Form->control('address', ['label' => 'Address of Institution', 'placeholder' => 'address of institution',
      'class' => 'form-control form-control-user2', 'required'])?>
              </div>
        
              
                                <div class="col-sm-4 mb-3 mb-sm-0">
            <?= $this->Form->control('courier_id', ['options' => $couriers,'label' => 'Select Courier', 'empty' => 'Select Courier', 'class' => 'form-control form-control-user']) ?>
                              
                                </div>
          </div>

                                 </fieldset>
                       <br /> <br />
<?= $this->Form->button('Submit & Pay Online', ['class' => 'btn btn-primary btn-user btn-block']) ?>
<?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    
    function getstates(stateid){ 

    $.ajax({
        url: '../students/getstates/'+stateid,
        method: 'GET',
        dataType: 'text',
        success: function(response) {
           // console.log(response);
            document.getElementById('userstates').innerHTML = "";
            document.getElementById('userstates').innerHTML = response;
            //location.href = redirect;
        }
    });

}

function getcountries(continentid){ //alert(continentid);
    $.ajax({
        url: '../students/getcountries/'+continentid,
        method: 'GET',
        dataType: 'text',
        success: function(response) {
          // console.log(response);
           // document.getElementById('country1').innerHTML = " ";
           // document.getElementById('country1').innerHTML = response;
            //location.href = redirect;
        }
    });
    
}


</script>