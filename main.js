const userIsSignedIn = false; // Change this to `true` or `false` to simulate whether a user is signed in

const loginButtonContainer = document.getElementById('login-button');

if (userIsSignedIn) {
   const profileContainer = document.createElement('div');
   profileContainer.className = 'profile-container';

   const profileImage = document.createElement('img');
   profileImage.src = '../images/profile-icon.png'; 
   profileImage.alt = 'Profile Icon';
   profileImage.className = 'profileIcon';

   const dropdownMenu = document.createElement('div');
   dropdownMenu.className = 'dropdown-menu';

   const profileLink = document.createElement('a');
   profileLink.href = '../profile/profile.html';
   profileLink.textContent = 'Profile';

   const listingsLink = document.createElement('a');
   listingsLink.href = '../listings/listings.html';
   listingsLink.textContent = 'My Listings';

   dropdownMenu.appendChild(profileLink);
   dropdownMenu.appendChild(listingsLink);

   profileContainer.appendChild(profileImage);
   profileContainer.appendChild(dropdownMenu);

   loginButtonContainer.appendChild(profileContainer);

} else {
   const signInButton = document.createElement('button');
   signInButton.textContent = 'Sign In';
   signInButton.className = 'headerbutton';

   signInButton.addEventListener('click', function() {
      window.location.href = './login/login.php'; 
   });

   loginButtonContainer.appendChild(signInButton);
}
