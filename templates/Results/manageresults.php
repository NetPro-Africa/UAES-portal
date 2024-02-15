<?php
$userdata = $this->request->getSession()->read('usersinfo');
$userrole = $this->request->getSession()->read('usersroles');
$settings = $this->request->getSession()->read('settings');
$semesta = "";
$dept = "";
$lev = "";
$session = "";
$faculty = "";
$persons_with_f = 0;
$pwfs = [];
$h_cgpa = 1.00;
$l_cgpa = 5.00;
$s_h_cgpa;
$s_l_cgpa;
$cgpa_less1 = 0;
$firstclass = '';
$secondclassupper = '';
$secondclasslower = '';
$thirdclass = '';
$pass = '';
$failed = '';
//set values for class of degree in current semester
$c_s_firstclass = 0;
$c_s_secondclassupper = 0;
$c_s_secondclasslower = 0;
$c_s_thirdclass = 0;
$c_s_pass = 0;
$c_s_failed = 0;
if ($courses != NULL) {
    foreach ($courses as $cours) {
        $semesta = $cours->semester->name;

        $lev = $cours->level->name;
        $session = $cours->session->name;
        $faculty = $cours->faculty->name;
    }
}
?>

<style>
    .rotateit{
        transform: rotate(90deg);
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="donotprint" style="padding-bottom: 10px; margin-bottom: 20px;">
        <!--<?= $this->Html->link(__(' '), ['action' => 'newresult'],
        ['class' => 'btn-circle btn-lg fa fa-plus float-right', 'title' => 'add student result'])
?>
        -->
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Manage Results</h1></div>
    <div class="col-lg-12 donotprint">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Search Result</h1>
            </div>
                        <?= $this->Form->create(null) ?>
            <fieldset>

                <div class="form-group row">
                    <div class="col-sm-4 mb-3 mb-sm-0">
<?=
$this->Form->control('faculty_id', ['options' => $faculties, 'label' => 'Select Faculty', 'empty' => 'Select Faculty',
    'placeholder' => 'Faculty', 'class' => 'form-control', 'onChange' => 'getdepartments(this.value)'])
?>     
                    </div>
                    <div class="col-sm-4 mb-3 mb-sm-0">
<?=
$this->Form->control('department_id', ['options' => $departments, 'label' => 'Select Department',
    'empty' => 'Select Department', 'class' => 'select2_multiple form-control', 'id' => 'dept1', 'onChange' => 'getstudents(this.value)'])
?>
                    </div>

                    <div class="col-sm-4">
                        <?=
                        $this->Form->control('subject_id', ['options' => $subjects, 'label' => 'Select Course', 'empty' => 'Select Course'
                            , 'class' => 'select2_multiple form-control'])
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <?=
                        $this->Form->control('semester_id', ['options' => $semesters, 'label' => 'Select Semester', 'empty' => 'Select Semester', 'placeholder' => 'Select Semester'
                            , 'class' => 'form-control'])
                        ?>
                    </div>  
                    <div class="col-sm-4">
            <?=
            $this->Form->control('session_id', ['options' => $sessions, 'label' => 'Select Session', 'empty' => 'Select Session', 'placeholder' => 'Select Session'
                , 'class' => 'form-control','required'])
            ?>
                    </div>
                    <div class="col-sm-4">
<?= $this->Form->control('student_id', ['options' => $students, 'label' => 'Select Student', 'empty' => 'Select Student'
    , 'class' => 'select2_multiple form-control', 'id' => 'studentss'])
?>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
<?=
$this->Form->control('level_id', ['options' => $levels, 'label' => 'Select Class', 'empty' => 'Select Class', 'placeholder' => 'Select Class'
    , 'class' => 'form-control'])
?>
                    </div>
                </div>
            </fieldset>
            <br /> <br />
<?= $this->Form->button('Search Result', ['class' => 'btn btn-primary btn-user btn-block']) ?>
<?= $this->Form->end() ?>

        </div>
        <br /> <br />

    </div>
    <div class="donotprint" style="padding: 10px; ">
        <select id="fontSizeTxt" style="width:100px; float: left " value="12" class="form-control">
            <option>8</option>
            <option>9</option>
            <option>10</option>
            <option>11</option>
            <option>12</option>
            <option>13</option>
            <option>14</option>
            <option>15</option>
        </select>
        <input style="float: left" onclick="changeFont()" class="btn btn-info" type="button" value="Set"/>
        <div style="clear: both"></div>
        <script>
            document.getElementById('myTabl').style.fontSize = '12px';
            document.getElementById('tableinfo').style.fontSize = '12px';

            function changeFont() {
                document.getElementById('myTabl').style.fontSize = document.getElementById('fontSizeTxt').value + 'px';
                document.getElementById('tableinfo').style.fontSize = document.getElementById('fontSizeTxt').value + 'px';
            }
        </script>
    </div>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;

        }
        td {
            padding: 5px;
        }

        .rotate3 {
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            width: 1.5em;
            padding: 5px;
            height: 140px;
            margin: 0px;
        }
        .rotate3 div {
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
            filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
        }
        @media print {
            .donotprint{
                display: none;
            }
            .page-wrapper {
                margin: 0 !important;
            }
        }



        @media print {
            .sumres {
                page-break-before: always;
            }
        }
    </style>


    <div >

        <p style="font-size:17px; font-weight: bold; float:left"><?= $settings->name ?><br>
            <span style="font-size:13px;"><?= $settings->address ?></span> 
        </p>
        <br />

        <image style=" height: 80px; float:right; margin-top: -30px" src="<?= '../img/' . $settings->logo ?>" />
        <div style="clear:both"></div>         

        <div style=" font-size: 18px; font-weight: bold; text-align: center"> Composite Result Sheet</div>

    </div>


<?php if (isset($courses)) { ?>

        <div class="card-body" id="printableArea">
            <div class="table-responsive">
                <table id="tableinfo" style="width:100%;border:none">
                    <tr>
                        <td style="border:none;width:120px; font-weight:bold">FACULTY:</td>
                        <td style="border:none;text-align:left"><?= $faculty ?></td>
                        <td style="border:none;width:100px; text-align:left; font-weight:bold">SEMESTER:</td>
                        <td style="border:none;width:150px; text-align:left"><?= $semesta ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:bold">DEPARTMENT:</td>
                        <td style="border:none;text-align:left"><?= $deptmt->name ?></td>
                        <td style="border:none;text-align:left; font-weight:bold">SESSION:</td>
                        <td style="border:none; text-align:left"><?= $session ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:bold">LEVEL:</td>
                        <td style="border:none;text-align:left"><?= $lev ?></td>
                        <td style="border:none;"></td>
                        <td style="border:none;"></td>
                    </tr>
                            </table>    
                <table id="myTabl" style="width:100%"  >
                    <thead>
                        <tr>
                            <td rowspan="2">#</td>
                            <td rowspan="2">MATRIC NO.</td>
                            <td rowspan="2">NAME</td>
    <?php $count_courses = 0;
    foreach ($courses as $subject) {
        $count_courses++; ?>

                                <td class="rotate3"><div style="margin-top: 9px;"><?= $subject->subject->subjectcode ?></div></td>
    <?php } ?>

                            <td colspan="3">CURRENT</td>
                            <td colspan="3" >PREVIOUS</td>
                            <td colspan="3" >CUMULATIVE</td>
                            <td rowspan="2">REMARK</td>

                        </tr>


                        <tr>

    <?php
    $tnp = 0;
    $tgp = 0;
    foreach ($courses as $subject) {
        $tnp += $subject->subject->creditload;
        $dsemeseter_id = $subject->semester_id;
        $dlevelid = $subject->level_id;
        ?>

                                <td><div><?= $subject->subject->creditload ?></div></td>
                            <?php } ?>  
                            <td>TGP</td>
                            <td>TNU</td>  
                            <td>GPA</td>
                            <td>TGP</td>
                            <td>TNU</td>  
                            <td>GPA</td>
                            <td>TGP</td>
                            <td>TNU</td>  
                            <td>GPA</td>
                        </tr>



                    </thead>
                    <tbody>
                            <?php $count = 0;
                            foreach ($dstudents as $dstudent) {
                                $count++; ?>
                            <tr>

                                <td><?= $count ?></td>
                                <td><?= $dstudent->regno ?></td>
                                <td><?= $dstudent->student->fname.' '.substr($dstudent->student->lname,0,1).'.' ?></td>

        <?php
        $tgp = 0;
        $no_res = "";
        $fs = 0;
        foreach ($courses as $subject) {
            $std_res = $this->getstudentresult($dstudent->student->id, $dstudent->student->level_id, $dsemeseter_id, $subject->subject_id);
            //  debug(json_encode( $dsemeseter_id, JSON_PRETTY_PRINT)); exit;


            if (!empty($std_res->grade)) {


                if ($std_res->grade == "A") {
                    $tgp += 5 * $std_res->creditload;
                } elseif ($std_res->grade == "B") {
                    $tgp += 4 * $std_res->creditload;
                } elseif ($std_res->grade == "C") {
                    $tgp += 3 * $std_res->creditload;
                } elseif ($std_res->grade == "D") {
                    $tgp += 2 * $std_res->creditload;
                } elseif ($std_res->grade == "E") {
                    $tgp += 1 * $std_res->creditload;
                } elseif ($std_res->grade == "F") {
                    $tgp += 0;
                    $fs++;
                    //check and count persons with F
                    if (!in_array($std_res->student_id, $pwfs)) {
                        array_push($pwfs, $std_res->student_id); //add this student id
                        $persons_with_f++;  //increase those with f
                    }
                } else {
                    $no_res = "yes";
                }
                ?>

                                        <td><div><?= $std_res->grade ?></div></td>
                    
            <?php } else {
                echo'<td></td>';
            }
        } ?>
                                <td><?= $tgp ?></td>   
                                <td><?= $tnp ?></td>    
                                <td><?php 
                                $pcgpa = number_format($tgp / $tnp, 2);
                                if($pcgpa>5.00){echo '<span style="color: red;">'.$pcgpa.'</span>';}
                                else{echo $pcgpa;}
                                        ?></td> 
                                <!-- get current cgpa/ class of degree   -->
                                    <?php
                                 //GET HIGHEST AND LOWEST CPA AND NAME
                                     if ($pcgpa > $h_cgpa) {
                                            $h_cgpa = $pcgpa;
                                            $s_h_cgpa = $dstudent->student->fname . ' ' . $dstudent->student->lname . '(' . $dstudent->student->regno . ')';
                                        } elseif ($pcgpa < $l_cgpa) {
                                            $l_cgpa = $pcgpa;
                                            $s_l_cgpa = $dstudent->student->fname . ' ' . $dstudent->student->lname . '(' . $dstudent->student->regno . ')';
                                        } elseif ($pcgpa < 1) {
                                            $cgpa_less1++;
                                        }
                                    
                                    
                                    //check for class of degree current semester
                                    if ($pcgpa >= 4.50) {
                                        $c_s_firstclass++;
                                    } elseif ($pcgpa > 3.49) {
                                        $c_s_secondclassupper++;
                                    } elseif ($pcgpa > 2.49) {
                                        $c_s_secondclasslower++;
                                    } elseif ($pcgpa > 1.49) {
                                        $c_s_thirdclass++;
                                    } elseif ($pcgpa > 1.0) {
                                        $c_s_pass++;
                                    } else {
                                        $c_s_failed++;
                                    }
                                    ?>

                                <?php
                                if ($dstudent->level_id == 1 && $dsemeseter_id == 1) {
                                //this is year1 and first semester so no previous data
                                    ?>
                                    <td> </td><td> </td><td> </td>
                                    <td> </td>
                                    <td> </td> <td> </td>
                                <?php
                                } else {
                                    $presult = $this->getstudentpresult($dstudent->student->id, $dstudent->student->level_id, $dsemeseter_id);
                                    $ptgp = 0;
                                    $ptnu = 0;
                                    if (!empty(array_filter($presult->toArray()))) {
                                         foreach ($presult as $pstd_res) {
                                            if ($pstd_res->grade == "A") {
                                                $ptgp += 5 * $pstd_res->creditload;
                                                $ptnu += $pstd_res->creditload;
                                            } elseif ($pstd_res->grade == "B") {
                                                $ptgp += 4 * $pstd_res->creditload;
                                                $ptnu += $pstd_res->creditload;
                                            } elseif ($pstd_res->grade == "C") {
                                                $ptgp += 3 * $pstd_res->creditload;
                                                $ptnu += $pstd_res->creditload;
                                            } elseif ($pstd_res->grade == "D") {
                                                $ptgp += 2 * $pstd_res->creditload;
                                                $ptnu += $pstd_res->creditload;
                                            } elseif ($pstd_res->grade == "E") {
                                                $ptgp += 1 * $pstd_res->creditload;
                                                $ptnu += $pstd_res->creditload;
                                            } elseif ($pstd_res->grade == "F") {
                                                $ptgp += 0;
                                                $ptnu += $pstd_res->creditload;
                                            } else {
                                                $no_res = "yes";
                                            }
                                        }
                                        ?>  

                                        <td> <?= $ptgp ?></td>
                                        <td> <?= $ptnu ?></td>
                                        <td><?php if ($ptnu > 0) {
                                            echo number_format($ptgp / $ptnu, 2);
                                        } ?> </td>
                                        <td> <?= $ptgp + $tgp ?></td>
                                        <td> <?= $ptnu + $tnp ?></td>
                                        <td> <?php
                                         $cum_gpa = number_format(($ptgp + $tgp) / ($ptnu + $tnp), 2);
                                         if($cum_gpa>5.00){echo '<span style="color: red;">'.$cum_gpa.'</span>';}
                                else{echo $cum_gpa;}
                                        
                                        if ($cum_gpa > $h_cgpa) {
                                            $h_cgpa = $cum_gpa;
                                            $s_h_cgpa = $dstudent->student->fname . ' ' . $dstudent->student->lname . '(' . $dstudent->student->regno . ')';
                                        } elseif ($cum_gpa < $l_cgpa) {
                                            $l_cgpa = $cum_gpa;
                                            $s_l_cgpa = $dstudent->student->fname . ' ' . $dstudent->student->lname . '(' . $dstudent->student->regno . ')';
                                        } elseif ($cum_gpa < 1) {
                                            $cgpa_less1++;
                                        }
                                        //check for class of degree - cumulative
                                        if ($cum_gpa >= 4.50) {
                                            $firstclass++;
                                        } elseif ($cum_gpa > 3.49) {
                                            $secondclassupper++;
                                        } elseif ($cum_gpa > 2.49) {
                                            $secondclasslower++;
                                        } elseif ($cum_gpa > 1.49) {
                                            $thirdclass++;
                                        } elseif ($cum_gpa > 1.0) {
                                            $pass++;
                                        } else {
                                            $failed++;
                                        }
                                        ?></td>

            <?php
            } else {
                echo '<td></td><td></td><td></td><td></td><td></td><td></td>';
            }
        }
        if ($fs > 0) {
            echo '<td> ' . $fs . ' F' . '</td></tr>';
        } else {
            echo "<td>PASS</td></tr>";
        }


//             else{
//                //for students with no results  
//                $empty_td = 0;
//                do {
//  echo '<td></td>';
//  $empty_td++;
//} while ($empty_td < $count_courses);
//            }
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
//        echo '<td></td>';
    }
    ?> 
                    </tbody>
                </table>
                <br /> <br /> <br />
                <table style="border:none; width: 100%">
                    <tr style="border:none;">
                        <td style="border:none;padding:10px">
                            VC: .........................
                        </td>
                        <td style="border:none;padding:10px">
                            Date: .........................
                        </td>
<!--                        <td style="border:none;padding:10px">
                            Dean: .........................
                        </td>
                        <td style="border:none;padding:10px">
                            Date: .........................
                        </td>-->
                    </tr>
                </table>

            </div>
        </div>  

<?php } ?>
    <br /><br /><br />

    <div class="col-auto float-right ml-auto donotprint">
        <div class="btn-group btn-group-sm">

            <button class="btn btn-info" onclick="window.print()" ><i class="fa fa-print fa-lg"></i> Print</button>
        </div>
    </div>      





    <!-- the result summary area  -->
    <br />  <br />  <br />  <br />
    <div class="sumres">
<?php if (isset($courses)) { ?>     
            <!-- space for the results summary  -->
            <p align="center"> <span style="font-size: 20px; font-weight: bold;">
    <?= $settings->name ?> </span><br /><?= $settings->address ?>
                <br /> Result Summary Sheet</p>
            <table  style="width:100%;border:none">
                <tr>
                    <td style="border:none;width:120px; font-weight:bold">FACULTY:</td>
                    <td style="border:none;text-align:left"><?= $faculty ?></td>
                    <td style="border:none;width:100px; text-align:left; font-weight:bold">SEMESTER:</td>
                    <td style="border:none;width:150px; text-align:left"><?= $semesta ?></td>
                </tr>
                <tr>
                    <td style="border:none;font-weight:bold">DEPARTMENT:</td>
                    <td style="border:none;text-align:left"><?= $deptmt->name ?></td>
                    <td style="border:none;text-align:left; font-weight:bold">SESSION:</td>
                    <td style="border:none; text-align:left"><?= $session ?></td>
                </tr>
                <tr>
                    <td style="border:none;font-weight:bold">LEVEL:</td>
                    <td style="border:none;text-align:left"><?= $lev ?></td>
                    <td style="border:none;"></td>
                    <td style="border:none;"></td>
                </tr>
                        </table> 
            <br /><br />
            <table style="margin: auto;"> 
                <tr>
                    <td>Total Number of Students </td> <td> <?= $count ?></td>
                </tr>
                <tr>
                    <td>   Number with Complete Pass </td> <td> <?= $count - $persons_with_f ?></td>
                </tr>
                <tr>
                    <td>   Number with Incomplete Pass </td> <td> <?= $persons_with_f ?></td>
                </tr>
                <tr>
                    <td>  Highest CGPA and Name </td> <td> <?= $h_cgpa ?><br /><?= strtoupper($s_h_cgpa) ?></td>


                </tr>
                <tr>
                    <td>   Lowest CGPA and Name</td> <td> <?= $l_cgpa ?><br /><?= strtoupper($s_l_cgpa) ?></td>
                </tr>
                <tr>
                    <td>  Number with CGPA less than 1.00: </td> <td> <?= $cgpa_less1 ?></td>


                </tr> 

            </table>
            <br /> <br />
            <table style="margin-left: 260px;"> <!-- when there is a past semester, use cumulative -->
                <?php if($firstclass!='' || $secondclasslower!='' || $secondclassupper !='' 
                        || $thirdclass!='' || $pass !='' || $failed !='' ) {?>
                <tr>
                    <td> 1st Class </td> <td> <?= $firstclass ?></td>
                </tr> 
                <tr>
                    <td> 2nd Class Upper </td> <td> <?= $secondclassupper ?></td>
                </tr>
                <tr>
                    <td> 2nd Class Lower </td> <td> <?= $secondclasslower ?></td>
                </tr>
                <tr>
                    <td> 3rd Class </td> <td> <?= $thirdclass ?></td>
                </tr>
                <tr>
                    <td> Pass </td> <td> <?= $pass ?></td>
                </tr>
                <tr>
                    <td> Fail </td> <td> <?= $failed ?></td>
                </tr>
                        <?php }else{ ?>
                <!-- no past semester, use current semester -->
                 <tr>
                    <td> 1st Class </td> <td> <?= $c_s_firstclass ?></td>
                </tr> 
                <tr>
                    <td> 2nd Class Upper </td> <td> <?= $c_s_secondclassupper ?></td>
                </tr>
                <tr>
                    <td> 2nd Class Lower </td> <td> <?= $c_s_secondclasslower ?></td>
                </tr>
                <tr>
                    <td> 3rd Class </td> <td> <?= $c_s_thirdclass ?></td>
                </tr>
                <tr>
                    <td> Pass </td> <td> <?= $c_s_pass ?></td>
                </tr>
                <tr>
                    <td> Fail </td> <td> <?= $c_s_failed ?></td>
                </tr>
                
                
                        <?php } ?>

            </table>
            <br /> <br /> <br />
            <table style="border:none; width: 100%">
                <tr style="border:none;">
                    <td style="border:none;padding:10px">
                        VC: .........................
                    </td>
                    <td style="border:none;padding:10px">
                        Date: .........................
                    </td>
<!--                   <td style="border:none;padding:10px">
                        Dean: .........................
                    </td>
                    <td style="border:none;padding:10px">
                        Date: .........................
                    </td>-->
                </tr>
            </table>

<?php } ?>  

        <br />  <br />  <br />

    </div>










    <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> 


    <!-- DataTales Example -->
    <div class="card shadow mb-4 donotprint">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Results Manager</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"
                       style="margin-top: 23px;">
                    <thead>
                        <tr>

                            <th>Student</th>

                            <th>Course</th>
                            <th >Semester</th>
                            <th >Session</th>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Class</th>
                            <th>faculty</th>
                            <th>Department</th>
                            <th >Remark</th>
                            <th >Upload Date</th>
                            <th> Admin </th>
                            <th>Regno</th>
                            <th >Credit Load</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    <tfoot>
                        <tr>

                            <th>Student</th>

                            <th>Course</th>
                            <th >Semester</th>
                            <th >Session</th>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Class</th>
                            <th>faculty</th>
                            <th>Department</th>
                            <th >Remark</th>
                            <th >Upload Date</th>
                            <th> Admin </th>
                            <th>Regno</th>
                            <th >Credit Load</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </tfoot>
                    </thead>
                    <tbody>
<?php
//count grades
$A = 0;
$B = 0;
$C = 0;
$D = 0;
$E = 0;
$F = 0;
foreach ($results as $result):
    //if($result->grade ==$A)  
    ?>
                            <tr>

                                <td><?= $result->has('student') ? $this->Html->link($result->student->fname . ' ' . $result->student->lname, ['controller' => 'Students', 'action' => 'viewstudent', $result->student->id, $this->generateurl($result->student->fname)]) : '' ?></td>
                                <td><?= $result->has('subject') ? $result->subject->name : '' ?></td>
                                <td><?= $result->has('semester') ? $result->semester->name : '' ?></td>
                                <td><?= $result->has('session') ? $result->session->name : '' ?></td>
                                <td><?= $this->Number->format($result->total) ?></td>
                                <td><?= h($result->grade) ?></td>
                                <td><?= h($result->level->name) ?></td>
                                <td><?= $result->has('faculty') ? $this->Html->link($result->faculty->name, ['controller' => 'Faculties', 'action' => 'viewfaculty', $result->faculty->id, $this->generateurl($result->faculty->name)]) : '' ?></td>
                                <td><?= $result->has('department') ? $this->Html->link($result->department->name, ['controller' => 'Departments', 'action' => 'viewdepartment', $result->department->id, $this->generateurl($result->department->name)]) : '' ?></td>

                                <td><?= h($result->remark) ?></td>
                                <td><?= h($result->uploaddate) ?></td>
                                <td><?= $result->has('user') ? $this->Html->link($result->user->username, ['controller' => 'Users', 'action' => 'view', $result->user->id]) : '' ?></td>
                                <td><?= h($result->regno) ?></td>
                                <td><?= $this->Number->format($result->creditload) ?></td>
                                <td class="actions">

    <?= $this->Html->link(__(' '), ['action' => 'updateresult', $result->id, $this->generateurl($result->student->lname)], ['class' => 'btn btn-round btn-primary fa fa-edit', 'title' => 'update result']) ?>
    <?= $this->Form->postLink(__(' '), ['action' => 'delete', $result->id], ['confirm' => __('Are you sure you want to delete # {0}?', $result->id), 'class' => 'btn btn-round btn-danger fa fa-times-circle', 'title' => 'delete result']) ?>
                                </td>
                            </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>

    function getdepartments(facultyid) {

        $.ajax({
            url: '../Results/getdaepts/' + facultyid,
            method: 'GET',
            dataType: 'text',
            success: function (response) {
                // console.log(response);
                document.getElementById('dept1').innerHTML = "";
                document.getElementById('dept1').innerHTML = response;
                //location.href = redirect;
            }
        });

    }

    function getstudents(deptid) {

        $.ajax({
            url: '../Results/studentsindept/' + deptid,
            method: 'GET',
            dataType: 'text',
            success: function (response) {
                // console.log(response);
                document.getElementById('studentss').innerHTML = "";
                document.getElementById('studentss').innerHTML = response;
                //location.href = redirect;
            }
        });

    }

</script>

<script>

    function printDiv(divName) { //alert('am called');
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

</script>

