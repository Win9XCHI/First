var Value = [];
var PlaceInfo;
var flagTransfer;
var P = 0;

$('#Car').toggle();
$('#carriage').toggle();
$('#Plac').toggle();
$('#Kup').toggle();
$('#SV').toggle();
$('#class12').toggle();
$('#infoPay').toggle();

$('#Car1').toggle();
$('#carriage1').toggle();
$('#Plac1').toggle();
$('#Kup1').toggle();
$('#SV1').toggle();
$('#class121').toggle();
$('#infoPay1').toggle();
$('#t1').hide();

/*setCookie("Num1", 70); //Установить куки
        setCookie("St_1", escape("Дніпро головний")); //Установить куки
        setCookie("St_2", escape("Львів")); //Установить куки
        
        setCookie("Time11", escape("2018-06-22")); //Установить куки
        setCookie("Time12", escape("2018-06-23")); //Установить куки
        setCookie("TimeRoad1", escape("08:00")); //Установить куки
*/

$('#tickets').click(function () {
    ClickTic("tab2", 0, "typeCar");
});

function ClickTic(id1, num, id2) {
    document.getElementById(id1).style.cssText = "height: 1000px;";

    //var Type = [0,0,0,0,0,0];
    var i = 1;
    var flag = ["", "", "", "", "", ""];
    while (PlaceInfo[num][i] != undefined) {

        switch (PlaceInfo[num][i]['Type']) {
            case 'Люкс':
                {
                    if (flag[0] == "") {
                        flag[0] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
            case 'СВ':
                {
                    if (flag[1] == "") {
                        flag[1] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
            case 'Купе':
                {
                    if (flag[2] == "") {
                        flag[2] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
            case 'Плацкарт':
                {
                    if (flag[3] == "") {
                        flag[3] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
            case '1-й клас':
                {
                    if (flag[4] == "") {
                        flag[4] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
            case '2-й клас':
                {
                    if (flag[5] == "") {
                        flag[5] = PlaceInfo[num][i]['Type'];
                    }
                    break;
                }
        }
        i++;
    }

    for (n = 0; n < 6; n++) {
        if (flag[n] != "") {
            var div = document.createElement('div');
            /*div.setAttribute('id', 'num');
            div.setAttribute('data-num', i);*/
            div.setAttribute('data-type', flag[n]);
            /*div.setAttribute('data-St1', data[i][0].St_1);
            div.setAttribute('data-St2', data[i][0].St_2);
            div.setAttribute('data-St3', data[i + 1][0].St_2);*/
            div.className = "type";
            div.innerHTML = "<p>" + flag[n] + "</p>";
            var t = document.getElementById(id2);
            t.appendChild(div);
            masDOM.push(div);
        }
    }
}

$('#tickets1').click(function () {
    ClickTic("tab1", 2, "typeCar1");
});

var masNum2 = [];
var MasPeople2 = [];
var masNum1 = [];
var MasPeople1 = [];
var index = 0;
var NumberTrain = 0;
var col1 = 0;
var col2 = 0;
var dateTrain = "";
var TypeTrain = "";
var flagToggle = [false, false];
var flagToggleCar = 0;
var flagPayPanel = false;
var masDOM = [];
var masDOM2 = [];
var Price;
var NumberVagon = 0;
var flagP = 1;

function DelCook() {
    var n = ["Num1", "St_1", "St_2", "Time11", "Time12", "TimeRoad1", "Num2", "St_3", "Time21", "Time22", "TimeRoad2", "Token"];
    for (var i = 0; i < n.length; i++) {
        if (getCookie(n[i]) != undefined) {
            deleteCookie(n[i]);
        }
    }
}

function CoAtt() {
    /*setTimeout(function () {DelCook();
        window.close();}, 100000);*/
}

function RerultTable() {
    var n = ["Num1", "St_1", "St_2", "Time11", "Time12", "TimeRoad1", "Num2", "St_3", "Time21", "Time22", "TimeRoad2"];
    for (var i = 0; i < n.length; i++) {
        if (getCookie(n[i]) != undefined) {
            Value[n[i]] = unescape(getCookie(n[i]));
            //deleteCookie(n[i]);
        }
    }

    setTimeout(function () {}, 10000);
    console.log(Value);

    if (Value["Num2"] != undefined) {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 22,
                'Num1': Value["Num1"],
                'Num2': Value["Num2"],
                'St_1': Value["St_1"],
                'St_2': Value["St_2"],
                'St_3': Value["St_3"],
                'Time11': Value["Time11"],
                'Time21': Value["Time21"]
            }),
            dataType: "html",
            beforeSend: CoAtt,
            success: SetInfo
        });
    } else {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 22,
                'Num1': Value["Num1"],
                'St_1': Value["St_1"],
                'St_2': Value["St_2"],
                'Time11': Value["Time11"]
            }),
            dataType: "html",
            beforeSend: CoAtt,
            success: SetInfo
        });
    }
}

function NewInfo(data, num, NumberTrain, St1, St2, id, T1, T2, TR) {
    var Type = [0, 0, 0, 0, 0, 0];
    var i = 1;
    while (data[num][i] != undefined) {

        switch (data[num][i]['Type']) {
            case 'Люкс':
                {
                    Type[0] += data[num][i]['Col'];
                    break;
                }
            case 'СВ':
                {
                    Type[1] += data[num][i]['Col'];
                    break;
                }
            case 'Купе':
                {
                    Type[2] += data[num][i]['Col'];
                    break;
                }
            case 'Плацкарт':
                {
                    Type[3] += data[num][i]['Col'];
                    break;
                }
            case '1-й клас':
                {
                    Type[4] += data[num][i]['Col'];
                    break;
                }
            case '2-й клас':
                {
                    Type[5] += data[num][i]['Col'];
                    break;
                }
        }
        i++;
    }

    var div = document.createElement('div');
    div.setAttribute('id', 'num');
    /*div.setAttribute('data-num1', data[i][0].id);
    div.setAttribute('data-num2', data[i + 1][0].id);
    div.setAttribute('data-St1', data[i][0].St_1);
    div.setAttribute('data-St2', data[i][0].St_2);
    div.setAttribute('data-St3', data[i + 1][0].St_2);*/
    var name = "info";
    div.className = "divT";
    div.innerHTML = "<p>" + NumberTrain + "</p>";

    var t = document.getElementById(id);
    t.appendChild(div);

    masDOM.push({
        div: div,
        name: name
    });

    var div = document.createElement('div');
    div.setAttribute('id', 'infoTrain');
    div.className = "divT";
    div.innerHTML = "<p>" + St1 + "</p><br><p>" + T1 + "</p><br><br><p>В дорозі: " + TR + "</p><br><br><p>" + T2 + "</p><br><p>" + St2 + "</p><br>";
    t.appendChild(div);
    masDOM.push({
        div: div,
        name: name
    });
    //
    var div = document.createElement('div');
    div.setAttribute('id', 'tic');
    div.className = "divT";
    div.innerHTML = "<p>Люкс</p> <br><p>СВ</p><br><p>Купе</p><br><p>Плацкарт</p><br><p>1-й клас</p><br><p>2-й клас</p><br>";
    t.appendChild(div);
    masDOM.push({
        div: div,
        name: name
    });
    //
    var div = document.createElement('div');
    div.setAttribute('id', 'ticg');
    div.className = "divT";
    div.innerHTML = "<p>" + Type[0] + " місць. </p> <br><p>" + Type[1] + " місць. </p><br><p>" + Type[2] + " місць. </p><br><p>" + Type[3] + " місць. </p><br><p>" + Type[4] + " місць. </p><br><p>" + Type[5] + " місць. </p><br>";
    t.appendChild(div);
    masDOM.push({
        div: div,
        name: name
    });
}

function SetInfo(data) {
    if (data == "Not Place") {
        alert("Місць не знайдено");
        DelCook();
        window.close();
    } else {
        if (data == "Error DB") {
            alert("Помилка бази даних");
            DelCook();
            window.close();
        } else {
            data = JSON.parse(data);
            PlaceInfo = data;

            if (data.length > 3) {
                NewInfo(data, 0, Value["Num1"], Value["St_1"], Value["St_2"], "info", Value["Time11"], Value["Time12"], Value["TimeRoad1"]);
                NewInfo(data, 2, Value["Num2"], Value["St_2"], Value["St_3"], "info1", Value["Time21"], Value["Time22"], Value["TimeRoad2"]);

            } else {
                $('#info1').toggle();
                NewInfo(data, 0, Value["Num1"], Value["St_1"], Value["St_2"], "info", Value["Time11"], Value["Time12"], Value["TimeRoad1"]);
            }
        }
    }
}

RerultTable();

function CarRes(t, end, col, masNum) {
    t.toggleClass("active");
    var Pas = document.getElementById("passenger" + end);

    //if (!flagPayPanel) {
        $('#infoPay' + end).show();
        /*flagPayPanel = true;
    }*/
    var flag = false;

    for (var i = 0; i < index; i++) {
        if (t.attr("data-num") == masNum[i]) {
            masNum.splice(i, 1);
            index--;
            flag = true;
        }
    }

    if (!flag) {
        masNum[index] = t.attr("data-num");
        index++;
    }

    if (t.attr("class").indexOf("active") != -1) {
        col++;
        var div = document.createElement('div');
        div.setAttribute('id', 'pasNum' + end + col);

        //div.className = "Vagon";
        div.innerHTML = '<p>Пасажир №' + col + '</p><div class="line"><input class="input" type="text" name="second" id="second' + end + col + '" placeholder="Прізвище" autocomplete="off" required onclick="$(this).css (\'border-color\',\'#ccc\')" maxlength="30"><input class="input" type="text" name="first" id="first' + end + col + '" placeholder="Ім\'я" autocomplete="off" required onclick="$(this).css (\'border-color\',\'#ccc\')" maxlength="30"><div class="line"><div class="block" data-num="' + col + '" data-type="standart">Стандартний</div><div class="block" data-num="' + col + '" data-type="children">Дитячий</div><div class="block" data-num="' + col + '" data-type="student">Студентський</div></div></div>  <div class="line"><input class="input" type="date" name="dat" id="dat' + end + col + '" placeholder="Дата народження" required onclick="$(this).css (\'border-color\',\'#ccc\')" hidden="true"><input class="input" type="text" name="numStud" id="numStud' + end + col + '" placeholder="Серія та номер" autocomplete="off" required onclick="$(this).css (\'border-color\',\'#ccc\')" maxlength="10" hidden="true"><div id="infoField' + end + col + '" hidden="true" class="infoField"></div></div>';
        Pas.appendChild(div);

        masDOM2[col] = div;

        //masDOM.push(div);
    } else {
        Pas.removeChild(masDOM2[col]);
        col--;
    }
    return col;
}

$("#carriage").on("click", ".reserve", function () {
    col1 = CarRes($(this), "", col1, masNum1);
});

$("#carriage1").on("click", ".reserve", function () {
    col2 = CarRes($(this), "1", col2, masNum2);
});

function PasBlo(t, end) {
    switch (t.attr("data-type")) {
        case "standart":
            {
                $('#dat' + end + t.attr("data-num")).hide();
                $('#numStud' + end + t.attr("data-num")).hide();
                $('#infoField' + end + t.attr("data-num")).hide();
                break;
            }
        case "children":
            {
                $('#dat' + end + t.attr("data-num")).show();

                $('#numStud' + end + t.attr("data-num")).hide();

                $('#infoField' + end + t.attr("data-num")).show();
                $('#infoField' + end + t.attr("data-num")).text("Введіть дату народження дитини. Діти віков до 6 років мають право на безоплатний проїзд без проїзного документа. Діти до 14 років допускаються до перевезення в пасажирських поїздах тільки в супроводі дорослих.");
                break;
            }
        case "student":
            {
                $('#numStud' + end + t.attr("data-num")).show();

                $('#dat' + end + t.attr("data-num")).hide();

                $('#infoField' + end + t.attr("data-num")).show();
                $('#infoField' + end + t.attr("data-num")).text("Введіть серію та номер студентського квитка. При посадці при собі мати студентський квиток.");
                break;
            }
    }
}

$("#passenger").on("click", ".block", function () {
    PasBlo($(this), "");
});

$("#passenger1").on("click", ".block", function () {
    PasBlo($(this), "1");
});

var $unique = $('.ch');
$unique.click(function () {
    $unique.filter(':checked').not(this).removeAttr('checked');
});

$("#typeCar").on("click", ".type", function () {
    flagTransfer = false;
    $('#Car').toggle();
    $('#carriage').hide();
    if (flagToggleCar == 1) {
        $('#Car').toggle();
    }
    flagToggleCar = 1;
    var t1 = document.getElementById("Carr");
    var t2 = document.getElementById("colV");

    for (var i = 0; i < masDOM.length; i++) {
        if (masDOM[i]['name'] == "Carr") {
            t1.removeChild(masDOM[i]['div']);
            masDOM.splice(i, 1);
        }

        if (masDOM[i]['name'] == "colV") {
            t2.removeChild(masDOM[i]['div']);
            masDOM.splice(i, 1);
        }
    }
    TypeTrain = $(this).attr("data-type");

    $.ajax({
        url: "php/CheckWay.php",
        type: "POST",
        cache: false,
        data: ({
            'C': 23,
            'Num1': Value["Num1"],
            'St_1': Value["St_1"],
            'St_2': Value["St_2"],
            'TypeTrain': TypeTrain
        }),
        dataType: "html",
        success: ListCarriage
    });
});

$("#typeCar1").on("click", ".type", function () {
    flagTransfer = true;
    $('#Car1').toggle();
    $('#carriage1').hide();
    if (flagToggleCar == 2) {
        $('#Car1').toggle();
    }
    flagToggleCar = 2;
    var t1 = document.getElementById("Carr1");
    var t2 = document.getElementById("colV1");

    for (var i = 0; i < masDOM.length; i++) {
        if (masDOM[i]['name'] == "Carr1") {
            t1.removeChild(masDOM[i]['div']);
            masDOM.splice(i, 1);
        }

        if (masDOM[i]['name'] == "colV1") {
            t2.removeChild(masDOM[i]['div']);
            masDOM.splice(i, 1);
        }
    }
    TypeTrain = $(this).attr("data-type");

    $.ajax({
        url: "php/CheckWay.php",
        type: "POST",
        cache: false,
        data: ({
            'C': 23,
            'Num1': Value["Num2"],
            'St_1': Value["St_2"],
            'St_2': Value["St_3"],
            'TypeTrain': TypeTrain
        }),
        dataType: "html",
        success: ListCarriage
    });
});

function PriceDiv(name, idMany, Price, id1, num, name2, id2) {
    var span = document.createElement('span');
    span.setAttribute('id', idMany);
    span.innerHTML = "Ціна: " + Price + " грн.";
    var Car = document.getElementById(id1);
    Car.appendChild(span);
    masDOM.push({
        div: span,
        name: name
    });

    var i = 1;

    while (PlaceInfo[num][i] != undefined) {

        if (PlaceInfo[num][i]['Type'] == TypeTrain) {
            var div = document.createElement('div');
            div.setAttribute('data-type', TypeTrain);
            div.setAttribute('data-car-num', i);
            div.className = "Vagon";
            div.innerHTML = "<p>" + i + " / " + PlaceInfo[num][i]['Col']; + "</p>";
            var t = document.getElementById(name2);
            t.appendChild(div);
            masDOM.push({
                div: div,
                name: name2
            });
        }
        i++;
    }
}

function ListCarriage(data) {
    Price = Math.round(data * 100) / 100;
    
    if (flagTransfer) {
        PriceDiv("Carr1", "many1", Price, "Carr1", 2, "colV1");
    } else {
        PriceDiv("Carr", "many", Price, "Carr", 0, "colV");
    }
    

    /*for (i = 0; i < masNum.length; i++) {
        var div = document.createElement('div');
        //div.setAttribute('id', 'new' + col);
        div.setAttribute('data-car-num', masNum[i]);
        div.setAttribute('data-num', NumberTrain);
        div.setAttribute('data-date', dateTrain);
        //div.setAttribute('data-type', TypeTrain);
        
        div.className = "Vagon";
        div.innerHTML = masNum[i];
        colV.appendChild(div);
    }*/
}

function COL(t, end, num) {
    TypeTrain = t.attr("data-type");
    NumberVagon = t.attr("data-car-num");
    var MassElClass = [];

    $('#carriage' + end).toggle();
    if (num == 3 && flagToggle[1])  {
        $('#carriage1').hide();
    }
    if (num == 1 && flagToggle[0]) {
        $('#carriage').hide();
    }
    
    if (num == 3) {
        flagToggle[1] = true;
    }
    else {
        flagToggle[0] = true;
    }
    

    switch (TypeTrain) {
        case "Плацкарт":
            {
                $('#Plac' + end).show();
                MassElClass = document.getElementsByClassName("plack" + end);

                $('#Kup' + end).hide();
                $('#class12' + end).hide();
                $('#SV' + end).hide();
                break;
            }
        case "Купе":
            {
                $('#Kup' + end).show();
                MassElClass = document.getElementsByClassName("Kupe" + end);

                $('#Plac' + end).hide();
                $('#class12' + end).hide();
                $('#SV' + end).hide();
                break;
            }
        case "Сидячий 1-го класу":
            {
                $('#class12' + end).show();
                MassElClass = document.getElementsByClassName("klass" + end);

                $('#Plac' + end).hide();
                $('#SV' + end).hide();
                $('#Kup' + end).hide();
                break;
            }
        case "Сидячий 2-го класу":
            {
                $('#class12' + end).show();
                MassElClass = document.getElementsByClassName("klass" + end);

                $('#Plac' + end).hide();
                $('#SV' + end).hide();
                $('#Kup' + end).hide();
                break;
            }
        case "СВ":
            {
                $('#SV' + end).show();
                MassElClass = document.getElementsByClassName("SeVe" + end);

                $('#Plac' + end).hide();
                $('#class12' + end).hide();
                $('#Kup' + end).hide();
                break;
            }
    }

    for (i = 0; i < MassElClass.length; i++) {

        for (var j = 0; j < PlaceInfo[num][NumberVagon].length; j++) {

            if (MassElClass[i].getAttribute("data-num") == PlaceInfo[num][NumberVagon][j]) {
                MassElClass[i].setAttribute("class", "reserve");
            }
        }
    }
}

$("#colV").on("click", ".Vagon", function () {
    COL($(this), "", 1);
});

$("#colV1").on("click", ".Vagon", function () {
    COL($(this), "1", 3);
});

function PBut(end, col, MasPeople) {
    var flag = true;
    var flagF = true;
    var lastname = 0;
    var firstname = 0;
    var code = 0;
    var date = 0;
    var error = "";
    MasPeople[0] = col;

    for (var i = 1; i < col + 1; i++) {
        error = "";
        $('#infoField' + end + i).hide();
        lastname = $('#second' + end + i).val();
        firstname = $('#first' + end + i).val();
        code = $('#numStud' + end + i).val();
        date = $('#dat' + end + i).val();

        for (var j = 0; j < lastname.length; j++) {

            if (Check(lastname.charAt(j)) || /[0-9]/i.test(lastname) == null) {
                flag = false;
                flagF = false;
            }
        }
        if (!flag) {
            flag = true;
            error = "Неправильне прізвище. ";
        }

        for (var j = 0; j < firstname.length; j++) {

            if (Check(firstname.charAt(j)) || /[0-9]/i.test(firstname) == null) {
                flag = false;
                flagF = false;
            }
        }
        if (!flag) {
            flag = true;
            error += "Неправильне ім'я. ";
        }

        if (code != "") {
            if (/[а-я]/i.test(code[0]) != null || /[а-я]/i.test(code[1]) != null) {
                flag = false;
                flagF = false;
            }
            for (var j = 2; j < code.length; j++) {

                if (Check(firstname.charAt(j)) || /[0-9]/i.test(code[j]) == null) {
                    flag = false;
                    flagF = false;
                }
            }
            if (!flag) {
                flag = true;
                error += "Неправильний код. ";
            }
        } else {
            code = "-";
        }

        if (date == "") {
            date = "-";
        }

        if (code != "-" && date != "-") {
            error += "Неможна вводити одночасно студентський та дату народження. ";
        }

        if (error == "") {
            MasPeople[i] = {
                'lastname': lastname,
                'firstname': firstname,
                'code': code,
                'date': date
            }
        } else {
            $('#infoField' + end + i).show();
            $('#infoField' + end + i).text(error);
        }
    }
    
    if (end == "") {
        MasPeople1 = MasPeople;
    }
    else {
        MasPeople2 = MasPeople;
    }

    if (flagF) {

        var d = new Date();
        var curr_date = d.getDate();
        var curr_month = d.getMonth() + 1;
        var curr_year = d.getFullYear();
        if (curr_month < 10) {
            curr_month = "0" + curr_month;
        }
        if (curr_date < 10) {
            curr_date = "0" + curr_date;
        }

        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 50,
                'Time': curr_year + "-" + curr_month + "-" + curr_date
            }),
            dataType: "html",
            success: Places
        });
    }
}

$('#p').click(function () {
    PBut("", col1, MasPeople1);
    P = 1;
    flagP = 0;
});

$('#p1').click(function () {
    PBut("1", col2, MasPeople2);
    P = 2;
    flagP = 1;
});

function Places(data) {
    if (data == "Error DB") {
        alert("Помилка бази даних");
        DelCook();
        window.close();
    } else {
        data = JSON.parse(data);

        setCookie("Token", escape(data));
        setCookie("Price", Price);

        window.open('Payment.html');

        var timerId = setInterval(function () {
            if (getCookie("Code") != undefined) {
                if (flagP == 0) {
                    YesOrNo(masNum1, MasPeople1);
                }
                if (flagP == 1) {
                    YesOrNo(masNum2, MasPeople2);
                }
                
                clearInterval(timerId);
            }
        }, 2000);

        // через 5 сек остановить повторы
        setTimeout(function () {
            clearInterval(timerId);
        }, 900000);
    }
}

function YesOrNo(masNum, MasPeople) {
    deleteCookie("Code");
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1;
    var curr_year = d.getFullYear();
    if (curr_month < 10) {
        curr_month = "0" + curr_month;
    }
    if (curr_date < 10) {
        curr_date = "0" + curr_date;
    }

    var v = [];
    var gr = document.getElementsByName('v');
    for (var i = 0; i < gr.length; i++) {
        if (gr[i].checked) {
            v[i] = 1;
        } else {
            v[i] = 0;
        }
    }

    var MasP = JSON.stringify(MasPeople);
    var MN = JSON.stringify(masNum);
    /*var MN = "";
    for (var i = 0; i < masNum.length; i++) {
        MN += i + "=" + masNum + "|";
    }*/
    var V = JSON.stringify(v);
    
    if (P == 1) {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 49,
                'MasPeople': MasP,
                'masNum': MN,
                'NumberVagon': NumberVagon,
                'Price': Price,
                'Num1': Value["Num1"],
                'St_1': Value["St_1"],
                'St_2': Value["St_2"],
                'Time11': Value["Time11"],
                'Time12': Value["Time12"],
                //'Col': masNum.length,
                'v': V,
                'Time': curr_year + "-" + curr_month + "-" + curr_date
            }),
            dataType: "html",
            success: PDF
        });
    } else {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 49,
                'MasPeople': MasP,
                'masNum': MN,
                'NumberVagon': NumberVagon,
                'Price': Price,
                'Num1': Value["Num2"],
                'St_1': Value["St_2"],
                'St_2': Value["St_3"],
                'Time11': Value["Time21"],
                'Time12': Value["Time22"],
                //'Col': masNum.length,
                'v': V,
                'Time': curr_year + "-" + curr_month + "-" + curr_date
            }),
            dataType: "html",
            success: PDF
        });
    }

}

function PDF(data) {
    if (data == "Error DB") {
        alert("Помилка бази даних");
        DelCook();
        window.close();
    } else {
        $('#t').toggle();
        $('#t1').toggle();
        
        // create a download anchor tag
        var downloadLink = document.createElement('a');
        downloadLink.target = '_blank';
        downloadLink.download = 'ticket.pdf';

        // convert downloaded data to a Blob
        var blob = new Blob([data.data], {
            type: 'application/pdf'
        });

        // create an object URL from the Blob
        var URL = window.URL || window.webkitURL;
        var downloadUrl = URL.createObjectURL(blob);

        // set object URL as the anchor href
        downloadLink.href = downloadUrl;

        // append the anchor to document body
        document.body.appendChild(downloadLink);

        // fire a click event on the anchor
        downloadLink.click();

        // cleanup: remove element and revoke object URL
        document.body.removeChild(downloadLink);
        URL.revokeObjectURL(downloadUrl);
        /*DelCook();*/
        //window.close();
    }
}

window.onbeforeunload = function () {
    DelCook();
};

$(window).unload(function(){ 
  DelCook(); 
});

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

// устанавливает cookie с именем name и значением value
// options - объект с свойствами cookie (expires, path, domain, secure)
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

// удаляет cookie с именем name
function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}

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
}
