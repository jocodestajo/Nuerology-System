
<?php
session_start();
require '../../config/dbcon.php';

if(isset($_POST['delete_record']))
{
    $records_id = mysqli_real_escape_string($conn, $_POST['delete_record']);

    $query = "DELETE FROM neurology_records WHERE id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Deleted Successfully";
        header("Location: ../../index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Records not Deleted";
        header("Location: ../../index.php");
        exit(0);
    }

}
?>