<?php
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
//load data from forms into variables
$user_name  = get_input("user_name");
$password   = md5(get_input("password"));

//the sql statement and querrying the database
$sql_login = "SELECT * FROM user_details WHERE user_name = '$user_name'";
$a_user = db_query($sql_login);
$this_user = $a_user->fetch_assoc();

//check if password is a match
if ($password == $this_user["password"]) {
    
    $_SESSION["full_name"]      = $this_user["full_name"];
    $_SESSION["user_name"]      = $this_user["user_name"];
    $_SESSION["user_id"]        = $this_user["user_id"];
    $_SESSION["admin"]          = $this_user["admin"];
    $_SESSION["first_login"]    = $this_user["first_login"];
    
    //for session expiry
    $_SESSION["start_time"]        = time();
    header('Location: /ExamManager/');
    }
    
else {
        toast("Error!</strong>wrong username or password.", "danger");
}

}

?>
<div class="section">
    <div class="container">
        <div class="col-md-offset-3  col-md-6">
            <h1 class="text-center landing-top" >Exam Manager</h1>
            <hr>
        </div>
       <div class="col-md-offset-4  col-md-4"><br>
          <div class="panel panel-default panel-faded">
            <div class="panel-body">
            <h1 class="text-center">Login</h1>
            <form method="post">
                <div class="form-group">
                    <br>
                  <input type="text" class="form-control" name="user_name" placeholder="username">
                </div>
                <div class="form-group">
                    <br>
                  <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password">
                </div>
                <div class="form-group">
                      <label>
                        <input type="checkbox" name="remember" value="yes"> Remember me
                      </label>
                  </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-block">Login</button>
                </div>
            </form>
            </div>
           </div>
        </div>
    </div>
</div>