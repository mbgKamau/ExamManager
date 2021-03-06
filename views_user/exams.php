<?php 
$page = isset($_GET['pg']) ? $_GET['pg']:1;
$misc = isset($_GET['misc']) ? $_GET['misc']:1;
switch ($misc) {
     case 1:
     $miscellaneous =" WHERE exam_info.created_at >= curdate() ";
     break;
     case 2:
     $miscellaneous =" ";
     break;
     case 3:
     $miscellaneous =" WHERE exam_info.created_at >=curdate() AND exam_done=0 AND report_done=0 ";
     break;
     case 4:
     $miscellaneous =" WHERE exam_done=0 AND report_done=0 ";
     break;
     case 5:
     $miscellaneous =" WHERE exam_done=1 AND report_done=0 ";
     break;
     case 6:
     $miscellaneous =" WHERE exam_done=1 AND report_done=1 ";
     break;
   
   default:
     $miscellaneous =" WHERE exam_info.created_at >=curdate() ";
     break;
 } 
$search = isset($_GET['esearch']) ? $_GET['esearch']:null;
$start = ($page > 1) ?($page *12)-12 :0;
$url_search = "";
$sql_exams = "SELECT exam_info.exam_id, patient_details.patient_name, exam_info.req_physician, exam_info.exam_name, exam_info.modality, exam_info.created_at FROM exam_info INNER JOIN patient_details ON exam_info.patient_id=patient_details.patient_id" . $miscellaneous . "LIMIT {$start} , 12";
$e_count ="SELECT COUNT(*)  FROM exam_info INNER JOIN patient_details ON exam_info.patient_id=patient_details.patient_id " . $miscellaneous;
$srch = 0;

if($search!=null){
      $srch = 1;
      $sql_exams = "SELECT exam_info.exam_id, patient_details.patient_name, exam_info.req_physician, exam_info.exam_name, exam_info.modality, exam_info.created_at  FROM exam_info INNER JOIN patient_details ON exam_info.patient_id=patient_details.patient_id WHERE patient_name LIKE '%$search%' OR exam_name LIKE '%$search%' OR modality LIKE '%$search%'  LIMIT {$start} , 12";
      $url_search = "&search=".$search;
      $e_count ="SELECT COUNT(*) FROM patient_details WHERE patient_name LIKE '%$search%' OR national_id LIKE '%$search%' OR phonenumber LIKE '%$search%' ";

}
$exams = db_query($sql_exams);

?>
<div class="section">
      <div class="container">

           <div class="panel panel-default panel-faded">
                <div class="panel-body">
          <div class="row">
         
          </div>
        <div class="row">
          <div class="col-md-12">
            <h1>Exams</h1>
          </div>
           <div class="col-md-5">
            <form role="form" method="get">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" name="esearch" placeholder="Enter Patient Name">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">search</button>
                  </span>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-offset-2 col-md-5">
            <div class="btn-group">
              <a href="<?php echo "exams?misc=1" ?>" class="btn btn-default <?php if($misc== 1)echo "active";?>"">today</a>
              <a href="<?php echo "exams?misc=2" ?>" class="btn btn-default <?php if($misc== 2)echo "active";?>">all</a>
              <a href="<?php echo "exams?misc=3" ?>" class="btn btn-default <?php if($misc== 3)echo "active";?>"">Worklist</a>
              <a href="<?php echo "exams?misc=4" ?>" class="btn btn-default <?php if($misc== 4)echo "active";?>"">pending</a>
              <a href="<?php echo "exams?misc=5" ?>" class="btn btn-default <?php if($misc== 5)echo "active";?>"">only taken</a>
              <a href="<?php echo "exams?misc=6" ?>" class="btn btn-default <?php if($misc== 6)echo "active";?>"">reported</a>              
            </div>
          </div>
            </div>
            <div class="row">
          <div class="col-md-12">
            <hr>
          </div>
        </div>
            <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th>Exam ID</th>
              <th>Patient Names</th>
              <th>Procedure</th>
              <th>modality</th>
              <th>Requesting Doctor</th>
              <th>Date and Time</th>
              <th>action 1</th>
            </tr>
          </thead>
          <tbody>
          <?php if($exams->num_rows > 0): ?>
            <?php while ($row = $exams->fetch_assoc()) {?>
            <tr> 
            <td><?php echo $row["exam_id"]?></td>
            <td><?php echo $row["patient_name"]?></td>
            <td><?php echo $row["exam_name"]?></td>
            <td><?php echo $row["modality"]?></td>
            <td><?php echo $row["req_physician"]?></td>
            <td><?php echo $row["created_at"]?></td>
            <td><a class="btn btn-default" href="examinfo?id=<?php echo $row['exam_id'];?>">view</a></td>
            </tr>
            <?php } ?>
          <?php else: ?>
            <h3>  no Exams </h3>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php 
          $res = db_query($e_count);
          $tot = $res->fetch_assoc();
          $tot = $tot['COUNT(*)'];
          $total = ceil($tot/12);
        ?>
        <div class="row">
        <div class="col-md-12">
        <ul class="pagination">
        <?php for($x = 1;$x <= $total ; $x++): ?>
          <li><a href="exams?pg=<?php echo $x.$url_search."&misc=".$misc; ?>"><?php echo $x; ?></a></li>
        <?php endfor; ?>
        </ul>
        </div>
        </div>
      </div>
  </div>
</div>
</div>
</div>