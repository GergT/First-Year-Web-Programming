document.getElementById("ear").addEventListener("click",function(){
    let div = document.getElementById("earInfo")
    getLocation(1);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
    
})

document.getElementById("nose").addEventListener("click",function(){
    let div = document.getElementById("noseInfo")
    getLocation(2);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
})

document.getElementById("throat").addEventListener("click",function(){
    let div = document.getElementById("throatInfo")
    getLocation(3);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
})

document.getElementById("pediatric").addEventListener("click",function(){
    let div = document.getElementById("pediatricInfo")
    getLocation(4);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
})

document.getElementById("allergy").addEventListener("click",function(){
    let div = document.getElementById("allergyInfo")
    getLocation(5);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
})

document.getElementById("headneck").addEventListener("click",function(){
    let div = document.getElementById("surgeryInfo")
    getLocation(6);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
        div.scrollIntoView({ behavior: 'smooth' });
    }
})



document.getElementById("orderSelect1").addEventListener("change", function () {
    sortTable(this.value, 1);
});

document.getElementById("orderSelect2").addEventListener("change", function () {
    sortTable(this.value, 2);
});

document.getElementById("orderSelect3").addEventListener("change", function () {
    sortTable(this.value, 3);
});
document.getElementById("orderSelect4").addEventListener("change", function () {
    sortTable(this.value, 4);
});

document.getElementById("orderSelect5").addEventListener("change", function () {
    sortTable(this.value, 5);
});

document.getElementById("orderSelect6").addEventListener("change", function () {
    sortTable(this.value, 6);
});





function closeAll(){
    let panels = document.getElementsByClassName("hiddenDiv")
    for (let i=0; i<panels.length;i++){
        panels[i].style.maxHeight = null;
    } 

}


function sortTable(option,tableNo) {
    let column;
    if (option == ""){}
    if (option == "rating") {
        column = 2;  
    } 
    else if (option == "avail"){
        column = 3;
    }
    else {
        column = 1;  
    }

    const newID = "consultantsTable" + tableNo;
    let table = document.getElementById(newID);
    let rows = Array.from(table.rows).slice(1);

    rows.sort(function(a, b) {
        let valueA, valueB;

        if (option === "rating") {
            valueA = getFirstNumber(a.cells[column].innerText);
            valueB = getFirstNumber(b.cells[column].innerText);
            return valueB - valueA; // higher rating first
        } 
        else if (option === "priceAscending" || option === "priceDescending") {
            valueA = parseFloat(a.cells[column].innerText.replace(/[^0-9.]/g, ""));
            valueB = parseFloat(b.cells[column].innerText.replace(/[^0-9.]/g, ""));
            if (option === "priceAscending"){
                return valueA - valueB 
            } else{ 
                return valueB - valueA;
            }
        } 
        else if (option === "avail") {
            const [dayA,monthA,yearA] = a.cells[column].innerText.trim().split("-")

            valueA = new Date(yearA, monthA - 1, dayA);
            const [dayB,monthB,yearB] = b.cells[column].innerText.trim().split("-")
            valueB = new Date(yearB, monthB - 1, dayB);
            return valueA - valueB; // earliest date first
        }
        else {
            // Default sort alphabetically
            valueA = a.cells[column].innerText.toLowerCase();
            valueB = b.cells[column].innerText.toLowerCase();
            return valueA.localeCompare(valueB);
        }
    });

    rows.forEach(row => table.appendChild(row)); 
}

function getFirstNumber(text) {
    let parts = text.split(' ');
    return parseFloat(parts[0]);
}

function getLocation(currentTable) {
    const backup = {lat: 52.843962, lng: -1.295724} // my backup location in the east midlands incase the user does not share their location.

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                initMap(position, currentTable,"NORMAL");
            },
            (error) => {
                console.error("Geolocation error:", error);
                initMap(backup, currentTable,"IRREG");

            }
        );
    } else {
        console.log("Geolocation is not supported by this browser.");
        initMap(backup, currentTable,"IRREG");
    }
}


function initMap(userPosition,tableNo,status) {

    let origin;
    if (status == "NORMAL"){
        origin = { lat: userPosition.coords.latitude, lng: userPosition.coords.longitude };
    }
    else{
        origin = {lat: userPosition.lat, lng: userPosition.lng}
    }
    let destinations = [];
    let table = document.getElementById("consultantsTable"+tableNo);
    let rows = Array.from(table.rows).slice(1);
    let names = [];
    
    
    rows.forEach((row) =>{
        let pairFound = false;
        const lat = parseFloat(row.dataset.lat);
        const long = parseFloat(row.dataset.long);

        for (let i =0; i<destinations.length;i++){
            if (destinations[i].lat === lat && destinations[i].lng === long){
                pairFound = true;
                names[i] = names[i] + " and " + row.cells[0].innerText;
            }
        }

        if (!pairFound){
            names.push(row.cells[0].innerText);
            destinations.push({
                lat: lat, 
                lng: long
            });
        }
    });
    
    const map = new google.maps.Map(document.getElementById("map"+tableNo), {
      center: origin,
      zoom: 9,
    });
    
    if (status == 'NORMAL'){
        new google.maps.Marker({ position: origin, map, label: "YOU" });
    }
    
    for (let i = 0;i<rows.length;i++){
        new google.maps.Marker({ position: destinations[i], map, label: names[i] });
    }
}



document.addEventListener("DOMContentLoaded", () => {
    
    
    for (let i =1;i <7;i++){
        
        const table = document.getElementById("consultantsTable"+i);
    
        Array.from(table.rows).slice(1).forEach(row => {

          row.cells[2].addEventListener("click", () => {
            const consultantId = row.dataset.consultantId;
            const stats = consultantRatings[consultantId];
            let name = row.cells[0].innerText;
            console.log(stats)
            data = {
              recommendNo : stats['recommend_no'],
              recommendYes : stats['recommend_yes'],
              stars : [stats['score_1'],stats['score_2'],stats['score_3'],stats['score_4'],stats['score_5']]
            }
            if (stats) {
              openRatingsModal(name,data,stats['feedback_samples'],i);
            }
          });




          row.cells[3].addEventListener("click", () => {
            const name = row.cells[0].innerText;
            const availCell = row.cells[3];
            const titleAttr = availCell.getAttribute("title");
            
            if (titleAttr) {
                const availableDates = titleAttr.split(',').map(date => date.trim());
                openCalendarModal(availableDates, `Availability for ${name}`,i);
                }
            });       
        });
    }
  });


let calendarChart = null;

function closeCalendarModal(table) {
  document.getElementById("calendarModal"+table).style.display = "none";
  if (calendarChart) calendarChart.destroy();
}

function openCalendarModal(availableDates, title = "Availability Calendar",table) {
  document.getElementById("calendarTitle"+table).innerText = title;
  document.getElementById("calendarModal"+table).style.display = "flex";

  const ctx = document.getElementById('calendarCanvas'+table).getContext('2d');
  const parsedDates = availableDates.map(d => {
    const [day, month, year] = d.split("-");
    return new Date(`${year}-${month}-${day}`);
  });

  const today = new Date();
  today.setHours(0, 0, 0, 0);
  
  // Find the Sunday of this week
  const startOfWeek = new Date(today);
  startOfWeek.setDate(today.getDate() - today.getDay());
  
  const endDate = new Date(today);
  endDate.setDate(today.getDate() + 14);
  
  const data = [];
  for (let d = new Date(today); d <= endDate; d.setDate(d.getDate() + 1)) {
    const isAvailable = parsedDates.some(avail => avail.toDateString() === d.toDateString());
    const weekday = d.getDay();
  
    const week = Math.floor((d - startOfWeek) / (7 * 24 * 60 * 60 * 1000));
  
    data.push({
      x: weekday,
      y: week,
      v: isAvailable ? 1 : 0,
      date: d.toDateString()
    });
  }

  if (calendarChart) calendarChart.destroy();

  calendarChart = new Chart(ctx, {
    type: 'matrix',
    data: {
      datasets: [{
        label: 'Availability',
        data,
        backgroundColor: ctx => ctx.raw.v ? 'green' : '#ddd',
        borderWidth: 2,
        borderColor: '#fff',
        width: ctx => (ctx.chart.width / 7) - 8,
        height: ctx => (ctx.chart.height / 3) -8,
      }]
    },
    options: {
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: () => '', 
            label: ctx => ctx.raw.v ? `Available: ${ctx.raw.date}` : ctx.raw.date
          }
        }
      },
      scales: {
        x: {
          type: 'linear',
          position: 'top',
          min: 0,
          max: 6,
          ticks: {
            stepSize: 1,
            callback: i => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][i],
            color: '#333',
            font: { size: 14 }
          },
          grid: { display: false }
        },
        y: {
          offset: true,
          type: 'linear',
          min: 0,
          max: 2,
          ticks: {
            stepSize: 1,
            callback: i => `Week ${i + 1}`,
            color: '#444',
            font: { size: 12 }
          },
          grid: { display: true }
        }
      }
    }
  });
}


let recommendChart = null;
let starsChart = null;

function closeRatingsModal(table) {
  document.getElementById("ratingsModal"+table).style.display = "none";
  if (recommendChart) recommendChart.destroy();
  if (starsChart) starsChart.destroy();
}

function openRatingsModal(name, data,feedback,table) {
  document.getElementById("ratingsTitle"+table).innerText = `Ratings for ${name}`;
  document.getElementById("ratingsModal"+table).style.display = "flex";
  let feedArray = feedback.split("|||")
  let newFeedEntry = "";
  for (let i = 0;i<feedArray.length;i++){
    newFeedEntry = newFeedEntry + feedArray[i] + "<br>";
  }
  document.getElementById("feedbackLocation"+table).innerHTML = newFeedEntry;

  const recommendData = {
    labels: ["Recommend", "Do Not Recommend"],
    datasets: [{
      data: [data.recommendYes, data.recommendNo],
      backgroundColor: ["green", "red"]
    }]
  };

  const starData = {
    labels: ["1★", "2★", "3★", "4★", "5★"],
    datasets: [{
      label: "Ratings Count",
      data: data.stars,
      backgroundColor: "dodgerblue"
    }]
  };

  const recommendCtx = document.getElementById("recommendChart"+table).getContext("2d");
  recommendChart = new Chart(recommendCtx, {
    type: "pie",
    data: recommendData,
    options: { plugins: { legend: { position: "bottom" } } }
  });

  const starsCtx = document.getElementById("starsChart"+table).getContext("2d");
  starsChart = new Chart(starsCtx, {
    type: "bar",
    data: starData,
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
}

