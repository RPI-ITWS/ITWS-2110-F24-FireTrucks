// On page load...
document.addEventListener('DOMContentLoaded', () => {
    fetchContent();
 });
 
 function fetchContent() {
    fetch(`./index_data.php`)
    .then(response => {
       if (!response.ok) {
          throw new Error('Network response was not ok ' + response.statusText);
       }
       return response.json();
    })
    .then(data => {
       displayListings(data.listings);
       displayAuctions(data.auctions);
       displayGiveaways(data.giveaways);
    })
    .catch(error => console.error('Error fetching data:', error));
 }
 
 function displayListings(listings) {
    const container = document.getElementById("featuredListingsContainer");
    container.innerHTML = '';
    
    listings.forEach(item => {
       const listingDiv = document.createElement("div");
       listingDiv.classList.add("listing");
 
       const img = document.createElement("img");
       img.src = item.image_url;
       img.alt = item.Name;
       img.classList.add("listingImg");
 
       const textDiv = document.createElement("div");
       textDiv.classList.add("listingText");
 
       const title = document.createElement("h3");
       title.classList.add("listingTitle");
       title.textContent = item.Name;
 
       const description = document.createElement("h5");
       description.classList.add("listingDescription");
       description.textContent = item.Description;
 
       const price = document.createElement("h4");
       price.classList.add("price");
       price.textContent = `$${item.Price}`;
 
       const button = document.createElement("button");
       button.classList.add("CTAbutton");
       button.textContent = "Buy Now";
       button.onclick = function() {
          if (!checkUserLoggedIn()) {
             window.location.href = 'login/login.php';
          } else {
             const baseUrl = "https://firetrucks.eastus.cloudapp.azure.com/ITWS-2110-F24-FireTrucks/listing_specific/index.html";
             const id = item.ListingId;
             const url = `${baseUrl}?id=${id}`;
             window.location.href = url;
          }
       };
 
       textDiv.appendChild(title);
       textDiv.appendChild(description);
       textDiv.appendChild(price);
       textDiv.appendChild(button);
 
       listingDiv.appendChild(img);
       listingDiv.appendChild(textDiv);
 
       container.appendChild(listingDiv);
    });
 }
 
 function displayAuctions(auctions) {
    const container = document.getElementById("activeAuctionsContainer");
    container.innerHTML = '';
 
    auctions.forEach(item => {
       const listingDiv = document.createElement("div");
       listingDiv.classList.add("listing");
 
       const img = document.createElement("img");
       img.src = item.image_url;
       img.alt = item.title;
       img.classList.add("listingImg");
 
       const textDiv = document.createElement("div");
       textDiv.classList.add("listingText");
 
       const title = document.createElement("h3");
       title.classList.add("listingTitle");
       title.textContent = item.title;
 
       const description = document.createElement("h5");
       description.classList.add("listingDescription");
       description.textContent = item.description;
 
       const price = document.createElement("h4");
       price.classList.add("price");
       price.textContent = `Starting Bid: $${item.starting_bid}`;
 
       const button = document.createElement("button");
       button.classList.add("CTAbutton");
       button.textContent = "Bid Now";
       button.onclick = function() {
          if (!checkUserLoggedIn()) {
             window.location.href = 'login/login.php';
          } else {
             const baseUrl = "https://firetrucks.eastus.cloudapp.azure.com/ITWS-2110-F24-FireTrucks/auction_specific/index.html";
             const id = item.id;
             const url = `${baseUrl}?id=${id}`;
             window.location.href = url;
          }
       };
 
       textDiv.appendChild(title);
       textDiv.appendChild(description);
       textDiv.appendChild(price);
       textDiv.appendChild(button);
 
       listingDiv.appendChild(img);
       listingDiv.appendChild(textDiv);
 
       container.appendChild(listingDiv);
    });
 }
 
 function displayGiveaways(giveaways) {
    const container = document.getElementById("giveawaysContainer");
    container.innerHTML = '';
 
    giveaways.forEach(item => {
       const listingDiv = document.createElement("div");
       listingDiv.classList.add("listing");
 
       const img = document.createElement("img");
       img.src = item.image_url;
       img.alt = item.name;
       img.classList.add("listingImg");
 
       const textDiv = document.createElement("div");
       textDiv.classList.add("listingText");
 
       const title = document.createElement("h3");
       title.classList.add("listingTitle");
       title.textContent = item.name;
 
       const description = document.createElement("h5");
       description.classList.add("listingDescription");
       description.textContent = item.description;
 
       const price = document.createElement("h4");
       price.classList.add("price");
       price.textContent = "Free";
 
       const button = document.createElement("button");
       button.classList.add("CTAbutton");
       button.textContent = "Enter Now";
       button.onclick = function() {
          if (!checkUserLoggedIn()) {
             window.location.href = 'login/login.php';
          } else {
             const baseUrl = "https://firetrucks.eastus.cloudapp.azure.com/ITWS-2110-F24-FireTrucks/giveaway_specific/index.html";
             const id = item.giveaway_id;
             const url = `${baseUrl}?id=${id}`;
             window.location.href = url;
          }
       };
 
       textDiv.appendChild(title);
       textDiv.appendChild(description);
       textDiv.appendChild(price);
       textDiv.appendChild(button);
 
       listingDiv.appendChild(img);
       listingDiv.appendChild(textDiv);
 
       container.appendChild(listingDiv);
    });
 }
 
 function checkUserLoggedIn() {
    // Since we cannot directly check PHP sessions in JS,
    // you might add a global JS variable set by PHP if user is logged in.
    // For now, assume a global variable `isUserLoggedIn` set in index.php.
    return window.isUserLoggedIn || false;
 }
 