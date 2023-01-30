ymaps.ready(init);

function init() {
    coord1 = '55.698411';
    coord2 = '37.706326';

    var myMap = new ymaps.Map('map', {
        center: [coord1, coord2],
        zoom: 17
    });

    myPlacemark = new ymaps.Placemark([coord1, coord2], {
            balloonContent: 'Фактические координаты',
        },
        {
            preset: 'islands#bluePersonIcon',
        });

    myMap.geoObjects.add(myPlacemark);

    var spanAddress = document.getElementById("addressMap");
    var inputAddress = document.getElementById("suggest");


    if (typeof (inputAddress) != 'undefined' && inputAddress != null) {
        if (inputAddress.value.length > 0){
            geocode(inputAddress.value);
        }
    }

    if (typeof (spanAddress) != 'undefined' && spanAddress != null) {
        geocode(spanAddress.innerText);
    } else {
        // Слушаем клик на карте.
        myMap.events.add('click', function (e) {

            var coords = e.get('coords');

            // Если метка уже создана – просто передвигаем ее.
            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            }
            // Если нет – создаем.
            else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
                // Слушаем событие окончания перетаскивания на метке.
                myPlacemark.events.add('dragend', function () {
                    getAddress(myPlacemark.geometry.getCoordinates());
                });
            }
            getAddress(coords);
        });
    }


    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties
                .set({
                    // Формируем строку с данными об объекте.
                    iconCaption: [
                        // Название населенного пункта или вышестоящее административно-территориальное образование.
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '),
                    // В качестве контента балуна задаем строку с адресом объекта.
                    balloonContent: firstGeoObject.getAddressLine()
                });

            $('#suggest').val(firstGeoObject.getAddressLine());
        });
    }

    // //поисковая строка
    new ymaps.SuggestView('suggest');

    $('#suggest').bind('blur', function (e) {
        geocode($('#suggest').val());
    });

    function geocode(address) {

        ymaps.geocode(address).then(function (res) {
            var obj = res.geoObjects.get(0),
                error, hint;

            if (obj) {
                switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                    case 'exact':
                        break;
                    case 'number':
                    case 'near':
                    case 'range':
                        error = 'Неточный адрес, требуется уточнение';
                        hint = 'Уточните номер дома';
                        break;
                    case 'street':
                        error = 'Неполный адрес, требуется уточнение';
                        hint = 'Уточните номер дома';
                        break;
                    case 'other':
                    default:
                        error = 'Неточный адрес, требуется уточнение';
                        hint = 'Уточните адрес';
                }
            } else {
                error = 'Адрес не найден';
                hint = 'Уточните адрес';
            }

            // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
            if (error) {
                showError(error);
            } else {
                myPlacemark.geometry.setCoordinates(obj.geometry.getCoordinates());
                myMap.setCenter(obj.geometry.getCoordinates(), 15);
                myMap.geoObjects.add(myPlacemark);

                $('#notice').css('display', 'none');
            }
        }, function (e) {
            console.log(e);
        });
    }

    function showError(message) {
        $('#notice').text(message);
        $('#notice').css('display', 'block');
    }
}


// ymaps.ready(init);
//
// function init() {
//
//     var spanAddress = document.getElementById("addressMap");
//     var inputMap = document.getElementById("suggest");
//
//     if (typeof (inputMap) != 'undefined' && inputMap != null) {
//         if (inputMap.value.length > 0) {
//             geocode(inputMap.value, "200px");
//         }
//     }
//
//     if (typeof (spanAddress) != 'undefined' && spanAddress != null) {
//         geocode(spanAddress.innerText, "400px");
//     }
//
//     // Подключаем поисковые подсказки к полю ввода.
//     var suggestView = new ymaps.SuggestView('suggest'), map, placemark;
//
//
//     inputMap.addEventListener("blur", function (event) {
//         // if (event.key === "Enter") {
//         //     event.preventDefault();
//         //     geocodeRun(inputMap.value, "200px");
//         // }
//         console.log(111);
//         geocodeRun(inputMap.value, "200px");
//     });
//
//
//     function geocodeRun(address, height) {
//         geocode(address, height)
//     }
//
//     function geocode(address, height) {
//
//
//         // Геокодируем введённые данные.
//         ymaps.geocode(address).then(function (res) {
//             var obj = res.geoObjects.get(0), error, hint;
//
//             if (obj) {
//                 switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
//                     case 'exact':
//                         break;
//                     case 'number':
//                     case 'near':
//                     case 'range':
//                         error = 'Inaccurate address, clarification required';
//                         hint = 'Specify the house number';
//                         break;
//                     case 'street':
//                         error = 'Incomplete address, clarification required';
//                         hint = 'Specify the house number';
//                         break;
//                     case 'other':
//                     default:
//                         error = 'Inaccurate address, clarification required';
//                         hint = 'Specify the address';
//                 }
//             } else {
//                 error = 'Address not found';
//                 hint = 'Specify the address';
//             }
//
//             // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
//             if (error) {
//                 showError(error);
//                 showMessage(hint);
//             } else {
//                 showResult(obj, height);
//             }
//         }, function (e) {
//             console.log(e)
//         })
//
//     }
//
//     function showResult(obj, height) {
//         // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
//         $('#suggest').removeClass('input_error');
//         $('#notice').css('display', 'none');
//
//         var mapDiv = document.getElementById("map");
//         mapDiv.classList.remove("hidden");
//         mapDiv.classList.add("mt-2");
//         mapDiv.style.height = height;
//
//         var mapContainer = $('#map'),
//             bounds = obj.properties.get('boundedBy'), // Рассчитываем видимую область для текущего положения пользователя.
//             mapState = ymaps.util.bounds.getCenterAndZoom(bounds, [mapContainer.width(), mapContainer.height()]), // Сохраняем полный адрес для сообщения под картой.
//             address = [obj.getCountry(), obj.getAddressLine()].join(', '), // Сохраняем укороченный адрес для подписи метки.
//             shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
//         // Убираем контролы с карты.
//         mapState.controls = [];
//         // Создаём карту.
//         createMap(mapState, shortAddress);
//         // Выводим сообщение под картой.
//         showMessage(address);
//     }
//
//     function showError(message) {
//         $('#notice').text(message);
//         $('#suggest').addClass('input_error');
//         $('#notice').css('display', 'block');
//         // Удаляем карту.
//         if (map) {
//             map.destroy();
//             map = null;
//         }
//     }
//
//     function createMap(state, caption) {
//         // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
//         if (!map) {
//             map = new ymaps.Map('map', state);
//             placemark = new ymaps.Placemark(map.getCenter(), {
//                 iconCaption: caption, balloonContent: caption
//             }, {
//                 preset: 'islands#redDotIconWithCaption'
//             });
//             map.geoObjects.add(placemark);
//             // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
//         } else {
//             map.setCenter(state.center, state.zoom);
//             placemark.geometry.setCoordinates(state.center);
//             placemark.properties.set({iconCaption: caption, balloonContent: caption});
//         }
//         // forclickmap(map)
//     }
//
//     function forclickmap(map){
//         map.events.add('click', function (e) {
//             var coords = e.get('coords');
//
//             // Если метка уже создана – просто передвигаем ее.
//             if (myPlacemark) {
//                 myPlacemark.geometry.setCoordinates(coords);
//             }
//             // Если нет – создаем.
//             else {
//                 myPlacemark = createPlacemark(coords);
//                 myMap.geoObjects.add(myPlacemark);
//                 // Слушаем событие окончания перетаскивания на метке.
//                 myPlacemark.events.add('dragend', function () {
//                     getAddress(myPlacemark.geometry.getCoordinates());
//                 });
//             }
//             getAddress(coords);
//         });
//     }
//
//     function getAddress(coords) {
//         myPlacemark.properties.set('iconCaption', 'поиск...');
//         ymaps.geocode(coords).then(function (res) {
//             var firstGeoObject = res.geoObjects.get(0);
//
//             myPlacemark.properties
//                 .set({
//                     // Формируем строку с данными об объекте.
//                     iconCaption: [
//                         // Название населенного пункта или вышестоящее административно-территориальное образование.
//                         firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
//                         // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
//                         firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
//                     ].filter(Boolean).join(', '),
//                     // В качестве контента балуна задаем строку с адресом объекта.
//                     balloonContent: firstGeoObject.getAddressLine()
//                 });
//
//             $('#suggest').val(firstGeoObject.getAddressLine());
//         });
//     }
//
//
// }
