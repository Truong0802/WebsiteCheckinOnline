const getLocation = () =>
{
    if ("geolocation" in navigator)
    {
        navigator.geolocation.getCurrentPosition(function(position)
        {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;

            console.log("Latitude: " + latitude);
            console.log("Longitude: " + longitude);

            // document.getElementById("Latitude").textContent = "Vĩ độ: " + latitude;
            // document.getElementById("Longitude").textContent = "Kinh độ: " + longitude;

            if ((latitude >= 10.854600 && latitude <= 10.856000) &&
                (longitude >= 106.784120 && longitude <= 106.786130))
            {
                // document.getElementById("notify").textContent = "Nằm trong vị trí đã cho";
                console.log("Nằm trong vị trí đã cho");
            }
            else
            {
                // document.getElementById("notify").textContent = "Không nằm trong vị trí đã cho";
                console.log("Không nằm trong vị trí đã cho");
                const button = document.getElementById("login-btn");
                button.disabled = true;
            }
        });
    }
    else
    {
        console.log("Trình duyệt không hỗ trợ Geolocation.");
    }
}


// 10.85519300012993, 106.78412306774622
// 10.855140315839378, 106.78613204227732x
