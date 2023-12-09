<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        $email = $_GET['email'];
        $password = $_GET['password'];

        $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User login successful"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to update user login status"
            ];
        }

        echo json_encode($users);

        break;


    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO credential (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        // $stmt->bindParam(':fullname', $user->fullname);
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':password', $user->password);


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
        $user = json_decode(file_get_contents('php://input'));
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
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':birthday', $user->birthday);
        $stmt->bindParam(':gender', $user->gender);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':address', $user->address);
        $stmt->bindParam(':user_id', $user->user_id);
        $stmt->bindParam(':profile_picture', $user->profile_picture);



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
