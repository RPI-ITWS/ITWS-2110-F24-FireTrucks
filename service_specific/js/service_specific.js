// Get service id from url
function getServiceIdFromUrl() {
   const params = new URLSearchParams(window.location.search);
   return params.get('id');  
}

// Get service information
function fetchServiceDetails(serviceId) {
   fetch(`./php/fetch_service.php?id=${serviceId}`, {
       method: 'GET',
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         document.querySelector('#serviceImage').src = data.image_url;
         document.querySelector('#itemTitle').textContent = data.Name;
         document.querySelector('#itemDescription').textContent = data.Description;
         document.querySelector('#itemSeller').textContent = data.Seller;
         document.querySelector('#itemPrice').textContent = data.Price.toFixed(2);
         document.querySelector('#itemPhoneNumber').textContent = data.PhoneNumber;
         document.querySelector('#itemEmail').textContent = data.Email;

         // Reveal hidden sections
         document.getElementById('current-item').style.display = 'block';
      } else {
         // Show error message if service not found
         document.getElementById('error-message').style.display = 'block';
      }
   })
   .catch(error => console.error('Error:', error));
}

// On page load...
document.addEventListener('DOMContentLoaded', () => {
   const serviceId = getServiceIdFromUrl();
   if (serviceId) {
       fetchServiceDetails(serviceId);
   } else {
       // No service id provided
       document.getElementById('error-message').style.display = 'block';
   }
});
