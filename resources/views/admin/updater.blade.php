<!DOCTYPE html>
<html>
<head>
    <title>Laravel Updater</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .container { max-width: 600px; margin: auto; }
        h2 { margin-bottom: 1rem; }
        .info { margin-bottom: 1rem; }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        #message {
            margin-top: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Laravel Updater</h2>
    <div class="info">
        <p>Current Version: <span id="currentVersion">{{ $currentVersion }}</span></p>
        <p>Latest Version: <span id="latestVersion">{{ $latestVersion ?? 'Unknown' }}</span></p>
    </div>
    <button id="checkUpdateBtn">Check for Updates</button>
    <button id="performUpdateBtn" disabled>Perform Update</button>
    <div id="message"></div>
</div>

<script>
    const checkUpdateBtn = document.getElementById('checkUpdateBtn');
    const performUpdateBtn = document.getElementById('performUpdateBtn');
    const messageDiv = document.getElementById('message');
    const latestVersionSpan = document.getElementById('latestVersion');

    checkUpdateBtn.addEventListener('click', () => {
        messageDiv.textContent = 'Checking for updates...';
        fetch('{{ route("admin.updater.check") }}')
            .then(response => response.json())
            .then(data => {
                if (data.updateAvailable) {
                    messageDiv.textContent = 'Update available: ' + data.latestVersion;
                    latestVersionSpan.textContent = data.latestVersion;
                    performUpdateBtn.disabled = false;
                } else {
                    messageDiv.textContent = 'No updates available.';
                    performUpdateBtn.disabled = true;
                }
            })
            .catch(() => {
                messageDiv.textContent = 'Error checking for updates.';
                performUpdateBtn.disabled = true;
            });
    });

    performUpdateBtn.addEventListener('click', () => {
        messageDiv.textContent = 'Performing update...';
        performUpdateBtn.disabled = true;
        fetch('{{ route("admin.updater.perform") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = data.message;
                    checkUpdateBtn.click(); // Refresh update status
                } else {
                    messageDiv.textContent = 'Update failed: ' + data.message;
                    performUpdateBtn.disabled = false;
                }
            })
            .catch(() => {
                messageDiv.textContent = 'Error performing update.';
                performUpdateBtn.disabled = false;
            });
    });
</script>
</body>
</html>
