  <?php

  $servername = "sci-mysql.lboro.ac.uk";
  $dbname = "coa123edb";
  $username = "coa123edb";
  $password = "E4XujVcLcNPhwfBjx-";


  $conn = mysqli_connect($servername, $username, $password, $dbname);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  ?>






  <header>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="ENT CARE HUB.png" id="logo">
        </a>
        </div>
      </div>
    </nav>
  </header>

  <body>
    <div class="bigQuestion">
      <h2><u><span>Where</span></u> is your problem?</h2>
    </div>

    <div class="card-group">
      
      <div class="card" id = "ear">
        <img src="ear.jpg" class="card-img-top" alt="ear image">
        <div class="card-body">
          <h5 class="card-title">Ear and Balance</h5>
          <p class="card-text">See our doctors that specialise in ear and balance issues.</p>
        </div>
      </div>
      
      
      <div class="card" id = "nose">
        <img src="nose.webp" class="card-img-top" alt="nose image">
        <div class="card-body">
          <h5 class="card-title">Nose and Sinuses</h5>
          <p class="card-text">Our specialists for nose or sinus health issues.</p>
        </div>
      </div>
      
      
      <div class="card" id = "throat">
        <img src="throat.jpg" class="card-img-top" alt="throat and voice image">
        <div class="card-body">
          <h5 class="card-title">Voice or Throat</h5>
          <p class="card-text">See our specialists who can help you with any nose or throat issues.</p>
        </div>
      </div>
      
      
      <div class="card" id = "pediatric">
        <img src="pediatric.png" class="card-img-top" alt="pediatrician">
        <div class="card-body">
          <h5 class="card-title">ENT care for Children</h5>
          <p class="card-text">See our specialists that can help your child with any ENT problems.</p>
        </div>
      </div>
      
      <div class="card" id ="allergy">
        <img src="allergy.webp" class="card-img-top" alt="allergies">
        <div class="card-body">
          <h5 class="card-title">ENT related Allergies</h5>
          <p class="card-text">See our doctors who specialise in ENT related allergies.</p>
        </div>
      </div>
      <div class="card" id = "headneck">
        <img src="headneck.jpg" class="card-img-top" alt="head/neck surgery">
        <div class="card-body">
          <h5 class="card-title">Head or Neck Surgery</h5>
          <p class="card-text">See our surgeons who can provide head or neck surgeries.</p>
        </div>
      </div>
    </div>


    <div class="hiddenDiv" id = "earInfo">
      <h2>Our Ear and Balance specialists:</h2>

      
      <label for "orderSelect1">Order By:</label>
      <select id = "orderSelect1">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>

      </select>


      <table id = "consultantsTable1"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available (click for more info) </th></tr>
      <?php
      $sql = "SELECT 
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 1
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      

      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }
          echo "</tr>";

        }
        echo "</table>";
      }

    ?>
    <div id="map1" style="height: 400px;"></div>

    <div id="ratingsModal1" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(1)" >&times;</span>
    <h2 id="ratingsTitle1">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart1"></canvas>
    <canvas id="starsChart1"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation1"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal1" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(1)">&times;</span>
    <h2 id="calendarTitle1">Availability Calendar</h2>
    <canvas id="calendarCanvas1" width="800" height="400"></canvas>
    </div>
    </div>

    </div>


    <div class="hiddenDiv" id = "noseInfo">
      <h2>Our Nose and Sinus specialists:</h2>

      
      <label for "orderSelect2">Order By:</label>
      <select id = "orderSelect2">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>

      </select>


      <table id = "consultantsTable2"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available (click for more info) </th></tr>
    
      <?php
      $sql = "SELECT 
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 2
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      

      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }
          echo "</tr>";

        }
        echo "</table>";
      }

    ?>
    <div id="map2" style="height: 400px;"></div>

    <div id="ratingsModal2" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(2)" >&times;</span>
    <h2 id="ratingsTitle2">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart2"></canvas>
    <canvas id="starsChart2"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation2"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal2" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(2)">&times;</span>
    <h2 id="calendarTitle2">Availability Calendar</h2>
    <canvas id="calendarCanvas2" width="800" height="400"></canvas>
    </div>
    </div>

    </div>


    <div class="hiddenDiv" id = "throatInfo">
      <h2>Our Voice or Throat specialists</h2>

      
      <label for "orderSelect3">Order By:</label>
      <select id = "orderSelect3">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>

      </select>


      <table id = "consultantsTable3"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available (click for more info) </th></tr>
      <?php
      $sql = "SELECT 
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 3
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      

      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }
          echo "</tr>";

        }
        echo "</table>";
      }

    ?>
    <div id="map3" style="height: 400px;"></div>

    <div id="ratingsModal3" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(3)" >&times;</span>
    <h2 id="ratingsTitle3">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart3"></canvas>
    <canvas id="starsChart3"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation3"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal3" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(3)">&times;</span>
    <h2 id="calendarTitle3">Availability Calendar</h2>
    <canvas id="calendarCanvas3" width="800" height="400"></canvas>
    </div>
    </div>

    </div>

    <div class="hiddenDiv" id = "pediatricInfo">
      <h2>Our specialists in ENT care for children</h2>

      
      <label for "orderSelect4">Order By:</label>
      <select id = "orderSelect4">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>

      </select>


    
      <table id = "consultantsTable4"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available (click for more info) </th></tr>
      <?php
      $sql = "SELECT 
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 4
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      

      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }
          echo "</tr>";

        }
        echo "</table>";
      }

    ?>

    <div id="map4" style="height: 400px;"></div>

    <div id="ratingsModal4" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(4)" >&times;</span>
    <h2 id="ratingsTitle4">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart4"></canvas>
    <canvas id="starsChart4"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation4"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal4" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(4)">&times;</span>
    <h2 id="calendarTitle4">Availability Calendar</h2>
    <canvas id="calendarCanvas4" width="800" height="400"></canvas>
    </div>
    </div>

    </div>


    <div class="hiddenDiv" id = "allergyInfo">
      <h2>Our allergy specialists</h2>

      
      <label for "orderSelect5">Order By:</label>
      <select id = "orderSelect5">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>

      </select>


      <table id = "consultantsTable5"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available Appointment <br> (click for more info) </th></tr>
    
      <?php
      $sql = "SELECT 
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 5
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      
      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }

        }
        echo "</table>";
      }

    ?>

    <div id="map5" style="height: 400px;"></div>

    <div id="ratingsModal5" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(5)" >&times;</span>
    <h2 id="ratingsTitle5">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart5"></canvas>
    <canvas id="starsChart5"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation5"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal5" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(5)">&times;</span>
    <h2 id="calendarTitle5">Availability Calendar</h2>
    <canvas id="calendarCanvas5" width="800" height="400"></canvas>
    </div>
    </div>

    </div>

    <div class="hiddenDiv" id = "surgeryInfo">
      <h2>Our specialists of Head and Neck surgeries </h2>

      
      <label for "orderSelect6">Order By:</label>
      <select id = "orderSelect6">
        <option value ="">---</option>
        <option id = "rat" value="rating">Average Rating</option>
        <option id="priD" value = "priceDescending">Price: High to Low</option>
        <option id="priA" value = "priceAscending">Price: Low to High</option>
        <option id="avail" value = "avail">Soonest available</option>
      </select>


      <table id = "consultantsTable6"> <tr><th>😷 Name</th> <th>💸 Consultation Fee</th> <th>📊 Real Patient Ratings /5<br> (click for more info) </th>  <th>📅 Next Available Appointment <br> (click for more info) </th></tr>
    
    <?php
      $sql = "SELECT
      consultants.id AS 'id',
      consultants.name AS 'Name',
      consultants.consultation_fee AS 'Consultation Fee',
      AVG(reviews.score) AS 'Rating /5',
      COUNT(DISTINCT reviews.id) AS 'Num Of Ratings',
      clinics.latitude AS 'lat',
      clinics.longitude AS 'long',
      GROUP_CONCAT(DISTINCT consultant_schedule.weekday ORDER BY consultant_schedule.weekday ASC SEPARATOR ', ') AS 'weekdays',
      GROUP_CONCAT(DISTINCT bookings.booking_date ORDER BY bookings.booking_date ASC SEPARATOR ', ') AS 'bookings'
      FROM consultants 
      JOIN reviews ON consultants.id = reviews.consultant_id
      JOIN clinics ON consultants.clinic_id = clinics.id
      JOIN consultant_schedule ON consultant_schedule.consultant_id = consultants.id
      JOIN bookings ON bookings.consultant_id = consultants.id
      WHERE consultants.speciality_id = 6
      GROUP BY consultants.id, consultants.name, consultants.consultation_fee, clinics.latitude, clinics.longitude, reviews.consultant_id";
      

      $result = mysqli_query($conn, $sql);
      if ($result) {
            
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}' data-lat='{$row['lat']}' data-consultant-id='{$row['id']}'>";
          echo "<td>" . $row['Name'] . "</td>";
          echo "<td>£" . $row['Consultation Fee'] . "</td>";
          if ($row['Rating /5'] <=2){
            $ratBound = "lowRating";
          }
          elseif ($row['Rating /5'] <=3.5){
            $ratBound = "medRating";
          }
          else{
            $ratBound = "highRating";
          }
          echo "<td class='{$ratBound}'>" . round($row['Rating /5'] , 1) . " (" . $row['Num Of Ratings'] . " ratings) </td>";
          
          

          $workingDays = explode(", ",$row['weekdays']);  
          $bookedDates = explode(", ",$row['bookings']);  // A list of dates of all the current consultant's bookings 
          $count = 14;  // Counts how many days ahead we are looking. Im going to allow bookings up to 2 weeks in advance
          $today = new DateTime();
          $availableDates =[];
          
          for ($i = 0; $i < count($workingDays); $i++){
            $workingDays[$i] += 1;
          }
          

          for ($i = 0; $i < $count; $i++) {
            $date = clone $today;
            $date->modify("+{$i} days");
            $weekday = $date->format('N');
            if ((!in_array($date->format("Y-m-d"),$bookedDates)) && ($weekday != 7) && ($weekday !=6) && (in_array($weekday,$workingDays))){
              array_push($availableDates,$date->format("d-m-Y"));
            }  
          }
          if ($availableDates){
            $nextDate = $availableDates[0];
            $laterDates = implode(", ",$availableDates);
          
            echo "<td class = 'datesClick' title = '$laterDates'>" . $nextDate . "</td>";
          }
          else{
            echo" <td> No dates available within next 14 days. </td>";
          }
        }
        echo "</table>";
      }

    ?>
    <div id="map6" style="height: 400px;"></div>

    <div id="ratingsModal6" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeRatingsModal(6)" >&times;</span>
    <h2 id="ratingsTitle6">Clinician Ratings</h2>
    <div class="chart-wrapper">
    <canvas id="recommendChart6"></canvas>
    <canvas id="starsChart6"></canvas>
    </div>
    <div id = "feedback">
    <h2>Clinician feedback:</h2>
    <p id = "feedbackLocation6"></p>
    </div>
    </div>
    </div>

    <div id="calendarModal6" class="modal" style="display:none;">
    <div class="modal-content">
    <span class="close-button" onclick="closeCalendarModal(6)">&times;</span>
    <h2 id="calendarTitle6">Availability Calendar</h2>
    <canvas id="calendarCanvas6" width="800" height="400"></canvas>
    </div>
    </div>

    </div>

  <?php

$sql = "SELECT 
  consultants.id AS consultant_id,
  consultants.name,
  consultants.speciality_id,
  SUM(CASE WHEN recommend = 'yes' THEN 1 ELSE 0 END) AS recommend_yes,
  SUM(CASE WHEN recommend = 'no' THEN 1 ELSE 0 END) AS recommend_no,
  SUM(CASE WHEN score = 1 THEN 1 ELSE 0 END) AS score_1,
  SUM(CASE WHEN score = 2 THEN 1 ELSE 0 END) AS score_2,
  SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS score_3,
  SUM(CASE WHEN score = 4 THEN 1 ELSE 0 END) AS score_4,
  SUM(CASE WHEN score = 5 THEN 1 ELSE 0 END) AS score_5,
  GROUP_CONCAT(feedback ORDER BY reviews.id DESC SEPARATOR '|||') AS feedback_samples
FROM reviews
JOIN consultants ON reviews.consultant_id = consultants.id
GROUP BY consultant_id";

$result = $conn->query($sql);

$ratingsData = [];

while ($row = $result->fetch_assoc()) {
    $ratingsData[$row['consultant_id']] = $row;
}
?>



  <script src="entActions.js"></script>

  <script>
  const consultantRatings = <?php echo json_encode($ratingsData); ?>;
  </script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.2.0/dist/chartjs-chart-matrix.min.js"></script>


  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCudFWNgLdMdsOtsuLS6ZPXiz1Hp02o56s">
  </script>

</body>

<footer>GEORGE TRAPP F418623</footer>