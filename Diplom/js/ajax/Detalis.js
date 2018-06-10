var Datedata = [];
var Datedata2 = [];

function Calendar2(id, year, month, data) {
    if (data != undefined) {
        for (var i = 0; i < data.length; i++) {
            data[i] = new Date(data[i]);
        }
    }
    
    var Dlast = new Date(year, month + 1, 0).getDate(),
        D = new Date(year, month, Dlast),
        DNlast = new Date(D.getFullYear(), D.getMonth(), Dlast).getDay(),
        DNfirst = new Date(D.getFullYear(), D.getMonth(), 1).getDay(),
        calendar = '<tr>',
        month = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
    
    if (DNfirst != 0) {
        for (var i = 1; i < DNfirst; i++) calendar += '<td>';
    } else {
        for (var i = 0; i < 6; i++) calendar += '<td>';
    }
    
    var flag = false;
        
    for (var i = 1; i <= Dlast; i++) {
        //if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
        for (var j = 0; j < data.length; j++) {
            if (i == data[j].getDate() && D.getFullYear() == data[j].getFullYear() && D.getMonth() == data[j].getMonth()) {
                calendar += '<td class="today">' + i;
                flag = true;
            } 
        }
        if (!flag) {
            calendar += '<td>' + i;
        } else {
            flag = false;
        }
            
        if (new Date(D.getFullYear(), D.getMonth(), i).getDay() == 0) {
            calendar += '<tr>';
        }
    }
    
    for (var i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    document.querySelector('#' + id + ' tbody').innerHTML = calendar;
    document.querySelector('#' + id + ' thead td:nth-child(2)').innerHTML = month[D.getMonth()] + ' ' + D.getFullYear();
    document.querySelector('#' + id + ' thead td:nth-child(2)').dataset.month = D.getMonth();
    document.querySelector('#' + id + ' thead td:nth-child(2)').dataset.year = D.getFullYear();
    if (document.querySelectorAll('#' + id + ' tbody tr').length < 6) { // чтобы при перелистывании месяцев не "подпрыгивала" вся страница, добавляется ряд пустых клеток. Итог: всегда 6 строк для цифр
        document.querySelector('#' + id + ' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
    }
}

// переключатель минус месяц
document.querySelector('#calendar2 thead tr:nth-child(1) td:nth-child(1)').onclick = function () {
    Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month) - 1, Datedata);
}
// переключатель плюс месяц
document.querySelector('#calendar2 thead tr:nth-child(1) td:nth-child(3)').onclick = function () {
    Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month) + 1, Datedata);
}

// переключатель минус месяц
document.querySelector('#calendar3 thead tr:nth-child(1) td:nth-child(1)').onclick = function () {
    Calendar2("calendar3", document.querySelector('#calendar3 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar3 thead td:nth-child(2)').dataset.month) - 1, Datedata2);
}
// переключатель плюс месяц
document.querySelector('#calendar3 thead tr:nth-child(1) td:nth-child(3)').onclick = function () {
    Calendar2("calendar3", document.querySelector('#calendar3 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar3 thead td:nth-child(2)').dataset.month) + 1, Datedata2);
}

var Value = [];
var masDOM = [];

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
                'C': 24,
                'Num1': Value["Num1"],
                'Num2': Value["Num2"],
                'St_1': Value["St_1"],
                'St_2': Value["St_2"],
                'St_3': Value["St_3"],
                'Time11': Value["Time11"],
                'Time21': Value["Time21"],
                'Date': new Date()
            }),
            dataType: "html",
            success: SetInfo
        });
    } else {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': 24,
                'Num1': Value["Num1"],
                'St_1': Value["St_1"],
                'St_2': Value["St_2"],
                'Time11': Value["Time11"],
                'Date': new Date()
            }),
            dataType: "html",
            success: SetInfo
        });
    }
}

function NewInfo(data, num, NumberTrain, St1, St2, id) {
    var Type = [0, 0, 0, 0, 0, 0];
    var i = 0;

    console.log(data[num]);

    while (data[num][i] != undefined) {

        switch (data[num][i]['Type']) {
            case 'Люкс':
                {
                    Type[0] = "+";
                    break;
                }
            case 'СВ':
                {
                    Type[1] = "+";
                    break;
                }
            case 'Купе':
                {
                    Type[2] = "+";
                    break;
                }
            case 'Плацкарт':
                {
                    Type[3] = "+";
                    break;
                }
            case '1-й клас':
                {
                    Type[4] = "+";
                    break;
                }
            case '2-й клас':
                {
                    Type[5] = "+";
                    break;
                }
        }
        i++;
    }
    
    for (i = 0; i < 6; i++) {
        if (Type[i] != 0) {
            Type[i] = "+";
        } else {
            Type[i] = "-";
        }
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
    div.innerHTML = "<p> Номер потягу - " + NumberTrain + "</p>";

    var t = document.getElementById(id);
    t.appendChild(div);

    masDOM.push({
        div: div,
        name: name
    });

    var div = document.createElement('div');
    div.setAttribute('id', 'infoTrain');
    div.className = "divT";
    div.innerHTML = "<p> Станція формування - " + St1 + "</p><br><p> Кінцева станція - " + St2 + "</p><br>";
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
    div.innerHTML = "<p>" + Type[0] + " </p> <br><p>" + Type[1] + " </p><br><p>" + Type[2] + " </p><br><p>" + Type[3] + "  </p><br><p>" + Type[4] + " </p><br><p>" + Type[5] + " </p><br>";
    t.appendChild(div);
    masDOM.push({
        div: div,
        name: name
    });
}

function SetInfo(data) {
    if (data == "Error DB") {
        alert("Помилка бази даних");
    } else {
        console.log(data);
        data = JSON.parse(data);
        PlaceInfo = data;

        if (data.length > 4) {
            NewInfo(data, 0, Value["Num1"], Value["St_1"], Value["St_2"], "info");
            NewInfo(data, 2, Value["Num2"], Value["St_2"], Value["St_3"], "info2");
            Station(data[6], "tb");
            Station(data[7], "tb2");
            Datedata = data[4];
            Datedata2 = data[5];
            Calendar2("calendar2", new Date().getFullYear(), new Date().getMonth(), data[4]);
            Calendar2("calendar3", new Date().getFullYear(), new Date().getMonth(), data[5]);

        } else {
            $('#info2').toggle();
            $('#tS').toggle();
            $('#calendar3').toggle();
            NewInfo(data, 0, Value["Num1"], Value["St_1"], Value["St_2"], "info");
            Station(data[3], "tb");
            Datedata = data[2];
            Calendar2("calendar2", new Date().getFullYear(), new Date().getMonth(), data[2]);
        }
    }
}

RerultTable();

function Station(data, id1) {

    for (var i = 1; i < data.length + 1; i++) {
        var tr = document.createElement('tr');
        tr.setAttribute('data-num', i);
        //tr.setAttribute('data-num', Object.GetNum());
        tr.setAttribute('role', "row");

        if (i % 2 != 0) {
            tr.className = "odd";
        } else {
            tr.className = "even";
        }

        tr.innerHTML = "<td><span >" + i + ".</span></td><td><span >" + data[i - 1]['Arrival_time'] + "</span></td><td><span >" + data[i - 1]['Name_S'] + "</span></td><td><span>" + data[i - 1]['Time_of_departure'] + " </span></td>";
        var t = document.getElementById(id1);
        t.appendChild(tr);

        masDOM.push(tr);
    }
}

/*$('#tickets').click(function () {
    window.open('Pay.html');
    window.close();
});*/

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}


$(window).unload(function(){ 
  DelCook(); 
});

function DelCook() {
    var n = ["Num1", "St_1", "St_2", "Time11", "Time12", "TimeRoad1", "Num2", "St_3", "Time21", "Time22", "TimeRoad2"];
    for (var i = 0; i < n.length; i++) {
        if (getCookie(n[i]) != undefined) {
            deleteCookie(n[i]);
        }
    }
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}

/*window.onbeforeunload = function() {
  DelCook();
};*/