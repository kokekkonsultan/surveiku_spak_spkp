<!DOCTYPE html>
<html>

<head>
    <title>Bootstrap color picker Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.css" /> -->

    <script src="https://cdn.jsdelivr.net/npm/@jaames/iro@5"></script>
    <style>
    body {
        color: #ffffff;
        background: #171F30;
        line-height: 150%;
    }

    .wrap {
        min-height: 100vh;
        max-width: 720px;
        margin: 0 auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .half {
        width: 50%;
        padding: 32px 0;
    }

    .title {
        font-family: sans-serif;
        line-height: 24px;
        display: block;
        padding: 8px 0;
    }

    .readout {
        margin-top: 32px;
        line-height: 180%;
    }

    .colorSquare {
        height: 70px;
        width: 70px;
        background-color: red;
        border-radius: 10%;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="half">
            <div class="colorPicker"></div>
        </div>
        <div class="half readout">
            <span class="title">Selected Color:</span>
            <div class="colorSquare" id="colorSquare"></div>
            <div id="values"></div>
            <input id="hexInput"></input>
        </div>
    </div>


    <script>
    var values = document.getElementById("values");
    var hexInput = document.getElementById("hexInput");
    let colorSquare = document.getElementById("colorSquare");
    var colorPicker = new iro.ColorPicker(".colorPicker", {
        width: 280,
        color: "rgb(255, 0, 0)",
        borderWidth: 1,
        borderColor: "#fff"
    });

    colorPicker.on(["color:init", "color:change"], function(color) {
        values.innerHTML = [
            "hex: " + color.hexString,
            "rgb: " + color.rgbString,
            "hsl: " + color.hslString
        ].join("<br>");

        hexInput.value = color.hexString;
        colorSquare.style.backgroundColor = color.hexString;
    });
    hexInput.addEventListener("change", function() {
        colorPicker.color.hexString = this.value;
    });
    </script>
</body>

</html>