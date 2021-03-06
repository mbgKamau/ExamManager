<?php 
$page = isset($_GET['pg']) ? $_GET['pg']:1;
$misc = isset($_GET['misc']) ? $_GET['misc']:1;
switch ($misc) {
     case 1:
     $miscellaneous =" WHERE created_at >= curdate() ";
     break;
     case 2:
     $miscellaneous =" ";
     break;
   default:
     $miscellaneous =" WHERE created_at >= curdate() ";
     break;
}
$search = isset($_GET['search']) ? $_GET['search']:null;
$start = ($page > 1) ?($page *12)-12 :0;
$url_search = "";
$sql_patients = "SELECT patient_id, patient_name, phonenumber, birthday FROM patient_details" . $miscellaneous . "LIMIT {$start} , 12";
$p_count ="SELECT COUNT(*) FROM patient_details " . $miscellaneous ;
$srch = 0;

if($search!=null){
      $srch = 1;
      $sql_patients = "SELECT patient_id, patient_name, phonenumber, birthday FROM patient_details WHERE patient_name LIKE '%$search%' OR national_id LIKE '%$search%' OR phonenumber LIKE '%$search%'  LIMIT {$start} , 12";
      $url_search = "&search=".$search;
      $p_count ="SELECT COUNT(*) FROM patient_details WHERE patient_name LIKE '%$search%' OR national_id LIKE '%$search%' OR phonenumber LIKE '%$search%' ";

}
$patients = db_query($sql_patients);

?>
<div class="section">
      <div class="container">

          <div class="panel panel-default panel-faded">
          <div class="panel-body">
        <div class="row">
         <div class="col-md-12">
        
            <h1>Patients</h1>
          </div>
          <div class="col-md-5">
            <form role="form" method="get">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" name="search" placeholder="Enter Patient Name Or National ID">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">search</button>
                  </span>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-offset-3 col-md-4">
            <div class="btn-group">
              <a href="<?php echo "patients?misc=1" ?>" class="btn btn-default <?php if($misc== 1)echo "active";?>"">today</a>
              <a href="<?php echo "patients?misc=2" ?>" class="btn btn-default <?php if($misc== 2)echo "active";?>">all</a>             
            </div>
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
              <ul class="media-list">
               <?php if($patients->num_rows > 0): ?>
                 <?php while ($row = $patients->fetch_assoc()) { $name = explode(" ", $row["patient_name"], 3);?>
                 <li class="col-md-6">
                  <div class="panel panel-default panel-faded">
                    <div class=" file-list">
                        <a class="pull-left img--space" href="patientinfo?id=<?php echo $name[0] . ", " . $name[1] . " " . $name[2];?>"><img class="media-object" src="assets/img/avatar.jpg" height="79" width="79"></a>
                        <div class="media-body">
                          <a href="patientinfo?id=<?php echo $row['patient_id'];?>" ><h4 class="name"><?php echo $row["patient_name"];?></h4></a>
                          <div> mobile .No :<?php echo $row["phonenumber"];?></div>
                          <div> Date Of Birth:<?php echo $row["birthday"];?></div>
                        </div>
                    </div>
                  </div>
                 </li>
                 <?php } ?>
                  <?php else: isset($_GET['search']) ? toast("No patient found using the search terms <i>$search</i>. Try again.", "warning"): toast("<h3>No Patinets Added Today</h3>", "info") ; endif; ?>                
               </ul>  
          </div>
        </div>
        <?php 
          $res = db_query($p_count);
          $tot = $res->fetch_assoc();
          $tot = $tot['COUNT(*)'];
          $total = ceil($tot/12);
        ?>
        <div class="row">
        <div class="col-md-12">

        <ul class="pagination">
        <?php for($x = 1;$x <= $total ; $x++): ?>
          <li><a href="patients?pg=<?php echo $x.$url_search."&misc=".$misc; ?>"><?php echo $x; ?></a></li>
        <?php endfor; ?>
        </ul>
        </div>
        </div>
      </div>
      </div>
    </div>
