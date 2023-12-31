<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        if (isset($_GET['credential_id'])) {
            $credential_id = $_GET['credential_id'];
            $sql = "SELECT * FROM profile WHERE credential_id = :credential_id";
        }

        if (isset($_GET['profile_id'])) {
            $profile_id = $_GET['profile_id'];
            $sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
        }


        if (!isset($_GET['credential_id']) && !isset($_GET['profile_id'])) {
            $sql = "SELECT * FROM profile";
        }

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($credential_id)) {
                $stmt->bindParam(':credential_id', $credential_id);
            }

            if (isset($profile_id)) {
                $stmt->bindParam(':profile_id', $profile_id);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($result);
        }
        break;

    case "POST":
        $profile = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO profile (fullname, gender, year, age, course, interest, looking_for, profile, credential_id, preferences, municipality, province) VALUES (:fullname, :gender, :year, :age, :course, :interest, :looking_for, :profile, :credential_id, :preferences, :municipality, :province)";
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
        $stmt->bindParam(':preferences', $profile->preferences);
        $stmt->bindParam(':municipality', $profile->municipality);
        $stmt->bindParam(':province', $profile->province);




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
        $sql = "UPDATE profile SET 
                fullname = :fullname,
                gender = :gender,
                year = :year,
                age = :age,
                course = :course,
                interest = :interest,
                looking_for = :looking_for,
                profile = :profile,
                preferences = :preferences,
                municipality = :municipality,
                province = :province
                WHERE credential_id = :credential_id";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':fullname', $profile->fullname);
        $stmt->bindParam(':gender', $profile->gender);
        $stmt->bindParam(':year', $profile->year);
        $stmt->bindParam(':age', $profile->age);
        $stmt->bindParam(':course', $profile->course);
        $stmt->bindParam(':interest', $profile->interest);
        $stmt->bindParam(':looking_for', $profile->looking_for);
        $stmt->bindParam(':profile', $profile->profile);
        $stmt->bindParam(':credential_id', $profile->credential_id);
        $stmt->bindParam(':preferences', $profile->preferences);
        $stmt->bindParam(':municipality', $profile->municipality);
        $stmt->bindParam(':province', $profile->province);



        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User profile updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to update user profile"
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
