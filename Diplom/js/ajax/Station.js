var c = 0;
var flag = false;
var mas = [];
var masDOM = [];
var flagLine = false;
var y = 0;
//Розрахунок дати
var d = new Date();
var day = d.getDate();
var month = d.getMonth() + 1;
var year = d.getFullYear();
var name_input = document.getElementById('dat')
name_input.setAttribute('min', year + "-" + month + "-" + day);

$('#box_text').toggle();

document.getElementById('St').onkeypress = function (e) {
    flag = false;
    var len = mas.length;

    if (len != 0) {
        var t = document.getElementById("kabsearchform");
        for (var i = 0; i < len; i++) {
            t.removeChild(mas[i]);
        }
        mas = [];
    }

    document.getElementById('kabsearchform').style.cssText = "display: none;";
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
}; //Ввід символів у поле станції

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
} //Відповідь з сервера

function StDiv(col, Object) {
    var div = document.createElement('div');
    div.setAttribute('id', 'mosearchresult' + col);
    div.setAttribute('data-name', Object.GetName());
    div.setAttribute('data-b', Object.GetBranch());

    div.className = "mosearchresult";
    document.getElementById('kabsearchform').style.cssText = "display: block; \
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
    var t = document.getElementById("kabsearchform");
    t.appendChild(div);

    mas.push(div);
} //Відображення випадаючих списків

$("#kabsearchform").on("click", ".mosearchresult", function () {
    flag = true;

    var St = $(this).attr("data-name");
    document.getElementById('St').value = St;
    document.getElementById('kabsearchform').style.cssText = "display: none;";
    var t = document.getElementById("kabsearchform");

    for (var i = 0; i < mas.length; i++) {
        t.removeChild(mas[i]);
        mas.splice(0, 1);
    }
}); //Обрання станції із списку по полю

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

                Route.count++;

                StDiv(Route.count, ObjectR);

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

//Маршрут

function Route(userData) {
    if (userData) { // если указаны данные -- одна ветка if
        var Num = userData.num;
        var Start = userData.start;
        var Finish = userData.finish;
        var Type = userData.Type;
        var StartTime = userData.StartTime;
        var FinishTime = userData.FinishTime;
        var serialNumber = 0;
    } else { // если не указаны -- другая
        var Num = "";
        var Start = "";
        var Finish = "";
        var Type = "";
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

    this.SetType = function (str) {
        Type = str;
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

    this.GetType = function () {
        return Type;
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

    var num = "";
    var start = "";
    var finish = "";
    var T = "";
    var ST = "";
    var FT = "";

    var flag_m = false;
    var flag_S = false;
    var flag_F = false;
    var flag_T = false;
    var flag_ST = false;
    var flag_FT = false;

    for (var i = 0; i < data.length; i++) {

        if (data[i] == '|') {
            flag_m = false;
            flag_S = false;
            flag_F = false;
            flag_T = false;
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

                var ObjectR = new Route();
                ObjectR.SetNum(num);
                ObjectR.SetStart(start);
                ObjectR.SetFinish(finish);
                ObjectR.SetType(T);
                ObjectR.SetStartTime(ST);
                ObjectR.SetFinishTime(FT);
                ObjectR.SetSN(Route.count + 1);

                Route.count++;

                Line(Route.count, ObjectR);

                num = "";
                start = "";
                finish = "";
                T = "";
                ST = "";
                FT = "";
            }
            continue;
        }

        if (flag_S) {
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

        if (flag_T) {
            for (var u = i; u < data.length; u++) {
                if (data[i] == '|') {
                    i--;
                    break;
                } else {
                    T += data[i];
                    i++;
                }
            }
            continue;
        }

        if (flag_ST) {
            //ST += data[i];
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
            flag_T = true;
        }

        if (j == 4) {
            flag_ST = true;
        }

        if (j == 5) {
            flag_FT = true;
        }

    }
} //Обробка інформації з сервера

function Line(col, Object) {
    var tr = document.createElement('tr');
    tr.setAttribute('id', 'new' + col);
    tr.setAttribute('data-num', Object.GetNum());
    tr.setAttribute('role', "row");

    if (col % 2 != 0) {
        tr.className = "odd";
    } else {
        tr.className = "even";
    }

    tr.innerHTML = "<td><span >" + col + ".</span></td><td><span >" + Object.GetType() + "</span></td><td><span >" + Object.GetNum() + "</span></td><td><span>" + Object.GetStart() + " </span></td><td><span >" + Object.GetFinish() + " </span></td><td><span >" + Object.GetStartTime() + " </span></td><td><span >" + Object.GetFinishTime() + "</span></td></tr>";
    var t = document.getElementById("tb");
    t.appendChild(tr);

    masDOM.push(tr);
} //Вивід інформації з сервера

$('#computing').click(function () {

    if (flag) {
        var t = document.getElementById("tb");
        for (var i = 0; i < masDOM.length; i++) {
            t.removeChild(masDOM[i]);
        }

        if (y != 0) {
            $('#box_text').toggle();
        }
        var St = $('#St').val();
        var dat = $('#dat').val();

        var error = "";

        if (St != "") {
            for (var i = 0; i < St.length; i++) {
                if (Check(St.charAt(i)) || /[а-я]/i.test(St) == null) {

                    err += "Неправильна станція. ";
                    $('#St').css("border-color", "#f7b4b4");
                    break;
                }
            }
        } else {
            error += "Нема станції. ";
            $('#St').css("border-color", "#f7b4b4");
        }

        if (dat == "") {
            error += "Нема дати. ";
            $('#dat').css("border-color", "#f7b4b4");
        }

        if (St == "" && error == "") {
            error += "Неправильна станція. ";
            $('#St').css("border-color", "#f7b4b4");
        }

        if (error == "") {
            //ajax
            $.ajax({
                url: "php/CheckWay.php",
                type: "POST",
                cache: false,
                data: ({
                    'C': "5",
                    'St': St,
                    'dat': dat
                }),
                dataType: "html",
                beforeSend: CoAtt,
                success: Computing
            });
            y++;

        } else {
            alert(error);
        }
    } else {
        alert("Виберіть станцію");
    }
}); //Перевірка введеної інформації

function CoAtt() {
    $('#computing').attr("disabled", "disabled");
} //Доки чекаємо відповіді сервера

function Computing(data) {
    var error = "";

    if (data == "Not St") {
        error += "Такої станції нема. ";
        $('#St').css("border-color", "#f7b4b4");
    }
    if (data == "Not dat") {
        error += "На таку дату нема потягів. ";
        $('#dat').css("border-color", "#f7b4b4");
    }
    if (data == "false" || data == "") {
        error += "Помилка бази даних. ";
    }

    if (error != "") {
        alert(error);
    } else {
        SetRoute(data);
        $('#box_text').toggle();
    }
    data = "";
    $('#computing').removeAttr("disabled");
} //Відповідь сервера

function Check(str, i) {
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
    if (i != 2 && i != 12) {
        if (str == ':') {
            return true;
        }
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
    if (i == 1 || i == 12) {
        if (/[а-яА-Я]/.test(str)) {
            return true;
        }
    }

    return false;
} //Перевірка на недопустимі символи

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
} *///Кодування кирилиці
