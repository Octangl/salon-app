<?php ini_set("display_errors", 1); ?>
<?php
      //require_once('form_handler.php');
      require_once('AfricasTalkingGateway.php');

      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
      // define variables and set to empty values
      $fnameErr = $lnameErr = $emailErr = $contactMessageErr = $sendMessageErr = $formMessageErr =  "";
      $fname = $lname = $email = $contactMessage = $fileName = "";
      $nameErr = $dateErr = $salonErr = $phoneErr = "";
      $name = $date = $salon = $phone = "";

      if (isset($_POST["submit"])) {
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
          if (!preg_match('/^[+][0-9]*$/', $phone)){
            $phoneErr = "Country code '+234' missing!";
          }
        }  

        
          /*SMS handling API*/
        // Specify your authentication credentials
        $message = "";
        $username   = "Octangl";
        $apikey     = "a7e467cf0ddf3c91f70ffa7f06d101d5e4f520a24ea950420cbb7297921caaba";
        // Specify the numbers that you want to send to in a comma-separated list
        // Please ensure you include the country code (+234 for Nigeria in this case)
        $recipients = "+2348166879424";

        if (! empty($name && $date && $phone && $selectedSalon != "default")){
          // And of course we want our recipients to know what we really do
          $message    = $name . " just booked an appointment with " . $selectedSalon . " for a hair-do on " . $date . " ." . "Phone Number: " . $phone . " Choice hairstyle: " . $selectedHairstyle;
        } else {
          $sendMessageErr = "Fill Out All Fields!";
        }
        
        // Create a new instance of our awesome gateway class
        $gateway    = new AfricasTalkingGateway($username, $apikey);
        /*************************************************************************************
          NOTE: If connecting to the sandbox:
          1. Use "sandbox" as the username
          2. Use the apiKey generated from your sandbox application
            https://account.africastalking.com/apps/sandbox/settings/key
          3. Add the "sandbox" flag to the constructor
          $gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
        **************************************************************************************/
        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block
        try 
        { 
          // Thats it, hit send and we'll take care of the rest. 
          $results = $gateway->sendMessage($recipients, $message);
                    
          foreach($results as $result) {
            // status is either "Success" or "error message"
            $recipientNumber = $result->number;
            $sendMessageErr = $result->status;
            if ($sendMessageErr == "Success"){
              $sendMessageErr = "Appointment Booked";
            }
            $messageStatusCode = $result->statusCode;
            $sentMessageId = $result->messageId;
            $messageCost = $result->cost;
          }
        }
        catch ( AfricasTalkingGatewayException $e )
        {
          $sendMessageErr = "Error: ".$e->getMessage();
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
                  $fileErr = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                  $fileName = basename( $_FILES["fileToUpload"]["name"]);
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
          $contactMessageErr = "Message is required";
        } else {
          $contactMessage = test_input($_POST["message"]);
        }
        /*SMS handling API*/
        // Specify your authentication credentials
        $message = "";
        $username   = "Octangl";
        $apikey     = "a7e467cf0ddf3c91f70ffa7f06d101d5e4f520a24ea950420cbb7297921caaba";
        // Specify the numbers that you want to send to in a comma-separated list
        // Please ensure you include the country code (+234 for Nigeria in this case)
        $recipients = "+2348166879424";
        if (! empty($fname && $lname && $contactMessage && $email)){
          // And of course we want our recipients to know what we really do
          $message = $fname . " " . $lname ." wrote via contact form: '" . $contactMessage . "' \nemail: " . $email . "\n" . "uploaded Image: " .$fileName;
        } else {
          $formMessageErr = "Fill Out All Fields!";
        }
        // Create a new instance of our awesome gateway class
        $gateway    = new AfricasTalkingGateway($username, $apikey);
        /*************************************************************************************
          NOTE: If connecting to the sandbox:
          1. Use "sandbox" as the username
          2. Use the apiKey generated from your sandbox application
            https://account.africastalking.com/apps/sandbox/settings/key
          3. Add the "sandbox" flag to the constructor
          $gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
        **************************************************************************************/
        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block
        try 
        { 
          // Thats it, hit send and we'll take care of the rest. 
          $results = $gateway->sendMessage($recipients, $message);
                    
          foreach($results as $result) {
            // status is either "Success" or "error message"
            $recipientNumber = $result->number;
            $formMessageErr = $result->status;
            if ($formMessageErr == "Success"){
              $formMessageErr = "Message Sent!";
            }
            $messageStatusCode = $result->statusCode;
            $sentMessageId = $result->messageId;
            $messageCost = $result->cost;
          }
        }
        catch ( AfricasTalkingGatewayException $e )
        {
          $formMessageErr = "Error: ".$e->getMessage();
        }
    } 
        //sendMailContact($message, $email);
    
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Octangl &mdash;Book Appointments With Proffesional Hair Salons</title>
		<meta name="description" content="Online Salon Booking Platform In Nigeria">
		<meta name="keywords" content="hair salon, best salons in Nigeria, make my hair, where can i make my hair, salons in owerri">
    
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,700" rel="stylesheet">


    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/fonts/ionicons/css/ionicons.min.css">
    
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    
    <link rel="stylesheet" href="assets/fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="assets/fonts/fontawesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="assets/css/select2.css">
    
    <link rel="stylesheet" href="assets/css/loader-css/style.css">
    <link rel="stylesheet" href="assets/css/loader-scss/style.scss">

    <link rel="stylesheet" href="assets/css/helpers.css">
    <link rel="stylesheet" href="assets/css/style.css">

	</head>
	<body> 
    <nav class="navbar navbar-expand-lg navbar-dark probootstrap_navbar" id="probootstrap-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.php">Octangl</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#probootstrap-menu" aria-controls="probootstrap-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span><i class="ion-navicon"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="probootstrap-menu">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#section-contact">Contact</a></li>
            <li>
              <div class="input-group margin-bottom-sm">
                <input class="form-control" id="mysearch-bar" type="text" placeholder="Search For A Salon">
                <span class="input-group-addon" id="mysearch-icon"><i class="fa fa-search fa-fw"></i></span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- END nav -->
  
    <section class="probootstrap-cover overflow-hidden relative"  style="background-image: url('assets/images/header-hair.jpg');" data-stellar-background-ratio="0.5"  id="section-home">
      <div class="overlay"></div>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md">
            <h2 class="heading mb-2 display-4 font-light probootstrap-animate">
               <!-- <span
                class="txt-rotate"
                data-period="2000"
                data-rotate='[ "be", "as", "beautiful"]'>
                </span>-->
                Book An Appointment With A salon For Your Next Hair-Do!
            </h2>
            <p class="lead mb-5 probootstrap-animate">No More Waiting in cues to get your hair done, Get Prompt Attention On All Scheduled Visits </p>
           <!-- <p class="probootstrap-animate">
              <a href="#section-city-guides" role="button" class="btn btn-primary p-3 mr-3 pl-5 pr-5 text-uppercase d-lg-inline d-md-inline d-sm-block d-block mb-3">See All Listed Salons</a> 
            </p> -->
          </div>
          
          

          <div class="col-md probootstrap-animate " id="booking-form">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
            <div class="form-group">
            <label class="btn btn-danger btn-block smserror" id="smserror"> 
              <?php 
                if ($sendMessageErr == ""){
                  echo '<script type="text/javascript">
                            document.getElementById("smserror").style.display = "none";  
                  </script>';
                }else if($sendMessageErr == "Appointment Booked"){
                  echo '<script type="text/javascript">
                      if ( document.getElementById("smserror").classList.contains("btn-danger") ){
                        document.getElementById("smserror").classList.remove("btn-danger");
                        document.getElementById("smserror").classList.add("btn-primary");
                      }  
                  </script>';
                  echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i> Appointment Booked!';
                }else{
                  echo $sendMessageErr;
                }
              ?>
            </label>
              <div class="row mb-3">
                <div class="col-md">
                      <label for="id_label_single" style="color:white;"><strong>SELECT YOUR CHOICE SALON</strong></label>

                      <label for="id_label_single" style="width: 100%; color:white;">
                        <select class="js-example-basic-single js-states form-control" name="choicesalons" id="id_label_single" style="width: 100%;">
                          <option value="default">Select Salon</option>
                          <option value="D'Hair Forest">D'Hair Forest</option>
                          <option value="Hair Wizard">Hair Wizard</option>
                        </select>
                      </label>
                     <span class="error">* <?php echo $salonErr;?>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="probootstrap-date-departure" style="color:white;"><strong>CREATE APPOINTMENT</strong></label>
                        <div class="probootstrap-date-wrap">
                          <span class="icon ion-calendar"></span> 
                          <input type="text" id="probootstrap-date-departure" name="date" class="form-control" placeholder="Schedule Your Hair-Do">
                        </div>
                        <span class="error">* <?php echo $dateErr;?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-6">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label for="probootstrap-date-departure" style="color:white;"><strong>NAME</strong></label>
                        <input type="text" class="form-control" id="name"  name="name" placeholder="Name">
                        <span class="error">* <?php echo $nameErr;?></span>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                          <label for="probootstrap-date-departure" style="color:white;"><strong>PHONE</strong></label>
                          <input type="text" class="form-control" id="phone"  name="phone" placeholder="Phone">
                          <span class="error">* <?php echo $phoneErr;?></span>
                        </div>
                    </div> 
                </div>
                <section>
                
                

                <div class="container">
                <!-- Trigger/Open The Modal -->
                  <!-- The Modal -->
                <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                  <a href="#"><span class="close">X</span></a>
                  <p class="text-center" style="color:black;">Hair Selection Is Optional</p>
                  <div class="row hair-row">
                    <div class="owl-carousel js-owl-carousel">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 hair-column">
                            <input 
                              type="radio" name="hairstyles" value="hairstyle1"
                              id="hairstyle1" class="input-hidden" />
                            <label for="hairstyle1">
                              <img 
                                src="assets/images/hair1.jpg" class="img-responsive img-circle img-thumbnail">
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 hair-column">
                            <input 
                              type="radio" name="hairstyles" value="hairstyle2"
                              id="hairstyle2" class="input-hidden" />
                            <label for="hairstyle2">
                              <img 
                                src="assets/images/hair3.jpg" class="img-responsive img-circle img-thumbnail">
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 hair-column">
                            <input 
                              type="radio" name="hairstyles" value="hairstyle3"
                              id="hairstyle3" class="input-hidden" />
                            <label for="hairstyle3">
                              <img 
                                src="assets/images/hair4.jpg" class="img-responsive img-circle img-thumbnail">
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 hair-column">
                            <input 
                              type="radio" name="hairstyles" value="hairstyle4"
                              id="hairstyle4" class="input-hidden" />
                            <label for="hairstyle4">
                              <img 
                                src="assets/images/hair1.jpg" class="img-responsive img-circle img-thumbnail">
                            </label>
                        </div>
                    </div>
                    <p class="text-center" style="color:black; font-size:15px;">Didn't See Your Hairstyle? <a href="#section-contact">Send Us A Picture</a> We'd Have It Added </p>
                    </div>

                  </div>
                </div>
                
              </section>
                <div class="row">
                  <div class="col-md">
                      <div class="form-group text-center">
                        <span id="hairBtn" style="color:white;" class="btn btn-default btn-sm"><i class="fa fa-picture-o" aria-hidden="true"></i>Select Hairstyle</span>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md">
                    <input type="submit" id="mySubmit" value="Book Now" name="submit" class="btn btn-primary btn-block">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    
    </section>
    <!-- END section -->


    <!--removed section
    <section class="probootstrap_section" id="section-city-guides">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Currently Listed Salons</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="assets/images/salon2.jpeg" alt="D'hair Wizard" class="img-fluid">
              <div class="probootstrap-text">
                  <div class='rating-stars text-center'>
                    <div>
                        <a class="btn btn-default btn-sm" href="#">
                        <i class=""></i> D'hair Forest</a>
                    </div>
                      <ul id='stars'>
                        <li class='star hover' title='Poor' data-value='1'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Fair' data-value='2'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Good' data-value='3'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Excellent' data-value='4'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='WOW!!!' data-value='5'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                      </ul>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="assets/images/salon2.jpeg" alt="Hair Wizard" class="img-fluid">
              <div class="probootstrap-text">
                  <div class='rating-stars text-center'>
                    <div>
                        <a class="btn btn-default btn-sm" href="#">
                        <i class=""></i> Hair Wizard</a>
                    </div>
                      <ul id='stars'>
                        <li class='star hover' title='Poor' data-value='1'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Fair' data-value='2'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Good' data-value='3'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Excellent' data-value='4'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='WOW!!!' data-value='5'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                      </ul>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="assets/images/salon2.jpeg" alt="" class="img-fluid">
              <div class="probootstrap-text">
                  <div class='rating-stars text-center'>
                    <div>
                        <a class="btn btn-default btn-sm" href="#">
                        <i class=""></i> Vickys Place</a>
                    </div>
                      <ul id='stars'>
                        <li class='star hover' title='Poor' data-value='1'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Fair' data-value='2'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Good' data-value='3'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Excellent' data-value='4'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='WOW!!!' data-value='5'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                      </ul>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="assets/images/salon2.jpeg" alt="Free Template by uicookies.com" class="img-fluid">
              <div class="probootstrap-text">
                  <div class='rating-stars text-center'>
                    <div>
                        <a class="btn btn-default btn-sm" href="#">
                        <i class=""></i> Hair World</a>
                    </div>
                      <ul id='stars'>
                        <li class='star hover' title='Poor' data-value='1'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Fair' data-value='2'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star hover' title='Good' data-value='3'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Excellent' data-value='4'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='WOW!!!' data-value='5'>
                          <i class='fa fa-star fa-fw'></i>
                        </li>
                      </ul>
                  </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
    
   END section -->

    <section class="probootstrap_section bg-light" id="section-contact">
      <div class="container">
        
        <div class="row">
          <div class="col-md-6 probootstrap-animate">
          <h2 class="display-4 border-bottom probootstrap-section-heading">Contact Us</h2>
            <p class="mb-5">Have A Salon You Would Want Us To List ? <br> Or You Have A Suggestion To Help Improve Our Services ? <br> Reach Out To Us Through The Contacts </p>
            <div class="row">
              <div class="col-md-6">
                <ul class="probootstrap-contact-details">
                  <li>
                    <span class="text-uppercase"><span class="ion-paper-airplane"></span> Email</span>
                    hello@octangl.com
                  </li>
                  <li>
                    <span class="text-uppercase"><span class="ion-ios-telephone"></span> Phone</span>
                    +234 706 082 9548
                  </li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="probootstrap-contact-details">
                    <span class="text-uppercase"><span class="ion-location"></span> Address</span>
                    <br>Owerri, Imo State <br>
                    #30 Mbari Street, Ikenegbu Layout
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6  probootstrap-animate">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="probootstrap-form probootstrap-form-box mb60" enctype="multipart/form-data">
              <label class="btn btn-danger btn-block contacterror" id="contacterror"> 
                      <?php 
                        if ($formMessageErr == ""){
                          echo '<script type="text/javascript">
                                    document.getElementById("contacterror").style.display = "none";  
                          </script>';
                        }else if($formMessageErr == "Message Sent!"){
                          echo '<script type="text/javascript">
                              if ( document.getElementById("contacterror").classList.contains("btn-danger") ){
                                document.getElementById("contacterror").classList.remove("btn-danger");
                                document.getElementById("contacterror").classList.add("btn-primary");
                              }  
                          </script>';
                          echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i> Message Sent!';
                        }else{
                          echo $formMessageErr;
                        }
                      ?>
                </label>
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="fname" class="sr-only sr-only-focusable">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name">
                    <span class="error">* <?php echo $fnameErr;?></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="lname" class="sr-only sr-only-focusable">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name">
                    <span class="error">* <?php echo $lnameErr;?></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="sr-only sr-only-focusable">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                <span class="error">* <?php echo $emailErr;?></span>
              </div>
              <div class="form-group">
                <label for="message" class="sr-only sr-only-focusable">Message</label>
                <textarea cols="30" rows="10" class="form-control" id="message" name="message" placeholder="Write your message"></textarea>
                <span class="error">* <?php echo $contactMessageErr;?></span>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="text-center">
                    <div class="form-group">
                      <input type="file" name="fileToUpload" id="fileToUpload">
                      <span class="error"> <?php echo isset($fileErr) ? $fileErr : '' ;?></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" id="submit" name="submit2" value="Send Message">
              </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    
    <footer class="probootstrap_section probootstrap-border-top">
      <div class="container">
      <!--
        <div class="row mb-5">
          <div class="col-md-3">
            <h3 class="probootstrap_font-18 mb-3">Quick Links</h3>
            <ul class="list-unstyled">
              <li><a href="https://free-template.co" target="_blank">Home</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <h3 class="probootstrap_font-18 mb-3">Quick Links</h3>
            <ul class="list-unstyled">
              <li><a href="https://free-template.co" target="_blank">About</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <h3 class="probootstrap_font-18 mb-3">Quick Links</h3>
            <ul class="list-unstyled">
              <li><a href="https://free-template.co" target="_blank">Services</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <h3 class="probootstrap_font-18 mb-3">Quick Links</h3>
            <ul class="list-unstyled">
              <li><a href="https://free-template.co" target="_blank">Contact</a></li>
            </ul>
          </div>
        </div>-->
        <div class="row pt-5">
          <div class="col-md-12 text-center">
            <p class="probootstrap_font-14"><strong> &copy; 2018 Octangl. All Rights Reserved. </strong>
            <!-- <p class="probootstrap_font-14">Demo Images: <a href="https://unsplash.com/" target="_blank">Unsplash</a></p> -->
          </div>
        </div>
      </div>
    </footer>


    <script src="assets/js/jquery.min.js"></script>
    
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>

    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>

    <script src="assets/js/select2.min.js"></script>

    <script src="assets/js/main.js"></script>
   <!-- <script src="assets/js/form_handler.js"></script> -->
    
	</body>
</html>