<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <!--          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
                <div class="col-lg-12">
                    <div class="p-5">
              
            <div class="timetables view content">
         
          
                    <?= __('Session') ?> : <?=$timetable->session->name ?><br />
                   
             
        <?= __('Department') ?> :   <?= $timetable->department->name ?> <br />
                 
               <?= __('Level') ?> :  <?=  $timetable->level->name ?><br />
                
                   <?= __('Semester') ?> : <?=$timetable->semester->name ?> <br /> 
              
                 
                <br />  <br />   <?= $timetable->timetable ?>
               
               
              
           
        </div> 
              
              
          </div>
            </div>
                 </div> 
              
              
          </div>
           </div>    </div>