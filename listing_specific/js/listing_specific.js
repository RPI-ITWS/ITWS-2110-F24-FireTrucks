// Get listing id from url
function getAuctionIdFromUrl() {
   const params = new URLSearchParams(window.location.search);
   return params.get('id');  
}

// Get listing information
function fetchAuctionDetails(listingId) {
   fetch(`./php/fetch_listing.php?id=${listingId}`, {
       method: 'GET',
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         // NOTE: data.UserId contains the hosts userId
         document.querySelector('#listingImage').src = data.image_url;
         document.querySelector('#itemTitle').textContent = data.Name;
         document.querySelector('#itemDescription').textContent = data.Description;
         document.querySelector('#itemSeller').textContent = data.Seller;
         document.querySelector('#itemPrice').textContent = data.Price.toFixed(2);
         document.querySelector('#itemPhoneNumber').textContent = data.PhoneNumber;
         document.querySelector('#itemEmail').textContent = data.Email;

         // Reveal hidden sections
         document.getElementById('current-item').style.display = 'block';
      } else {
         // Show error message if listing not found
         document.getElementById('error-message').textContent = 'Error fetching listing: ' + data.message;
         document.getElementById('error-message').style.display = 'block';
      }
   })
   .catch(error => console.error('Error:', error));
}

// On page load...
document.addEventListener('DOMContentLoaded', () => {
   const listingId = getAuctionIdFromUrl();
   if (listingId) {
       fetchAuctionDetails(listingId);
   } else {
       // No listing id provided
       document.getElementById('error-message').style.display = 'block';
   }
});
