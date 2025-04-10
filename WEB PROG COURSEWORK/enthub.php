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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          
          <li class="nav-item">
            <a class="nav-link" href="#">Symptoms Advice</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Our services</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</header>

<body>
  <div class="bigQuestion">
    <h2><span>Where</span> is your problem?</h2>
  </div>

  <div class="card-group">
    
    <div class="card" id = "ear">
      <img src="ear.jpg" class="card-img-top" alt="ear image">
      <div class="card-body">
        <h5 class="card-title">Ear and Balance</h5>
        <p class="card-text">See our surgeons that can help with any ear of your ear balance issues.</p>
      </div>
    </div>
    
    
    <div class="card" id = "nose">
      <img src="nose.webp" class="card-img-top" alt="nose image">
      <div class="card-body">
        <h5 class="card-title">Nose and Sinuses</h5>
        <p class="card-text">Our services for nose or sinus health issues.</p>
      </div>
    </div>
    
    
    <div class="card" id = "throat">
      <img src="throat.jpg" class="card-img-top" alt="throat and voice image">
      <div class="card-body">
        <h5 class="card-title">Voice or Throat</h5>
        <p class="card-text">See how we can help you with any nose or throat issues</p>
      </div>
    </div>
    
    
    <div class="card" id = "pediatric">
      <img src="pediatric.png" class="card-img-top" alt="pediatrician">
      <div class="card-body">
        <h5 class="card-title">ENT care for Children</h5>
        <p class="card-text">See how our specialists can help your child with any ENT problems.</p>
      </div>
    </div>
    
    <div class="card" id ="allergy">
      <img src="allergy.webp" class="card-img-top" alt="allergies">
      <div class="card-body">
        <h5 class="card-title">ENT related Allergies</h5>
        <p class="card-text">If you currently have or think you might have any ENT related allergies our doctors can help!</p>
      </div>
    </div>
    <div class="card" id = "headneck">
      <img src="headneck.jpg" class="card-img-top" alt="head/neck surgery">
      <div class="card-body">
        <h5 class="card-title">Head or Neck Surgery</h5>
        <p class="card-text">See the complex head and neck surgeries we can provide.</p>
      </div>
    </div>
  </div>


  <div class="hiddenDiv" id = "earInfo">
    <h2>Our Ear and Balance specialists:</h2>

    
    <label for "orderSelect1">Order By:</label>
    <select id = "orderSelect1">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable1"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 1
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
      
    
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map1" style="height: 400px;"></div>
  </div>


  <div class="hiddenDiv" id = "noseInfo">
    <h2>Our Nose and Sinus specialists:</h2>

    
    <label for "orderSelect2">Order By:</label>
    <select id = "orderSelect2">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable2"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 2
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
      
    
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map2" style="height: 400px;"></div>
  </div>


  <div class="hiddenDiv" id = "throatInfo">
    <h2>Our Voice or Throat specialists</h2>

    
    <label for "orderSelect3">Order By:</label>
    <select id = "orderSelect3">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable3"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 3
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
      
    
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map3" style="height: 400px;"></div>
  </div>

  <div class="hiddenDiv" id = "pediatricInfo">
    <h2>Our specialists in ENT care for children</h2>

    
    <label for "orderSelect4">Order By:</label>
    <select id = "orderSelect4">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable4"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 4
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
      
    
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map4" style="height: 400px;"></div>
  </div>


  <div class="hiddenDiv" id = "allergyInfo">
    <h2>Our allergy specialists</h2>

    
    <label for "orderSelect5">Order By:</label>
    <select id = "orderSelect5">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable5"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 5
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
      
    
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map5" style="height: 400px;"></div>
  </div>

  <div class="hiddenDiv" id = "surgeryInfo">
    <h2>Our specialists of Head and Neck surgeries </h2>

    
    <label for "orderSelect6">Order By:</label>
    <select id = "orderSelect6">
      <option value ="">Relevance</option>
      <option id = "rat" value="rating">Average Rating</option>
      <option id="priD" value = "priceDescending">Price: High to Low</option>
      <option id="priA" value = "priceAscending">Price: Low to High</option>
    </select>


    <table id = "consultantsTable6"> <tr><th>Name</th> <th>Consultation Fee</th> <th>Rating /5</th></tr> 
  
  <?php
    $sql = "SELECT consultants.name as 'Name', consultants.consultation_fee as 'Consultation Fee', AVG(reviews.score) as 'Rating /5', clinics.latitude as 'lat',clinics.longitude as 'long'
    FROM consultants 
    JOIN reviews ON consultants.id = reviews.consultant_id
    JOIN clinics ON consultants.clinic_id = clinics.id
    WHERE consultants.speciality_id = 6
    GROUP BY consultants.id, consultants.name, consultants.consultation_fee";
    

    $result = mysqli_query($conn, $sql);
    if ($result) {
          
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr data-lat='{$row['lat']}' data-long='{$row['long']}'>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['Consultation Fee'] . "</td>";
        echo "<td>" . round($row['Rating /5'] , 1) . "</td>";
        echo "</tr>";

      }
      echo "</table>";
    }

  ?>
  <div id="map6" style="height: 400px;"></div>


  </div>

  





  <script src="entActions.js"></script>
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=MY_KEY">
  </script>

</body>

<footer>GEORGE TRAPP F418623</footer>
