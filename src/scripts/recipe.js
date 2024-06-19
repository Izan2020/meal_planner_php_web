
// OnStart
document.addEventListener('DOMContentLoaded', () => {    
    // Loads Option Value from static-data.js (Dietary Preferences)
    dietOptions.forEach(optionD => {
    const dietPreferencesSelect = document.getElementsByName('dietary-preferences')[0];
    dietPreferencesSelect.appendChild(createOption(optionD, optionD));
    });

    // Loads Option Value from static-data.js (Allergies)
    allergyOptions.forEach(optionA => {    
    const allergiesSelect = document.getElementsByName('allergies')[0];
    allergiesSelect.appendChild(createOption(optionA, optionA));
    });

    // OnChangeValue Calories Input
    var inputCalories = document.getElementsByName('calorie-target')[0];
    inputCalories.addEventListener('input', (event) => checkInputValue(event));
    
});


// Sticky Navbar
window.addEventListener('scroll', function(){
    const header = document.querySelector('.header');    
    header.classList.toggle('sticky', window.scrollY>0);    
});


// OnClick Button Generate
var buttonGenerate = document.getElementById('button-generate');
buttonGenerate.addEventListener('click', async (event) => {
    event.preventDefault();
    const diet = document.getElementsByName('dietary-preferences')[0].value;
    const allergy = document.getElementsByName('allergies')[0].value;
    const caloriesTarget = document.getElementsByName('calorie-target')[0].value;    
    const resultList = document.getElementById('result-list');

    if (caloriesTarget == '') {                
        return setAlertBoxMessage(`Insert Calories!`);
    }

    
    setLoading(true);
    const result = await fetch(`/search-meal?diet=${diet}&intolerances=${allergy}&minCalories=${caloriesTarget}`);
            
    if(!result.ok) {
        setLoading(false);        
        const errorMessage = await result.text();
        return setAlertBoxMessage(errorMessage);
    }

    const resultData = await result.json();

    if(resultData.results.length === 0) {
        setLoading(false);
        return setAlertBoxMessage('No result Found.');
    }

    resultData.results.forEach((recipe) => {
        const recipeItem = document.createElement(`recipe-item`);
        recipeItem.innerHTML = itemRecipe(recipe);
        resultList.appendChild(recipeItem);
    });
    clearAlertBox();
    setLoading(false);
});




// Functions ================================================================================================================

// ? Sets Alertbox Message
function setAlertBoxMessage(message) {    
    const alertBox = document.getElementById('alert');
    alertBox.className = 'alert';
    alertBox.style.display = 'block';
    return alertBox.innerHTML = `
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                ${message}
                `;
}

function clearAlertBox() {
    const alertBox = document.getElementById('alert');
    alertBox.style.display = 'none';
}



// ? Set Result list innerHTML with list of Items
function itemRecipe(recipe) {
    return  `
    <a id="recipe-card" href="/recipe-detail?id=${recipe.id}&title=${recipe.title}" >
                    <img src="${recipe.image}" alt="">
                    <h1>
                    ${recipe.title}<br> 
                    <ul>
                    ${recipe.nutrition.nutrients[0].amount}kcal
                    </ul>
                    </h1>                                 
                </a>                
    `;
}

// ? Set Loading Status
function setLoading(status) {
    var buttonGenerate = document.getElementById('button-generate');
    const loadingIndicator = document.getElementById('loading');
    if(!status) {
        buttonGenerate.style.display = 'block';
        return loadingIndicator.innerHTML = '';
    }
    buttonGenerate.style.display = 'none';
    loadingIndicator.innerHTML = 'Loading...';
}

// ? Set Check input value of maxium Calories
function checkInputValue(event) {
    const maxValue = 2200    
    if (event.target.value > maxValue) {
        event.target.value = maxValue;
    }
}

// ? Loads option Values
function createOption(value, text) {
    const option = document.createElement('option');
    option.value = value;
    option.text = text;
    return option;
}


// Check loading Status 
function checkLoading() {
    const loadingIndicator = document.getElementById('loading');
    if (loadingIndicator.innerHTML == 'Loading..') return setAlertBoxMessage('Still Loading Please wait...');
}

// Static Data =========================================================================================================

// ? Diet Options
const dietOptions = [
    "Gluten Free",
    "Ketogenic",
    "Vegetarian",
    "Lacto-Vegetarian",
    "Ovo-Vegetarian",
    "Vegan",
    "Pescetarian",
    "Paleo",
    "Primal",
    "Low FODMAP",
    "Whole30"
];

// ? Allergy Options
const allergyOptions = [
    "Dairy",
    "Egg",
    "Gluten",
    "Grain",
    "Peanuts",
    "Seafood",
    "Sesame",
    "Shellfish",
    "Soy",
    "Sulfite",
    "Tree Nut",
    "Wheat"
];