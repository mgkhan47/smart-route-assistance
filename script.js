document.addEventListener('DOMContentLoaded', () => {
    loadCountries();

    // Event Listeners
    document.getElementById('country').addEventListener('change', loadCities);
    document.getElementById('route-form').addEventListener('submit', findRoute);
});

async function loadCountries() {
    try {
        const response = await fetch('api.php?action=get_countries');
        const countries = await response.json();
        
        const countrySelect = document.getElementById('country');
        countries.forEach(c => {
            const option = document.createElement('option');
            option.value = c.id;
            option.textContent = c.name;
            countrySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading countries:', error);
    }
}

async function loadCities() {
    const countryId = document.getElementById('country').value;
    const sourceSelect = document.getElementById('source');
    const destSelect = document.getElementById('destination');

    // Reset Dropdowns
    sourceSelect.innerHTML = '<option value="">-- Select Source --</option>';
    destSelect.innerHTML = '<option value="">-- Select Destination --</option>';

    if (!countryId) return;

    try {
        const response = await fetch(`api.php?action=get_cities&country_id=${countryId}`);
        const cities = await response.json();

        cities.forEach(c => {
            // Add to Source
            const opt1 = document.createElement('option');
            opt1.value = c.id;
            opt1.textContent = c.name;
            sourceSelect.appendChild(opt1);

            // Add to Destination
            const opt2 = opt1.cloneNode(true); // Clone for second dropdown
            destSelect.appendChild(opt2);
        });
    } catch (error) {
        console.error('Error loading cities:', error);
    }
}

async function findRoute(e) {
    e.preventDefault();
    
    const source = document.getElementById('source').value;
    const dest = document.getElementById('destination').value;
    const criteria = document.querySelector('input[name="criteria"]:checked').value;
    
    if(!source || !dest) {
        alert("Please select both a source and a destination.");
        return;
    }

    // Show Loading
    document.querySelector('.loading').style.display = 'block';
    document.getElementById('result-area').style.display = 'none';

    try {
        const response = await fetch(`api.php?action=calculate_route&source_id=${source}&dest_id=${dest}&criteria=${criteria}`);
        const data = await response.json();

        document.querySelector('.loading').style.display = 'none';
        
        if (data.error) {
            alert(data.error);
        } else {
            renderResult(data);
        }
    } catch (error) {
        console.error('Error finding route:', error);
        alert('Something went wrong!');
    }
}

function renderResult(data) {
    const resultArea = document.getElementById('result-area');
    const summaryText = document.getElementById('route-summary');
    const vizContainer = document.getElementById('route-viz');

    resultArea.style.display = 'block';
    
    // 1. Update Text Summary
    summaryText.innerHTML = `Total ${data.unit === 'km' ? 'Distance' : 'Cost'}: <strong>${data.total_value} ${data.unit}</strong>`;

    // 2. Draw Visualization (Circles and Arrows)
    vizContainer.innerHTML = ''; // Clear previous

    data.path.forEach((city, index) => {
        // Create City Node
        const node = document.createElement('div');
        node.className = 'city-node';
        node.textContent = city;
        vizContainer.appendChild(node);

        // Add Arrow if not the last city
        if (index < data.path.length - 1) {
            const arrow = document.createElement('div');
            arrow.className = 'arrow';
            arrow.innerHTML = '&#8594;'; // Right arrow symbol
            vizContainer.appendChild(arrow);
        }
    });
}