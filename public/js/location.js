const getLocation = () =>
    {
        if ("geolocation" in navigator)
        {
            navigator.permissions.query({ name: 'geolocation' })
            .then(function(permissionStatus)
            {
                if (permissionStatus.state === 'granted')
                {
                    // alert('Trình duyệt đã được cấp quyền truy cập vị trí.');
                    }
                    else if (permissionStatus.state === 'prompt')
                    {
                        alert('Trình duyệt đang yêu cầu quyền truy cập vị trí.');
                    }
                    else
                    {
                        alert('Quyền truy cập vị trí bị từ chối hoặc bị hạn chế.');
                    }
                })
            .catch(function(error)
            {
                console.error('Lỗi khi kiểm tra quyền truy cập vị trí: ' + error.message);
            });

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
                    //(latitude >= 10.88270 && latitude <= 10.88390) && (longitude >= 106.77920 && longitude <= 106.78025) (Ký túc xá khu B)
                     //(latitude >= 10.93315 && latitude <= 10.93340) && (longitude >= 107.10270 && longitude <= 107.10290) (Nhà riêng)
                {
                    // document.getElementById("notify").textContent = "Nằm trong vị trí đã cho";
                    console.log("Nằm trong vị trí đã cho");
                    document.getElementById("login-btn").disabled = false;
                }
                else
                {
                    // document.getElementById("notify").textContent = "Không nằm trong vị trí đã cho";
                    console.log("Không nằm trong vị trí đã cho");
                    // document.getElementById("login-btn").disabled = false;
                    document.getElementById("username").addEventListener('input', function()
                    {
                        let inputValue = this.value;
                        if(!isNaN(inputValue))
                        {
                            console.log("là kiểu số");
                            // document.getElementById("login-btn").disabled = true;
                            // Bật cho phép sv truy cập từ xa
                            document.getElementById("login-btn").disabled = false;
                        }
                        else
                        {
                            console.log("là kiểu chuỗi");
                            document.getElementById("login-btn").disabled = false;
                        }
                    })
                }
            });
        }
        else
        {
            console.log("Trình duyệt không hỗ trợ Geolocation.");
        }
    }
