// On page load...
document.addEventListener('DOMContentLoaded', () => {
   fetchContent();
});

// Get data
function fetchContent() {
   fetch(`./services.php`)   
   .then(response => {
      if (!response.ok) {
         throw new Error('Network response was not ok ' + response.statusText);
      }
      return response.json();
   })
   .then(data => {
      const selectedCategoriesJSON = getSelectedCategories(); 
      const searchText = searchTextContent(); 
      displayItems(selectedCategoriesJSON, searchText, data);
   })
   .catch(error => console.error('Error fetching data:', error));
}

function displayItems(selectedCategoriesJSON, searchText, data) {
   const container = document.getElementById("listings");
   container.innerHTML = '';  // Clear any existing content
   const priceInputMin = document.querySelector(".min-input");
   const priceInputMax = document.querySelector(".max-input");
   
   data.forEach(item => {
      // Loop through item.Categories and check if any category matches selectedCategoriesJSON.categories      
      let hasMatchingCategory = JSON.parse(item.category).some(category => 
         selectedCategoriesJSON.categories.includes(category)
      );

      // Checks if item price is in price range
      let isInPriceRange = false; 
      if (parseInt(item.Price, 10) >= priceInputMin.value && parseInt(item.Price, 10) <= priceInputMax.value){
         isInPriceRange = true;
      }

      // Checks if item name matches search 
      let search = false;
      if (searchText.words.length === 0){
         search = true;
      } else {
         for (let word of searchText.words) {
            // Check if the item.Name contains the word (case-insensitive)
            if (item.Name.toLowerCase().includes(word.toLowerCase())) {
               search = true; // Set search to true if a match is found
               break; // Exit the loop
            }
         }          
      }

      if (hasMatchingCategory && isInPriceRange && search){
         // Create a div for the listing
         const listingDiv = document.createElement("div");
         listingDiv.classList.add("listing");
   
         // Create the image element
         const img = document.createElement("img");
         img.src = item.image_url;  // Set the image link from the data
         img.alt = item.Name;       // Use the item name as the alt text
         img.classList.add("listingImg");
         
         // Create the text container div
         const textDiv = document.createElement("div");
         textDiv.classList.add("listingText");
   
         // Create the title (h3)
         const title = document.createElement("h3");
         title.classList.add("listingTitle");
         title.textContent = item.Name;  // Set the title text from the data
   
         // Create the description (h5)
         const description = document.createElement("h5");
         description.classList.add("listingDescription");
         description.textContent = item.Description;  // Set the description text
   
         // Create the price (h4)
         const price = document.createElement("h4");
         price.classList.add("price");
         price.textContent = `Price: $${item.Price}`;  // Set the price with a dollar sign
   
         // Create the Buy Now button
         const button = document.createElement("button");
         button.classList.add("CTAbutton");
         button.textContent = "Get Now";
         button.onclick = function() {
            const baseUrl = "https://firetrucks.eastus.cloudapp.azure.com/ITWS-2110-F24-FireTrucks/service_specific/index.html"; // Replace with your desired URL
            const id = item.ListingId; // Set the desired ID
            const url = `${baseUrl}?id=${id}`; // Construct the URL with the query parameter
            window.location.href = url; // Redirect the user to the constructed URL
         };
   
         // Append all the elements into the textDiv
         textDiv.appendChild(title);
         textDiv.appendChild(description);
         textDiv.appendChild(price);
         textDiv.appendChild(button);
   
         // Append the image and the textDiv into the listingDiv
         listingDiv.appendChild(img);
         listingDiv.appendChild(textDiv);
   
         // Append the listingDiv into the container
         container.appendChild(listingDiv);
      }
   });   
}

function getSelectedCategories() {
   // List of all available categories
   const allCategories = ["Tutoring", "Writing Help & Editing", "Tech Support", "Photography or Videography", "Moving Assistance", "Cleaning Services", "Pet Services", "Sports Equipment", "Transportation Services", "Miscellaneous"];
   
   // Get checkboxes
   const checkboxes = document.querySelectorAll("#categories input[type='checkbox']");
   
   // Create an array for selected categories
   let selectedCategories = [];
 
   // Loop through checkboxes to find checked ones
   checkboxes.forEach((checkbox, index) => {
      if (checkbox.checked) {
         selectedCategories.push(allCategories[index]);
      }
   });
 
   // If none selected, return all categories
   if (selectedCategories.length === 0) {
      selectedCategories = allCategories;
   }
 
   // Create a JSON object
   const categoriesJSON = { categories: selectedCategories };
   
   // Return JSON object
   return categoriesJSON;
}

function searchTextContent() {
   // Get the input element by its ID
   const searchInput = document.getElementById("searchInput");
   
   // Get the value from the input field and trim whitespace
   const searchText = searchInput.value.trim();
   
   // Check if the search text is empty
   if (searchText === "") {
     // Return an empty JSON object or handle it as needed
     return { words: [] }; // Return an empty array for words
   }
   
   // Split the search text into individual words
   const wordsArray = searchText.split(/\s+/); // Split by whitespace
   
   // Create a JSON object of the individual words
   const searchWordsJSON = { words: wordsArray };
   
   return searchWordsJSON;
}

function smoothScroll(){
   window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById("searchInput").addEventListener("keydown", function(event) {
   if (event.key === "Enter") { // Check if the pressed key is Enter
     event.preventDefault(); // Prevent the default action (form submission, if applicable)
     document.getElementById("searchButton").click(); // Programmatically click the search button
   }
});