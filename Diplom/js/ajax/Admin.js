var menu = 0;
var y = 1;
var num;
var Col;
var Col_V;
var T1;
var T2;
var v = 1;
var c = 0;
var flag = false;
var mas = [];
var mas2 = [];
$('#r').toggle();
$('#d').toggle();
$('#red').toggle();
$('#del').toggle();
$('#a').toggle();
$('#ad').toggle();
$('#computing2').attr("disabled", "disabled");
$('#computing3').attr("disabled", "disabled");
$('#computing4').attr("disabled", "disabled");

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

//Вхід

$('#login').click(function () {
    var login = $('#loginn').val();
    var password = $('#passwordd').val();

    var error = "";

    if (login == "") {
        error += "Не введений логін. ";
    }
    if (password == "") {
        error += "Не введений пароль. ";
    }

    if (error != "") {

    } else {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': "111",
                'login': login,
                'password': password
            }),
            dataType: "html",
            beforeSend: LoAtt,
            success: loginn
        });
    }
}); //Натискання по кнопці входу

function LoAtt() {
    $('#login').attr("disabled", "disabled");
} //Очікування відповіді сервера

function loginn(data) {
    if (data == "true") {
        $('#ok').toggle();
        $('#login_container').toggle();
    } else {
        alert(data);
    }
    data = "";
} //Відповідь сервера

//Розділи 

$('#add').click(function () {

    if (menu != 1) {
        if (y == 1) {
            $('#mes').toggle();
            y++;
        }
        $('#a').toggle();
        $('#ad').toggle();
        $('#computing2').attr("disabled", "disabled");
        $('#computing3').attr("disabled", "disabled");
        $('#computing4').attr("disabled", "disabled");

        var d = new Date();
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        var name_input = document.getElementById('dat')
        name_input.setAttribute('min', year + "-" + month + "-" + day);

        //button
        $('#computing1').click(function () {

            if (flag) {
                num = $('#num').val();
                var St_1 = $('#St_1').val();
                var St_2 = $('#St_2').val();
                Col = $('#Col').val();
                Col_V = $('#Col_V').val();
                var Time = $('#Time').val();
                var Type = $('#Type').val();

                var error = "";

                if (num != "") {
                    for (i = 0; i < num.length; i++) {
                        if (Check(num.charAt(i, 0)) || num.match(/^\d+$/) == null) {

                            error += "Неправильний номер. ";
                            $('#num').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема коду. ";
                    $('#num').css("border-color", "#f7b4b4");
                }

                if (St_1 != "" && St_1 != "-") {
                    for (i = 0; i < St_1.length; i++) {
                        if (Check(St_1.charAt(i, 0)) || /[а-я]/i.test(St_1) == null) {

                            error += "Неправильна перша станція. ";
                            $('#St_1').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема першої станції. ";
                    $('#St_1').css("border-color", "#f7b4b4");
                }

                if (St_2 != "" && St_2 != "-") {
                    for (i = 0; i < St_2.length; i++) {
                        if (Check(St_2.charAt(i, 0)) || /[а-я]/i.test(St_2) == null) {

                            error += "Неправильна друга станція. ";
                            $('#St_2').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема другої станції. ";
                    $('#St_2').css("border-color", "#f7b4b4");
                }

                if (Col != "" && Col != "0" && Col != "1") {
                    for (i = 0; i < Col.length; i++) {
                        if (Check(Col.charAt(i, 0)) || Col.match(/^\d+$/) == null) {

                            error += "Неправильна кількість. ";
                            $('#Col').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема кількості станцій. ";
                    $('#Col').css("border-color", "#f7b4b4");
                }

                if (Col_V != "" && Col_V != "0" && Col_V != "1") {
                    for (i = 0; i < Col_V.length; i++) {
                        if (Check(Col_V.charAt(i, 0)) || Col_V.match(/^\d+$/) == null) {

                            error += "Неправильна кількість вагонів. ";
                            $('#Col_V').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема кількості вагонів. ";
                    $('#Col_V').css("border-color", "#f7b4b4");
                }

                if (Time != "") {
                    for (var i = 0; i < Time.length; i++) {
                        if (Check(Time[i], 12) || Time[2] != ':' || Time.length > 5 || Time_1.length < 5 || Time[3] + Time[4] > 59) {
                            error += "Неправильний час. ";
                            $('#Time').css("border-color", "#f7b4b4");
                            break;
                        }
                    }
                } else {
                    error += "Нема часу. ";
                    $('#Time').css("border-color", "#f7b4b4");
                }

                if (St_1 == "" && error == "") {
                    error += "Неправильна перша станція. ";
                    $('#St_1').css("border-color", "#f7b4b4");
                }

                if (St_2 == "" && error == "") {
                    error += "Неправильна друга станція. ";
                    $('#St_2').css("border-color", "#f7b4b4");
                }

                if (St_1 == St_2 && St_1 != "") {
                    error += "Станції однакові. ";
                }

                if (error == "") {
                    //ajax
                    $.ajax({
                        url: "php/CheckWay.php",
                        type: "POST",
                        cache: false,
                        data: ({
                            'C': "1",
                            'num': num,
                            'St_1': St_1,
                            'St_2': St_2,
                            'Col': Col,
                            'Col_V': Col_V,
                            'Time': Time,
                            'Type': Type
                        }),
                        dataType: "html",
                        beforeSend: CoAtt,
                        success: Computing1
                    });
                } else {
                    document.getElementById('message').innerHTML = '';
                    document.getElementById('message').innerHTML = error;
                }
            } else {
                document.getElementById('message').innerHTML += " Виберіть станцію";
            }
        }); //Натискання по першій кнопці додавання

        $('#computing2').click(function () {
            var cod = $('#cod').val();
            var Time_1 = $('#Time_1').val();
            var Time_2 = $('#Time_2').val();
            var Type = $('#Type').val();

            var error = "";

            if (cod != "") {
                for (i = 0; i < cod.length; i++) {
                    if (Check(cod.charAt(i, 0)) || cod.match(/^\d+$/) == null || cod.length < 6) {

                        error += "Неправильний код. ";
                        $('#cod').css("border-color", "#f7b4b4");
                        break;
                    }
                }
            } else {
                error += "Нема коду. ";
                $('#cod').css("border-color", "#f7b4b4");
            }

            if (Time_1 != "") {
                for (var i = 0; i < Time_1.length; i++) {
                    if (Check(Time_1[i], 12) || Time_1[2] != ':' || Time_1.length != 5 || Time_1[0] + Time_1[1] > 23 || Time_1[3] + Time_1[4] > 59) {
                        error += "Неправильний час1. ";
                        $('#Time_1').css("border-color", "#f7b4b4");
                        break;
                    }
                }
            } else {
                if (Time_2 == "") {
                    error += "Нема часу1. ";
                    $('#Time_1').css("border-color", "#f7b4b4");
                }
            }

            if (Time_2 != "") {
                for (var i = 0; i < Time_2.length; i++) {
                    if (Check(Time_2[i], 12) || Time_2[2] != ':' || Time_2.length != 5 || Time_2[0] + Time_2[1] > 23 || Time_2[3] + Time_2[4] > 59) {
                        error += "Неправильний час2. ";
                        $('#Time_2').css("border-color", "#f7b4b4");
                        break;
                    }
                }
            } else {
                if (Time_1 == "") {
                    error += "Нема часу2. ";
                    $('#Time_2').css("border-color", "#f7b4b4");
                }
            }

            if (Time_1 == "") {
                Time_1 = "0";
            }
            if (Time_2 == "") {
                Time_2 = "0";
            }

            if (error == "") {
                //ajax
                $.ajax({
                    url: "php/CheckWay.php",
                    type: "POST",
                    cache: false,
                    data: ({
                        'C': "2",
                        'cod': cod,
                        'num_S': v,
                        'Time_1': Time_1,
                        'Time_2': Time_2,
                        'num': num
                    }),
                    dataType: "html",
                    beforeSend: CoAtt,
                    success: Computing2
                });

                if (+v == 1) {
                    T1 = Time_2;
                }
                if (+v == +Col) {
                    T2 = Time_1;
                }
            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;
            }

        }); //Натискання по другій кнопці додавання

        $('#computing3').click(function () {
            var dat = $('#dat').val();
            var now = new Date();
            var Entr = new Date(dat);

            var error = "";


            if (dat == "") {
                error += "Нема дати. ";
                $('#dat').css("border-color", "#f7b4b4");
            }

            if (error == "") {
                //ajax
                $.ajax({
                    url: "php/CheckWay.php",
                    type: "POST",
                    cache: false,
                    data: ({
                        'C': "3",
                        'dat': dat,
                        'T1': T1,
                        'T2': T2,
                        'num': num
                    }),
                    dataType: "html",
                    beforeSend: CoAtt,
                    success: Computing3
                });

            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;

            }

        }); //Натискання по третій кнопці додавання

        $('#computing4').click(function () {
            var cod_V = $('#cod_V').val();
            var Type_V = $('#Type_V').val();

            var error = "";

            if (cod_V != "") {
                for (i = 0; i < cod_V.length; i++) {
                    if (Check(cod_V.charAt(i, 0)) || cod_V.match(/^\d+$/) == null || cod_V.length < 6) {

                        error += "Неправильний код. ";
                        $('#cod_V').css("border-color", "#f7b4b4");
                        break;
                    }
                }
            } else {
                error += "Нема коду. ";
                $('#cod_V').css("border-color", "#f7b4b4");
            }

            if (error == "") {
                //ajax
                $.ajax({
                    url: "php/CheckWay.php",
                    type: "POST",
                    cache: false,
                    data: ({
                        'C': "4",
                        'cod_V': cod_V,
                        'num_V': v,
                        'Type_V': Type_V,
                        'Col': Col,
                        'Col_V': Col_V,
                        'num': num
                    }),
                    dataType: "html",
                    beforeSend: CoAtt,
                    success: Computing4
                });

            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;
            }

        }); //Натискання по четвертій кнопці додавання

        //
        if (menu == 2) {
            $('#r').toggle();
            $('#red').toggle();
        }

        if (menu == 3) {
            $('#d').toggle();
            $('#del').toggle();
        }

        menu = 1;
    }
}); //Додавання

$('#edit').click(function () {
    if (menu != 2) {
        if (y == 1) {
            $('#mes').toggle();
            y++;
        }
        $('#r').toggle();
        $('#red').toggle();

        if (menu == 1) {
            $('#a').toggle();
            $('#ad').toggle();
        }
        if (menu == 3) {
            $('#d').toggle();
            $('#del').toggle();
        }

        menu = 2;
    }
}); //Редагування

$('#delete').click(function () {
    if (menu != 3) {
        $('#dell').attr("disabled", "disabled");
        $('#numS').attr("disabled", "disabled");
        $('#numC').attr("disabled", "disabled");
        $('#datD').attr("disabled", "disabled");

        $('#delnum').click(function () {
            var Num = $('#numm').val();

            var error = checkNum(Num);

            if (error == "") {
                $.ajax({
                    url: "php/CheckWay.php",
                    type: "POST",
                    cache: false,
                    data: ({
                        'C': "7",
                        'Num': Num
                    }),
                    dataType: "html",
                    beforeSend: ADN,
                    success: DelNum
                });
            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;
                error = "";
            }
        }); //Кнопка видалення по номеру

        $('#delStCarr').click(function () {
            var Num = $('#numm').val();

            var error = checkNum(Num);

            if (error == "") {
                $('#numS').removeAttr("disabled");
                $('#numC').removeAttr("disabled");
                $('#dell').removeAttr("disabled");
                $('#numm').attr("disabled", "disabled");
                $('#delnum').attr("disabled", "disabled");
                $('#delStCarr').attr("disabled", "disabled");
                $('#delSchedul').attr("disabled", "disabled");

                $('#dell').click(function () {
                    var S = $('#numS').val();
                    var nC = $('#numC').val();

                    if (S == "" && nC == "") {
                        error = "Нічого не введено. ";
                        $('#numS').css("border-color", "#f7b4b4");
                        $('#numC').css("border-color", "#f7b4b4");
                    } else {
                        if (S == "") {
                            S = 0;

                            for (i = 0; i < nC.length; i++) {
                                if (Check(nC.charAt(i, 0)) || nC.match(/^\d+$/) == null || nC.length < 7) {

                                    error += "Неправильний код. ";
                                    $('#nC').css("border-color", "#f7b4b4");
                                    break;
                                }
                            }

                        } else {
                            nC = 0;
                            for (i = 0; i < S.length; i++) {
                                if (Check(S.charAt(i, 0)) || S.match(/^\d+$/) == null || S.length < 6) {

                                    error += "Неправильний код. ";
                                    $('#S').css("border-color", "#f7b4b4");
                                    break;
                                }
                            }
                        }
                    }

                    if (S != "" && nC != "") {
                        for (i = 0; i < nC.length; i++) {
                            if (Check(nC.charAt(i, 0)) || nC.match(/^\d+$/) == null || nC.length < 7) {

                                error += "Неправильний код. ";
                                $('#nC').css("border-color", "#f7b4b4");
                                break;
                            }
                        }
                        for (i = 0; i < S.length; i++) {
                            if (Check(S.charAt(i, 0)) || S.match(/^\d+$/) == null || S.length < 6) {

                                error += "Неправильний код. ";
                                $('#S').css("border-color", "#f7b4b4");
                                break;
                            }
                        }
                    }

                    if (error == "") {
                        $.ajax({
                            url: "php/CheckWay.php",
                            type: "POST",
                            cache: false,
                            data: ({
                                'C': "8",
                                'S': S,
                                'nC': nC,
                                'Num': Num
                            }),
                            dataType: "html",
                            beforeSend: ADN,
                            success: DelNum
                        });
                    } else {
                        document.getElementById('message').innerHTML = '';
                        document.getElementById('message').innerHTML = error;
                        error = "";
                    }
                });
            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;
                error = "";
            }
        }); //Кнопка видалення станції або вагону

        $('#delSchedul').click(function () {
            var Num = $('#numm').val();

            var error = checkNum(Num);

            if (error == "") {
                $('#datD').removeAttr("disabled");
                $('#dell').removeAttr("disabled");
                $('#numm').attr("disabled", "disabled");
                $('#delnum').attr("disabled", "disabled");
                $('#delStCarr').attr("disabled", "disabled");
                $('#delSchedul').attr("disabled", "disabled");

                $('#dell').click(function () {
                    var D = $('#datD').val();

                    if (D == "") {
                        error += "Нема дати. ";
                        $('#datD').css("border-color", "#f7b4b4");
                    }

                    $.ajax({
                        url: "php/CheckWay.php",
                        type: "POST",
                        cache: false,
                        data: ({
                            'C': "9",
                            'dat': D,
                            'Num': Num
                        }),
                        dataType: "html",
                        beforeSend: ADN,
                        success: DelNum
                    });
                });
            } else {
                document.getElementById('message').innerHTML = '';
                document.getElementById('message').innerHTML = error;
            }

        }); //Кнопка видалення графіку

        $('#defoult').click(function () {
            $('#datD').removeAttr("disabled");
            $('#numS').removeAttr("disabled");
            $('#numC').removeAttr("disabled");
            $('#dell').removeAttr("disabled");
            $('#numm').removeAttr("disabled");
            $('#delnum').removeAttr("disabled");
            $('#delStCarr').removeAttr("disabled");
            $('#delSchedul').removeAttr("disabled");
            document.getElementById('numm').value = '';
            document.getElementById('numS').value = '';
            document.getElementById('numC').value = '';
            document.getElementById('datD').value = '';
        }); //Поля за умовчуванням

        if (y == 1) {
            $('#mes').toggle();
            y++;
        }
        $('#d').toggle();
        $('#del').toggle();

        if (menu == 1) {
            $('#a').toggle();
            $('#ad').toggle();
        }
        if (menu == 2) {
            $('#r').toggle();
            $('#red').toggle();
        }

        menu = 3;
    }
}); //Видалення

function ADN() {
    $('#delnum').attr("disabled", "disabled");
    $('#dell').attr("disabled", "disabled");
} //Доки чеваємо відповіді сервера

function checkNum(num) {
    var error = "";

    if (num != "" && num != "0") {
        for (i = 0; i < num.length; i++) {
            if (Check(num.charAt(i, 0)) || num.match(/^\d+$/) == null) {

                error += "Неправильний код. ";
                $('#numm').css("border-color", "#f7b4b4");
                break;
            }
        }
    } else {
        error += "Нема номера. ";
        $('#numm').css("border-color", "#f7b4b4");
    }
    return error;
} //Перевірка номеру

function DelNum(data) {
    $('#dell').removeAttr("disabled");
    $('#delnum').removeAttr("disabled");

    if (data == "true") {
        document.getElementById('message').innerHTML = '';
        document.getElementById('message').innerHTML = "Successfully!";
    } else {
        document.getElementById('message').innerHTML = '';
        document.getElementById('message').innerHTML = data;
    }
} //Видалення по номеру

function CoAtt() {
    $('#computing1').attr("disabled", "disabled");
    $('#computing2').attr("disabled", "disabled");
    $('#computing3').attr("disabled", "disabled");
    $('#computing4').attr("disabled", "disabled");
} //Доки чекаємо відповіді з сервера

function Computing1(data) {
    var error = "";

    if (data == "Not num") {
        error += "Такий номер вже є. ";
        $('#num').css("border-color", "#f7b4b4");
    }
    if (data == "Not St_1") {
        error += "Першої станції нема. ";
        $('#St_1').css("border-color", "#f7b4b4");
    }
    if (data == "Not St_2") {
        error += "Другої станції нема. ";
        $('#St_2').css("border-color", "#f7b4b4");
    }
    if (data == "false" || data == "") {
        error += "Помилка бази даних. ";
    }

    if (error != "") {
        document.getElementById('message').innerHTML = '';
        document.getElementById('message').innerHTML = error;
        num = "";
        Col = "";
        Col_V = "";
        $('#computing1').attr("disabled", "disabled");
    } else {
        $('#computing1').attr("disabled", "disabled");
        //$('#computing1').removeAttr("disabled");
        $('#computing2').removeAttr("disabled");
        document.getElementById('message').innerHTML = '';
        document.getElementById('message').innerHTML = "Successfully!";
    }
    data = "";
} //Відповідь сервера по першій кнопці додавання

function Computing2(data) {
    var error = "";

    if (data == "Not cod") {
        error += "Нема станції по введеному коду. ";
        $('#cod').css("border-color", "#f7b4b4");
    }
    if (data == "false" || data == "") {
        error += "Помилка бази даних. ";
    }

    if (error != "") {
        document.getElementById('message').innerHTML = '';
        document.getElementById('message').innerHTML = error;
        $('#computing1').removeAttr("disabled");
        $('#computing2').removeAttr("disabled");
    } else {
        document.getElementById('message').innerHTML = '';
        $('#computing1').removeAttr("disabled");
        $('#computing2').removeAttr("disabled");

        if (v == +Col) {
            $('#computing2').attr("disabled", "disabled");
            $('#computing3').removeAttr("disabled");
            v = 1;
            document.getElementById('message').innerHTML = "Successfully!! ";
        } else {
            document.getElementById('message').innerHTML = "Successfully!! " + v;
            v++;
        }

    }
    data = "";
} //Відповідь сервера по другій кнопці додавання

function Computing3(data) {
    if (data == "false" || data == "") {
        error += "Помилка бази даних. ";
    }
    data = "";
    $('#computing3').removeAttr("disabled");
    $('#computing4').removeAttr("disabled");
    document.getElementById('message').innerHTML = '';
    document.getElementById('message').innerHTML = "Successfully!!!";
} //Відповідь сервера по третій кнопці додавання

function Computing4(data) {
    if (data == "false" || data == "") {
        error += "Помилка бази даних. ";
    }
    data = "";
    $('#computing4').removeAttr("disabled");
    document.getElementById('message').innerHTML = '';
    if (v == +Col_V) {
        $('#computing4').attr("disabled", "disabled");
        document.getElementById('message').innerHTML = "Successfully!!!! ";
    } else {
        document.getElementById('message').innerHTML = "Successfully!!!! " + v;
        v++;
    }
} //Відповідь сервера по четвертій кнопці додавання

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