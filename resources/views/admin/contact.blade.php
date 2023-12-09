@extends('layouts.master-student')

@section('content')
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>HỖ TRỢ</a>
            </li>
        </ol>
    </div>
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-solid fa-phone"></i> HỖ TRỢ
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .map-container iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .contact-container {
                grid-template-columns: 2fr 2fr;
            }

            .contact-adress {
                width: 50%;
            }

            .contact-address h2 {
                font-size: 20px;
            }

            .contact-map {
                width: 100%;
                height: auto;
                float: 50%;
            }

            .address {
                margin-left: 20px;
            }
        </style>

        <div class="container">
            <div class="contact-container">
                <div class="row">
                    <div class="contact-address">
                        <div class="table">
                            <h1>Địa chỉ</h1>
                            <div class="address">
                                <h2><strong>Thu Duc Campus ( Khu E )</strong></h2>
                                <p><strong>Địa chỉ: </strong>Phân khu đào tạo E1, Khu Công Nghệ cao TP.HCM, Phường Hiệp Phú,
                                    TP. Thủ Đức, TP.HCM</p>
                                <h2><strong>Sai Gon Campus ( Khu A,B )</strong></h2>
                                <p><strong>Địa chỉ: </strong>475A Điện Biên Phủ, P.25, Q.Bình Thạnh, TP.HCM</p>
                                <h2><strong>Ung Van Khiem Campus ( Khu U )</strong></h2>
                                <p><strong>Địa chỉ: </strong>31/36 Ung Văn Khiêm, P.25, Q.Bình Thạnh, TP.HCM</p>
                                <h2><strong>Hitech Park Campus ( Khu R )</strong></h2>
                                <p><strong>Địa chỉ: </strong>Viện Công nghệ Cao Hutech, Lô E2B4, đường D1, Phường Long Thạnh
                                    Mỹ, khu Công Nghệ Cao, TP. Thủ Đức, TP.HCM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="contact-map" class="contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62698.58478963264!2d106.67754862557004!3d10.837191866285288!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527c3debb5aad%3A0x5fb58956eb4194d0!2zxJDhuqFpIEjhu41jIEh1dGVjaCBLaHUgRQ!5e0!3m2!1svi!2s!4v1685284973513!5m2!1svi!2s"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <br><br><br><br><br>

        <!-- <script>
            function initMap() {
                // Tạo đối tượng map và đặt tọa độ trung tâm
                var map = new google.maps.Map(document.getElementById('contact-map'), {
                    center: {
                        lat: 10.793647,
                        lng: 106.675965
                    },
                    zoom: 16
                });

                // Đặt đánh dấu vị trí trên bản đồ
                var marker = new google.maps.Marker({
                    position: {
                        lat: 10.793647,
                        lng: 106.675965
                    },
                    map: map,
                    title: 'Khu E, Đại học Hutech'
                });
            }
        </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script> -->
    </div>
@stop
