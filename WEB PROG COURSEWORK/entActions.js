document.getElementById("ear").addEventListener("click",function(){
    let div = document.getElementById("earInfo")
    getLocation(1);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
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
    }
})

document.getElementById("headneck").addEventListener("click",function(){
    let div = document.getElementById("surgeryInfo")
    getLocation(6);
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        
        closeAll();
        div.style.maxHeight = div.scrollHeight + "px";
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
    if (option == "rating") {
        column = 2;  // Rating column index
    } else {
        column = 1;  // Price column index
    }

    const newID = "consultantsTable" + tableNo;
    let table = document.getElementById(newID);
    let rows = Array.from(table.rows).slice(1); // Skip the header row

    rows.sort(function(a, b) {
        let valueA = parseFloat(a.cells[column].innerText.replace(/[^\d.-]/g, ''));  // Clean up the price/rating value
        let valueB = parseFloat(b.cells[column].innerText.replace(/[^\d.-]/g, ''));

        // Sorting logic based on selected option
        if (option === "priceAscending") {
            return valueA - valueB;
        } else {
            return valueB - valueA;  // Descending order for price
        }
    });

    // Append the sorted rows back to the table
    rows.forEach(row => table.appendChild(row));
}


function getLocation(currentTable) {
    const backup = {lat: 52.843962, lng: -1.295724} // my backup location in the east midlands incase the user does not share their gps

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
    console.log(status == "NORMAL");
    if (status == "NORMAL"){
        origin = { lat: userPosition.coords.latitude, lng: userPosition.coords.longitude };
    }
    else{
        origin = {lat: userPosition.lat, lng: userPosition.lng}
    }
    let destinations = [];
    let table = document.getElementById("consultantsTable"+tableNo);
    let rows = Array.from(table.rows).slice(1);
    
    rows.forEach((row) =>{
        const lat = parseFloat(row.dataset.lat);
        const long = parseFloat(row.dataset.long);

        
        destinations.push({
            lat: lat, 
            lng: long
        });
    });
  
    const map = new google.maps.Map(document.getElementById("map"+tableNo), {
      center: origin,
      zoom: 9,
    });
    
    if (status == 'NORMAL'){
        new google.maps.Marker({ position: origin, map, label: "YOU" });
    }
    console.log(destinations)
    
    for (let i = 0;i<rows.length;i++){
        new google.maps.Marker({ position: destinations[i], map, label: rows[i].cells[0].innerText });
    }
}
