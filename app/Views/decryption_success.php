<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decryption Successful</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: #00b894;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #2d3436;
            margin-bottom: 15px;
            font-size: 2.2em;
        }
        
        .message {
            color: #636e72;
            font-size: 1.1em;
            margin-bottom: 25px;
        }
        
        .stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .btn {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .warning {
            background: #ffeaa7;
            color: #e17055;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">🎉</div>
        <h1>Decryption Successful!</h1>
        
        <div class="message">
            Your files have been successfully decrypted and restored to their original state.
        </div>
        
        <div class="stats">
            <strong><?php echo $decryptedFiles; ?> files</strong> have been restored
        </div>
        
        <a href="/" class="btn">Return to Home</a>
        
        <div class="warning">
            <strong>Note:</strong> It's recommended to backup your important files regularly 
            to prevent data loss in the future.
        </div>
    </div>
</body>
</html>