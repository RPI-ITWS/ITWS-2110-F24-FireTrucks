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
          displayListings(data.listings, 'featuredListingsContainer', 'Buy Now', 'listing_specific/index.html');
          displayListings(data.auctions, 'activeAuctionsContainer', 'Bid Now', 'auction_specific/index.html');
          displayListings(data.giveaways, 'giveawaysContainer', 'Enter Now', 'giveaway_specific/index.html');
       })
       .catch(error => console.error('Error fetching data:', error));
 }
 
 // Generic function to display listings
 function displayListings(items, containerId, buttonText, baseUrl) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
 
    items.forEach(item => {
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
       description.textContent = item.description || item.Description || '';
 
       const price = document.createElement("h4");
       price.classList.add("price");
       price.textContent = item.Price ? `$${item.Price}` : item.starting_bid ? `Starting Bid: $${item.starting_bid}` : 'Free';
 
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
    });
 }
 