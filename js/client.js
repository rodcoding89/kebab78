/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */
// Autocomplétion d'adresse avec Nominatim
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById("client")){
        const streetInput = document.getElementById('street');
        const postalCodeInput = document.getElementById('postalCode');
        const cityInput = document.getElementById('city');
        const suggestionsDiv = document.getElementById('suggestions');

        let timeoutId;

        streetInput.addEventListener('input', function() {
            clearTimeout(timeoutId);

            if (streetInput.value.length < 3) {
                suggestionsDiv.style.display = 'none';
                return;
            }

            timeoutId = setTimeout(() => {
                fetchAddressSuggestions(streetInput.value);
            }, 300);
        });

        function fetchAddressSuggestions(query) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&countrycodes=fr&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        suggestionsDiv.style.display = 'none';
                        return;
                    }

                    suggestionsDiv.innerHTML = '';
                    data.forEach(item => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.className = 'suggestion-item';
                        suggestionItem.textContent = item.display_name;

                        suggestionItem.addEventListener('click', function() {
                            streetInput.value = item.address.road || item.address.house_number || '';
                            postalCodeInput.value = item.address.postcode || '';
                            cityInput.value = item.address.city || item.address.town || item.address.village || '';
                            postalCodeInput.removeAttribute('readonly');
                            cityInput.removeAttribute('readonly');
                            suggestionsDiv.style.display = 'none';
                        });

                        suggestionsDiv.appendChild(suggestionItem);
                    });

                    suggestionsDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des suggestions:', error);
                    suggestionsDiv.style.display = 'none';
                });
        }

        // Cacher les suggestions quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (e.target !== streetInput) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});
