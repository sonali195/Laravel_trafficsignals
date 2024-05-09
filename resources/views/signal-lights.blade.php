<!DOCTYPE html>
<html>
<head>
    <title>Signal Lights</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .signal{
            width: 100px;
        }
        .signal > div{
            border-radius: 100%;
            margin: 0 auto;
        }
        .signal > input{
            width: 100%;
        }
        div#signal-lights {
            display: flex;
            gap: 17px;
        }
    </style>
</head>
<body>
    <h1>Signal Lights</h1>
    <form id="signal-form">
        @csrf

        <div id="signal-lights">
            <div class="signal">
                <div id="signal-A" style="background-color: red; width: 50px; height: 50px; margin-bottom: 10px;"></div>
                <input type="number" name="sequence[A]" id="sequence-A" />
            </div>

            <div class="signal">
                <div id="signal-B" style="background-color: red; width: 50px; height: 50px; margin-bottom: 10px;"></div>
                <input type="number" name="sequence[B]" id="sequence-B" />
            </div>

            <div class="signal">
                <div id="signal-C" style="background-color: red; width: 50px; height: 50px; margin-bottom: 10px;"></div>
                <input type="number" name="sequence[C]" id="sequence-C" />
            </div>

            <div class="signal">
                <div id="signal-D" style="background-color: red; width: 50px; height: 50px; margin-bottom: 10px;"></div>
                <input type="number" name="sequence[D]" id="sequence-D" />
            </div>
        </div>

        <br>
        <label for="green-interval">Green Interval:</label>
        <input type="number" name="green_interval" id="green-interval" />
        <br>
        <label for="yellow-interval">Yellow Interval:</label>
        <input type="number" name="yellow_interval" id="yellow-interval" />
        <br>
        <button type="submit">Start</button>
        <button type="stop" id="stop-button">Stop</button>
    </form>

    <script>
       $(document).ready(function () {
    let signalOrder = ['A', 'B', 'C', 'D'];
    let currentSignalIndex = 0;
    let currentSequenceNumber = 1;
    let greenInterval = 10; // Default values, update with actual values
    let yellowInterval = 5; // Default values, update with actual values
    let totalDuration = greenInterval + yellowInterval;
    let interval;
    let nextSignalIndex = 0;

    $('#signal-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '/signal-lights/start',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                let { sequence } = response.signal_light;

                // Reset the nextSignalIndex when starting a new sequence
                if (currentSignalIndex === 0) {
                    nextSignalIndex = 0;
                }

                // Clear any existing interval
                clearInterval(interval);

                // Start the interval to change the signal lights
                interval = setInterval(() => {
                    let signal = signalOrder[nextSignalIndex];
                    let color = getColor(signal, sequence[signal], currentSequenceNumber);

                    changeSignalLights(signal, color);

                    // Move to the next signal in the fixed order
                    nextSignalIndex = (nextSignalIndex + 1) % signalOrder.length;

                    if (nextSignalIndex === 0) {
                        // Increment the sequence number when all signals have been shown
                        currentSequenceNumber = currentSequenceNumber % 4 + 1;
                    }
                }, 1000); // Check every second for color change
            },
            error: function (response) {
                alert(response.responseJSON.errors.join("\n"));
            }
        });
    });

    $('#stop-button').click(function () {
        clearInterval(interval);
        resetSignalLights();
    });

    function changeSignalLights(signal, color) {
        resetSignalLights();
        $(`#signal-${signal}`).css('background-color', color);
    }

    function resetSignalLights() {
        signalOrder.forEach(signal => {
            $(`#signal-${signal}`).css('background-color', 'red');
        });
    }

    function getColor(signal, signalSequence, currentSequenceNumber) {
        let elapsedTime = Date.now() % totalDuration;

        if (signalSequence == currentSequenceNumber) {
            if (elapsedTime < greenInterval) {
                return 'green';
            } else if (elapsedTime < greenInterval + yellowInterval) {
                return 'yellow';
            } else if (elapsedTime < totalDuration) {
                return 'red';
            }
        }

        return 'red';
    }
});



    </script>
</body>
</html>