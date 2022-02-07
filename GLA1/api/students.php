<?php
    include_once '../config/connection.php';

    $connection = new Connection(); //Instantiate connection
    $db = $connection -> connect(); //Connection String

    $table = 'student';

    switch ($_SERVER['REQUEST_METHOD']){ //Filter requests

        //`UPDATE at id` method
        case "PUT" : {

            //Get raw read-only data from I/O stream
            $data = json_decode(file_get_contents('php://input'));

            //If variable exists from raw data else default values
            $student_id = isset($data -> id) ? $data -> id : "";
            $student_name = isset($data -> student_name) ? $data -> student_name : "";
            $student_number = isset($data -> student_number) ? $data -> student_number : "null";
            $student_age = isset($data -> student_age) ? $data -> student_age : "null";

            //Construct Query
            $query = 'UPDATE ' .
                $table .
                ' SET  student_name = "'. $student_name .
                '", student_number = ' . $student_number .
                ', student_age = ' . $student_age .
                ' WHERE id = ' . $student_id;

            //Wrapped in try catch to prevent API from crashing
            try {

                //Execute query
                $db -> query($query);

                //Checks if database was updated
                if(mysqli_affected_rows($db) > 0) {
                    echo json_encode(array("message"=>"Succesfully Updated " . $student_id . " From Database", 
                    "Student Name: " => $student_name, "Student Number: " => $student_number, "Student Age: " => $student_age));
                } else {
                    echo json_encode(array("message"=>"No Student With The ID: " . $student_id . " Found In Database "));
                    die(); //End request
                }
            } catch (Throwable $err) {
                printf("\n Error: \n %s.\n", $err);
            }   
        }
        break;

        //`DELETE at id` method
        case "DELETE" : {

            //Get raw read-only data from I/O stream
            $data = json_decode(file_get_contents('php://input'));

            //If variable exists from raw data else default values
            $student_id = isset($data -> id) ? $data -> id : "";
            $student_name = isset($data -> student_name) ? $data -> student_name : "";
            $student_number = isset($data -> student_number) ? $data -> student_number : "";
            $student_age = isset($data -> student_age) ? $data -> student_age : "";

            //Construct Query
            $query = 'DELETE FROM ' . $table . ' WHERE id = ' . $student_id;

            //Wrapped in try catch to prevent API from crashing
            try {

                //Execute query
                $db -> query($query);

                //Checks if database was updated
                if(mysqli_affected_rows($db) > 0) {
                    echo json_encode(array("message"=>"Succesfully Deleted " . $student_id . " From Database"));
                } else {
                    echo json_encode(array("message"=>"Could Not Delete or Already Deleted: " . $student_id . " From Database"));
                    die();
                }
            } catch (Throwable $err) {
                echo json_encode(array("message"=>"Succesfully Deleted " . $student_id . " From Database"));
                printf("\n Error: \n %s.\n", $err);
            }   
        }
        break;

        //`GET data at id` or `GET data` methods
        case "GET" : {

            //If variable exists from raw data else default values
            $student_id = isset($_GET['id']) ? $_GET['id'] : "";
            
            //Checks if GET request all or a single data
            if(strlen($student_id) > 0) {

                //Construct Query
                $query = 'SELECT * FROM ' . $table . ' WHERE id = ' . $student_id;

                //Wrapped in try catch to prevent API from crashing
                try {

                    //Execute Query
                    $result = $db -> query($query);

                    //Gets number of rows
                    $rows = mysqli_num_rows($result);
                    
                    //Loop through all the rows and add to result array
                    if ($rows > 0) {

                        //Instantiate new array to store data retrieved
                        $result_arr = array();
                        $result_arr['data'] = array();

                        //Fetches associated array from result and pushes data to $result_arr
                        while ($thisRow = $result->fetch_assoc()) {
                            
                            extract($thisRow);
                            $query_item = array(
                                'id' => $id,
                                'student_name' => $student_name,
                                'student_number' => $student_number,
                                'student_age' => $student_age
                            );
                            array_push($result_arr['data'],$query_item);
                        }
                        echo json_encode($result_arr);
                    } else {
                        echo json_encode(array('message' => 'No Data Found'));
                    }
                } catch (Throwable $err) {
                    printf("\n Error: \n %s.\n", $err);
                }
            } else { //Else when id isn't specified

                //Construct Query
                $query = 'SELECT * FROM ' . $table;

                //Wrapped in try catch to prevent API from crashing
                try {

                    //Execute Query
                    $result = $db -> query($query);

                    //Get number of rows
                    $rows = mysqli_num_rows($result);
                    
                    //Loop through all the rows and add to result array
                    if ($rows > 0) {

                        //Instantiate new array to store data retrieved
                        $result_arr = array();
                        $result_arr['data'] = array();

                        //Fetches associated array from result and pushes data to $result_arr
                        while ($thisRow = $result->fetch_assoc()) {
                            extract($thisRow);
                            $query_item = array(
                                'id' => $id,
                                'student_name' => $student_name,
                                'student_number' => $student_number,
                                'student_age' => $student_age
                            );
                            array_push($result_arr['data'],$query_item);
                        }
                        echo json_encode($result_arr);
                    } else {
                        echo json_encode(array('message' => 'No Data Found'));
                    }
                } catch (Throwable $err) {
                    printf("\n Error: \n %s.\n", $err);
                }
            }
        }
        break;

        //`POST data` method (create new student with id)
        case "POST" : {

            //Get raw read-only data from I/O stream
            $data = json_decode(file_get_contents('php://input'));

            if(!isset($data -> id)) {
                //If ID not set throw error
                echo json_encode(array("message"=>"ID not set"));
                die();
            }
            //If variable exists from raw data else default values
            $student_id = isset($data -> id) ? $data -> id : ""; 
            $student_name = isset($data -> student_name) ? $data -> student_name : "";
            $student_number = isset($data -> student_number) ? $data -> student_number : "null";
            $student_age = isset($data -> student_age) ? $data -> student_age : "null";

            //Construct Query
            $query = 'INSERT INTO ' .
                $table .
                ' SET id = ' . $student_id .
                ', student_name = "' . $student_name .
                '", student_number = ' . $student_number .
                ', student_age = ' . $student_age;
            
            //Wrapped in try catch to prevent API from crashing
            try {

                //Execute Query
                $db -> query($query);

                //Checks if database was updated
                if(mysqli_affected_rows($db) > 0) {
                    echo json_encode(array("message"=>"Successfully created new student!"));
                } else {
                    echo json_encode(array("message"=>"Cannot create new student!"));
                    die();
                }
            } catch (Throwable $err) {
                printf("\n Error: \n %s.\n", $err);
            }
           
        }
        break;
    }
?>