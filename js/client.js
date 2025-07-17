/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */
// Autocomplétion d'adresse avec Nominatim
const lat =  "48.9324544";
const lon = "2.0611072";
const adresse = "3 Rue des Châtaigniers, 78300 Poissy, France";
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById("client")){
        const streetInput = document.getElementById('street');
        const postalCodeInput = document.getElementById('postalCode');
        const cityInput = document.getElementById('city');
        const countryInput = document.getElementById('country');
        const suggestionsDiv = document.getElementById('suggestions');

        let timeoutId;

        const API_KEY = 'pk.4351bea6a436ddc1d6c63c720e595fd1'; // Remplace par ta clé

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

        async function fetchAddressSuggestions(query) {
            try {
                const res = await fetch(`https://api.locationiq.com/v1/autocomplete.php?key=${API_KEY}&q=${encodeURIComponent(query)}&limit=5&countrycodes=fr&format=json`);
                const data = await res.json();
                console.log(data);
                if (data.length === 0) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }

                suggestionsDiv.innerHTML = '';
                data.forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'suggestion-item';
                    suggestionItem.textContent = item.display_address || item.display_name;
                    const form = document.getElementById("checkoutForm");
                    suggestionItem.addEventListener('click', function() {
                        console.log("address",item);
                        streetInput.value = (item.address.house_number || '') + (' '+item.address.road || '');
                        postalCodeInput.value = item.address.postcode || '';
                        cityInput.value = item.address.city || '';
                        countryInput.value = item.address.country || '';
                        postalCodeInput.removeAttribute('readonly');
                        cityInput.removeAttribute('readonly');
                        countryInput.removeAttribute('readonly');
                        suggestionsDiv.style.display = 'none';
                        const inputLat = document.createElement('input');
                        inputLat.type = 'hidden';
                        inputLat.value = item.lat;
                        inputLat.name = 'inputLat';
                        const inputLon = document.createElement('input');
                        inputLon.type = 'hidden';
                        inputLon.value = item.lon;
                        inputLon.name = 'inputLon';
                        form.appendChild(inputLat);
                        form.appendChild(inputLon);
                        sessionStorage.setItem("coordone",JSON.stringify({lat:item.lat,lon:item.lon,adresse:streetInput.value+', '+cityInput.value+', '+postalCodeInput.value+','+countryInput.value}));
                    });

                    suggestionsDiv.appendChild(suggestionItem);
                });

                suggestionsDiv.style.display = 'block';
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Cacher les suggestions quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (e.target !== streetInput) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});

$("#firstName").on("input",async()=>{

    if ($("#firstName").val().length < 3) {
        $("#firstNameResult").css({display:'none'});
        return;
    }

    const divList = await filterClient($("#firstName").val(),"firstName");
    //console.log("finding",divList);
    if (divList) {
        $("#firstNameResult div").remove();
        $("#firstNameResult").prepend(divList);
        $("#firstNameResult").css({display:'flex'});
    }else{
        $("#firstNameResult").css({display:'none'});
        return;
    }
});

$("#lastName").on("input",async()=>{
    if ($("#lastName").val().length < 3) {
        $("#lastNameResult").css({display:'none'});
        return;
    }
    const divList = await filterClient($("#lastName").val(),"lastName");
    if (divList) {
        $("#lastNameResult div").remove();
        $("#lastNameResult").prepend(divList);
        $("#lastNameResult").css({display:'flex'});
    }else{
        $("#lastNameResult").css({display:'none'});
        return;
    }
});

function fillInputField(clientData){
    const firstName = $('#firstName');
    const lastName = $('#lastName');
    const email = $('#email');
    const tel = $('#phone');
    const street = $('#street');
    const cp = $('#postalCode');
    const city = $('#city');
    const country = $('#country');
    firstName.val(clientData.first_name);
    lastName.val(clientData.last_name);
    email.val(clientData.email);
    tel.val(clientData.tel);
    street.val(clientData.street);
    cp.val(clientData.postal_code);
    city.val(clientData.city);
    country.val(clientData.country);
    $("#firstNameResult").css({ display: 'none' });
    $("#lastNameResult").css({ display: 'none' });
    sessionStorage.setItem("coordone",JSON.stringify({lat:clientData.lat,lon:clientData.lon,adresse:clientData.street+', '+clientData.city+', '+clientData.postal_code+','+clientData.country}));
    console.log("clientData",clientData);
}


$("#firstNameResult").on("click","div", (event)=>{
    const client = $(event.target).data('client');
    fillInputField(client);
});
$("#lastNameResult").on("click","div", (event)=>{
    const client = $(event.target).data('client');
    fillInputField(client);
});

function filterClient(input,type) {
    return new Promise((resolve, reject) => {
        $.post(
            RACINE + "inc/controls.php",
            { 'postType': 'filterClient', searchTerm: input },
            (res) => {
                if (res.client) {
                    if (res.client.length === 0) {
                        $("#firstNameResult").css({ display: 'none' });
                        $("#lastNameResult").css({ display: 'none' });
                        resolve(null);
                        return; // Added return to prevent further execution
                    }
                    let listDiv = '';
                    res.client.forEach((el) => {
                        listDiv += '<div data-client=\'' + JSON.stringify(el) + '\'>' + el.first_name + ' ' + el.last_name + '</div>';
                    });
                    //console.log("listDiv", listDiv);
                    resolve(listDiv);
                } else {
                    resolve(null);
                }
            },
            'json'
        ).fail((error) => {
            reject(error); // Handle AJAX failure
        });
    });
}

if(document.getElementById('confirmation')){
    const coordonne = sessionStorage.getItem('coordone');
    if(coordonne){
        const data = JSON.parse(coordonne);
        const destAdresse = data.adresse;
        const destLat = data.lat;
        const destLon = data.lon;
        const distance = haversineDistance(lat,lon,destLat,destLon);
        let map = L.map('map').setView([lat, lon], 4);
        let marker = L.marker([lat, lon]).addTo(map);
        const distancePrice = 0.5 * Math.round(distance);
        const totalOrders = document.getElementById("odersTotal");
        const totalDelivery = document.getElementById("totalDelivery");
        const ordersPlusDelivery = document.getElementById("ordersPlusDelivery");
        const pTotal = document.createElement("p");
        const div = document.createElement('div');
        const em = document.createElement('em');
        em.style = "font-size:13px;font-style:normal;";
        em.textContent = "Nombre de Kilomètre (KM) : " +Math.round(distance);
        const em1 = document.createElement('em');
        em1.textContent = "Prix du KM : "+ 0.5 +"€";
        em1.style = "font-size:13px;font-style:normal;";
        const p = document.createElement('p');
        p.textContent = "Prix totale : "+distancePrice +"€";
        p.style = "font-size:15px";

        div.appendChild(em);
        div.appendChild(em1);
        div.appendChild(p);
        div.style = "display:flex;flex-direction:column;gap:5px;align-items: flex-end;";
        totalDelivery.appendChild(div);
        console.log("distancePrice",distancePrice,"totalOrders.value",totalOrders.value);
        pTotal.textContent = (parseFloat(distancePrice) + parseFloat(totalOrders.value)) + '€';
        ordersPlusDelivery.appendChild(pTotal);
        // Ajouter la tuile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);
        
        marker.bindPopup("<b> Distance : "+Math.round(distance)+" KM</b>.").openPopup();

        // Ajouter l'itinéraire : Paris (48.8566, 2.3522) -> Toulouse (43.6047, 1.4442)
        let control = L.Routing.control({
          waypoints: [
            L.latLng(lat, lon), // Paris
            L.latLng(destLat, destLon)  // Toulouse
          ],
          routeWhileDragging: true,
          language: 'fr',
          showAlternatives: true,
          /*fitSelectedRoutes: false*/
        }).addTo(map);
        control.on('routesfound', function(e) {
          // Exemple : centre sur ton point initial avec ton zoom préféré
          map.setZoom(10);
        });
    }
}

function haversineDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Rayon de la Terre en km
  const toRad = angle => angle * Math.PI / 180;

  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);

  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);

  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  const distance = R * c;

  return distance; // en km
}

