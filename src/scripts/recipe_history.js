
// OnStart
document.addEventListener(`DOMContentLoaded`, async (event) => {
    event.preventDefault()
    const response = await fetch(`/get-meal-history`);
    if(!response.ok) {
        const message = await response.text();
        return alert(message);
    }

    const resultJson = await response.json();    
    const resultData = JSON.parse(resultJson);
    
    if(resultData.length === 0 ) {
        const listViewHistory = document.getElementById(`listview-history`);
        const btnClear = document.getElementById(`btn-clear-h`);
        btnClear.style.display = `none`;
        return listViewHistory.innerHTML = 'History is Empty.';
    }
    
    return showItems(resultData);
});

// Sticky Navbar
window.addEventListener('scroll', function(){
    const header = document.querySelector('.header');    
    header.classList.toggle('sticky', window.scrollY>0);    
});


// OnTap Clear
const btnClear = document.getElementById(`btn-clear-h`);
btnClear.addEventListener(`click`, async (event) =>{
    event.preventDefault();
    var result = window.confirm("Do you wish to Clear History?");
    if (result === true) {
        return fetch(`/clear-history`).then(async response => {
            if(!response.ok)  {
                const message = await response.text();
                return setAlertBoxMessage(message)
            }            

            return location.reload();
        });
    }

    
});

// FUNCTIONS =========================================

function showItems(data) {
    const listViewHistory = document.getElementById(`listview-history`);

    data.forEach(history => {
        const currentDate = new Date(); 
        const specificDate = new Date(history.created_at);

        const dateString = currentDate.toDateString(); // "Sat Jun 13 2024"
        // Get the current hour and minutes
        const currentHour = String(currentDate.getHours()).padStart(2, '0');
        const currentMinutes = String(currentDate.getMinutes()).padStart(2, '0');

        // Concatenate hour and minutes with a colon
        const hour = `${currentHour}:${currentMinutes}`;


        const itemWrapper = document.createElement(`wrapper-item`);
        itemWrapper.innerHTML = `
        <a href="/recipe-detail?id=${history.id}" class="item-history">
        <h1>${history.title}</h1>
        <h2>${dateString} | ${hour}</h2>
        </a>`;
        listViewHistory.appendChild(itemWrapper);
    });
}


function setAlertBoxMessage(message) {    
    const alertBox = document.getElementById('alert');
    alertBox.className = 'alert';
    return alertBox.innerHTML = `
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                ${message}
                `;
}
