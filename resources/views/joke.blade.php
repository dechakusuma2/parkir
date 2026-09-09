<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Joke Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }

        .joke-box {
            background: #f8f9fa;
            border-left: 5px solid #667eea;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .setup {
            color: #333;
            font-size: 1.1em;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .punchline {
            color: #667eea;
            font-size: 1.2em;
            font-weight: 700;
            font-style: italic;
            margin-top: 10px;
        }

        .joke-type {
            color: #999;
            font-size: 0.85em;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .loading {
            display: none;
            color: #667eea;
            font-size: 1em;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        button {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-copy {
            background: #e9ecef;
            color: #333;
            padding: 12px 20px;
        }

        .btn-copy:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .error {
            color: #e74c3c;
            background: #fadbd8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .success-message {
            color: #27ae60;
            font-size: 0.9em;
            margin-top: 10px;
            display: none;
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px;
            }

            h1 {
                font-size: 2em;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-copy {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>😂 Joke Generator</h1>
        <p class="subtitle">Get a random joke every time!</p>

        <div class="error" id="errorBox"></div>

        <div class="joke-box" id="jokeBox" style="display: none;">
            <div class="setup" id="setup"></div>
            <div class="punchline" id="punchline"></div>
            <div class="joke-type" id="jokeType"></div>
        </div>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            Loading a funny joke...
        </div>

        <div class="button-group">
            <button class="btn-primary" id="fetchBtn" onclick="fetchJoke()">Get a Joke 🎭</button>
            <button class="btn-copy" id="copyBtn" onclick="copyJoke()" style="display: none;">Copy 📋</button>
        </div>

        <div class="success-message" id="successMessage">Copied to clipboard!</div>
    </div>

    <script>
        const jokeBox = document.getElementById('jokeBox');
        const loading = document.getElementById('loading');
        const errorBox = document.getElementById('errorBox');
        const fetchBtn = document.getElementById('fetchBtn');
        const copyBtn = document.getElementById('copyBtn');
        const successMessage = document.getElementById('successMessage');
        const setupEl = document.getElementById('setup');
        const punchlineEl = document.getElementById('punchline');
        const jokeTypeEl = document.getElementById('jokeType');

        let currentJoke = null;

        async function fetchJoke() {
            errorBox.style.display = 'none';
            jokeBox.style.display = 'none';
            successMessage.style.display = 'none';
            loading.style.display = 'block';
            fetchBtn.disabled = true;
            copyBtn.style.display = 'none';

            try {
                const response = await fetch('{{ route("joke.fetch") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const data = await response.json();
                loading.style.display = 'none';

                if (data.success) {
                    currentJoke = data;
                    setupEl.textContent = data.setup;
                    punchlineEl.textContent = data.punchline;
                    jokeTypeEl.textContent = '📌 ' + data.type;
                    jokeBox.style.display = 'block';
                    copyBtn.style.display = 'block';
                } else {
                    errorBox.textContent = data.message || 'Failed to fetch joke';
                    errorBox.style.display = 'block';
                }
            } catch (error) {
                loading.style.display = 'none';
                errorBox.textContent = 'Error: ' + error.message;
                errorBox.style.display = 'block';
            } finally {
                fetchBtn.disabled = false;
            }
        }

        function copyJoke() {
            if (currentJoke) {
                const text = currentJoke.setup + '\n\n' + currentJoke.punchline;
                navigator.clipboard.writeText(text).then(() => {
                    successMessage.style.display = 'block';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 2000);
                });
            }
        }

        window.addEventListener('load', fetchJoke);

        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !fetchBtn.disabled) {
                fetchJoke();
            }
        });
    </script>
</body>
</html>
