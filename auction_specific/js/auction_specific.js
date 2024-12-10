// Get auction id from url
function getAuctionIdFromUrl() {
   const params = new URLSearchParams(window.location.search);
   return params.get('id');  
}

// Get auction information
function fetchAuctionDetails(auctionId) {
   fetch(`./php/fetch_auction.php?id=${auctionId}`, {
      method: 'GET',
   })
   .then(response => response.json())
   .then(data => {
       if (data.success) {
           // Update the HTML with the fetched auction data
           document.querySelector('#auctionImage').src = data.image_url;
           document.querySelector('#itemTitle').textContent = data.title;
           document.querySelector('#itemDescription').textContent = data.description;
           document.querySelector('.seller-name').textContent = "Hosted By: " + data.seller_name + " - " + data.seller_email;
           document.querySelector('.bid-amount').textContent = '$' + data.current_bid;
           if(data.time_left == 'Auction Over!') {
               document.querySelector('#winner-block').style.display = '';
           }
           document.querySelector('.winner').textContent = data.winner;
           document.querySelector('.time').textContent = data.time_left;

           // Reveal hidden sections
           document.getElementById('current-item').style.display = 'block';
           document.querySelector('.previous-bidders').style.display = 'block';
           document.querySelector('.place-bid').style.display = 'block';

           // Set previous bidders table
           setPreviousBidders(data.bidders);
       } else {
           // Show error message if auction not found
           document.getElementById('error-message').textContent = 'Error fetching auction: ' + data.message;
           document.getElementById('error-message').style.display = 'block';
       }
   })
   .catch(error => console.error('Error:', error));
}

// Sets the previous bidders
function setPreviousBidders(bidders) {
   const tbody = document.querySelector('.previous-bidders tbody');
   tbody.innerHTML = '';  // Clear existing rows
   bidders.forEach(bid => {
      const row = document.createElement('tr');
      row.innerHTML = `
         <td>${bid.anonymous ? "Anonymous" : bid.bidder_name}</td>
         <td>$${bid.bid_amount}</td>
         <td>${bid.bid_time}</td>
      `;
      tbody.appendChild(row);
   });
}

// Placing a bid
document.querySelector('form').addEventListener('submit', function (event) {
   event.preventDefault();

   // Values
   const auctionId = getAuctionIdFromUrl();
   const bidAmount = parseFloat(document.querySelector('#bid-amount').value);
   const anonymous = document.querySelector('#anonymous').checked ? 1 : 0;

   // Validate bid amount
   if (isNaN(bidAmount) || bidAmount <= 0) {
      alert("Please enter a valid bid amount.");
      return;
   }

   // Send the bid data to PHP
   fetch('./php/place_bid.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
         auction_id: auctionId,
         bid_amount: bidAmount,
         anonymous: anonymous,
         bidder_name: "Person" // Replace this when login is complete
      })
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         alert("Bid placed successfully!");
         window.location.reload();
      } else {
         alert("Error placing bid: " + data.message);
      }
   })
   .catch(error => console.error('Error:', error));
});

// On page load...
document.addEventListener('DOMContentLoaded', () => {
   const auctionId = getAuctionIdFromUrl();
   if (auctionId) {
      fetchAuctionDetails(auctionId);
   } else {
      // No auction id provided
      document.getElementById('error-message').style.display = 'block';
   }
});
