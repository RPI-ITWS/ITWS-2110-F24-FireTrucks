//function to make form dynamic
document.getElementById('next').addEventListener('click', function() {
    document.querySelectorAll('.box').forEach(function (box) {
        box.classList.add('hidden');
    });
    const selected = document.querySelector('input[name="listingType"]:checked');
    if (selected) {
        const formId = selected.value.toLowerCase() + 'form';
        const formName = document.getElementById(formId);
        formName.classList.remove('hidden');
        formName.parentElement.classList.remove('hidden');
    } else {
        alert('Please select type of listing.')
    }
});