
document.getElementById("ear").addEventListener("click",function(){
    let div = document.getElementById("earInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
    }
})

document.getElementById("nose").addEventListener("click",function(){
    let div = document.getElementById("noseInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
    }
})

document.getElementById("throat").addEventListener("click",function(){
    let div = document.getElementById("throatInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
    }
})

document.getElementById("pediatric").addEventListener("click",function(){
    let div = document.getElementById("pediatricInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
    }
})

document.getElementById("allergy").addEventListener("click",function(){
    let div = document.getElementById("allergyInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
        div.style.maxHeight = div.scrollHeight + "px";
    }
})

document.getElementById("headneck").addEventListener("click",function(){
    let div = document.getElementById("surgeryInfo")
    if (div.style.maxHeight) {
        div.style.maxHeight = null;
    } else {
        closeAll()
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

    let newID = "consultantsTable" + tableNo;
    console.log(newID);
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
