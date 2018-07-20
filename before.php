<?php
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }


      // define variables and set to empty values
      $fnameErr = $lnameErr = $emailErr = $messageErr = "";
      $fname = $lname = $email = $message = "";
      $nameErr = $dateErr = $salonErr = $phoneErr = "";
      $name = $date = $salon = $phone = "";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $selectedSalon = $_POST['choicesalons'];
        
        if ($selectedSalon == "default"){
          $salonErr = "Select a Salon";
        } else{
          $selectedSalon = $_POST['choicesalons'];
        }

        $selectedHairstyle = isset($_POST["hairstyles"]) ? $_POST["hairstyles"] : '';
        
        if (empty($_POST["name"])) {
          $nameErr = "Name is required";
        } else {
          $name = test_input($_POST["name"]);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
          }
        }

        if (empty($_POST["date"])) {
          $dateErr = "Date is required";
        } else {
          $date = test_input($_POST["date"]);
        }
        
        
        if (empty($_POST["phone"])) {
          $phoneErr = "Phone is required";
        } else {
          $phone = test_input($_POST["phone"]);
          // check if e-mail address is well-formed
          if (!preg_match('/^[0-9]/', $phone)) {
            $phoneErr = "Invalid phone format";
          }
          header('Location: https://www.octangl.com/success.php');
        }
        /*function sendMail($name, $selectedSalon, $date, $phone, $selectedHairstyle) {
        
          $to = "princekelvin91@gmail.com";
          $subject = "New Appointment Booked On Octangl";
          $txt = $name . " just booked an appointment with " . $selectedSalon . " for a hair-do on " . $date . " ." . "Phone Number: " . $phone . " Choice hairstyle: " . $selectedHairstyle;
          $headers = "From: octangl.com";
      
          mail($to,$subject,$txt,$headers);
        }
        sendMail($name, $selectedSalon, $date, $phone, $selectedHairstyle);*/
       

        
      
      }elseif(isset($_POST["submit2"])) {

        function sendMailContact($message, $email) {
        
          $to = "princekelvin91@gmail.com";
          $subject = "New Interaction On Octangl";
          $txt = $message;
          $headers = "From: " . $email;
      
          mail($to,$subject,$txt,$headers);
        }
        
        if(! empty($_FILES["fileToUpload"]["tmp_name"])) {
          $target_dir = "uploads/";
          $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

          // echo "<pre>";
          // var_export($_FILES["fileToUpload"]);
          // echo "</pre>";
          // exit();
          // Check if image file is a actual image or fake image
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

          if($check !== false) {
              $fileOk = "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
          } else {
              $fileErr = "File is not an image.";
              $uploadOk = 0;
          }

          // Check if file already exists
          if (file_exists($target_file)) {
              $fileErr = "Sorry, file already exists.";
              $uploadOk = 0;
          }
          // Check file size
          if ($_FILES["fileToUpload"]["size"] > 500000) {
              $fileErr = "Sorry, your file is too large.";
              $uploadOk = 0;
          }
          // Allow certain file formats
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" ) {
              $fileErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
          }
          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk != 0) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
              } else {
                $fileErr = "Sorry, there was an error uploading your file.";
              }
          }
        }

        if (empty($_POST["fname"])) {
          $fnameErr = "First Name is required";
        } else {
          $fname = test_input($_POST["fname"]);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
            $fnameErr = "Only letters and white space allowed";
          }
        }
      
        if (empty($_POST["lname"])) {
          $lnameErr = "Last Name is required";
        } else {
          $fname = test_input($_POST["lname"]);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z ]*$/",$lname)) {
            $lnameErr = "Only letters and white space allowed";
          }
        }
        
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
          $email = test_input($_POST["email"]);
          // check if e-mail address is well-formed
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
          }
        }
      
        if (empty($_POST["message"])) {
          $messageErr = "Message is required";
        } else {
          $message = test_input($_POST["message"]);
        } 
        
        //sendMailContact($message, $email);
        header('Location: https://www.octangl.com/success.php');
        die();
    }
?>