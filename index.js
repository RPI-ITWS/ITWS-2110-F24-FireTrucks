// On page load
document.addEventListener('DOMContentLoaded', () => {
    fetchContent();
 });
 
 // Fetch data from the server
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
 
 // Display Featured Listings
 function displayListings(listings) {
    const container = document.getElementById("featuredListingsContainer");
    container.innerHTML = '';
    listings.forEach(item => createCard(item, container, "Get Now", "listing_specific/index.html"));
 }
 
 // Display Active Auctions
 function displayAuctions(auctions) {
    const container = document.getElementById("activeAuctionsContainer");
    container.innerHTML = '';
    auctions.forEach(item => createCard(item, container, "Bid Now", "auction_specific/index.html"));
 }
 
 // Display Giveaways
 function displayGiveaways(giveaways) {
    const container = document.getElementById("giveawaysContainer");
    container.innerHTML = '';
    giveaways.forEach(item => createCard(item, container, "Enter Now", "giveaway_specific/index.html"));
 }
 
 // Create Card for Listings, Auctions, or Giveaways
 function createCard(item, container, buttonText, baseUrl) {
    const listingDiv = document.createElement("div");
    listingDiv.classList.add("listing");
 
    const img = document.createElement("img");
    img.src = item.image_url;
    img.alt = item.name || item.title || item.Name;
    img.classList.add("listingImg");
 
    const textDiv = document.createElement("div");
    textDiv.classList.add("listingText");
 
    const title = document.createElement("h3");
    title.classList.add("listingTitle");
    title.textContent = item.name || item.title || item.Name;
 
    const description = document.createElement("h5");
    description.classList.add("listingDescription");
    description.textContent = item.description || item.Description || "";
 
    const price = document.createElement("h4");
    price.classList.add("price");
    price.textContent = item.Price ? `$${item.Price}` : item.starting_bid ? `Starting Bid: $${item.starting_bid}` : "Free";
 
    const button = document.createElement("button");
    button.classList.add("CTAbutton");
    button.textContent = buttonText;
    button.onclick = () => {
       const id = item.giveaway_id || item.ListingId || item.id;
       window.location.href = `${baseUrl}?id=${id}`;
    };
 
    textDiv.appendChild(title);
    textDiv.appendChild(description);
    textDiv.appendChild(price);
    textDiv.appendChild(button);
 
    listingDiv.appendChild(img);
    listingDiv.appendChild(textDiv);
 
    container.appendChild(listingDiv);
 }
 