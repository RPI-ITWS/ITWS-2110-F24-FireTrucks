// Get auction id from url
function getGiveawayIdFromUrl() {
   const params = new URLSearchParams(window.location.search);
   return params.get('id');  
}

// Get auction information
function fetchGiveawayDetails(giveawayId) {
   fetch(`./php/fetch_giveaway.php?id=${giveawayId}`, {
      method: 'GET',
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         // Update the HTML with the fetched auction data
         document.querySelector('#giveawayImage').src = data.image_url;
         document.querySelector('#itemTitle').textContent = data.title;
         document.querySelector('#itemDescription').textContent = data.description;
         document.querySelector('.seller-name').textContent = data.seller;
         document.querySelector('#winner').textContent = data.winner_first_name + " " + data.winner_last_name;
         if(data.time_left == 'Giveaway Over!') {
            document.querySelector('#winner-block').style.display = '';
         }
         document.querySelector('.time').textContent = data.time_left;
         document.querySelector('#numberEntrants').textContent = "Number of entrants: " + data.participantsNum;

         // Reveal hidden sections
         document.getElementById('current-item').style.display = 'block';
         document.querySelector('.enter-giveaway').style.display = 'block';
      } else {
         // Show error message if auction not found
         document.getElementById('error-message').textContent = 'Error fetching giveaway: ' + data.message;
         document.getElementById('error-message').style.display = 'block';
      }
   })
   .catch(error => console.error('Error:', error));
}

// Joining giveaway
document.querySelector('form').addEventListener('submit', function (event) {
   event.preventDefault();

   // Values
   const giveawayId = getGiveawayIdFromUrl();
   const user = "NAME"

   // Send the giveaway data to PHP
   fetch('./php/enter_giveaway.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
         giveaway_id: giveawayId,
         user: user // Replace this when login is complete
      })
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         alert(data.message);
         window.location.reload();
      } else {
         alert("Error placing giveaway: " + data.message);
      }
   })
   .catch(error => console.error('Error:', error));
});


// On page load...
document.addEventListener('DOMContentLoaded', () => {
   const giveawayId = getGiveawayIdFromUrl();
   if (giveawayId) {
      fetchGiveawayDetails(giveawayId);
   } else {
      // No auction id provided
      document.getElementById('error-message').style.display = 'block';
   }
});