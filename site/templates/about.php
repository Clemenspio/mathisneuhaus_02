<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Mathis Neuhaus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Univers Selectric One', 'SF Pro', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #FFFFFF;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Frame 4 */
        .about-frame {
            box-sizing: border-box;
            position: absolute;
            width: 1345px;
            height: 803px;
            left: calc(50% - 1345px/2 - 6.5px);
            top: calc(50% - 803px/2 + 0.5px);
            background: #000000;
            border: 3px solid #000000;
            box-shadow: 10px 14px 14px rgba(0, 0, 0, 0.35);
            border-radius: 24px;
            position: relative;
        }

        /* Group 4 */
        .header-group {
            position: absolute;
            width: 109px;
            height: 36px;
            left: 40px;
            top: 30px;
        }

        /* Files */
        .files-text {
            position: absolute;
            width: 49px;
            height: 16px;
            left: 40px;
            top: 40px;
            font-family: 'Univers Selectric One';
            font-style: normal;
            font-weight: 400;
            font-size: 23px;
            line-height: 16px;
            color: #FFFFFF;
        }

        /* Button 1 */
        .close-button {
            position: absolute;
            width: 55px;
            height: 36px;
            left: 94px;
            top: 30px;
            font-family: 'SF Pro';
            font-style: normal;
            font-weight: 400;
            font-size: 27.5px;
            line-height: 36px;
            display: flex;
            align-items: center;
            text-align: center;
            color: #FFFFFF;
            background: none;
            border: none;
            cursor: pointer;
            mix-blend-mode: normal;
        }

        /* Main Content */
        .about-content {
            position: absolute;
            width: 1071px;
            height: 408px;
            left: 137px;
            top: 199px;
            font-family: 'Univers Selectric One';
            font-style: normal;
            font-weight: 400;
            font-size: 78.0112px;
            line-height: 81px;
            text-align: center;
            color: #FFFFFF;
            white-space: pre-line;
        }

        /* Back to Finder Button */
        .back-button {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            background: #FFFFFF;
            color: #000000;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: 'SF Pro';
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .back-button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
            <div class="about-frame">
            <div class="header-group">
                <div class="files-text">FILES</div>
                <button class="close-button" onclick="goBack()">←</button>
            </div>
        
        <div class="about-content">
            <?= $page->about_text()->kt() ?>
        </div>
        
        <button class="back-button" onclick="goBack()">← Back to Finder</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                goBack();
            }
        });
    </script>
</body>
</html> 