<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":


        if (isset($_GET['credential_id'])) {
            $credential_id = $_GET['credential_id'];
            $sql = "SELECT * FROM credential WHERE credential_id = :credential_id";
        }


        if (!isset($_GET['credential_id'])) {
            $sql = " SELECT * FROM credential";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($credential_id)) {
                $stmt->bindParam(':credential_id', $credential_id);
            }

            $stmt->execute();
            $cred = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($cred);
        }

        break;


    case "POST":
        $profile = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO profile (fullname, gender, year, age, course, interest, looking_for, profile, credential_id) VALUES (:fullname, :gender, :year, :age, :course, :interest, :looking_for, :profile, :credential_id)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':fullname', $profile->fullname);
        $stmt->bindParam(':gender', $profile->gender);
        $stmt->bindParam(':year', $profile->year);
        $stmt->bindParam(':age', $profile->age);
        $stmt->bindParam(':course', $profile->course);
        $stmt->bindParam(':interest',  $profile->interest);
        $stmt->bindParam(':looking_for', $profile->looking_for);
        $stmt->bindParam(':profile', $profile->profile);
        $stmt->bindParam(':credential_id', $profile->credential_id);



        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User created successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User creation failed"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $profile = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE users 
                SET name = :name, 
                    email = :email, 
                    password = :password, 
                    birthday = :birthday, 
                    gender = :gender, 
                    created_at = :created_at, 
                    address = :address,
                    profile_picture = :profile_picture
                WHERE user_id = :user_id";

        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':name', $profile->name);
        $stmt->bindParam(':email', $profile->email);
        $stmt->bindParam(':password', $profile->password);
        $stmt->bindParam(':birthday', $profile->birthday);
        $stmt->bindParam(':gender', $profile->gender);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':address', $profile->address);
        $stmt->bindParam(':user_id', $profile->user_id);
        $stmt->bindParam(':profile_picture', $profile->profile_picture);



        if ($stmt->execute()) {

            $response = [
                "status" => "success",
                "message" => "User updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User update failed"
            ];
        }

        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM users WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[2]);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User deletion failed"
            ];
        }
}
