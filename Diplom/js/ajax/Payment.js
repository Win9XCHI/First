var Value = [];
var masDOM = [];
RerultTable();

function RerultTable() {
    $('#f2').toggle();
    
    var n = ["Token", "Price"];
    for (var i = 0; i < n.length; i++) {
        if (getCookie(n[i]) != undefined) {
            Value[n[i]] = unescape(getCookie(n[i]));
        }
    }
    
    var div = document.createElement('div');
    div.innerHTML = "<p>Сума оплати</p><p>" + Value["Price"] + " грн</p>";
    var t = document.getElementById("Pay");
    t.appendChild(div);
    masDOM.push({div: div, name: "noname"});
    
    var div = document.createElement('div');
    div.innerHTML = "<p>Оплатити " + Value["Price"] + " грн</p>";
    var t = document.getElementById("computing");
    t.appendChild(div);
    masDOM.push({div: div, name: "noname"});
    
    var div = document.createElement('div');
    div.innerHTML = "<p>" + Value["Price"] + " грн</p><p>Ви успішно оплатили замовлення</p>";
    var t = document.getElementById("Pay1");
    t.appendChild(div);
    masDOM.push({div: div, name: "noname"});
}

document.getElementById('CardNumber').onkeypress = function (e) {
    var val = 0;
    // спец. сочетание - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return;
    }

    var char = getChar(e);

    if (!char) {
        return; // спец. символ - не обрабатываем
    }
    
    if (!isNaN(+char) && this.value.length < 16) {
        this.value += char;
    }

    return false;
}; //Ввід у поле першої станції

document.getElementById('SecurityCode').onkeypress = function (e) {
    // спец. сочетание - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return;
    }

    var char = getChar(e);

    if (!char) {
        return; // спец. символ - не обрабатываем
    }
    
    if (!isNaN(+char)  && this.value.length < 10) {
        this.value += char;
    }

    return false;
}; //Ввід у поле першої станції

document.getElementById('dat').onkeypress = function (e) {
    // спец. сочетание - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return;
    }

    var char = getChar(e);

    if (!char) {
        return; // спец. символ - не обрабатываем
    }
    
    
    
    
    
    if (!isNaN(+char) && this.value.length < 5 && this.value.length != 0 && this.value.length != 1) {
        this.value += char;
    }
        
    if (!isNaN(+char) && this.value.length == 1) {
        if (this.value == 1) {
            if (char > 0 && char < 3) {
                this.value += char;
            }
        } else {
            if (char > 0) {
                this.value += char;
            }
        }   
    }
    
    if (!isNaN(+char) && this.value.length == 0) {
        if (char < 2) {
            this.value += char;  
        }
    }
    
    if (this.value.length == 2) {
        this.value += "/";
    }

    return false;
}; //Ввід у поле першої станції


$('#computing').click(function () {
    var CodeCard = $('#CardNumber').val();
    var SCodeCard = $('#SecurityCode').val();
    var DateCard = $('#dat').val();
    
    var now = new Date();
    var Cnow = new Date(20 + DateCard[3] + DateCard[4], 0, DateCard[0] + DateCard[1], 0, 0, 0, 0);
    
    if (now.getTime() > Cnow.getTime()) {
        $('#dat').css("border-color", "#f7b4b4");
    } else {
        $.ajax({
            url: "php/CheckWay.php",
            type: "POST",
            cache: false,
            data: ({
                'C': "48",
                'CodeCard': CodeCard,
                'SCodeCard': SCodeCard,
                'DateCard': DateCard,
                'Token': Value["Token"]
            }),
            dataType: "html",
            beforeSend: CoAtt,
            success: Computing
        });
    }
});

function CoAtt() {
    $('#computing').attr("disabled", "disabled");
}

function Computing(data) {
    $('#computing').removeAttr("disabled");
    if (data != "No" && data != "Error DB") {
        $('#f1').toggle();
        $('#f2').toggle();
        
        setCookie("Code", escape(data));
    }
}

$('#cloase').click(function () {
    window.close();
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