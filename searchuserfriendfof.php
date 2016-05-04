<?php
session_start();
if (!isset($_SESSION["is_auth"]))
    {

        header("location: login.php");
        exit;

    }
?>
<?php
    $host        = "host=pdc-amd01.poly.edu";
    $port        = "port=5432";
    $dbname      = "dbname=ku336";
    $credentials = "user=ku336 password=e0eycb7p";

    $conn = pg_connect( "$host $port $dbname $credentials"  );
    if(!$conn)
        {
            echo "Error : Unable to open database\n";
        }
    $userName=$_SESSION['user'];
    $friend=$_SESSION['searcheduser'];
?>
<?php
#if the searched user is friend of friend
    $stmt1=pg_prepare($conn,"s","select privacy from users where user_name=$1");
    $sqlname1="s";
    $result1=pg_execute($conn,$sqlname1,array("$friend"));
    $rows1=pg_num_rows($result1);
        if ($rows1>0)
            {
                $privacy=pg_fetch_array($result1,0,PGSQL_NUM);
                if($privacy==1)
                    {
                        $stmt=pg_prepare($conn,"m","select profile from user_profile where user_name=$1");
                        $sqlname="m";
                        $result=pg_execute($conn,$sqlname,array("$friend"));
                        $rows=pg_num_rows($result);
                        if ($rows>0)
                            {
                                echo "This page belongs to  " . $friend ;
                                $profile=pg_fetch_result($result, 0, 0);
                           }
                        else if($rows=0)
                           {
                                echo"No such record exists";
                           }
                        $SQL=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname));
                        pg_query($SQL);
                    }
                else
                    {
                        $profile="The profile is private";
                    }
            }
    $SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
    pg_query($SQL1);
?>
<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
<div class="container">
        <div class="page-header text-center"><h1><?php echo $friend;?></h1></div>
        <div class="row">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                      <a class="navbar-brand" href="profile.php">Techies</a>
                    </div>
                    <ul class="nav navbar-nav">
                      <li><a href="profile.php">Profile</a></li>
                      <li><a href="friends.php">Friends</a></li>
                      <li><a href="settings.php">Settings</a></li>
                      <li><a href="search.php">Search</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" method="Post" role="search">
                        <div class="form-group">
                            <input type="text" id="searchUser" name="searchUser" class="form-control" placeholder="Search Users">
                        </div>
                        <button type="submit" id="searchButton" name="searchButton" class="btn btn-default">Submit</button>
                        <button type="submit" id="addfriend" name="addfriend" class="btn btn-primary">+ Add Friend</button>
                    </form>

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    </ul>
                  </div>
                </nav>
        </div>


<form method="post" action="searchuserfriend.php">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row form-group">
                <div class="col-sm-12">
                    <h6>About Me:</h6>
                    <textarea class="form-control" readonly="" cols="50" rows="2" style="resize:none"><?php echo($profile);?></textarea>
                </div>

        <!--The diary entry-->
        <div class="row">
            <div class="col-sm-2" >
                <h4>Diary Entries:</h4>
            </div>
        </div>
<?php
    $stmt3=pg_prepare($conn,"l","select * from sp_post_diary_entry($1)");
    $sqlname3="l";
    $result3=pg_execute($conn,"l",array("$friend"));
    $rows3=pg_num_rows($result3);
    if ($rows3>0)
        {
            while ($row3=pg_fetch_array($result3,NULL,PGSQL_NUM))
                {
                    if($row3[4]==1)
                            {
                                $time_post=date("Y-M-d(g:i a)",strtotime($row3[3]));
?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <h5>Title:</h5>
                                        <b> <input type="text" class="form-control" name="title_" readonly="" value="<?php echo ($row3[1]);?>"></input> </b>
                                        <p>             </p>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h5>Body:</h5>
                                            <textarea class="form-control" style="resize:none" readonly=""><?php echo($row3[2]);?> </textarea>
                                            <p>             </p>
                                        </div>
                                    </div>
                                </div>
<?php
                            }
                        else
                        {
                            ?>
                            <div class="row">
                                    <div class="col-sm-6">
                                        <b> <textarea class="form-control" readonly="" cols="50" rows="2" style="resize:none">This diary entry is private.</textarea> </b>
                                        <p>             </p>
                                    </div>
                            </div>
                            <?php
                        }
                }
        }
    else echo"The user has no diary entry";
    $SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
    pg_query($SQL3);
?>
</div>
</div>
</div>
</form>
</div>
</body>
</html>