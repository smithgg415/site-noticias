async function getWeather() {
    const cityName = "Presidente Prudente";
    const API_KEY = '848082604d168d1154ccdd2326eb057e';
    const apiURL = `https://api.openweathermap.org/data/2.5/forecast?q=${encodeURI(cityName)}&appid=${API_KEY}&units=metric&lang=pt_br`;

    const response = await fetch(apiURL);
    const data = await response.json();

    const currentWeather = data.list[0];
    document.getElementById('city-name').innerText = cityName;
    document.getElementById('current-temp').innerText = `${Math.round(currentWeather.main.temp)}°C`;
    document.getElementById('current-desc').innerText = currentWeather.weather[0].description;
    updateWeatherIcon('current-weather-icon', currentWeather.weather[0].icon);

    const forecastContainer = document.getElementById('forecast-container');
    forecastContainer.innerHTML = '';

    const forecastsByDay = groupForecastsByDay(data.list);

    const today = new Date().toISOString().split('T')[0];
    Object.keys(forecastsByDay)
        .filter(date => date > today)
        .slice(0, 3)
        .forEach((date, index) => {
            const forecast = forecastsByDay[date][0];
            const temp = Math.round(forecast.main.temp);
            const icon = forecast.weather[0].icon;

            const dayCard = document.createElement('div');
            dayCard.classList.add('col-4');
            dayCard.innerHTML = `
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="weather-icon mb-2">
                            <img src="http://openweathermap.org/img/wn/${icon}@2x.png" alt="Weather Icon" width="50">
                        </div>
                        <h6 class="date">${new Date(date).toLocaleDateString()}</h6>
                        <p class="text-muted">${forecast.weather[0].description}</p>
                        <p class="temp mb-0">${temp}&deg;C</p>
                    </div>
                </div>
            `;
            forecastContainer.appendChild(dayCard);
        });
}

function updateWeatherIcon(elementId, iconCode) {
    const iconElement = document.getElementById(elementId);
    iconElement.innerHTML = `<img src="http://openweathermap.org/img/wn/${iconCode}@2x.png" alt="Weather Icon" width="60">`;
}

function groupForecastsByDay(forecasts) {
    return forecasts.reduce((acc, forecast) => {
        const date = new Date(forecast.dt * 1000).toISOString().split('T')[0];
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(forecast);
        return acc;
    }, {});
}

getWeather();
setInterval(() => {
    getWeather();
}, 3600000);