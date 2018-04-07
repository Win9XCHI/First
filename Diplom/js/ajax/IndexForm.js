'use strict';

var c = 0;
var flag = false;
var mas = [];
var mas2 = [];
var masDOM = [];
var flagLine = false;
var map;
var marker1;
var marker2;
var flightPath;
$('#box_text').toggle();

//Вирахування дати
var d = new Date();
var day = d.getDate();
var month = d.getMonth() + 1;
var year = d.getFullYear();
var name_input = document.getElementById('dat')
name_input.setAttribute('min', year + "-" + month + "-" + day);

//Випадаючий список санцій

document.getElementById('St_1').onkeypress = function (e) {
    flag = false;
    var len = mas.length;

    if (len != 0) {
        var t = document.getElementById("kabsearchform1");
        for (var i = 0; i < len; i++) {
            t.removeChild(mas[i]);
        }
        mas = [];
    }
    len = mas2.length;

    if (len != 0) {
        var t = document.getElementById("kabsearchform2");

        for (var i = 0; i < len; i++) {
            t.removeChild(mas2[i]);
        }
        mas2 = [];
    }
    c = 1;

    document.getElementById('kabsearchform1').style.cssText = "display: none;";
    document.getElementById('kabsearchform2').style.cssText = "display: none;";
    // спец. сочетание - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return;
    }

    var char = getChar(e);

    if (!char) {
        return; // спец. символ - не обрабатываем
    }


    if ($(this).val().length < 30) {
        if ($(this).val().length == 0) {
            this.value += char.toUpperCase();
        } else {
            this.value += char.toLowerCase();
        }

        if ($(this).val().length > 2) {

            $.ajax({
                url: "php/CheckWay.php",
                type: "POST",
                cache: false,
                data: ({
                    'C': "6",
                    'value': this.value
                }),
                dataType: "html",
                success: StationCheck
            });
        }
    }
    return false;
}; //Ввід у поле першої станції

document.getElementById('St_2').onkeypress = function (e) {
    flag = false;
    var len = mas2.length;

    if (len != 0) {
        var t = document.getElementById("kabsearchform2");

        for (var i = 0; i < len; i++) {
            t.removeChild(mas2[i]);
        }
        mas2 = [];
    }
    len = mas.length;

    if (len != 0) {
        var t = document.getElementById("kabsearchform1");
        for (var i = 0; i < len; i++) {
            t.removeChild(mas[i]);
        }
        mas = [];
    }
    c = 2;

    document.getElementById('kabsearchform1').style.cssText = "display: none;";
    document.getElementById('kabsearchform2').style.cssText = "display: none;";
    // спец. сочетание - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) return;

    var char = getChar(e);

    if (!char) return; // спец. символ - не обрабатываем


    if ($(this).val().length < 30) {
        if ($(this).val().length == 0) {
            this.value += char.toUpperCase();
        } else {
            this.value += char.toLowerCase();
        }

        if ($(this).val().length > 2) {

            $.ajax({
                url: "php/CheckWay.php",
                type: "POST",
                cache: false,
                data: ({
                    'C': "6",
                    'value': this.value
                }),
                dataType: "html",
                success: StationCheck
            });
        }
    }
    return false;
}; //Ввід у друге поле станції

function getChar(event) {
    if (event.which == null) { // IE
        if (event.keyCode < 32) return null; // спец. символ
        return String.fromCharCode(event.keyCode)
    }

    if (event.which != 0 && event.charCode != 0) { // все кроме IE
        if (event.which < 32) return null; // спец. символ
        return String.fromCharCode(event.which); // остальные
    }

    return null; // спец. символ
} //Перевірка на спец символи

function StationCheck(data) {
    if (data != "Not Station") {
        SetStation(data);
    }
    data = "";
} //Іноормація з сервера по полям вводу

function StDiv(col, Object) {
    var div = document.createElement('div');
    div.setAttribute('id', 'mosearchresult' + col);
    div.setAttribute('data-name', Object.GetName());
    div.setAttribute('data-b', Object.GetBranch());

    div.className = "mosearchresult";
    if (c == 1) {
        document.getElementById('kabsearchform1').style.cssText = "display: block; \
        position: absolute; \
        background-color: #fafafa; \
        border: solid 1px #a3a3a3; \
        padding: 5px 10px; \
        font-family: Arial; \
        -webkit-box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        -moz-box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        -webkit-border-radius: 5px;  \
        -moz-border-radius: 5px; \
        border-radius: 5px; \
        -webkit-appearance: none; \
        z-index: 99; \
        line-height: normal; \
        width: 160px; \
      ";
        div.innerHTML = "<div><p style=\"font-size: 14px;line-height: 1;\">" + Object.GetName() + "</p> <br><p style=\"font-size: 11px;line-height: 0;\">" + Object.GetBranch() + "</p> <br><p style=\"font-size: 11px;line-height: 0;\">---------------------------</p>";
        var t = document.getElementById("kabsearchform1");
        t.appendChild(div);

        mas.push(div);
    } else {
        document.getElementById('kabsearchform2').style.cssText = "display: block; \
        position: absolute; \
        background-color: #fafafa; \
        border: solid 1px #a3a3a3; \
        padding: 5px 10px; \
        font-family: Arial; \
        -webkit-box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        -moz-box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        box-shadow: inset 0 2px 3px rgba(0, 0, 0, .1); \
        -webkit-border-radius: 5px;  \
        -moz-border-radius: 5px; \
        border-radius: 5px; \
        -webkit-appearance: none; \
        z-index: 99; \
        line-height: normal; \
        width: 160px; \
      ";
        div.innerHTML = "<div><p style=\"font-size: 14px;line-height: 1;\">" + Object.GetName() + "</p> <br><p style=\"font-size: 11px;line-height: 0;\">" + Object.GetBranch() + "</p> <br><p style=\"font-size: 11px;line-height: 0;\">---------------------------</p>";
        var t = document.getElementById("kabsearchform2");
        t.appendChild(div);

        mas2.push(div);
    }
} //Відображення випадаючих списків

$("#kabsearchform1").on("click", ".mosearchresult", function () {
    flag = true;
    c = 0;

    var St = $(this).attr("data-name");
    document.getElementById('St_1').value = St;
    document.getElementById('kabsearchform1').style.cssText = "display: none;";
    var t = document.getElementById("kabsearchform1");

    for (var i = 0; i < mas.length; i++) {
        t.removeChild(mas[i]);
        mas.splice(0, 1);
    }
}); //Обрання станції із списку по першому полю

$("#kabsearchform2").on("click", ".mosearchresult", function () {
    flag = true;
    c = 0;
    var St = $(this).attr("data-name");
    document.getElementById('St_2').value = St;
    document.getElementById('kabsearchform2').style.cssText = "display: none;";
    var t = document.getElementById("kabsearchform2");

    for (var i = 0; i < mas.length; i++) {
        t.removeChild(mas2[i]);
        mas2.splice(0, 1);
    }
}); //Обрання станції із списку по другому полю

function Station() {
    var Name = "";
    var Branch = "";

    this.SetName = function (str) {
        Name = str;
    }

    this.SetBranch = function (str) {
        Branch = str;
    }

    this.GetName = function () {
        return Name;
    }

    this.GetBranch = function () {
        return Branch;
    }
} //Клас станції для полей станцій

function SetStation(data) {
    Station.count = 0;
    var j = 0;
    var q = 0;

    var name = "";
    var branch = "";

    var flag_n = false;
    var flag_b = false;

    for (var i = 0; i < data.length; i++) {

        if (data[i] == '|') {
            flag_n = false;
            flag_b = true;
            continue;
        }

        if (flag_n) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    name += data[i];
                    i++;
                }
            }
            continue;
        }

        if (data[i] == '$') {
            flag_n = true;
            flag_b = false;

            if (j != 0) {
                j = 0;

                var ObjectR = new Station();
                ObjectR.SetName(name);
                ObjectR.SetBranch(branch);

                Station.count++;

                StDiv(Station.count, ObjectR);

                name = "";
                branch = "";
            }
            j++;
            continue;
        }

        if (flag_b) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '$') {
                    i--;
                    break;
                } else {
                    branch += data[i];
                    i++;
                }
            }
        }

    }
} //Обробка інформації з сервера 

//Пошук маршруту

function Route(userData) {
    if (userData) { // если указаны данные -- одна ветка if
        var Num = userData.num;
        var Start = userData.start;
        var Finish = userData.finish;
        var St1 = userData.st1;
        var St2 = userData.st2;
        var TimeRoute = userData.TimeRoute;
        var StartTime = userData.StartTime;
        var FinishTime = userData.FinishTime;
        var serialNumber = 0;
    } else { // если не указаны -- другая
        var Num = "";
        var Start = "";
        var Finish = "";
        var St1 = "";
        var St2 = "";
        var TimeRoute = "";
        var StartTime = "";
        var FinishTime = "";
        var serialNumber = 0;
    }

    this.SetNum = function (str) {
        Num = str;
    }

    this.SetStart = function (str) {
        Start = str;
    }

    this.SetFinish = function (str) {
        Finish = str;
    }

    this.SetSt1 = function (str) {
        St1 = str;
    }

    this.SetSt2 = function (str) {
        St2 = str;
    }

    this.SetTimeRoute = function (str) {
        TimeRoute = str;
    }

    this.SetStartTime = function (str) {
        StartTime = str;
    }

    this.SetFinishTime = function (str) {
        FinishTime = str;
    }

    this.SetSN = function (str) {
        serialNumber = str;
    }

    this.GetSN = function () {
        return serialNumber;
    }

    this.GetNum = function () {
        return Num;
    }

    this.GetStart = function () {
        return Start;
    }

    this.GetFinish = function () {
        return Finish;
    }

    this.GetSt1 = function () {
        return St1;
    }

    this.GetSt2 = function () {
        return St2;
    }

    this.GetTimeRoute = function () {
        return TimeRoute;
    }

    this.GetStartTime = function () {
        return StartTime;
    }

    this.GetFinishTime = function () {
        return FinishTime;
    }


} //Клас маршруту

function SetRoute(data) {
    Route.count = 0;
    var j = 0;
    var q = 0;

    var num = "";
    var start = "";
    var finish = "";
    var S1 = "";
    var S2 = "";
    var TR = "";
    var ST = "";
    var FT = "";

    var flag_m = false;
    var flag_S = false;
    var flag_F = false;
    var flag_St1 = false;
    var flag_St2 = false;
    var flag_TR = false;
    var flag_ST = false;
    var flag_FT = false;

    for (var i = 0; i < data.length; i++) {

        if (data[i] == '|') {
            flag_m = false;
            flag_S = false;
            flag_F = false;
            flag_St1 = false;
            flag_St2 = false;
            flag_TR = false;
            flag_ST = false;
            flag_FT = false;

            j++;
        }

        if (flag_m) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    num += data[i];
                    i++;
                }
            }
            continue;
        }

        if (data[i] == '$') {
            flag_m = true;

            if (j != 0) {
                j = 0;
                q = 0;

                var ObjectR = new Route();
                ObjectR.SetNum(num);
                ObjectR.SetStart(start);
                ObjectR.SetFinish(finish);
                ObjectR.SetSt1(S1);
                ObjectR.SetSt2(S2);
                ObjectR.SetTimeRoute(TR);
                ObjectR.SetStartTime(ST);
                ObjectR.SetFinishTime(FT);
                ObjectR.SetSN(Route.count + 1);

                Route.count++;

                ColDiv(Route.count, ObjectR);

                num = "";
                start = "";
                finish = "";
                S1 = "";
                S2 = "";
                TR = "";
                ST = "";
                FT = "";
            }
            continue;
        }

        if (flag_S) {
            //start += data[i];
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    start += data[i];
                    i++;
                }
            }
            continue;
        }

        if (flag_F) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    finish += data[i];
                    i++;
                }
            }
            continue;
        }

        if (flag_TR) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    TR += data[i];
                    i++;
                }
            }
            continue;
        }

        if (flag_ST) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    ST += data[i];
                    i++;
                }
            }
            continue;
        }

        if (flag_FT) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|' || data[i] == '$') {
                    i--;
                    break;
                } else {
                    FT += data[i];
                    i++;
                }
            }
            continue;
        }

        if (j == 1) {
            flag_S = true;
        }

        if (j == 2) {
            flag_F = true;
        }

        if (j == 3) {
            flag_TR = true;
        }

        if (j == 4) {
            flag_ST = true;
        }

        if (j == 5) {
            flag_FT = true;
        }


        if (flag_St1) {
            S1 += data[i];
        }

        if (flag_St2) {
            S2 += data[i];
        }

        if (data[i] == '&') {

            if (q == 0) {
                flag_St1 = true;
            } else {
                flag_St2 = true;
            }
            q++;
        }

    }
} //Обробка інформації з сервера

function ColDiv(col, Object) {
    var div = document.createElement('div');
    div.setAttribute('id', 'new' + col);
    div.setAttribute('data-num', Object.GetNum());
    div.setAttribute('data-St1', Object.GetSt1());
    div.setAttribute('data-St2', Object.GetSt2());

    div.className = "Way";
    div.innerHTML = "<p> &nbsp;&nbsp;<span>" + col + ".</span> <span style='padding-left:50px;'> </span><span>" + Object.GetStart() + " - " + Object.GetFinish() + "</span> </p> <p> <span style='padding-left:30px;'> </span> <span>В дорозі: " + Object.GetTimeRoute() + " </span><span style='padding-left:50px;'> </span> <span>" + Object.GetStartTime() + "</span><span style='padding-left:50px;'> </span>  </p><p> <span style='padding-left:200px;'> </span><span>" + Object.GetFinishTime() + "</span> <span style='padding-left:50px;'> </span></p>";
    var t = document.getElementById("box_text");
    t.appendChild(div);

    masDOM.push(div);

} //Відображення маршруту

function CoAtt() {
    $('#computing').attr("disabled", "disabled");
} //Поки чекаем відповіді сервера

function Computing(data) {
    if (data == "Not Train") {
        alert("Маршруту не знайдено");
    } else {
        var t = document.getElementById("box_text");
        t.getAttribute('hidden', 'true');
        $('#St_1').val("");
        $('#St_2').val("");
        $('#dat').val("");
        SetRoute(data);
    }
    data = "";
    $('#computing').removeAttr("disabled");
} //Інформація з сервера

$('#computing').click(function () {

    var t = document.getElementById("box_text");
    for (var i = 0; i < masDOM.length; i++) {
        t.removeChild(masDOM[i]);
    }
    masDOM = [];
    ClearMarkerAndLine();

    if (flag) {

        var St_1 = $('#St_1').val();
        var St_2 = $('#St_2').val();
        var dat = $('#dat').val();
        var trans = $('#trans').val();
        var sort = $('#sort').val();

        var err = "";
        var now = new Date();
        var Entr = new Date(dat);
        var i;

        var v = [];
        var t = [];


        var gr = document.getElementsByName('v');
        for (var i = 0; i < gr.length; i++) {
            if (gr[i].checked) {
                v[i] = 1;
            } else {
                v[i] = 0;
            }
        }

        var gr = document.getElementsByName('t');
        for (var i = 0; i < gr.length; i++) {
            if (gr[i].checked) {
                t[i] = 1;
            } else {
                t[i] = 0;
            }
        }

        var v1 = v[0];
        var v2 = v[1];
        var v3 = v[2];
        var v4 = v[3];
        var v5 = v[4];
        var t1 = t[0];
        var t2 = t[1];
        var t3 = t[2];
        var t4 = t[3];
        var t5 = t[4];
        var t6 = t[5];


        if (St_1 != "" && St_1 != "-") {
            for (i = 0; i < St_1.length - 1; i++) {
                if (Check(St_1.charAt(i)) || /[а-я]/i.test(St_1) == null) {

                    err += "Неправильна перша станція. ";
                    $('#St_1').css("border-color", "#f7b4b4");
                    break;
                }
            }
        } else {
            err += "Нема першої станції. ";
            $('#St_1').css("border-color", "#f7b4b4");
        }

        if (St_2 != "" && St_2 != "-") {
            for (i = 0; i < St_2.length - 1; i++) {
                if (Check(St_2.charAt(i)) || /[а-я]/i.test(St_2) == null) {

                    err += "Неправильна друга станція. ";
                    $('#St_2').css("border-color", "#f7b4b4");
                    break;
                }
            }
        } else {
            err += "Нема другої станції. ";
            $('#St_2').css("border-color", "#f7b4b4");
        }

        if (dat == "") {
            err += "Нема дати. ";
            $('#dat').css("border-color", "#f7b4b4");
        }

        if (St_1 == "" && err == "") {
            err += "Неправильна перша станція. ";
            $('#St_1').css("border-color", "#f7b4b4");
        }

        if (St_2 == "" && err == "") {
            err += "Неправильна друга станція. ";
            $('#St_2').css("border-color", "#f7b4b4");
        }

        if (v1 == 0 && v2 == 0 && v3 == 0 && v4 == 0 && v5 == 0) {
            err += "Не вибраний тип потягу. ";
        }

        if (t1 == 0 && t2 == 0 && t3 == 0 && t4 == 0 && t5 == 0 && t6 == 0) {
            err += "Не вибраний тип вагону. ";
        }

        if (St_1 == St_2 && St_1 != "") {
            err += "Станції однакові. ";
        }

        if (err != "") {
            alert(err);
        } else {


            $.ajax({
                url: "php/CheckWay.php",
                type: "POST",
                cache: false,
                data: ({
                    'C': "0",
                    'St_1': St_1,
                    'St_2': St_2,
                    'dat': dat,
                    'v1': v1,
                    'v2': v2,
                    'v3': v3,
                    'v4': v4,
                    'v5': v5,
                    't1': t1,
                    't2': t2,
                    't3': t3,
                    't4': t4,
                    't5': t5,
                    't6': t6,
                    'trans': trans,
                    'sort': sort
                }),
                dataType: "html",
                beforeSend: CoAtt,
                success: Computing
            });
        }
    } else {
        alert("Виберіть станцію");
    }
}); //Подія відправки форми на сервер

//Map function -------------------------

function initialize() {
    var mapOptions = {
        zoom: 6,
        center: new google.maps.LatLng(48.45, 35.02)
    };

    map = new google.maps.Map(document.getElementById("map"), mapOptions);
} //Намалювати мапу

google.maps.event.addDomListener(window, "load", initialize);

function SetMarker(location, title, MarkerCounter) {

    if (MarkerCounter == 1) {
        marker1 = new google.maps.Marker({
            position: location, //new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            title: title
        });
    }

    if (MarkerCounter == 2) {
        marker2 = new google.maps.Marker({
            position: location, //new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            title: title
        });
    }

} //Поставити маркер

function SetLine(flightPlanCoordinates) {
    flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });
    flightPath.setMap(map);
    flagLine = true;
} //Відмалювати криву 

function ClearMarkerAndLine() {
    if (flagLine) {
        marker1.setMap(null);
        marker2.setMap(null);
        flightPath.setMap(null);
        flagLine = false;
    }
} //Прибрати маркери та криву

$("#box_text").on("click", ".Way", function () {
    var St_1 = $(this).attr("data-st1");
    var St_2 = $(this).attr("data-st2");

    $.ajax({
        url: "php/CheckWay.php",
        type: "POST",
        cache: false,
        data: ({
            'Num': $(this).attr("data-num"),
            'St_1': St_1,
            'St_2': St_2
        }),
        dataType: "html",
        success: NewLine
    });
}); //Обрання маршруту зі списку

function NewLine(data) {
    ClearMarkerAndLine();
    var number = "";
    var flag = false;
    var flag_t1 = false;
    var flag_t2 = false;
    var flag_c1 = false;
    var flag_c2 = false;
    var j, u = 0;
    var t = 0;
    var title1 = "";
    var title2 = "";
    var Loc1 = "";
    var Loc2 = "";
    var Coo1 = "";
    var Coo2 = "";
    var Object;
    var flightPlanCoordinates = [];

    for (var i = 0; i < 10; i++) {

        if (data[i] == '|') {
            flag = false;
            j = i;
            break;
        }

        if (flag) {
            number += data[i];
        }

        if (data[i] == '$') {
            flag = true;
        }


    }

    for (var i = j + 1; i < data.length; i++) {

        if (data[i] == '|' || data[i] == '*') {
            if (data[i] == '*') {
                flag_t1 = false;
                flag_t2 = false;
                t++;
            }
            flag_c1 = true;
            flag_c2 = false;
            i++;
        }

        if (data[i] == '!' || data[i] == '$' || data[i] == '&') {

            flag_c1 = false;
            flag_c2 = false;

            if (data[i] == '&' && t != 2 && t != 5) {
                if (t == 0) {
                    flag_t1 = true;
                    i++;
                } else {
                    flag_t2 = true;
                    i++;
                }
                t++;
            }

            if (t == 2) {
                Loc1 = {
                    lat: +Coo1,
                    lng: +Coo2
                };
                t++;
            }
            if (t == 5) {
                Loc2 = {
                    lat: +Coo1,
                    lng: +Coo2
                };
                t++;
            }

            if (Coo1 != "" && Coo2 != "") {

                if (u == 0) {
                    Object = {
                        lat: +Coo1,
                        lng: +Coo2
                    };
                    flightPlanCoordinates.push(Object);
                } else {
                    Object = {
                        lat: +Coo1,
                        lng: +Coo2
                    };
                    flightPlanCoordinates.push(Object);
                }
                Coo1 = "";
                Coo2 = "";
                u++;
            }
        }

        if (flag_t1) {
            title1 += data[i];
        }

        if (flag_t2) {
            title2 += data[i];
        }

        if (data[i] == ',') {
            flag_c1 = false;
            flag_c2 = true;
            i += 2;
        }

        if (flag_c1) {
            Coo1 += data[i];
        }

        if (flag_c2) {
            Coo2 += data[i];
        }


    }

    SetLine(flightPlanCoordinates);
    SetMarker(Loc1, title1, 1);
    SetMarker(Loc2, title2, 2);
} //Обробка відповіді сервера по координатам

/*function RU_EN(a) {
    var p = "";

    for (var i = 0; i < a.length; i++) {
        if (a[i] == "а") {
            p += "a";
        }
        if (a[i] == "б") {
            p += "b";
        }
        if (a[i] == "в") {
            p += "c";
        }
        if (a[i] == "г") {
            p += "d";
        }
        if (a[i] == "д") {
            p += "e";
        }
        if (a[i] == "е") {
            p += "f";
        }
        if (a[i] == "є") {
            p += "g";
        }
        if (a[i] == "ж") {
            p += "h";
        }
        if (a[i] == "з") {
            p += "i";
        }
        if (a[i] == "и") {
            p += "j";
        }
        if (a[i] == "і") {
            p += "k";
        }
        if (a[i] == "ї") {
            p += "l";
        }
        if (a[i] == "й") {
            p += "m";
        }
        if (a[i] == "к") {
            p += "n";
        }
        if (a[i] == "л") {
            p += "o";
        }
        if (a[i] == "м") {
            p += "p";
        }
        if (a[i] == "н") {
            p += "q";
        }
        if (a[i] == "о") {
            p += "r";
        }
        if (a[i] == "п") {
            p += "s";
        }
        if (a[i] == "р") {
            p += "t";
        }
        if (a[i] == "с") {
            p += "u";
        }
        if (a[i] == "т") {
            p += "v";
        }
        if (a[i] == "у") {
            p += "w";
        }
        if (a[i] == "ф") {
            p += "x";
        }
        if (a[i] == "х") {
            p += "y";
        }
        if (a[i] == "ш") {
            p += "z";
        }
        if (a[i] == "ц") {
            p += "+";
        }
        if (a[i] == "ч") {
            p += "=";
        }
        if (a[i] == "щ") {
            p += "&";
        }
        if (a[i] == "ь") {
            p += "~";
        }
        if (a[i] == "ю") {
            p += "[";
        }
        if (a[i] == "я") {
            p += "]";
        }
        if (a[i] == "А") {
            p += "A";
        }
        if (a[i] == "Б") {
            p += "B";
        }
        if (a[i] == "В") {
            p += "C";
        }
        if (a[i] == "Г") {
            p += "D";
        }
        if (a[i] == "Д") {
            p += "E";
        }
        if (a[i] == "Е") {
            p += "F";
        }
        if (a[i] == "Є") {
            p += "G";
        }
        if (a[i] == "Ж") {
            p += "H";
        }
        if (a[i] == "З") {
            p += "I";
        }
        if (a[i] == "И") {
            p += "J";
        }
        if (a[i] == "І") {
            p += "K";
        }
        if (a[i] == "Ї") {
            p += "L";
        }
        if (a[i] == "Й") {
            p += "M";
        }
        if (a[i] == "К") {
            p += "N";
        }
        if (a[i] == "Л") {
            p += "O";
        }
        if (a[i] == "М") {
            p += "P";
        }
        if (a[i] == "Н") {
            p += "Q";
        }
        if (a[i] == "О") {
            p += "R";
        }
        if (a[i] == "П") {
            p += "S";
        }
        if (a[i] == "Р") {
            p += "T";
        }
        if (a[i] == "С") {
            p += "U";
        }
        if (a[i] == "Т") {
            p += "V";
        }
        if (a[i] == "У") {
            p += "W";
        }
        if (a[i] == "Ф") {
            p += "X";
        }
        if (a[i] == "Х") {
            p += "Y";
        }
        if (a[i] == "Ш") {
            p += "Z";
        }
        if (a[i] == "Ц") {
            p += "!";
        }
        if (a[i] == "Ч") {
            p += "@";
        }
        if (a[i] == "Щ") {
            p += "#";
        }
        if (a[i] == "Ю") {
            p += "$";
        }
        if (a[i] == "Я") {
            p += "%";
        }
        if (a[i] == " ") {
            p += "_";
        }
        if (a[i] == "-") {
            p += "-";
        }
        if (a[i] == "'") {
            p += "''";
        }
        if (a[i] == ".") {
            p += ".";
        }
    }
    return p;
} //Кодування кирилиці

function Check(str) {
    if (str == '!') {
        return true;
    }
    if (str == '@') {
        return true;
    }
    if (str == '#') {
        return true;
    }
    if (str == '') {
        return true;
    }
    if (str == '$') {
        return true;
    }
    if (str == '%') {
        return true;
    }
    if (str == '^') {
        return true;
    }
    if (str == '&') {
        return true;
    }
    if (str == '*') {
        return true;
    }
    if (str == '(') {
        return true;
    }
    if (str == ')') {
        return true;
    }
    if (str == '{') {
        return true;
    }
    if (str == '}') {
        return true;
    }
    if (str == '[') {
        return true;
    }
    if (str == ']') {
        return true;
    }
    if (str == ';') {
        return true;
    }
    if (str == ':') {
        return true;
    }
    if (str == '|') {
        return true;
    }
    if (str == '<') {
        return true;
    }
    if (str == '"') {
        return true;
    }
    if (str == '>') {
        return true;
    }
    if (str == ',') {
        return true;
    }
    if (str == '/') {
        return true;
    }
    if (str == '?') {
        return true;
    }
    if (str == '=') {
        return true;
    }
    if (str == '+') {
        return true;
    }
    if (str == '~') {
        return true;
    }
    if (str == '№') {
        return true;
    }
    if (/[a-zA-Z]/.test(str)) {
        return true;
    }
    return false;
} *///Перевірка на недопустимі символи
