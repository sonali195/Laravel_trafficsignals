<!DOCTYPE html>
<html>
<head>
    <title>Traffic Signal</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        .signal_wrapper {
            display: flex;
            gap: 10px;
        }

        .signal_wrapper input {
            margin: 0 auto;
            display: block;
        }

        .signal {
            display: inline-block;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 20px;
            border: 1px solid #333;
            background-color: red;
        }
        .red { background-color: red; }
        .yellow { background-color: yellow; }
        .green { background-color: green; }
    </style>
</head>
<body>
    
    <form id="signal-form">
        <?php echo csrf_field(); ?>

        <div class="signal_wrapper">
            <?php $__currentLoopData = ['A', 'B', 'C', 'D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div id="signal-<?php echo e($signal); ?>" class="signal"></div>
                    <input type="number" id="sequence-<?php echo e($signal); ?>" name="sequence[<?php echo e($signal); ?>]" placeholder="Sequence (1-4)" min="1" max="4" value="<?php echo e($loop->index + 1); ?>">
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <br />
        <div class="input-field">
            <label for="green-interval">Green Interval (seconds):</label>
            <input type="number" name="green_interval" id="green-interval" placeholder="Green Interval" min="1" value="5">
        </div>

        <br />
        <div class="input-field">
            <label for="yellow-interval">Yellow Interval (seconds):</label>
            <input type="number" name="yellow_interval" id="yellow-interval" placeholder="Yellow Interval" min="1" value="2">
        </div>

        <br />
        <div class="input-field">
            <button id="start-button">Start</button>
            <button id="stop-button">Stop</button>
        </div>
        <div class="input-field">
            <i><b>Note:</b> After click wait for signal start</i>
        </div>
    </form>
    <script>
        let signals = ['A', 'B', 'C', 'D'];
        let currentSignalIndex = 0;
        let interval;

        function changeLight(signal, color) {
            const signalElement = document.getElementById('signal-' + signal);
            signalElement.className = 'signal ' + color;
        }

        function startSignalChange() {
            let greenDuration = parseInt(document.getElementById('green-interval').value) * 1000;
            let yellowDuration = parseInt(document.getElementById('yellow-interval').value) * 1000;

            let sequenceInput = document.querySelectorAll('input[id^="sequence-"]');
            let sequence = Array.from(sequenceInput).map(input => parseInt(input.value));

            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let formData = $('#signal-form').serialize();
            formData += '&_token=' + csrfToken;

            $.ajax({
                url: "<?php echo e(route('storeSignalData')); ?>",
                method: "POST",
                data: formData,
                success: function(response) {
                    console.log('Data stored successfully');

                    let currentSequenceIndex = 0;

                    interval = setInterval(() => {
                        let signal = signals[sequence[currentSequenceIndex] - 1];
                        changeLight(signal, 'green');

                        setTimeout(() => {
                            changeLight(signal, 'yellow');
                            setTimeout(() => {
                                changeLight(signal, 'red');
                                currentSequenceIndex = (currentSequenceIndex + 1) % sequence.length;
                            }, yellowDuration);
                        }, greenDuration);
                    }, greenDuration + yellowDuration);
                },
                error: function(response) {
                    console.error('Failed to store data');
                }
            });
        }



        function stopSignalChange() {
            clearInterval(interval);
            signals.forEach(signal => {
                changeLight(signal, 'red');
            });
            currentSignalIndex = 0;
        }

        //document.getElementById('start-button').addEventListener('click', startSignalChange);
        document.getElementById('start-button').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default form submission
            startSignalChange(); // Call your function to handle the AJAX request
        });
        document.getElementById('stop-button').addEventListener('click', stopSignalChange);
    </script>
</body>
</html>
<?php /**PATH /Users/sanjayparmar/Sites/Learning/laravel_learning/resources/views/signal-lights-new.blade.php ENDPATH**/ ?>